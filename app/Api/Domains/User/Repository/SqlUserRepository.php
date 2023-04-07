<?php

namespace App\Api\Domains\User\Repository;

use App\Api\Domains\Common\Repository\SqlCommonRepository;

class SqlUserRepository extends SqlCommonRepository implements IUserRepository
{
	public $model; 
	public function __construct()
    {
		parent::__construct('\App\Api\Domains\User\Model\User');
		$this->model = model('\App\Api\Domains\User\Model\User');
	}


	
}