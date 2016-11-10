<?php
namespace app\Libs;

/**
 * Created by PhpStorm.
 * User: fieldhawker
 * Date: 2016/07/01
 * Time: 21:35
 */

use App\Models\OperationLogs;

class OperationLogsClass
{

    private $ope;

    /**
     * OperationLogsClass constructor.
     *
     * @param OperationLogs $ope
     */
    public function __construct(OperationLogs $ope)
    {
        $this->ope = $ope;
    }

    /**
     * @param $data
     *
     * @return mixed
     */
    public function registerGetId($data)
    {

        $data["executed_at"] = date("Y-m-d H:i:s");

        $ope_id = $this->ope->insertGetId($data);

        return $ope_id;
    }
}