<?php

namespace App\Api\Domains\BadgeCollection\Model;

use CodeIgniter\Model;

class BadgeCollection extends Model
{
    protected $table = 'badges_collection';
    protected $primaryKey = 'id';
    protected $beforeInsert = ['generateId'];
    protected $allowedFields = [
        'id',
        'badge_type',
        'comment',
        'exhibition_id',
        'product_id',
        'user_id',
    ];

    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'badge_type'        => 'required|in_list[silver,gold,platinum]',
        'user_id'        => 'required',
        'exhibition_id'        => 'required'
    ];

    protected $validationMessages = [
        'comment' => [
            'product_id' => 'product_id required hehe',
        ],
        'user_id' => [
            'required' => 'user id required hehe',
        ],
    ];

    protected function generateId($data)
    {
        $data['data']['id'] = uniqid();
        return $data;
    }

}
