<?php

namespace App\Api\Domains\GuestBook\Service;

use App\Api\Domains\GuestBook\Model\GuestBook;
use App\Api\Domains\GuestBook\Repository\IGuestBookRepository;
use App\Exceptions\ValidationException;
use App\Utils\Response;
use CodeIgniter\Database\Exceptions\DatabaseException;
use Exception;
use Throwable;

class GuestBookService
{
    public $repository;
    function __construct(IGuestBookRepository $repository)
    {
        $this->repository = $repository;
    }


 
    public function find($id, $fields = ["*"])
    {
        try {
            $user = $this->repository->find($id, $fields);
            return new Response(200, null, true, $user);
        } catch (Exception $th) {
            return new Response(404, $th->getMessage(), false);
        }
    }


    public function list($fields = ["*"])
    {
        $exhibitions = $this->repository->list($fields);
        return new Response(200, 'all guests books data', true, $exhibitions);
    }


    public function create($data)
    {
        try {
            $insertedData = $this->repository->store($data);
            if ($insertedData === false) {
                return new Response(400, 'error insert data', false, null, null);
            }
            return new Response(200, 'exhibition inserted successfully', true, null, null);
        } catch (ValidationException $e) {
            return new Response($e->getCode(), $e->getMessage(), false, null, $e->getErrors());
        } catch (Exception $e) {
            return new Response($e->getCode(), $e->getMessage(), false, null, null);
        } catch (Throwable $e){
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
            return new Response(200, 'guest book updated successfully', true, $userUpdated);
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
            return new Response(200, 'guest book data', true, $result);
        } catch (Exception $e) {
            return new Response(400, $e->getMessage(), false);
        }
    }
 


}

?>