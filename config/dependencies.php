<?php
declare(strict_types=1);

use App\Application\Settings\SettingsInterface;
use Illuminate\Container\Container;
use Illuminate\Database\Connection;
use Illuminate\Database\Connectors\ConnectionFactory;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Middleware\ErrorMiddleware;

return [
    App::class => function (ContainerInterface $container) {
        AppFactory::setContainer($container);
        return AppFactory::create();
    },

    LoggerInterface::class => function (ContainerInterface $container) {
        $settings = $container->get(SettingsInterface::class);

        $loggerSettings = $settings->get('logger');
        $logger = new Logger($loggerSettings['name']);

        $processor = new UidProcessor();
        $logger->pushProcessor($processor);

        $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
        $logger->pushHandler($handler);

        return $logger;
    },

    ErrorMiddleware::class => function(ContainerInterface $container)
    {
        /** @var App $app */
        $app = $container->get(App::class);
        $settings = $container->get(SettingsInterface::class);
        return new ErrorMiddleware(
            $app->getCallableResolver(),
            $app->getResponseFactory(),
            (bool)$settings->get('displayErrorDetails'),
            (bool)$settings->get('logError'),
            (bool)$settings->get('logErrorDetails')
        );
    },

    Connection::class => function (ContainerInterface $container) {
        $factory = new ConnectionFactory(new Container());
        $settings = $container->get(SettingsInterface::class);
        $connection = $factory->make($settings->get('db'));
        // Disable the query log to prevent memory issues
        $connection->disableQueryLog();
        return $connection;
    },

    PDO::class => function (ContainerInterface $container) {
        return $container->get(Connection::class)->getPdo();
    },
];
