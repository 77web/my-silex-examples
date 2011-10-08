<?php

// monolog/index.php/hello-log/foo --> tail test.log

require_once dirname(__DIR__).'/silex.phar';

$app = new Silex\Application();

//twig
use Silex\Provider\TwigServiceProvider;
$app->register(new TwigServiceProvider(), array('twig.path'=>__DIR__.'/views', 'twig.class_path'=>dirname(__DIR__).'/vendor/twig/lib'));

//monolog
use Silex\Provider\MonologServiceProvider;
$app->register(new MonologServiceProvider(), array('monolog.logfile'=>__DIR__.'/test.log', 'monolog.class_path'=>dirname(__DIR__).'/vendor/monolog/src'));


//routings
$app->get('/hello-log/{name}', function($name) use ($app){ 
  $app['monolog']->addDebug('name: '.$name);
  return $app['twig']->render('hello.twig', array('name'=>$name));
});

$app->run();