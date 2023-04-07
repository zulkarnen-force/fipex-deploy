<?php

namespace App\Api\Domains\User\Model;

use CodeIgniter\Model;
use Exception;

class User extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'id',
        'name',
        'email',
        'is_author',
        'password',
        'image_url',
        'bio'
    ];

    protected $updatedField = 'updated_at';
    protected $validationRules = [
        'id'     => 'is_unique[users.id]',
        'name' => 'required',
        'email' => 'required|min_length[6]|max_length[50]|valid_email|is_unique[users.email]',
        'password' => 'required|min_length[8]|max_length[255]',
    ];
    protected $validationMessages = [

    ];


    
    protected $beforeInsert = ['encryptPassword', 'generateId'];
    protected $beforeUpdate = ['beforeUpdate'];

    protected function generateId($data)
    {
        if (isset($data['data']['id'])) {
            return $data;
        }
        
        $data['data']['id'] = uniqid();
        return $data;
    }
    protected function encryptPassword(array $data): array
    {
        return $this->getUpdatedDataWithHashedPassword($data);
    }

    protected function beforeUpdate(array $data): array
    {
        return $this->getUpdatedDataWithHashedPassword($data);
    }

    private function getUpdatedDataWithHashedPassword(array $data): array
    {
        if (isset($data['data']['password'])) {
            $plaintextPassword = $data['data']['password'];
            $data['data']['password'] = $this->hashPassword($plaintextPassword);
        }
  
        return $data;
    }

    private function hashPassword(string $plaintextPassword): string
    {
        return password_hash($plaintextPassword, PASSWORD_BCRYPT);
    }
                                      
    public function findUserByEmailAddress(string $emailAddress)
    {
        $user = $this
            ->asArray()
            ->where(['email' => $emailAddress])
            ->first();

        if (!$user) 
            throw new Exception('User does not exist for specified email address');

        return $user;
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
