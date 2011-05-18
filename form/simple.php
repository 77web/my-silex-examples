<?php
//form/simple.php/ => post and see

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

//simple form
$app['simpleForm'] = $app['form.factory']->createBuilder('form')->add('name', 'text')->add('message', 'textarea')->getForm();

//form display
$app->get('/', function() use ($app){
  $url = $app['url_generator']->generate('form_post');
  $form = $app['simpleForm']->createView();
  return $app['twig']->render('form.twig', array('post_url'=>$url, 'form'=>$form));
});

//simply post it & show data
$app->post('/post', function() use ($app){
  $form = $app['simpleForm'];
  $form->bindRequest($app['request']);
  if($form->isValid())
  {
    $data = $form->getData();
    return $app['twig']->render('result.twig', array('data'=>$data));
  }
  else
  {
    return 'form error!';
  }
})->bind('form_post');

$app->run();