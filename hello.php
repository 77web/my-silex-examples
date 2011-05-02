<?php

require_once __DIR__.'/silex.phar';

$app = new Silex\Application();

//routings
$app->get('/hello/{name}', function($name){
  return 'Hello '.$name; 
});

$app->run();