<?php
namespace App\Repositories;
/**
 * Interface OperationLogsRepositoryInterface
 */
interface OperationLogsRepositoryInterface
{

    /**
     * @return mixed
     */
    public function findAll();


    /**
     * @param array $params
     *
     * @return mixed
     */
    public function validate(array $params);
    
    public function getErrors();
    
    
    /**
     * @param array $user
     *
     * @return mixed
     */
    public function registerGetId(array $data);
    
    
    
    /**
     * @param array $params
     *
     * @return mixed
     */
    public function save(array $params);
    /**
     * @param $id
     *
     * @return mixed
     */
    public function find($id);
    /**
     * @return mixed
     */
    public function count();

}