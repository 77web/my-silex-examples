<?php
// translation/index.php/hello/{name}/in/{locale}  locale must be one of (ja | en | de)

require_once dirname(__DIR__).'/silex.phar';

$app = new Silex\Application();

//twig
use Silex\Provider\TwigServiceProvider;
$app->register(new TwigServiceProvider(), array('twig.path'=>__DIR__.'/views', 'twig.class_path'=>dirname(__DIR__).'/vendor/twig/lib'));

//translation
use Silex\Provider\TranslationServiceProvider;
$app->register(new TranslationServiceProvider(), array(
  'locale_fallback' => 'ja',
  'translation.class_path' => dirname(__DIR__).'/vendor/symfony/src'
));

$app['translator.messages'] = array(
    'en' => array(
        'hello'     => 'Hello, %name%',
        'oyasumi' => 'Good night, %name%',
        'kokuhaku' => 'I love you, %name%!!',
    ),
    'de' => array(
        'hello'     => 'Guten Tag, %name%',
        'oyasumi' => 'Guten Nacht, %name%',
        'kokuhaku' => 'Ich liebe dich, %name%!!',
    ),
    'ja' => array(
        'hello'     => '%name%さん、こんにちは',
        'oyasumi' => '%name%さん、おやすみなさい',
        'kokuhaku' => '%name%愛してるよ！'
    ),
);

$app->get('/{message}/{name}/in/{locale}', function($message, $name, $locale) use ($app){
  $app['translator']->setLocale($locale);
  return $app['translator']->trans($message, array('%name%' => $name));
});



$app->run();