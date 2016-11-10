<?php

namespace App\Models;

use DB;
use Log;
use Validator;
use Illuminate\Database\Eloquent\Model;

class OperationLogs extends Model
{


    /**
     * @var string
     */
    protected $table = "operation_logs";


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'screen_number',
      'target_id',
      'comment',
      'executed_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * @var array
     */
    private $rules = [
      'screen_number' => 'required|numeric|min:1|max:9999',
      'target_id'     => 'required|numeric|min:1|max:99999999999',
      'executed_at'   => 'required|date',
    ];

    /**
     * @var array
     */
    private $messages = [
      'required' => ':attributeフィールドは必須です。',
      'numeric'  => ':attributeフィールドは数字で入力してください。',
      'min'      => ':attributeフィールドは:minより大きく。',
      'max'      => ':attributeフィールドは:maxより小さく。',
      'date'     => ':attributeフィールドは日付型で入力してください。',
    ];

    /**
     * @var
     */
    private $errors;

    /**
     * @return mixed
     */
    public function errors()
    {
        return $this->errors;
    }

    /**
     * @param        $data
     * @param string $id
     *
     * @return bool
     */
    public function validate($data)
    {

        $rules = $this->rules;

        $v = Validator::make($data, $rules, $this->messages);

        if ($v->fails()) {

//            var_dump($v->errors());

            $this->errors = $v->errors();
            Log::info('validate error', ['errors' => $this->errors]);

            return false;
        }

        return true;
    }

    /**
     * @param $data
     *
     * @return mixed
     */
    public function insertGetId($data)
    {

        $id = DB::table($this->table)->insertGetId($data);

        return $id;

    }

}
