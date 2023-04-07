<?php

namespace App\Api\Domains\BadgeInventory\Repository;

use App\Api\Domains\Common\Repository\SqlCommonRepository;

class SqlBadgeInventoryRepository extends SqlCommonRepository implements IBadgeInventoryRepository
{
	public $model; 
	public function __construct()
    {
		parent::__construct('App\Api\Domains\BadgeInventory\Model\BadgeInventory');
		$this->model = $this->getModel();
	}


	
}