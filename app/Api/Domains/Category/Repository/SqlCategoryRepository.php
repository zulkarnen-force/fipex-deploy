<?php

namespace App\Api\Domains\Category\Repository;

use App\Api\Domains\Common\Repository\SqlCommonRepository;

class SqlCategoryRepository extends SqlCommonRepository implements ICategoryRepository
{
	public $model; 
	public function __construct()
    {
		parent::__construct('\App\Api\Domains\Category\Model\Category');
		$this->model = $this->getModel();
	}
	
}