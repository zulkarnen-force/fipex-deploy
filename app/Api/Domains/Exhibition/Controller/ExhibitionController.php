<?php

namespace App\Api\Domains\Exhibition\Controller;

use App\Api\Domains\Exhibition\Service\ExhibitionService;
use App\Api\Domains\Exhibition\Repository\SqlExhibitionRepository;
use CodeIgniter\RESTful\ResourceController;

class ExhibitionController extends ResourceController
{
    public $service;
    public function __construct() {
        $this->service = new ExhibitionService(new SqlExhibitionRepository());
    }

    public function index()
    {
        $products = $this->service->list();
        return $this->respond($products->getData());
    }

    public function create()
    {
        $requestJson = $this->request->getJson(true);
        $response = $this->service->create($requestJson);
        return $this->respond($response->getResponse(), $response->getCode());
    }

    public function show($id = null)
    {
        $response = $this->service->find($id);
        if (!$response->isSuccess()) {
            return $this->respond($response->getResponse(), $response->getCode());
        }
        return $this->respond($response->getData(), $response->getCode());

    }
    public function update($id = null)
    {
        $requset = $this->request->getJson(true);
        $response = $this->service->update($id, $requset);
        return $this->respond($response->getResponse(), $response->getCode());
    }


    public function destroy($id = null)
    {
        $response = $this->service->delete($id);
        return $this->respond($response->getResponse(), $response->getCode());
    }


}