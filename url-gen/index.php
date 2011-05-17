<?php
// url-gen/index.php/sitemap
// url-gen/index.php/
// url-gen/index.php/page
// url-gen/index.php/link

require_once dirname(__DIR__).'/silex.phar';

$app = new Silex\Application();

//twig
use Silex\Extension\TwigExtension;
$app->register(new TwigExtension(), array('twig.path'=>__DIR__.'/views', 'twig.class_path'=>dirname(__DIR__).'/vendor/twig/lib'));

//url-generator
use Silex\Extension\UrlGeneratorExtension;
$app->register(new Silex\Extension\UrlGeneratorExtension());


//routings
$app->get('/', function() use ($app){
  return $app['twig']->render('home.twig', array());
})->bind('homepage');
$app->get('/page/{slug}', function($slug) use ($app){
  return $app['twig']->render('page.twig', array('slug'=>$slug));
})->bind('page');

$app->get('/sitemap', function() use ($app){
  $links = array();
  $links['home'] = $app['url_generator']->generate('homepage');
  $dummy = array('cake', 'codeigniter', 'symfony', 'silex');
  foreach($dummy as $slug)
  {
    $links[$slug] = $app['url_generator']->generate('page', array('slug'=>$slug));
  }
  return $app['twig']->render('sitemap.twig', array('links'=>$links));
});


$app->run();