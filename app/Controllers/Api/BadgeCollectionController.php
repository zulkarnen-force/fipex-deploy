<?php
 
namespace App\Controllers\Api;
 
use App\Models\BadgeCollection;
use App\Models\UserModel;
use App\Repositories\BadgeCollectionRepository;
use App\Repositories\Common\CommonRepository;
use App\Services\BadgeCollectionService;
use CodeIgniter\Debug\Toolbar\Collectors\BaseCollector;
use CodeIgniter\RESTful\ResourceController;

class BadgeCollectionController extends ResourceController
{
    // use ResponseTrait;
    public $db;
    public $service;
    public function __construct() {
        $this->db = \Config\Database::connect();
        $this->service = new BadgeCollectionService(new BadgeCollectionRepository(new BadgeCollection()));
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


    public function getCommentsProduct(string $productId)
    {
        $response = $this->service->getCommentsProduct($productId);
        return $this->respond($response);
    }

    
    public function getBadgesCount($productId)
    {
        $response = $this->service->getBadgesCountProduct($productId);
        return $this->respond($response);
    }
    
}