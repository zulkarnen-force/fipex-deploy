<?php
 
namespace App\Controllers\Api;
 
use App\Models\Exhibition;
use App\Repositories\Common\CommonRepository;
use App\Services\ExhibitionService;
use CodeIgniter\RESTful\ResourceController;

class ExhibitionController extends ResourceController
{
    // use ResponseTrait;
    public $db;
    public $service;

    // use ResponseTrait;
    public function __construct() {
        $this->db = \Config\Database::connect();
        $this->service = new ExhibitionService(new CommonRepository(new Exhibition()));
    }
    public function index()
    {
        $products = $this->service->list();
        return $this->respond($products->getResponse(), $products->getCode());
    }
 
    
    public function create()
    {
        $requestJson = $this->request->getJson(true);
        $requestJson['id'] = bin2hex(random_bytes(10));

        $response = $this->service->create($requestJson);
 
        return $this->respond($response->getResponse(), $response->getCode());

    }
    

    public function show($id = null)
    {
        $response = $this->service->getById($id);
        return $this->respond($response->getResponse(), $response->getCode());

    }
    // update
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

    
}