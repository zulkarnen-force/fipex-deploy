<?php

namespace App\Services;

use App\Repositories\Common\RepositoryInterface;

class BaseService {

    private $repository;
    function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }


    public function find($id)
    {
        return $this->repository->find($id);
    }


    public function list()
    {
        return $this->repository->list();
    }

    public function create($data)
    {
        return $this->repository->insert($data);
    }

    public function delete($id)
    {
        return $this->repository->delete($id);
    }


}


?>