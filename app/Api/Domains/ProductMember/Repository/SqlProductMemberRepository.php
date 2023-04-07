<?php

namespace App\Api\Domains\ProductMember\Repository;

use App\Api\Domains\Common\Repository\SqlCommonRepository;
use Exception;

class SqlProductMemberRepository extends SqlCommonRepository implements IProductMemberRepository
{
	public $model; 
	public function __construct()
    {
		parent::__construct('\App\Api\Domains\ProductMember\Model\ProductMember');
		$this->model = $this->getModel();
	}

	public function getProductOfMemberUser($id)
	{
		$query = $this->model->select('p.*')
        ->join('products p', 'p.id = product_members.product_id')
        ->where(['product_members.user_id' => $id]);

		$result = $query->get()->getResult();
	
		if (!$result) {
			throw new Exception('user member no have product');
		}

		return $result;
	}
	

	
}