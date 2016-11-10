<?php

namespace App\Models;

use DB;
use Validator;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Items
 * @package App
 */
class Items extends Model
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = "items";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'user_id',
      'name',
      'price',
      'caption',
      'status',
      'items_status',
      'started_at',
      'ended_at',
      'delivery_charge',
      'delivery_plan',
      'pref',
      'delivery_date',
      'comment'
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
      'user_id'         => 'required|numeric|sometimes',
      'name'            => 'required|string|min:2|max:256',
      'price'           => 'required|numeric|min:1|max:1000000',
      'caption'         => 'required|string|min:1|max:256',
      'status'          => 'required|numeric|min:0|max:3',
      'items_status'    => 'required|numeric|min:0|max:3',
      'started_at'      => 'required|date',
      'ended_at'        => 'required|date',
      'delivery_charge' => 'required|numeric|min:0|max:3',
      'delivery_plan'   => 'required|numeric|min:0|max:3',
      'pref'            => 'required|numeric|min:1|max:47',
      'delivery_date'   => 'required|numeric|min:0|max:3',
      'comment'         => 'required|string|min:2|max:256',
    ];

    /**
     * @var array
     */
    private $messages = [
//      'required' => ':attributeフィールドは必須です。',
//      'string' => ':attributeフィールドは文字列で入力してください。',
//      'min' => ':attributeフィールドは:sizeより大きく。',
//      'max' => ':attributeフィールドは:sizeより小さく。',
//      'email' => ':attributeフィールドはメールで。',
//      'unique' => ':attributeフィールドはユニークで。',
    ];

    /**
     * 日付により変更を起こすべき属性
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @var
     */
    private $errors;

    /**
     * @param        $data
     * @param string $id
     *
     * @return bool
     */
    public function validate($data, $id = 'NULL')
    {

        $rules = $this->rules;

        $v = Validator::make($data, $rules, $this->messages);

        if ($v->fails()) {

            $this->errors = $v->errors();

            return false;
        }

        return true;
    }

    /**
     * @return mixed
     */
    public function errors()
    {
        return $this->errors;
    }


    /**
     * @param $data
     *
     * @return null
     */
    public function registerGetId($data)
    {

        if ($this->validate($data)) {

            $id = DB::table('items')->insertGetId($data);

            return $id;

        } else {

            return false;

        }
    }


    /**
     * @param $data
     * @param $id
     *
     * @return bool
     */
    public function updateItems($data, $id)
    {

        if ($this->validate($data, $id)) {

            // アップデート処理
            Items::where('id', '=', $id)->update($data);

            return true;

        } else {

            return false;

        }

    }
}
