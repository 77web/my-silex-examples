<?php
//1:simple validator/index.php/simple-form ==> type a email and post, type some string(not email) and post
//2:using class validator/index.php/form ==> choose and input, then post

require_once dirname(__DIR__).'/silex.phar';

$app = new Silex\Application();

//twig
use Silex\Extension\TwigExtension;
$app->register(new TwigExtension(), array('twig.path'=>__DIR__.'/views', 'twig.class_path'=>dirname(__DIR__).'/vendor/twig/lib'));

//url-generator
use Silex\Extension\UrlGeneratorExtension;
$app->register(new Silex\Extension\UrlGeneratorExtension());

//validator
use Silex\Extension\ValidatorExtension;
$app->register(new ValidatorExtension(), array('validator.class_path'=>dirname(__DIR__).'/vendor/symfony/src'));

//1:simple 
//form display
$app->get('/simple-form', function() use ($app){
  $url = $app['url_generator']->generate('simple_validate');
  return $app['twig']->render('simple-form.twig', array('post_url'=>$url));
});

//validate
use Symfony\Component\Validator\Constraints;
$app->post('/simple-validate', function() use ($app){
  $v = $app['validator']->validateValue($app['request']->request->get('value'), new Constraints\Email());
  if(count($v)>0)
  {
    return $app['twig']->render('result.twig', array('errors'=>$v));
  }
  else
  {
    return 'fine!';
  }
})->bind('simple_validate');

//2:use validation class of Symfony2
//form display
$app->get('/form', function() use ($app){
  $url = $app['url_generator']->generate('class_validate');
  return $app['twig']->render('form.twig', array('post_url'=>$url));
});

//validate
require __DIR__.'/Sample.php';
$app->post('/validate', function() use ($app){
  $sample = new Sample();
  $sample->title = $app['request']->request->get('title');
  $sample->name = $app['request']->request->get('name');
  $sample->email = $app['request']->request->get('email');
  $v = $app['validator']->validate($sample);
  if(count($v)>0)
  {
    return $app['twig']->render('result.twig', array('errors'=>$v));
  }
  else
  {
    return 'fine!';
  }
})->bind('class_validate');


$app->run();