<?php

//this is a little complicated sample. please show simple.php first.
//form/simple2.php/ => post and see

require_once dirname(__DIR__).'/silex.phar';

$app = new Silex\Application();

//twig
use Silex\Extension\TwigExtension;
$app->register(new TwigExtension(), array('twig.path'=>__DIR__.'/views', 'twig.class_path'=>dirname(__DIR__).'/vendor/twig/lib'));

//url-generator
use Silex\Extension\UrlGeneratorExtension;
$app->register(new Silex\Extension\UrlGeneratorExtension());

//form
use Silex\Extension\FormExtension;
$app->register(new FormExtension(), array('form.class_path'=>dirname(__DIR__).'/vendor/symfony/src'));

//bridge: to render form in twig template (maybe necessary)
use Silex\Extension\SymfonyBridgesExtension;
$app->register(new SymfonyBridgesExtension(), array('symfony_bridges.class_path'=>dirname(__DIR__).'/vendor/symfony/src'));

//translation: to render form in twig template
use Silex\Extension\TranslationExtension;
$app->register(new TranslationExtension(), array(
  'locale_fallback' => 'en',
  'translation.class_path' => dirname(__DIR__).'/vendor/symfony/src',
  'translator.messages' => array(),
));

//validator
use Silex\Extension\ValidatorExtension;
$app->register(new ValidatorExtension(), array('validator.class_path'=>dirname(__DIR__).'/vendor/symfony/src'));

//a little complicated form
require_once __DIR__.'/Sample.php';
$sample = new Sample();
$app['sampleObj'] = $sample;
$choices = array('fuga'=>'fuga', 'Mr.'=>'Mr.', 'Miss'=>'Miss', 'Mrs.'=>'Mrs.');
$app['sampleForm'] = $app['form.factory']->createBuilder('form', $app['sampleObj'])->add('title', 'choice', array('choices'=>$choices))->add('name', 'text')->add('email', 'text')->getForm();

//form display
$app->get('/', function() use ($app){
  $url = $app['url_generator']->generate('form_post');
  $form = $app['sampleForm']->createView();
  return $app['twig']->render('form.twig', array('post_url'=>$url, 'form'=>$form));
});

//post & validate & show data
$app->post('/post', function() use ($app){
  $form = $app['sampleForm'];
  $form->bindRequest($app['request']);
  if($form->isValid())
  {
    $v = $app['validator']->validate($app['sampleObj']);
    if(count($v)==0)
    {
      return $app['twig']->render('result2.twig', array('data'=>$app['sampleObj']));
    }
    else
    {
      return $app['twig']->render('errors.twig', array('errors'=>$v));
    }
  }
  else
  {
    return 'form error!';
  }

})->bind('form_post');

$app->run();