<?php
require "config.php";
$url = $_GET["url"] ?? "Index/index";
$url = explode("/", $url);
$controller = "";
$method = "";
$params = "";
if (isset($url[0])) {
    $controller = $url[0];
}
if (isset($url[1])) {
    if ($url[1] != '') {
        $method = $url[1];
    }
}
if (isset($url[2])) {
    if ($url[1] != '') {
        $params = $url[2];
    }
}
spl_autoload_register(function ($class) {
    if (file_exists(LBS . $class . ".php")) {
        require LBS . $class . ".php";
    }
});
require 'Controllers/Error.php';
$error = new Errors();
/*$obj = new Controllers();*/
//echo  $controller."----- ".$method;
//Llamaos a los controladores
$controllersPath = "Controllers/" . $controller . '.php';
if (file_exists($controllersPath)) {
    require $controllersPath;
    //instanciamos de la clase 
    $controller = new $controller();
    if (isset($method)) {
        if (method_exists($controller, $method)) {
            if (isset($params)) {
                //ejecutamos el meto que resiva el parametro
                $controller->{$method}($params);
            } else {
                $controller->{$method}();
            }
        } else {
            $error->error();
        }
    }
} else {
    $error->error();
}
?>