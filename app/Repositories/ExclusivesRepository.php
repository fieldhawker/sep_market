<?php
namespace App\Repositories;

use App\Models\Exclusives;

/**
 * Class ExclusivesRepository
 */
class ExclusivesRepository implements ExclusivesRepositoryInterface
{

    protected $eloquent;


    /**
     * ExclusivesRepository constructor.
     *
     * @param Exclusives $eloquent
     */
    public function __construct(Exclusives $eloquent)
    {
        $this->eloquent = $eloquent;
    }

    /**
     * @return mixed
     */
    public function findAll()
    {
        $Exclusives = $this->eloquent->findAll();

        return $Exclusives;
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function findOrFail($id)
    {
        $Exclusives = $this->eloquent->findOrFail($id);

        return $Exclusives;
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
     * @return bool
     */
    public function setExclusive(array $data)
    {
        $result = $this->eloquent->setExclusive($data);
        
        return $result;
    }

    /**
     * @param $user
     *
     * @return mixed
     */
    public function insertGetId(array $data)
    {

        $id = $this->eloquent->insertGetId($data);

        return $id;
    }


    /**
     * @param array $data
     *
     * @return bool
     */
    public function isExpiredByOtherAdmin()
    {
        $is_exclusives = $this->eloquent->isExpiredByOtherAdmin();

        return $is_exclusives;
    }
    
    public function deleteExclusive()
    {
        $result = $this->eloquent->deleteExclusive();

        return $result;
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

    /**
     * @param int $page
     * @param int $limit
     *
     * @return mixed|\StdClass
     */
    public function byPage($page = 1, $limit = 20)
    {

    }

}