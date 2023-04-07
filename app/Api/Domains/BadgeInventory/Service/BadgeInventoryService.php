<?php

namespace App\Api\Domains\BadgeInventory\Service;

use App\Api\Domains\BadgeInventory\Repository\IBadgeInventoryRepository;
use App\Exceptions\ValidationException;
use App\Models\ProductThumbnail;
use App\Utils\Response;
use CodeIgniter\Database\Exceptions\DatabaseException;
use Exception;

class BadgeInventoryService
{
    public $repository;
    function __construct(IBadgeInventoryRepository $repository)
    {
        $this->repository = $repository;
    }


    public function find($id)
    {
        try {
            $user = $this->repository->find($id, ['name']);
            return new Response(200, null, true, $user);
        } catch (Exception $th) {
            return new Response(404, $th->getMessage(), false);
        }
    }


    public function list()
    {
        $users = $this->repository->list();
        return new Response(200, 'all badge inventories data', true, $users);
    }


    public function create($data)
    {
        try {
            $insertedData = $this->repository->store($data);
            if ($insertedData === false) {
                return new Response(400, 'error insert data', false, null, null);
            }
            return new Response(201, 'badge inventory created successfully', true, null, null);
        } catch (ValidationException $e) {
            return new Response($e->getCode(), $e->getMessage(), false, null, $e->getErrors());
        } catch (Exception $e){
            return new Response($e->getCode(), $e->getMessage(), false, null, null);
        }

    }


    public function update(string $id, $data = [])
    {
        try {
            $this->repository->find($id);
            $userUpdated = $this->repository->update($id, $data);
            if ($userUpdated === false) {
                throw new Exception('error on update data');
            }
            return new Response(201, 'badge inventory updated successfully', true, $userUpdated);
        } catch (ValidationException $e) {
            return new Response(400, $e->getErrors(), false, null, null);
        } catch (Exception $e) {
            return new Response(400, $e->getMessage(), false, null, null);
        }
    }



    public function delete($id)
    {
        try {
            $response = $this->repository->find($id);
            $deleted = $this->repository->delete($id);
            return new Response(200, 'exhibition deleted', true, $response, null);
        } catch (DatabaseException $th) {
            return new Response($th->getCode(), $th->getMessage(), false, null);
        } catch (Exception $th) {
            return new Response(404, $th->getMessage(), false, null);
        }
    }


    public function getById($id)
    {
        
        try {
            $result = $this->repository->find($id);
            return new Response(200, 'product data', true, $result);
        } catch (Exception $e) {
            return new Response(400, $e->getMessage(), false);
        }
    }


    public function getProductByAuthorId(string $userId, array $fields = ["*"])
    {
        $result = $this->repository->getWhere(['author_id' => $userId], $fields);
        if ($result === false) {
            return new Response(404, 'product not found', false, null, null);
        };
        return new Response(200, 'product of user', true, $result, null);
    }


    public function getBadgesOfUser($userId, $fields = ["*"])
    {
        try {
            $result = $this->repository->getWhere(['user_id' => $userId], $fields);
            if (!$result) {
                throw new Exception('badge of user not found');
            }
            return new Response(200, 'badges of user', true, $result[0], null);
        } catch (Exception $e) {
            return new Response(404, $e->getMessage(), false, null, null);
        }

    }

    

 


}

?>