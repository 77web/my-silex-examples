<?php

//hello/index.php/hello/foo

require_once dirname(__DIR__).'/silex.phar';

$app = new Silex\Application();

//routings
$app->get('/hello/{name}', function($name){
  return 'Hello '.$name; 
});

$app->run();