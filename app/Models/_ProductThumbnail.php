<?php

namespace App\Models;

use CodeIgniter\Model;


class ProductThumbnail extends Model
{
    protected $table = 'product_thumbnails';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'product_id',
        'image_url',
    ];

    protected $updatedField = 'updated_at';

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
