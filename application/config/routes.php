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
|	https://codeigniter.com/user_guide/general/routing.html
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
$route['default_controller'] = 'main/index';
$route['404_override'] = 'main/error404';
$route['translate_uri_dashes'] = FALSE;
$route["/"] = "main/index";
$route["index"] = "main/index";
$route["financervotreprojet"] = "main/financervotreprojet";
$route["financervotreprojet/login"] = "main/login";
$route["financervotreprojet/solutions"] = "main/solutions";
$route["financervotreprojet/details/(:any)/(:any)"] = "main/details/$1/$2";
$route["financervotreprojet/administration"] = "administration/index";
$route["financervotreprojet/administration/fiche/(:any)"] = "administration/fiche/$1";
$route["financervotreprojet/solutions/(:any)"] = "main/details/$1";
$route["financervotreprojet/administration/ajouter-fiche"] = "administration/addFiche";
$route["financervotreprojet/administration/ajouter-solution"] = "administration/addSolution";

$url = "/";

include('database.php');

$connBDD = new PDO("mysql:host=".$db['default']['hostname'].";dbname=".$db['default']['database'], $db['default']['username'], $db['default']['password'], array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
$query = $connBDD->prepare("SELECT * FROM pages");
$query->execute();
$pages = $query->fetchAll(PDO::FETCH_CLASS);

foreach( $pages as $page ){
    $r_url = $url.substr($page->pa_url, 1);
    if(substr($page->pa_url, -1) == "/"){
        $r_url = substr($url, 0, -1);
    }
    $route[ $r_url ] = $page->pa_view;
}
