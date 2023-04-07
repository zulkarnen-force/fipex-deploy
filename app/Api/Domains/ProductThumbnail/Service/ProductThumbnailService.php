<?php

namespace App\Api\Domains\ProductThumbnail\Service;

use App\Api\Domains\ProductThumbnail\Repository\IProductThumbnailRepository;
use App\Exceptions\ValidationException;
use App\Utils\Response;
use CodeIgniter\Database\Exceptions\DatabaseException;
use Exception;
use Throwable;

class ProductThumbnailService
{
    public $repository;
    function __construct(IProductThumbnailRepository $repository)
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
        return new Response(200, 'all exhibitions data', true, $exhibitions);
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
            return new Response(200, 'product thumbnail updated successfully', true, $userUpdated);
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
            return new Response(200, 'product thumbnail data', true, $result);
        } catch (Exception $e) {
            return new Response(400, $e->getMessage(), false);
        }
    }


    public function login($request)
    {
        try {
            $user = $this->repository->getWhere(['email' => $request['email']])[0];
            $hashPassword = $user->password;
            $plainPassword = $request['password'];
            if (!password_verify($plainPassword, $hashPassword)) {
                return new Response(400, 'password not match', false);
            }
            helper('jwt');
            $token = getSignedJWTForUser($user->id, $user->email);
            $response = new Response(200, 'user authenticated', true);
            $response->setResult(['token' => $token]);
            return $response;
        } catch (ValidationException $e) {
            return new Response(400, $e->getErrors(), false);
        } catch (Exception $e) {
            return new Response(400, $e->getMessage(), false);
        }
    }


    public function getProductByAuthorId(string $userId, array $fields = ["*"])
    {
        $result = $this->repository->getWhere(['author_id' => $userId], $fields);
        if ($result === false) {
            return new Response(404, 'product thumbnail not found', false, null, null);
        };
        return new Response(200, 'product thumbnail of user', true, $result, null);
    }


    public function getBadgesOfUser($userId, $fields = ["*"])
    {
        try {
            $result = $this->repository->getWhere(['user_id' => $userId], $fields);
            return new Response(200, 'badges of user', true, $result, null);
        } catch (Exception $e) {
            return new Response(404, $e->getMessage(), false, null, null);
        }

    }
    
        public function storeImageFromBase64($path, $base64)
    {
        try {
            $image = base64_decode($base64);
            file_put_contents($path, $image);
            return $path;
        } catch (Throwable $th) {
            throw $th;
        }
    }

    

 


}

?>