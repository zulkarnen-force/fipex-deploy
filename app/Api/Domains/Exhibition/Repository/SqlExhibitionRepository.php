<?php

namespace App\Api\Domains\Exhibition\Repository;

use App\Api\Domains\Common\Repository\SqlCommonRepository;

class SqlExhibitionRepository extends SqlCommonRepository implements IExhibitionRepository
{
	public $model; 
	public function __construct()
    {
		parent::__construct('\App\Api\Domains\Exhibition\Model\Exhibition');
		$this->model = $this->getModel();
	}
	
}