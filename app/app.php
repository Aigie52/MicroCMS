<?php

use MicroCMS\DAO\ArticleDAO;
use MicroCMS\DAO\UserDAO;
use Silex\Provider\AssetServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\LocaleServiceProvider;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;
use Symfony\Component\HttpFoundation\Request;

// Register global error and exception handlers
ErrorHandler::register();
ExceptionHandler::register();

// Register service providers.
$app->register(new DoctrineServiceProvider());
$app->register(new TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));
$app['twig'] = $app->extend('twig', function (Twig_Environment $twig, $app) {
    $twig->addExtension(new Twig_Extensions_Extension_Text());
    return $twig;
});
$app->register(new ValidatorServiceProvider());
$app->register(new AssetServiceProvider(), array(
    'assets.version' => 'v1',
));
$app->register(new SessionServiceProvider());
$app->register(new SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'secured' => array(
            'pattern' => '^/',
            'anonymous' => true,
            'logout' => true,
            'form' => array(
                'login_path' => '/login',
                'check_path' => '/login_check',
            ),
            'users' => function () use ($app) {
                return new UserDAO($app['db']);
            },
        ),
    ),
    'security.role_hierarchy' => array(
        'ROLE_ADMIN' => array('ROLE_USER'),
    ),
    'security.access_rules' => array(
        array('^/admin', 'ROLE_ADMIN'),
    ),
));
$app->register(new FormServiceProvider());
$app->register(new LocaleServiceProvider());
$app->register(new TranslationServiceProvider());
$app->register(new MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__.'/../var/log.microcms.log',
    'monolog.name' => 'MicroCMS',
    'monolog.level' => $app['monolog.level']
));

// Register services.
$app['dao.article'] = function ($app) {
    return new ArticleDAO($app['db']);
};
$app['dao.user'] = function ($app) {
    return new UserDAO($app['db']);
};
$app['dao.comment'] = function ($app) {
    $commentDAO = new \MicroCMS\DAO\CommentDAO($app['db']);
    $commentDAO->setArticleDAO($app['dao.article']);
    $commentDAO->setUserDAO($app['dao.user']);
    return $commentDAO;
};

// Register error handler
$app->error(function (Exception $e, Request $request, $code) use ($app) {
    switch ($code) {
        case 403:
            $message = 'Access denied.';
            break;
        case 404:
            $message = 'The requested resource could not be found.';
            break;
        default:
            $message = 'Something went wrong.';
    }
    return $app['twig']->render('error.html.twig', array('message' => $message));
});
