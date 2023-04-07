<?php

namespace Config;

use App\Api\Domains\Product\Controller\ApiProductController;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Api\Domains');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
// auth
$routes->get('fitalks', 'Fitalk\Controller\FitalkController::list');
$routes->put('fitalks/(:segment)', 'Fitalk\Controller\FitalkController::setStatus/$1');

/*
 * --------------------------------------------------------------------
 * REST API
 * --------------------------------------------------------------------
 */

/**
 * products members
 */
$routes->get('banners', 'Banner\Controller\BannerController::index');

$routes->get('member/product', 'ProductMember\Controller\ProductMemberController::getProductOfUserMember', ['filter' => 'auth']);
$routes->post('products/members/', 'ProductMember\Controller\ProductMemberController::create', ['filter' => 'OnlyAuthor']);
$routes->get('products/members', 'ProductMember\Controller\ProductMemberController::index');
$routes->get('products/members/(:segment)', 'ProductMember\Controller\ProductMemberController::show/$1');
$routes->put('products/members/(:segment)', 'ProductMember\Controller\ProductMemberController::update/$1');
$routes->delete('products/members/(:segment)', 'ProductMember\Controller\ProductMemberController::destroy/$1');


/**
 * Guest Books
 */
$routes->post('products/thumbnails', 'ProductThumbnail\Controller\ProductThumbnailController::create');
$routes->get('products/thumbnails', 'ProductThumbnail\Controller\ProductThumbnailController::index');
$routes->get('products/thumbnails/(:segment)', 'ProductThumbnail\Controller\ProductThumbnailController::show/$1');
$routes->put('products/thumbnails/(:segment)', 'ProductThumbnail\Controller\ProductThumbnailController::update/$1');
$routes->delete('products/thumbnails/(:segment)', 'ProductThumbnail\Controller\ProductThumbnailController::destroy/$1');
$routes->post('upload/products/thumbnails/(:segment)', 'ProductThumbnail\Controller\ProductThumbnailController::saveThumbnail/$1');

/**
 * Products
 */
$routes->get('author/product', 'Product\Controller\ApiProductController::getProductOfAuthor', ['filter' => 'auth']);
$routes->get('products/(:segment)/detail', 'Product\Controller\ApiProductController::getProductDetail/$1');
$routes->get('products', 'Product\Controller\ApiProductController::index');
$routes->get('products/categories/(:segment)', 'Product\Controller\ApiProductController::productsOnCategory/$1');
$routes->get('products/(:segment)/author', 'Product\Controller\ApiProductController::getAuthor/$1');
$routes->post('products', 'Product\Controller\ApiProductController::create');
$routes->get('products/(:segment)', 'Product\Controller\ApiProductController::getProductDetail/$1');

$routes->put('products/(:segment)', 'Product\Controller\ApiProductController::update/$1');
$routes->delete('products/(:segment)', 'Product\Controller\ApiProductController::destroy/$1');
$routes->get('products/leaderboard/categories/(:segment)', 'Product\Controller\ApiProductController::getLeaderboardProductCategoryBased/$1');



// User CRUD

$routes->post('users/upload/', 'User\Controller\UserController::storeImage');
$routes->get('users', 'User\Controller\UserController::index');
$routes->get('users/(:segment)', 'User\Controller\UserController::show/$1');
$routes->put('users/(:segment)', 'User\Controller\UserController::update/$1');
$routes->delete('users/(:segment)', 'User\Controller\UserController::destroy/$1');


/**
 * Auth
 */
$routes->post('auth/register', 'User\Controller\UserController::register');
$routes->post('auth/login', 'User\Controller\UserController::login/$1');
$routes->get('auth/me', 'User\Controller\UserController::me', ['filter' => 'auth']);

/**
 * Use cases
 */
$routes->get('user/check/products/(:segment)', 'BadgeCollection\Controller\BadgeCollectionController::checkUserHasGivenBadge/$1');
$routes->post('user/send/badge/product/(:segment)', 'BadgeCollection\Controller\BadgeCollectionController::sendBadgeUserToProduct/$1', ['filter' => 'MakeSureEnoughBadge']);
$routes->post('user/cancle/badge/products/(:segment)', 'BadgeCollection\Controller\BadgeCollectionController::cancleBadgeOfProduct/$1', ['filter' => 'auth']);
$routes->get('products/(:segment)/badges', 'BadgeCollection\Controller\BadgeCollectionController::getBadgesOfProduct/$1');
$routes->get('products/(:segment)/comments', 'BadgeCollection\Controller\BadgeCollectionController::getCommentsOfProduct/$1');

/**
 * Badge Inventories
 */
$routes->get('badges/inventories', 'BadgeInventory\Controller\BadgeInventoryController::index');
$routes->get('badges/inventories/(:segment)', 'BadgeInventory\Controller\BadgeInventoryController::show/$1');
$routes->get('user/badges', 'BadgeInventory\Controller\BadgeInventoryController::getBadgesOfUser', ['filter' => 'auth']);
$routes->post('badges/inventories', 'BadgeInventory\Controller\BadgeInventoryController::create', ['filter' => 'EnsureOneUserOneBadgeInventory']);
$routes->put('badges/inventories/(:segment)', 'BadgeInventory\Controller\BadgeInventoryController::update/$1');
$routes->delete('badges/inventories/(:segment)', 'BadgeInventory\Controller\BadgeInventoryController::destroy/$1');



/**
 * Exhibitions
 */
$routes->post('exhibitions', 'Exhibition\Controller\ExhibitionController::create');
$routes->get('exhibitions', 'Exhibition\Controller\ExhibitionController::index');
$routes->get('exhibitions/(:segment)', 'Exhibition\Controller\ExhibitionController::show/$1');
$routes->put('exhibitions/(:segment)', 'Exhibition\Controller\ExhibitionController::update/$1');
$routes->delete('exhibitions/(:segment)', 'Exhibition\Controller\ExhibitionController::destroy/$1');

/**
 * Categories
 */
$routes->post('categories/', 'Category\Controller\CategoryController::create');
$routes->get('categories', 'Category\Controller\CategoryController::index');
$routes->get('categories/(:segment)', 'Category\Controller\CategoryController::show/$1');
$routes->put('categories/(:segment)', 'Category\Controller\CategoryController::update/$1');
$routes->delete('categories/(:segment)', 'Category\Controller\CategoryController::destroy/$1');
$routes->get('categories/exhibitions/(:segment)', 'Category\Controller\CategoryController::getCategoriesOfExhibition/$1');


/**
 * Guest Books
 */
$routes->post('guests/books/', 'GuestBook\Controller\GuestBookController::create');
$routes->get('guests/books', 'GuestBook\Controller\GuestBookController::index');
$routes->get('guests/books/limit/(:segment)/', 'GuestBook\Controller\GuestBookController::showLimit/$1');
$routes->get('guests/books/(:segment)', 'GuestBook\Controller\GuestBookController::show/$1');
$routes->put('guests/books/(:segment)', 'GuestBook\Controller\GuestBookController::update/$1');
$routes->delete('guests/books/(:segment)', 'GuestBook\Controller\GuestBookController::destroy/$1');

/**
 * Badge Collections
 */

$routes->get('badges/collections/', 'BadgeCollection\Controller\BadgeCollectionController::index');
$routes->get('badges/collections/(:segment)', 'BadgeCollection\Controller\BadgeCollectionController::show/$1');
$routes->post('badges/collections/', 'BadgeCollection\Controller\BadgeCollectionController::create');
$routes->put('badges/collections/(:segment)', 'BadgeCollection\Controller\BadgeCollectionController::update/$1', ['filter' => 'auth']);
$routes->delete('badges/collections/(:segment)', 'BadgeCollection\Controller\BadgeCollectionController::destroy/$1');

$routes->get('/', 'Home::index');




/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
