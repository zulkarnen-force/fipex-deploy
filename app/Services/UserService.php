<?php

namespace App\Services;

use App\Exceptions\ValidationException;

use App\Utils\Response;
use App\Repositories\User\IUserRepository;

use Exception;

class UserService
{
    public $repository;
    function __construct(IUserRepository $repository)
    {
        $this->repository = $repository;
    }


    public function find($id)
    {

        try {
            $user = $this->repository->find($id);
            return new Response(200, null, true, $user);
        } catch (Exception $th) {
            return new Response(404, $th->getMessage(), false);
        }
    }


    public function list()
    {
        $users = $this->repository->list();
        return new Response(200, 'all users', true, $users);
    }


    public function create($data)
    {
        try {
            $insertedData = $this->repository->store($data);
            if ($insertedData === false) {
                return new Response(400, 'error insert data', false, null, null);
            }
            return new Response(200, 'user inserted successfully', true, null, null);
        } catch (ValidationException $e) {
            return new Response($e->getCode(), $e->getMessage(), false, null, $e->getErrors());
        } catch (Exception $e){
            return new Response($e->getCode(), $e->getMessage(), false, null, null);
        }

    }


    public function update(string $id, array $data)
    {
        try {
            $this->repository->find($id);
            $userUpdated = $this->repository->update($id, $data);
            if ($userUpdated === false) {
                throw new Exception('error on update data');
            }
            return new Response(200, 'user updated successfully', true, $userUpdated);
        } catch (Exception $th) {
            return new Response(400, $th->getMessage(), false, null, null);
        }
    }


    public function delete($id)
    {
        try {
            $response = $this->repository->find($id);
            $this->repository->delete($id);
            return new Response(200,'user deleted', true, $response, null);
        } catch (Exception $th) {
            return new Response(404, $th->getMessage(), false, null);
        }
    }


   

    public function getById($id)
    {
        
        try {
            $result = $this->repository->find($id);
            return new Response(200, 'user data', true, $result);
        } catch (Exception $e) {
            return new Response(400, $e->getMessage(), false);
        }
    }


    public function getProductOfUser(string $productId)
    {
    }
}

?>