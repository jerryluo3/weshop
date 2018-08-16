<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

$route['uploadmyfile'] = 'uploadmyfile/index';
$route['uploadmyfile/(:any)'] = 'uploadmyfile/$1';
$route['search'] = 'pages/search';
$route['search/(:any)'] = 'pages/search/$1';
$route['search/(:any)/(:any)'] = 'pages/search/$1/$2';
$route['search/(:any)/(:any)/(:any)'] = 'pages/search/$1/$2/$3';
$route['search/(:any)/(:any)/(:any)/(:any)'] = 'pages/search/$1/$2/$3/$4';

$route['about/(:any)'] = 'pages/view/about/$1';
$route['dsj'] = 'pages/view/dsj';
$route['dsj/(:any)'] = 'pages/view/dsj/$1';

$route['article/(:any)'] = 'pages/view/article/$1';
$route['article/(:any)/(:any)'] = 'pages/view/article/$1/$2';
$route['article/(:any)/(:any)/(:any)'] = 'pages/view/article/$1/$2/$3';

$route['articleview/(:any)'] = 'pages/view/articleview/$1';

$route['project/(:any)'] = 'pages/view/project/$1';
$route['project/(:any)/(:any)'] = 'pages/view/project/$1/$2';

//$route['forum/(:any)'] = 'forum/$1';
//$route['forum/(:any)/(:any)'] = 'forum/$1/$2';
//$route['forum/(:any)/(:any)/(:any)'] = 'forum/$1/$2/$3';


$route['industry/(:any)'] = 'pages/view/industry/$1';
$route['industry/(:any)/(:any)'] = 'pages/view/industry/$1/$2';

$route['culture/(:any)'] = 'pages/view/culture/$1';
$route['culture/(:any)/(:any)'] = 'pages/view/culture/$1/$2';

$route['team/(:any)'] = 'pages/view/team/$1';
$route['team/(:any)/(:any)'] = 'pages/view/team/$1/$2';

$route['teamview/(:any)'] = 'pages/view/teamview/$1';
$route['teamview/(:any)/(:any)'] = 'pages/view/teamview/$1/$2';

$route['talent/(:any)'] = 'pages/view/talent/$1';
$route['talent/(:any)/(:any)'] = 'pages/view/talent/$1/$2';

$route['contact/(:any)'] = 'pages/view/contact/$1';
$route['contact/(:any)/(:any)'] = 'pages/view/contact/$1/$2';


$route['(:any)'] = 'pages/view/$1';
$route['default_controller'] = 'pages/view';



$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

