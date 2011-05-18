<?php

//this is complicated sample. please use simple.php first.
//form/index.php/ => post and see

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

//read form class
require_once __DIR__.'/SampleForm.php';
$form = new SampleForm();
$app['sampleForm'] = $app['form.factory']->create($form, $form);

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
    $v = $app['validator']->validate($form->getData());
    if(count($v)==0)
    {
      return $app['twig']->render('result2.twig', array('data'=>$form->getData()));
    }
    else
    {
      return $app['twig']->render('errors.twig', array('errors'=>$v));
    }
  }
  else
  {
    return 'form error';
  }

})->bind('form_post');

$app->run();