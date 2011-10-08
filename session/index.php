<?php
// session/index.php/login
// session/index.php/
// session/index.php/logout

require_once dirname(__DIR__).'/silex.phar';

$app = new Silex\Application();

//twig
use Silex\Provider\TwigServiceProvider;
$app->register(new TwigServiceProvider(), array('twig.path'=>__DIR__.'/views', 'twig.class_path'=>dirname(__DIR__).'/vendor/twig/lib'));

//session
use Silex\Provider\SessionServiceProvider;
$app->register(new SessionServiceProvider());

//filter
$app->before(function() use ($app){
  if($app['session']->hasFlash('msg'))
  {
    $msg = $app['session']->getFlash('msg');
    $app['twig']->addGlobal('msg', $msg);
  }
});

//routings
//login form
$app->get('/login', function() use ($app){
  return $app['twig']->render('login.twig');
});
//login action
$app->post('/login', function() use ($app){
  $username = $app['request']->request->get('username');
  $password = $app['request']->request->get('password');
  if($username=='test' && $password=='test')
  {
    $app['session']->set('is_user', true);
    $app['session']->set('user', $username);
    return $app->redirect('/silex/session/index.php/');
  }
  return $app['twig']->render('login.twig');
});
//secure page
$app->get('/', function() use ($app){
  if(!$app['session']->get('is_user'))
  {
    return $app->redirect('/login');
  }
  return $app['twig']->render('home.twig', array('user'=>$app['session']->get('user')));
});

//logout
$app->get('/logout', function() use ($app){
  $app['session']->clear();
  $app['session']->setFlash('msg', 'logged out.');
  return $app->redirect('/silex/session/index.php/login');
});

$app->run();