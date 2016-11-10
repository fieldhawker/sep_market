<?php
namespace App\Repositories;

use App\Models\User;

/**
 * Class UsersRepository
 */
class UsersRepository implements UsersRepositoryInterface
{

    protected $eloquent;


    /**
     * UsersRepository constructor.
     *
     * @param User $eloquent
     */
    public function __construct(User $eloquent)
    {
        $this->eloquent = $eloquent;
    }

    /**
     * @return mixed
     */
    public function findAll()
    {
        $users = $this->eloquent->orderBy('id', 'desc')->get();

        return $users;
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function findById($id)
    {
        $users = $this->eloquent->findOrFail($id);

        return $users;
    }


    /**
     * @param array $params
     *
     * @return bool
     */
    public function validate(array $params, $id=null)
    {
        $result = $this->eloquent->validate($params, $id);

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
     * @param $user
     *
     * @return mixed
     */
    public function insertGetId(array $user)
    {
        $id = $this->eloquent->insertGetId($user);

        return $id;
    }


    /**
     * @param $input
     * @param $id
     *
     * @return mixed
     */
    public function updateUser($input, $id) {
        
        $result = $this->eloquent->updateUser($input, $id);

        return $result;
    }
    
    public function deleteUser($id)
    {
        $user = $this->findById($id);
        $result = $user->delete();

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