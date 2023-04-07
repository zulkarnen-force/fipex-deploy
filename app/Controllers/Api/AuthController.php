<?php
 
namespace App\Controllers\Api;

use App\Models\User;
use App\Repositories\AuthRepository;
use App\Services\AuthService;
use CodeIgniter\RESTful\ResourceController;

class AuthController extends ResourceController
{
    use \CodeIgniter\HTTP\ResponseTrait;
    public $db;
    public $service;
    public function __construct() {
        $this->db = \Config\Database::connect();
        $this->service = new AuthService(new AuthRepository(new User()));
    }

    public function register()
    {
        $request = $this->request->getJSON(true);
        $response = $this->service->register($request);
        return $this->respond($response->getResponse(), $response->getCode());
    }

    public function login()
    {
        $validation = \Config\Services::validation();

        $validation->setRules([
            'email' => 'required',
            'password' => 'required|min_length[10]',
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


}