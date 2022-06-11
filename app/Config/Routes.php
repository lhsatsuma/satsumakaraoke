<?php

namespace Config;

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
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override(function() {
	require_once(APPPATH . 'Helpers/Sys_helper.php');
	$focus = new \App\Controllers\BaseController();

	$last_uri = $focus->uri[count($focus->uri) - 1];
	$ext_http_code = [
		'.js',
		'.css',
		'.map'
	];
	foreach($ext_http_code as $type){
		if(substr($last_uri, strlen($last_uri) - strlen($type), strlen($type)) == $type){
			header('HTTP/1.0 404 Not Found');
			//let's throw an simple 404 errordocument
			exit;
		}
	}

	$focus->SetView();
	$focus->SetLayout();
	$focus->SetInitialData();
	return $focus->displayNew('404',false);
});
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');

$routes->match(['get', 'post'], '/login', 'Users::login');
$routes->match(['get', 'post'], '/login/(:any)', 'Users::$1');

$routes->match(['get', 'post'], '/admin/login/', 'Users::login', ['namespace' => 'App\Controllers\Admin']);
$routes->match(['get', 'post'], '/admin/login/auth', 'Users::auth', ['namespace' => 'App\Controllers\Admin']);
$routes->match(['get', 'post'], '/admin/login/logout', 'Users::logout', ['namespace' => 'App\Controllers\Admin']);

$routes->match(['get'], '/jsManager/(:any)', 'JsManager::get/$1');
$routes->match(['get'], '/cssManager/(:any)', 'cssManager::get/$1');

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
