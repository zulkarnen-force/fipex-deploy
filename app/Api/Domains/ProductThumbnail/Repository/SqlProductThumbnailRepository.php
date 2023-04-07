<?php

namespace App\Api\Domains\ProductThumbnail\Repository;

use App\Api\Domains\Common\Repository\SqlCommonRepository;

class SqlProductThumbnailRepository extends SqlCommonRepository implements IProductThumbnailRepository
{
	public $model; 
	public function __construct()
    {
		parent::__construct('App\Api\Domains\ProductThumbnail\Model\ProductThumbnail');
		$this->model = $this->getModel();
	}
	
}