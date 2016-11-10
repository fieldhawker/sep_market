<?php
namespace App\Services;

use DB;
use Log;
use Auth;

use App\Repositories\UsersRepositoryInterface;
use App\Repositories\OperationLogsRepositoryInterface;
use App\Repositories\ExclusivesRepositoryInterface;

/**
 * Class UsersService
 */
class UsersService
{
    const SCREEN_NUMBER_REGISTER = 110;
    const SCREEN_NUMBER_UPDATE   = 120;
    const SCREEN_NUMBER_DELETE   = 130;

    protected $users;
    protected $ope;
    protected $exclusives;

    /**
     * UsersService constructor.
     *
     * @param UsersRepositoryInterface         $users
     * @param OperationLogsRepositoryInterface $ope
     * @param ExclusivesRepositoryInterface    $exclusives
     */
    public function __construct(
      UsersRepositoryInterface $users,
      OperationLogsRepositoryInterface $ope,
      ExclusivesRepositoryInterface $exclusives
    ) {
        $this->users      = $users;
        $this->ope        = $ope;
        $this->exclusives = $exclusives;
    }

    /**
     * 会員情報を論理削除以外すべて取得する
     *
     * @return mixed
     */
    public function findAll()
    {

        return $this->users->findAll();

    }


    /**
     * ID指定で会員情報を取得する
     *
     * @param $id
     *
     * @return mixed
     */
    public function findById($id)
    {

        Log::info('会員の検索に指定したID', ['id' => $id]);

        return $this->users->findById($id);

    }


    /**
     * 指定されたIDの会員が編集中か否か
     *
     * @param $id
     *
     * @return mixed
     */
    public function isExpiredByOtherAdmin($id)
    {

        $data = [
          'screen_number' => self::SCREEN_NUMBER_UPDATE,
          'target_id'     => $id,
          'operator'      => Auth::guard("admin")->user()->id,
        ];

        Log::info('編集中かの判定に使用した情報', $data);

        $this->exclusives->setExclusive($data);

        $is_exclusives = $this->exclusives->isExpiredByOtherAdmin();

        if (!$is_exclusives) {

            // ----------------------------
            // 編集中にする
            // ----------------------------

            $exclusives_id = $this->exclusives->insertGetId($data);

            Log::info('以下のIDで編集中に更新されました', ['id' => $exclusives_id]);

        }

        return $is_exclusives;

    }


    /**
     * リクエストパラメータから必要な情報を抽出する
     *
     * @param $request
     *
     * @return mixed
     */
    public function getRequest($request)
    {

        $input["name"]  = $request->name;
        $input["kana"]  = $request->kana;
        $input["email"] = $request->email;

        if (!empty($request->password)) {

            $input["password"] = $request->password;

        }

        Log::info('入力されたパラメータ', $input);

        return $input;

    }

    /**
     * 配列に対してバリデーション判定を行う
     *
     * @param $params
     *
     * @return mixed
     */
    public function validate($params, $id = null)
    {

        return $this->users->validate($params, $id);

    }

    /**
     * 発生したエラー情報を取得する
     *
     * @return mixed
     */
    public function getErrors()
    {
        return $this->users->getErrors();
    }

    /**
     * 会員を登録する
     *
     * @param $input
     *
     * @return mixed
     */
    public function registerUser($input)
    {

        $exception = DB::transaction(function () use ($input) {

            // ----------------------------
            // 会員登録
            // ----------------------------

            $id          = $this->users->insertGetId($input);
            $input["id"] = $id;

            Log::info('会員が登録されました。', $input);

            // ----------------------------
            // 操作ログ登録
            // ----------------------------

            $data = [
              'screen_number' => self::SCREEN_NUMBER_REGISTER,
              'target_id'     => $id,
              'operator'      => Auth::guard("admin")->user()->id,
              'comment'       => json_encode($input, JSON_UNESCAPED_UNICODE),
            ];

            $id         = $this->ope->registerGetId($data);
            $data["id"] = $id;

            Log::info('操作ログが登録されました。', $data);

        });

        return $exception;

    }


    /**
     * 会員情報を編集する
     * 
     * @param $input
     * @param $id
     *
     * @return mixed
     */
    public function updateUser($input, $id)
    {

        $exception = DB::transaction(function () use ($input, $id) {

            // ----------------------------
            // 更新を開始
            // ----------------------------

            $this->users->updateUser($input, $id);

            $input["id"] = $id;
            
            Log::info('会員が更新されました', $input);

            // ----------------------------
            // 排他制御を削除
            // ----------------------------

            $this->exclusives->deleteExclusive();
            
            Log::info('会員情報の排他制御が削除されました');

            // ----------------------------
            // 操作ログを記録
            // ----------------------------

            $data = [
              'screen_number' => self::SCREEN_NUMBER_UPDATE,
              'target_id'     => $id,
              'operator'      => Auth::guard("admin")->user()->id,
              'comment'       => json_encode($input, JSON_UNESCAPED_UNICODE),
            ];

            $id         = $this->ope->registerGetId($data);
            $data["id"] = $id;

            Log::info('操作ログが登録されました', $data);

        });

        return $exception;

    }


    /**
     * 会員情報を削除する
     * 
     * @param $id
     *
     * @return mixed
     */
    public function deleteUser($id)
    {

        $exception = DB::transaction(function () use ($id) {
            
            // ----------------------------
            // 論理削除
            // ----------------------------

            $result = $this->users->deleteUser($id);

            Log::info('会員が削除されました。', ['id' => $id]);

            // ----------------------------
            // 排他制御を削除
            // ----------------------------

            $this->exclusives->deleteExclusive();

            Log::info('会員情報の排他制御が削除されました');

            // ----------------------------
            // 操作ログを記録
            // ----------------------------

            $data = [
              'screen_number' => self::SCREEN_NUMBER_DELETE,
              'target_id'     => $id,
              'operator'      => Auth::guard("admin")->user()->id,
              'comment'       => json_encode($id, JSON_UNESCAPED_UNICODE),
            ];

            $id         = $this->ope->registerGetId($data);
            $data["id"] = $id;

            Log::info('操作ログが登録されました', $data);

        });

        return $exception;

    }

}