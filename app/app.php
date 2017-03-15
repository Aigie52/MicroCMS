<?php

use Silex\Provider\DoctrineServiceProvider;
use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;

// Register global error and exception handlers
ErrorHandler::register();
ExceptionHandler::register();

// Register service providers.
$app->register(new DoctrineServiceProvider());

// Register services.
$app['dao.article'] = function ($app) {
    return new \MicroCMS\DAO\ArticleDAO($app['db']);
};
