<?php

namespace App\Models;

use DB;
use Log;
use Config;
use Illuminate\Database\Eloquent\Model;

class Exclusives extends Model
{
    /**
     * @var string
     */
    protected $table = "exclusives";
    
    public $exclusive;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'screen_number',
      'target_id',
      'operator',
      'expired_at',
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
     * @param $data
     *
     * @return bool
     */
    public function setExclusive($data)
    {
        $this->exclusive = $data;
        
        return true;
    }

    /**
     * @param $data
     *
     * @return bool
     */
    public function isExpiredByOtherAdmin()
    {
        $data = $this->exclusive;
        
        $count_exclusives = DB::table($this->table)
          ->where('screen_number', '=', $data["screen_number"])
          ->where('target_id', '=', $data["target_id"])
          ->where('operator', '<>', $data["operator"])
          ->where('expired_at', '>', date('Y/m/d H:i:s'))
          ->count();

        $is_exclusives = ($count_exclusives > 0) ? true : false;

        return $is_exclusives;
    }

    /**
     * @return mixed
     */
    public function deleteExclusive()
    {

        $data = $this->exclusive;
        
        Log::info('削除する排他制御', $data);
        
        $result = DB::table($this->table)
          ->where('screen_number', '=', $data["screen_number"])
          ->where('target_id', '=', $data["target_id"])
          ->where('operator', '=', $data["operator"])
          ->delete();

        return $result;
    }
    
    /**
     * @param $data
     *
     * @return bool
     */
    public function deleteExpiredByMine($data)
    {
        // 削除対象レコードを検索
        $result = DB::table($this->table)
          ->where('screen_number', '=', $data["screen_number"])
          ->where('target_id', '=', $data["target_id"])
          ->where('operator', '=', $data["operator"])
          ->delete();

        return $result;
    }

    /**
     * @param $data
     *
     * @return mixed
     */
    public function insertGetId($data)
    {

        $data["expired_at"] = date("Y/m/d H:i:s", strtotime(Config::get('const.exclusives_time')));
        $id                 = DB::table($this->table)->insertGetId($data);

        return $id;

    }
}
