<?php

namespace App\Api\Domains\GuestBook\Model;

use CodeIgniter\Model;
use Exception;


class GuestBook extends Model
{
    protected $table = 'guest_books';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'comment',
        'exhibition_id',
        'user_id',
        'image_url',
    ];

    protected $updatedField = 'updated_at';

    
    protected $beforeInsert   = ['generateId'];
    protected function generateId($data)
    {
        $data['data']['id'] = uniqid();
        return $data;
    }
    protected $validationRules = [
        'comment'        => 'required',
        'user_id'        => 'required',
    ];

    protected $validationMessages = [
        'comment' => [
            'required' => 'comment required hehe',
        ],
        'user_id' => [
            'required' => 'user id required hehe',
        ],
    ];

}
