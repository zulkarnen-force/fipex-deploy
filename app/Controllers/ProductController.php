<?php

namespace App\Controllers;
use CodeIgniter\API\ResponseTrait;
use App\Models\ProductModel;
use Exception;

class ProductController extends BaseController
{
    // use ResponseTrait;
    public $db;
    public function __construct() {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {        
        $model = new ProductModel();
        $data = $model->getProduct();
        $points = $data[0]['points'];

        foreach($data as $key => $value)
        {
            $data[$key]['points'] = (int) $data[$key]['points'];
        }

        return $this->getResponse($data);

        // return var_dump($data);
        // return $this->getResponse($data);
        // return $this->getResponse($data);
        // return $this->getResponse($data);
    }

    // public function find()
    // {                
    //     $id = $this->getSegment(3);
    //     $userModel = new UserModel();        
    //     try {
    //         $user = $userModel->find($id);
    //         return $this->response->setJSON($user);
    //     } catch (\Exception $e) {
    //         die($e->getMessage());
    //     }        
        
    // }


    public function add()
    {
        $model = new Product();
        $data = [
            'id' => 'asad',
            'name' => 'aasd',
            'description' => 'aasd',
            'exhibition_id' => 'asad',
            'author_id' => 'asad',
            'total_points' => 100,
        ];

        // $result = $model->addProduct();
        // var_dump($result);
    }


}