<?php
 
namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\ProductModel;
use App\Repositories\Products\ProductRepository;
use App\Services\ProductService;


class ProductController extends ResourceController
{
    // use ResponseTrait;
    public $db;
    public $service;
    public function __construct() {
        $this->db = \Config\Database::connect();
        $this->service = new ProductService(new ProductRepository(new ProductModel()));
    }

    // all users
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

    public function getAuthor($product_id= null)
    {
        $response = $this->service->getAuthorByProductId($product_id);
        return $this->respond($response->getResponse(), $response->getCode());
    }

    
  
    public function getProductDetail($productId)
    {
        $response = $this->service->getProductDetail($productId);
        return $this->respond($response);
    }

    
    public function getProductByUserId()
    { 
        $mdl = new ProductModel();
        $result = $mdl->where(['author_id' => '63a7f2a793dbf'])->get();
        return $this->respond($result->getResult());

    }

    

}