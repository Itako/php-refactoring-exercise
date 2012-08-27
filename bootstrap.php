<?php

use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Symfony\Component\Yaml\Parser;

$loader = require_once __DIR__ . '/vendor/autoload.php';
$app = new Application();
$app['config'] = (new Parser())->parse(file_get_contents(__DIR__ . "/config/config.yml"));
$app['root_dir'] = __DIR__;

$app->register(new UrlGeneratorServiceProvider());
$app->register(new TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/views',
));
$app->register(new DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver' => 'pdo_mysql',
        'host' => $app['config']['database']['host'],
        'dbname' => $app['config']['database']['name'],
        'user' => $app['config']['database']['username'],
        'password' => $app['config']['database']['password'],
    )
));

$app->error(function (PDOException $e) use ($app)
        {
            switch ($e->getCode())
            {
                case 1045:
                    die("Can't connect do database");
                    break;
                case 1044;
                    die("The database selected does not exists");
                    break;
                default :
                    $message = 'Invalid query: ' . $e->getMessage() . "\n";
                    //$message .= 'Whole query: ' . $query; //TODO
                    die($message);
                    break;
            }
        });

require_once __DIR__ . '/routing.php';

//TODO: remove this after refactorization (needed for legacy includes)
set_include_path(__DIR__ . ";" . get_include_path());
require_once('functions.php');