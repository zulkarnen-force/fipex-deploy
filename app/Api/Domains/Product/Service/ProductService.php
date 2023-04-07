<?php

namespace App\Api\Domains\Product\Service;

use App\Api\Domains\Category\Model\Category;
use App\Api\Domains\Product\Model\Product;
use App\Api\Domains\Product\Repository\IProductRepository;
use App\Api\Domains\Product\Repository\SqlProductRepository;
use App\Api\Domains\ProductMember\Model\ProductMember;
use App\Exceptions\ValidationException;
use App\Api\Domains\ProductThumbnail\Model\ProductThumbnail;
use App\Utils\Response;
use CodeIgniter\Database\Exceptions\DatabaseException;
use Exception;
use PhpParser\Node\Expr\Throw_;
use Throwable;

class ProductService
{
    public $repository;
    function __construct(IProductRepository $repository)
    {
        $this->repository = $repository;
    }

    
    public function list()
    {
        try {
            $products = $this->repository->list();
            array_map(function ($prd) {
                $prd->thumbnails = $this->getThumbnails($prd->id);
                $prd->total_points = (int) $prd->total_points;
                return $prd;
            }, $products);
            return new Response(200, null, true, $products);
        } catch (Exception $th) {
            return new Response(404, $th->getMessage(), false);
        }
    }


    public function find($id)
    {
        try {
            $product = $this->repository->find($id);
            return new Response(200, null, true, $product);
        } catch (Exception $th) {
            return new Response(404, $th->getMessage(), false);
        }
    }


 
    function getProductWithThumbnails()
    {
        try {
            $r = $this->repository->list();
            return new Response(200, 'products data with thumbnails', true, $r);
        } catch (Exception $e) {
            return new Response($e->getCode(), $e->getMessage(), false, null);
        }

    }


    public function create($data)
    {
        try {
            $insertedData = $this->repository->store($data);
            if ($insertedData === false) {
                return new Response(400, 'error insert data', false, null, null);
            }
            return new Response(201, 'product created successfully', true, null, null);
        } catch (ValidationException $e) {
            return new Response($e->getCode(), $e->getMessage(), false, null, $e->getErrors());
        } catch (Exception $e){
            return new Response($e->getCode(), $e->getMessage(), false, null, null);
        }

    }


    public function update(string $id, $data = [])
    {
        try {
            $this->repository->find($id);
            $userUpdated = $this->repository->update($id, $data);
            if ($userUpdated === false) {
                throw new Exception('error on update data');
            }
            return new Response(201, 'product updated successfully', true, $userUpdated);
        } catch (ValidationException $th) {
            return new Response(400, $th->getMessage(), false, null, $th->getErrors());
        } catch (Throwable $th) {
            return new Response(400, $th->getMessage(), false, null, $th->getMessage());
        }
    }


    public function delete($id)
    {
        try {
            $response = $this->repository->find($id);
            $deleted = $this->repository->delete($id);
            return new Response(200, 'exhibition deleted', true, $response, null);
        } catch (DatabaseException $th) {
            return new Response($th->getCode(), $th->getMessage(), false, null);
        } catch (Exception $th) {
            return new Response(404, $th->getMessage(), false, null);
        }
    }


    public function getProductOfAuthor($authorId)
    {
        try {
            $result = $this->repository->getProductOfAuthor($authorId);
            return new Response(200, 'data product of author', true, $result, null);
        } catch (Exception $e) {
            return new Response($e->getCode(), $e->getMessage(), false, null, null);
        }
  
    }

    private function getThumbnails($productId) 
    {
        try {
    		$thumbanils = new ProductThumbnail();
            $query = $thumbanils->select('id, image_url')->where('product_id', $productId);
            $result = $query->get()->getResult(); 
            return $result;
        } catch (Throwable $e) {
            throw $e;
        }

	}

    private function getMembers($productId) 
    {
		$members = new ProductMember();
		$members->select('usr.name, usr.image_url, usr.bio, usr.email, usr.id')->join('users usr', 'usr.id = user_id')->where('product_id', $productId);
		$result = $members->get()->getResult();
        return $result;
	}

    private function isExists($productId) 
    {
        try {
            $model = new Product();
            $q = $model->where(['id' => $productId]);
            $result =$q->get()->getResult();
            if ($result) {
                return true;
            }
            return false;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function getProductsDetail($productId)
    {
        if (!$this->isExists($productId)) {
            return new Response(404, 'record not found', false);
        }

        try {
            $product = $this->repository->getProductDetail($productId);
            $thumbnails = $this->getThumbnails($productId);
        } catch (Throwable $th) {
            throw $th;
        }
        
                
        // TODO: refactor mapping
        $timestamps = array('created_at' => $product->created_at, 'updated_at' => $product->updated_at);
        $author = array('id' => $product->user_id, 'name' => $product->user_name, 'email' => $product->email, 'bio' => $product->bio, 'avatar' => $product->avatar);
        $product->timestamps = $timestamps;
        $product->author = $author;
        $product->total_points = (int) $product->total_points;
        $product->category = ["id" => $product->category_id, "name" => $product->category_name];
        
        unset($product->created_at, $product->updated_at, $product->username, $product->user_id, 
            $product->email, $product->bio, $product->user_name, $product->avatar);
        
        $result = $product;
        $result->thumbnails = $thumbnails;
        $result->members = $this->getMembers($productId);

        return new Response(200, 'oke', true, $result);
    }


    public function getBiggestPointsOfProduct($categoryId) 
    {
        try {
            $result = $this->repository->getBiggestPointOfProducts($categoryId);

            array_map(function ($data) {
                $data->timestamps = ['created_at' => $data->created_at, 'updated_at' => $data->updated_at];
                $data->total_points = (int) $data->total_points;
                $data->category = ['id' => $data->category_id, 'category_name' => $data->category_name];
                $data->thumbnails = $this->getThumbnails($data->id);
                unset($data->created_at, $data->updated_at, $data->category_id, $data->category_name);
            }, $result);


            return new Response(200, '5 biggest product based total points in spesific category', true, $result, null);
        } catch (Exception $e) {
            return new Response(400, $e->getMessage(), false, null, null);
        }
    }


    public function getProductCategoryBased($categoryId)
    {
        $categoryModel = new Category();
        $category = $categoryModel->find($categoryId);
        try {
            $products = $this->repository->getWhere(['category_id' => $categoryId]);
            array_map(function ($prd) use ($category) {
                $prd->thumbnails = $this->getThumbnails($prd->id);
                $prd->total_points = (int) $prd->total_points;
                $prd->category = $category;
                return $prd;
            }, $products);
            return new Response(200, null, true, $products);
        } catch (Exception $th) {
            return new Response(404, $th->getMessage(), false);
        }
    }



}

?>