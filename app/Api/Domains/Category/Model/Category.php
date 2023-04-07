<?php

namespace App\Api\Domains\Category\Model;

use CodeIgniter\Model;
use Exception;

class Category extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'category_name',
        'exhibition_id',
    ];
    protected $beforeInsert = ['generateId'];

    protected function generateId($data) {
       $data['data']['id'] =  uniqid();
       return $data;
    }


    protected $updatedField = 'updated_at';

    
    protected $validationRules = [
        'category_name' => 'required',
        'exhibition_id' => 'required',
    ];

    protected $validationMessages = [

    ];



}
