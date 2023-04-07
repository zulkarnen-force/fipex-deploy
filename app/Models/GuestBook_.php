<?php

namespace App\Models;

use CodeIgniter\Model;


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
