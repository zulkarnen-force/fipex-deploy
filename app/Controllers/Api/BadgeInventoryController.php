<?php
 
namespace App\Controllers\Api;
 
use App\Models\BadgeInventory;
use App\Repositories\BadgeInventoryRepository;
use App\Repositories\Common\CommonRepository;
use App\Services\BadgeInventoryService;
use CodeIgniter\RESTful\ResourceController;

class BadgeInventoryController extends ResourceController
{
    // use ResponseTrait;
    public $db;
    public $service;
    public function __construct() {
        $this->db = \Config\Database::connect();
        $this->service = new BadgeInventoryService(new CommonRepository(new BadgeInventory()));
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

    public function getInventoryUser()
    {
        $repository = new BadgeInventoryRepository(new BadgeInventory());
        helper('jwt');
        $id = toPayloadFromRequset($this->request)['id'];
              
        return $this->respond($repository->getBadgeByUserId($id));
    }

}