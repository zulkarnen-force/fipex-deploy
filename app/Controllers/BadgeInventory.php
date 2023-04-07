<?php

namespace App\Controllers;
use CodeIgniter\API\ResponseTrait;
use App\Models\BadgeInventoryModel;
use Exception;

class Product extends BaseController
{
    // use ResponseTrait;
    public $db;
    public function __construct() {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {        
        $model = new BadgeInventoryModel();
        $data = $model->get();
        // $category_ids = array_map('intval', array_column($$, 'id'));
        // $test = array_map(function($v) { return (int)$v['total_points']; }, $data);

        var_dump($data);
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
}