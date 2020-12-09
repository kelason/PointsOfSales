<?php
spl_autoload_register('classAutoloader');

function classAutoloader($classname) {
    require_once WWW_ROOT . 'classes/' . $classname . '.php';
}

function cors($method) {

    // Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
        // you want to allow, and if so:
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
        header('Content-Type: application/json');
        header("Access-Control-Allow-Methods: " . $method);
        header("Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With");
    }
}
// Get 6 string and 6 number randomly
function genRandStr(){
    $a = $b = '';
  
    for($i = 0; $i < 6; $i++){
      $a .= chr(mt_rand(65, 90)); // see the ascii table why 65 to 90.    
      $b .= mt_rand(0, 9);
    }
  
    return $a . $b;
}

function Space($int) { $spc = '';
	for ($a = 0; $a <= $int; $a++) { $spc .= ' '; } return $spc;
}