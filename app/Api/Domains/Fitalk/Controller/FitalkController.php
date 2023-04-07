<?php

namespace App\Api\Domains\Fitalk\Controller;

use App\Api\Domains\Fitalk\Model\Fitalk;
use CodeIgniter\RESTful\ResourceController;
use Exception;

class FitalkController extends ResourceController
{
    public $service;
    public function __construct() {
    }

    public function list()
    {
        $m = new Fitalk();
        try {
            return $this->respond($m->list(), 200);
        } catch (\Throwable $th) {
            return $this->respond($th->getMessage(), 400);

        }
    }


    public function setStatus($id)
    {
        $m = new Fitalk();
        $rq = $this->request->getJSON(true);
        try {
            $res = $m->setStatus($id, $rq);
            return $this->respond($res, 200);
        } catch (\Throwable $th) {
            return $this->respond($th->getMessage(), 400);
        }
    }



}