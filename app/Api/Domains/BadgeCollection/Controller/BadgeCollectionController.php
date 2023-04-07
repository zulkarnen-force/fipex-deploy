<?php

namespace App\Api\Domains\BadgeCollection\Controller;

use App\Api\Domains\BadgeCollection\Model\BadgeCollection;
use App\Api\Domains\BadgeCollection\Repository\SqlBadgeCollectionRepository;
use App\Api\Domains\BadgeCollection\Service\BadgeCollectionService;
use App\Api\Domains\Product\Model\Product;
use CodeIgniter\RESTful\ResourceController;


class BadgeCollectionController extends ResourceController
{
    public $service;
    public function __construct() {
        $this->service = new BadgeCollectionService(new SqlBadgeCollectionRepository());
    }

    public function index()
    {
        $res = $this->service->list();
        if (!$res->isSuccess()) 
        {
            return $this->respond($res->getResponse(), $res->getCode());
        }
        return $this->respond($res->getData());
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
        if (!$response->isSuccess())
        {
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
    // select badge_type, 
    // @silver:= sum(case when badge_type = 'silver' then 1 else 0 end) as silver,
    // @gold:=sum(case when badge_type = 'gold' then 1 else 0 end) as gold,
    // @platinum:=sum(case when badge_type = 'platinum' then 1 else 0 end) as platinum,
    // @total:= @silver*25 + (@gold*50) + (@platinum*100) as total_points
    // from badges_collection bc where product_id = '63ae1e4165aac';

    // $db = \Config\Database::connect();
    // $result = $db->query("SELECT badge_type, 
    // @silver:= sum(case when badge_type = 'silver' then 1 else 0 end) as silver,
    // @gold:=sum(case when badge_type = 'gold' then 1 else 0 end) as gold,
    // @platinum:=sum(case when badge_type = 'platinum' then 1 else 0 end) as platinum,
    // @total:= (@silver*25) + (@gold*50) + (@platinum*100) as total_points,
    // from badges_collection bc where product_id = ?"
    // , $productId);

    function getBadgesOfProduct($productId)
    {
        $response = $this->service->getBadgesWithTotalPoints($productId);

        
        
        if (!$response->isSuccess()) {
            return $this->respond($response->getResponse(), $response->getCode());
        }
        
        return $this->respond($response->getData(), $response->getCode());

  
    }

    public function getCommentsOfProduct($productId)
    {
        return $this->respond($this->service->getCommentsOfProduct($productId));
    }    


    public function sendBadgeUserToProduct($productId)
    {
        $requset = $this->request->getJSON(true);
        
        helper('jwt');
        $userId = toPayloadFromRequset($this->request)['id'];
        $requset['product_id'] = $productId;
        $requset['user_id'] = $userId;
        
        $result = $this->service->sendBadgeToProduct($requset);
        return $this->respond($result->getResponse(), $result->getCode());
    }


    public function cancleBadgeOfProduct($productId) 
    {
        helper('jwt');
        $userId = toPayloadFromRequset($this->request)['id'];
        $response = $this->service->cancleBadgeOfUser($userId, $productId);
        return $this->respond($response->getResponse(), $response->getCode());
    }

    // select e.* from products p join exhibitions e on exhibition_id = p.exhibition_id where p.id = '63ae1e4165aac';

    public function checkUserHasGivenBadge($productId)
    {
        helper('jwt');
        $userId = toPayloadFromRequset($this->request)['id'];

        $exhibitionModel = new Product();
        $exhibitionQuery = $exhibitionModel->select('e.*')->join('exhibitions e', 'e.id = products.exhibition_id')->where('products.id', $productId);
        $exhibitionResult = $exhibitionQuery->get()->getResult();

        $bc = new BadgeCollection();
        $q = $bc->select(['e.*'])->join('exhibitions e', 'e.id = badges_collection.exhibition_id')->where('product_id', $productId)->where('user_id', $userId);
        $hasGiven = $q->get()->getResult();
        if ($hasGiven) {
            return $this->respond(['has_given' => true, 'message' => 'this user has given badge to this product'], 200);
        }
        return $this->respond(['has_given' => false, 'exhibitions' => $exhibitionResult[0], 'message' => 'this user belum pernah memberikan badge pada produk ini'], 200);
    }


}