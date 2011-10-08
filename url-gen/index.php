<?php
// url-gen/index.php/sitemap ==> click links and confirm!

require_once dirname(__DIR__).'/silex.phar';

$app = new Silex\Application();

//twig
use Silex\Provider\TwigServiceProvider;
$app->register(new TwigServiceProvider(), array('twig.path'=>__DIR__.'/views', 'twig.class_path'=>dirname(__DIR__).'/vendor/twig/lib'));

//url-generator
use Silex\Provider\UrlGeneratorServiceProvider;
$app->register(new UrlGeneratorServiceProvider());


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