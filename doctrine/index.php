<?php

// doctrine/index.php/hello/1

require_once dirname(__DIR__).'/silex.phar';

$app = new Silex\Application();

//twig
use Silex\Extension\TwigExtension;
$app->register(new TwigExtension(), array('twig.path'=>__DIR__.'/views', 'twig.class_path'=>dirname(__DIR__).'/vendor/twig/lib'));

//doctrine
use Silex\Extension\DoctrineExtension;
$app->register(new DoctrineExtension(), array('db.options'=>array('driver'=>'pdo_mysql', 'dbname'=>'test', 'user'=>'root', 'host'=>'127.0.0.1'), 'db.dbal.class_path'=>dirname(__DIR__).'/vendor/doctrine-dbal/lib', 'db.common.class_path'=>dirname(__DIR__).'/vendor/doctrine-common/lib'));

//routings
$app->get('/hello/{id}', function($id) use ($app){ 
  $sql = 'SELECT * FROM user WHERE id = ?';
  $user = $app['db']->fetchAssoc($sql, array((int)$id));
  return $app['twig']->render('hello-doctrine.twig', array('user'=>$user));
});

$app->run();