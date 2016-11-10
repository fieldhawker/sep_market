<?php
namespace App\Repositories;

/**
 * Interface ExclusivesRepositoryInterface
 */
interface ExclusivesRepositoryInterface
{

    /**
     * @return mixed
     */
    public function findAll();


    /**
     * @param $id
     *
     * @return mixed
     */
    public function findOrFail($id);

    /**
     * @param array $params
     *
     * @return mixed
     */
    public function validate(array $params);

    /**
     * @return mixed
     */
    public function getErrors();


    /**
     * @param array $data
     *
     * @return mixed
     */
    public function setExclusive(array $data);

    /**
     * @param array $user
     *
     * @return mixed
     */
    public function insertGetId(array $user);


    /**
     * @param array $data
     *
     * @return mixed
     */
    public function isExpiredByOtherAdmin();

    /**
     * @return mixed
     */
    public function deleteExclusive();
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

    /**
     * @param int $page
     * @param int $limit
     *
     * @return mixed
     */
    public function byPage($page = 1, $limit = 20);
}