<?php

// monolog/index.php/hello-log/foo --> tail test.log

require_once dirname(__DIR__).'/silex.phar';

$app = new Silex\Application();

//twig
use Silex\Extension\TwigExtension;
$app->register(new TwigExtension(), array('twig.path'=>__DIR__.'/views', 'twig.class_path'=>dirname(__DIR__).'/vendor/twig/lib'));

//monolog
use Silex\Extension\MonologExtension;
$app->register(new MonologExtension(), array('monolog.logfile'=>__DIR__.'/test.log', 'monolog.class_path'=>dirname(__DIR__).'/vendor/monolog/src'));


//routings
$app->get('/hello-log/{name}', function($name) use ($app){ 
  $app['monolog']->addDebug('name: '.$name);
  return $app['twig']->render('hello.twig', array('name'=>$name));
});

$app->run();