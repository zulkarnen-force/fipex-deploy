<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;
use phpDocumentor\Reflection\PseudoTypes\True_;

class ProductModel extends Model
{
    protected $table = 'products';
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
        'id'     => 'is_unique[products.id]',
        'name'        => 'required|is_unique[products.name]|min_length[3]',
        // 'password'     => 'required|min_length[8]',
        // 'pass_confirm' => 'required_with[password]|matches[password]',
    ];
    protected $validationMessages = [
        'name' => [
            'is_unique' => 'Sorry. That name has already been taken. Please choose another.',
            'required' => 'require name, please let your name',
        ],
        'id' => [
            'is_unique' => 'Sorry. That id has already been taken. Please choose another.',
        ],
    ];
    // protected $beforeInsert = ['beforeInsert'];
    // protected $beforeUpdate = ['beforeUpdate'];

    // protected function beforeInsert(array $data): array
    // {
    //     return $this->getUpdatedDataWithHashedPassword($data);
    // }

    // protected function beforeUpdate(array $data): array
    // {
    //     return $this->getUpdatedDataWithHashedPassword($data);
    // }

    // private function getUpdatedDataWithHashedPassword(array $data): array
    // {
    //     if (isset($data['data']['password'])) {
    //         $plaintextPassword = $data['data']['password'];
    //         $data['data']['password'] = $this->hashPassword($plaintextPassword);
    //     }
    //     $data['data']['id'] = uniqid();
    //     return $data;
    // }

    // private function hashPassword(string $plaintextPassword): string
    // {
    //     return password_hash($plaintextPassword, PASSWORD_BCRYPT);
    // }
                                      
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
