<?php

namespace App\Api\Domains\Product\Model;

use CodeIgniter\Model;
use Exception;

class Product extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'name',
        'description',
        'exhibition_id',
        'category_id',
        'author_id',
        'total_points'
    ];

    protected $updatedField = 'updated_at';


    protected $validationRules = [
        // 'id'     => 'is_unique[products.id]',
        // 'name'        => 'required|is_unique[products.name]|min_length[3]',
        'author_id'        => 'required',
        'exhibition_id'        => 'required',
        'category_id'        => 'required',
    ];
    protected $validationMessages = [
        
    ];

    protected $beforeInsert   = ['generateId'];
    
    protected function generateId($data)
    {
        if (isset($data['data']['id'])) {
            return $data;
        }
        
        $data['data']['id'] = uniqid();
        return $data;
    }
  
 
    public function getProducts($id = false)
    {
        $result = $this->orderBy('id', 'DESC')->findAll();
        return $result;
    }


    public function addProduct($data)
    {
        try {
            return $this->insert($data, true);
        } catch(Exception $e) {
            throw $e;
        }
        
    }

    public function findUserByUid(string $uid)
    {
        $user = $this
            ->asArray()
            ->where(['uid' => $uid])
            ->first();

        if (!$user) 
            throw new Exception('User does not exist for specified email address');

        return $user;
    }

    
}
