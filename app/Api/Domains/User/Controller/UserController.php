<?php

namespace App\Api\Domains\user\Controller;

use App\Api\Domains\User\Service\UserService;
use App\Api\Domains\User\Repository\SqlUserRepository;
use CodeIgniter\HTTP\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;
use Exception;

class UserController extends ResourceController
{
    public $service;
    use ResponseTrait;
    public function __construct() {
        $this->service = new UserService(new SqlUserRepository());
    }

    public function index()
    {
        $products = $this->service->list();
        return $this->respond($products->getData());
    }

    public function register()
    {
        $requestJson = $this->request->getJson(true);
        $response = $this->service->create($requestJson);
        return $this->respond($response->getResponse(), $response->getCode());
    }

    public function show($id = null)
    {
        $response = $this->service->find($id, ["id", "email", "name", "bio", "image_url", 'is_author', "created_at", "updated_at"]);

        if (!$response->isSuccess()) {
            return $this->respond($response->getResponse(), $response->getCode());
        }
        
        return $this->respond($response->getData(), $response->getCode());

    }
    public function update($id = null)
    {
        $requset = $this->request->getJson(true);
        $response = $this->service->update($id, $requset);
        return $this->respond($response->getResponse(), $response->getCode());
    }


    public function destroy($id = null)
    {
        $response = $this->service->delete($id);
        return $this->respond($response->getResponse(), $response->getCode());
    }

    public function login()
    {
        $validation = \Config\Services::validation();

        $validation->setRules([
            'email' => 'required',
            'password' => 'required',
        ]);

        $validation->withRequest($this->request)->run();
        if (!empty($validation->getErrors())) {     
            return $this->fail(
                $validation->getErrors()
            );
        }

        $request = $this->request->getJSON(true);
        $response = $this->service->login($request);
        return $this->respond($response->getResponse(), $response->getCode());

    }
    
    public function me() 
    {
        helper('jwt');
        return $this->respond(toPayloadFromRequset($this->request));   
    }

    public function storeImage()
    {

        try {
             $validation = \Config\Services::validation();
            $validation->setRules([
                'base64' => 'required',
                'extension' => 'required',
            ]);
            $validation->withRequest($this->request)->run();

            if (!empty($validation->getErrors())) {
                return $this->fail(
                    $validation->getErrors()
                );
            }

            helper('jwt');
            $userId = toPayloadFromRequset($this->request)['id'];
            $base64 = $this->request->getJsonVar('base64');
            $ext = $this->request->getJsonVar('extension');
            $filename = uniqid() . "." . $ext;
            
            $path = 'public/images/users/' . $filename;
            $url = $this->service->storeImageFromBase64($path, $base64);
            
            $response = $this->service->update($userId, ['image_url' => $url]);
            return $this->respond(['message' => 'image uploaded successfully', 'path' => $url], 200);

        }    catch (\Throwable $th) {
            return $this->respond($th->getMessage(), $th->getCode());
        }

    }


}