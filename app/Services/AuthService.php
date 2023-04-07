<?php

namespace App\Services;

use App\Exceptions\ValidationException;
use App\Repositories\AuthRepository;
use App\Utils\Response;
use Exception;

class AuthService
{
    private $repository;
    function __construct(AuthRepository $repository)
    {
        $this->repository = $repository;
    }


    public function register($requset)
    {
        $requset['id'] = uniqid();
    
        try {
            $this->repository->save($requset);
            helper('jwt');
            $token = getSignedJWTForUser($requset['id'], $requset['email']);
            $response = new Response(200, 'user created successfully', true);
            $response->setResult(['token' => $token]);
            return $response;
        } catch (ValidationException $e) {
            return new Response(400, $e->getErrors(), false);
        } catch (Exception $e) {
            return new Response(400, $e->getTrace(), false);
        }
        
    }


    public function login($request)
    {           
        try {
            $user = $this->repository->getByEmail($request['email']);
            $hashPassword = $user['password'];
            $plainPassword = $request['password'];
            if (!password_verify($plainPassword, $hashPassword))
            {
                return new Response(400, 'password not match', false);
            }
            helper('jwt');
            $token = getSignedJWTForUser($user['id'], $user['email']);
            $response = new Response(200, 'user authenticated', true);
            $response->setResult(['token' => $token]);
            return $response;
        } catch (ValidationException $e) {
            return new Response(400, $e->getErrors(), false);
        } catch (Exception $e) {
            return new Response(400, $e->getMessage(), false);
        }
        
    }

    public function find($id)
    {
        return $this->repository->find($id);
    }


    public function list()
    {
        return $this->repository->list();
    }

    public function create($data)
    {
        return  $this->repository->insert($data);
        // try {
        //     $result = $this->repository->insert($data);
        //     return new Response(201, $result, false);
        // } catch (ValidationException $e) {
        //     return new Response(400, $e->getMessage(), false);
        // } catch (Exception $e) {
        //     return new Response(400, $e->getMessage(), false);
        // }
    }


    public function update($id, $data)
    {
        
        if (is_null($data) || count($data) === 0)
        {
            return [false, 'no body requset'];
        }

        try {
            return $this->repository->update($id, $data);
        } catch (Exception $e) {
            throw $e;
        }
    }


    public function getById($id)
    {
        return $this->repository->getById($id);
    }

    
    public function delete($id)
    {

        return $this->repository->delete($id);

    }

}

?>