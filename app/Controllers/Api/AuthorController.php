<?php
 
namespace App\Controllers\Api;

use App\Models\ProductModel;
use App\Models\User;
use App\Repositories\AuthRepository;
use App\Services\AuthService;
use CodeIgniter\RESTful\ResourceController;

class AuthorController extends ResourceController
{
    use \CodeIgniter\HTTP\ResponseTrait;
    public $db;
    public $service;
    public function __construct() {
        $this->db = \Config\Database::connect();
        
    }

    public function getAuthorByProductId(string $productId)
    {
        $builder = new User();
        $builder->select('users.*')->join('products', 'users.id = products.author_id')->where('products.id', $productId);
        $result = $builder->get()->getResult("object")[0];
        $result->type = 'author';
        
        return $this->respond($result);

    }


}