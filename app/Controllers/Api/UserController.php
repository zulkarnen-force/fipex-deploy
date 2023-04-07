<?php
 
namespace App\Controllers\Api;
 

use App\Models\User;
use App\Models\UserModel;
use App\Repositories\User\SqlUserRepository;
use App\Services\UserService;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;


class UserController extends ResourceController
{
    use ResponseTrait;
    public $db;
    public $service;
    use ResponseTrait;
    public function __construct() {
        $this->db = \Config\Database::connect();
        $this->service = new UserService(new SqlUserRepository());
    }


    public function index()
    {
        $result = $this->service->list();
        return $this->respond($result->getResponse(), $result->getCode());
    }
 
    
    public function create()
    {
        $requestJson = $this->request->getJson(true);
        $response = $this->service->create($requestJson);
        return $this->respond($response->getResponse(), $response->getCode());
    }
    

    public function show($id = null)
    {
        $response = $this->service->find($id);
        if ($response->isSuccess()) {
            return $this->respond($response->getData());        
        }
        return $this->respond($response->getResponse());
    }

    public function updateUser()
    {
        helper('jwt');
        $id = toPayloadFromRequset($this->request)['id'];
        $request = $this->request->getJSON(true);
        $result = $this->service->update($id, $request);
        return $this->respond($result->getResponse(), $result->getCode());
    }

    public function destroy($id)
    {
        $response = $this->service->delete($id);
        return $this->respond($response->getResponse(), $response->getCode());
    }

}