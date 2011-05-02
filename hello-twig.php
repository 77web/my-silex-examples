<?php

// hello-twig.php/hello-twig/foo

require_once __DIR__.'/silex.phar';

$app = new Silex\Application();

//twig
use Silex\Extension\TwigExtension;
$app->register(new TwigExtension(), array('twig.path'=>__DIR__.'/views', 'twig.class_path'=>__DIR__.'/vendor/twig/lib'));

//routings
$app->get('/hello-twig/{name}', function($name) use ($app){ 
  return $app['twig']->render('hello-twig.twig', array('name'=>$name));
});

$app->run();