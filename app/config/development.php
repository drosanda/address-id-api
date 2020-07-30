<?php
/**
 * Configuration file for Development version
 *   You can create one for:
 *   development.php
 *   staging.php
 *   production.php
 */

/****************************/
/* == Base Configuration == */
/* @var string */
/****************************/

/**
 * Site Base URL with http:// or https:// prefix and trailing slash
 * @var string
 */
$site = "http://".$_SERVER['HTTP_HOST']."/cenah/api-alamat/";
/**
 * URL parse method
 *   - REQUEST_URI, suitable for Nginx
 *   - PATH_INFO, suitable for XAMPP
 *   - ORIG_PATH_INFO
 * @var string
 */
$method = "PATH_INFO";//REQUEST_URI,PATH_INFO,ORIG_PATH_INFO,
/**
 * Admin Secret re-routing
 * this is alias for app/controller/admin/*
 * @var string
 */
$admin_secret_url = 'admin';
/**
 * Base URL with http:// or https:// prefix and trailing slash
 * @var string
 */
$cdn_url = '';

/********************************/
/* == Database Configuration == */
/* Database connection information */
/* @var array of string */
/********************************/
$db['host']  = 'localhost';
$db['user']  = 'root';
$db['pass']  = '';
$db['name']  = 'tca_alamat';
$db['port'] = '3306';
$db['charset'] = 'utf8mb4';
$db['engine'] = 'mysqli';

/****************************/
/* == Session Configuration == */
/* @var string */
/****************************/
$saltkey = 's3mEFr4';

/********************************/
/* == Timezone Configuration == */
/* @var string */
/****************************/
$timezone = 'Asia/Jakarta';

/********************************/
/* == Core Configuration == */
/* register your core class, and put it on: */
/*   - app/core/ */
/* all $semevar['core_* value in lower case string*/
/* @var string */
/****************************/
$core_prefix = 'ji_';
$core_controller = 'controller';
$core_model = '';

/********************************/
/* == Controller Configuration == */
/* register your onboarding (main) controller */
/*   - make sure dont add any traing slash in array key of routes */
/*   - all $semevar['controller_* value in lower case string */
/*   - example $routes['produk/(:any)'] = 'produk/detail/index/$1' */
/*   - example example $routes['blog/id/(:num)/(:any)'] = 'blog/detail/index/$1/$2'' */
/* @var string */
/****************************/
$controller_main='home';
$controller_404='notfound';

/********************************/
/* == Controller Re-Routing Configuration == */
/* make sure dont add any traing slash in array key of routes */
/* @var array of string */
/****************************/
// $routes['produk/(:any)'] = 'produk/detail/index/$1';
// $routes['blog/id/(:num)/(:any)'] = 'blog/detail/index/$1/$2';


/********************************/
/* == Another Configuration == */
/* configuration are in array of string format */
/*  - as name value pair */
/*  - accessing value by $this->semevar->key in controller extended class */
/*  - accessing value by $this->semevar->key in model extended class */
/****************************/

//firebase messaging
$semevar['fcm'] = new stdClass();
$semevar['fcm']->version = '';
$semevar['fcm']->apiKey = '';
$semevar['fcm']->authDomain = '';
$semevar['fcm']->databaseURL = '';
$semevar['fcm']->projectId = '';
$semevar['fcm']->storageBucket = '';
$semevar['fcm']->messagingSenderId = '';
$semevar['fcm']->appId = '';

// example
$semevar['site_name'] = 'Seme Framework';
$semevar['email_from'] = 'noreply@thecloudalert.com';
$semevar['email_reply'] = 'hi@thecloudalert.com';
$semevar['app_name'] = 'Seme Framework';
$semevar['app_logo'] = 'Seme Framework';

$semevar['site_name'] = 'Seme Address (ID)';
$semevar['site_name_admin'] = 'Seme Address Admin';
$semevar['site_version'] = '1.0.0';
$semevar['site_title'] = 'Seme address provider API';
$semevar['site_description'] = 'Multipurpose address provider, Indonesia version.';
$semevar['site_email'] = 'hi@thecloudalert.com';
$semevar['site_replyto'] = 'nyingspot@gmail.com';
$semevar['site_phone'] = '085861624300';
$semevar['site_suffix'] = ' - SEME Framework';
$semevar['site_suffix_admin'] = ' - Admin Seme Address';
$semevar['site_keyword'] = 'SEME Framework';
$semevar['page_current'] = 'beranda';
$semevar['menu_current'] = 'beranda';

$semevar['site_author'] = 'Cenah.co.id';
$semevar['site_company'] = '';
$semevar['site_address'] = '';
$semevar['user_login'] = 0;
$semevar['admin_login'] = 0;

$semevar['cms_blog'] = 'media/blog';
$semevar['user_img'] = 'media/user/';
$semevar['user_toko'] = 'media/user/store/';
$semevar['produk_foto'] = 'media/produk/';
$semevar['produk_thumb'] = 'media/produk/thumb/';
$semevar['order_konfirmasi'] = 'media/order/konfirmasi/';
$semevar['order_qc'] = 'media/order/qc/';
$semevar['order_packing'] = 'media/order/packing/';
$semevar['order_resi'] = 'media/order/resi/';
$semevar['apikeys'] = 'IF8088,AK8088';
