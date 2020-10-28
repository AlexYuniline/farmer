<?php
require_once 'core/Autoload.php';
const ADMIN_PATH = 'admin';
$module = 'index';
$action = 'index';
$params = array();
$admin = false;

if ($_SERVER['REQUEST_URI'] != '/') {
    $url_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri_parts = explode('/', trim($url_path, ' /'));
    if ($uri_parts[0] == ADMIN_PATH) {
        $admin = true;
        array_shift($uri_parts);
    }

    $module = array_shift($uri_parts);
    $action = array_shift($uri_parts);
    for ($i = 0; $i < count($uri_parts); $i++) {
        $params[$uri_parts[$i]] = $uri_parts[++$i];
    }
}
if (!$module) {
    $module = 'index';
}
if (!$action) {
    $action = 'index';
}

if ($admin) {
    $baseUri = ADMIN_PATH . '/controllers/';
} else {
    $baseUri = 'controllers/';
}

$classFile = $baseUri . $module . '.controller.php';
if (file_exists($classFile)) {
    require_once($classFile);
} else {
    header('Location: /');
}

$partModules = explode('.', $module);

$class = ucfirst($partModules[0]) . 'Controller';
$includeClass = new $class($_POST);
if (method_exists($includeClass, $action)) {
    $includeClass->$action($params);
} else {
    header('Location: /');
}