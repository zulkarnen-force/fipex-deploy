<?php

namespace App\Api\Domains\BadgeInventory\Model;

use CodeIgniter\Model;

class BadgeInventory extends Model
{
    protected $table = 'badge_inventories';
    protected $primaryKey = 'id';
    protected $updatedField = 'updated_at';

    protected $allowedFields = [
        'badge_type',
        'badge_count',
        'user_id',
        'exhibition_id',
    ];

    protected $validationRules = [
        // 'badge_type'        => 'required|in_list[silver,gold,platinum]',
        // 'badge_count'        => 'required',
        'user_id'        =>  'required',
        'exhibition_id'        => 'required',
    ];

    protected $validationMessages = [
     
    ];

    protected $beforeInsert   = ['generateId'];
    protected function generateId($data)
    {
        $data['data']['id'] = uniqid();
        return $data;
    }

    

}