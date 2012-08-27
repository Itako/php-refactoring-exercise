<?php

use Symfony\Component\Yaml\Parser;

$loader = require_once __DIR__ . '/vendor/autoload.php';
$app = new Silex\Application();

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

$app['root_dir'] = __DIR__;

require_once __DIR__ . '/routing.php';

$app['config'] = (new Parser())->parse(file_get_contents(__DIR__."/config/config.yml"));

//TODO: remove this after refactorization (needed for legacy includes)
set_include_path(__DIR__ . ";" . get_include_path());
require_once('functions.php');