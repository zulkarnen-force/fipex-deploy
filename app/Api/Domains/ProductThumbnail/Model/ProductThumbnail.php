<?php

namespace App\Api\Domains\ProductThumbnail\Model;

use CodeIgniter\Model;
use Exception;


class ProductThumbnail extends Model
{
    protected $table = 'product_thumbnails';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'product_id',
        'image_url',
    ];

    protected $updatedField = 'updated_at';
    protected $beforeInsert = ['generateId'];

    protected function generateId($data)
    {
        $data['data']['id'] = uniqid();
        return $data;
    }

    protected $validationRules = [
        'image_url'        => 'required',
        'product_id'        => 'required',
    ];

    protected $validationMessages = [
        'image_url' => [
            'required' => 'let your nice img url',
        ],
    ];


    public function getThumbnailsOfProduct(string $productId)
    {
        $query = $this->select('image_url')->where('product_id', $productId);
        $thumbnails = $query->get()->getResult();
        return $thumbnails;
    }

}