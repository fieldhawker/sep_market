<?php
namespace App\Repositories;

use App\Models\OperationLogs;

/**
 * Class OperationLogsRepository
 */
class OperationLogsRepository implements OperationLogsRepositoryInterface
{

    protected $eloquent;


    /**
     * OperationLogsRepository constructor.
     *
     * @param OperationLogs $eloquent
     */
    public function __construct(OperationLogs $eloquent)
    {
        $this->eloquent = $eloquent;
    }

    /**
     * @return mixed
     */
    public function findAll()
    {
        $OperationLogs = $this->eloquent->findAll();

        return $OperationLogs;
    }


    /**
     * @param array $params
     *
     * @return bool
     */
    public function validate(array $params)
    {
        $result = $this->eloquent->validate($params);

        return $result;
    }

    /**
     * @return mixed
     */
    public function getErrors()
    {
        $errors = $this->eloquent->getErrors();

        return $errors;
    }


    /**
     * @param array $data
     *
     * @return mixed
     */
    public function registerGetId(array $data)
    {

        $data["executed_at"] = date("Y-m-d H:i:s");

        $ope_id = $this->eloquent->insertGetId($data);

        return $ope_id;

    }

    /**
     * @param array $params
     *
     * @return mixed
     */
    public function save(array $params)
    {

    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function find($id)
    {

    }

    /**
     * @return int
     */
    public function count()
    {

    }

}