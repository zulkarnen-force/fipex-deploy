<?php

namespace App\Api\Domains\Exhibition\Model;

use CodeIgniter\Model;
use Exception;

class Exhibition extends Model
{
    protected $table = 'exhibitions';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'name',
        'description',
        'date_start',
        'date_end',
        'scoring_start',
        'scoring_end',
        'register_start',
        'register_end',
        'image_url',
    ];

    protected $updatedField = 'updated_at';
    protected $beforeInsert = ['generateId'];

    protected function generateId($data) {
       $data['data']['id'] =  uniqid();
       return $data;
    }

    protected $validationRules = [
        'name' => 'required',
        'description' => 'required',
        'date_start' => 'required',
        'date_end' => 'required',
        'scoring_start' => 'required',
        'scoring_end' => 'required',
        'register_start' => 'required',
        'register_end' => 'required',
        'image_url' => 'required',
    ];

    protected $validationMessages = [
        'comment' => [
            'product_id' => 'product_id required hehe',
        ],
        'user_id' => [
            'required' => 'user id required hehe',
        ],
    ];


    
}

