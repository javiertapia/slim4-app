<?php
declare(strict_types=1);

use App\Application\Handlers\HttpErrorHandler;
use App\Application\Handlers\ShutdownHandler;
use App\Application\ResponseEmitter\ResponseEmitter;
use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Dotenv\Dotenv;
use Slim\App;
use Slim\Factory\ServerRequestCreatorFactory;

require dirname(__DIR__) . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

// Instantiate PHP-DI ContainerBuilder
$containerBuilder = new ContainerBuilder();

$enableCompilation = (bool)($_ENV['APP_ENABLE_COMPILATION'] ?? true);
if ($enableCompilation === false) {
    $containerBuilder->enableCompilation(dirname(__DIR__) . '/var/cache');
}

// Set up settings
$containerBuilder->addDefinitions(__DIR__ . '/settings.php');

// Set up dependencies
$containerBuilder->addDefinitions(__DIR__ . '/dependencies.php');

// Set up repositories
$containerBuilder->addDefinitions(__DIR__ . '/repositories.php');

// Build PHP-DI Container instance
$container = $containerBuilder->build();

// Instantiate the app
$app = $container->get(App::class);

// Register routes
(require __DIR__ . '/routes.php')($app);

// Register middleware
(require __DIR__ . '/middleware.php')($app);

// Notices and Warnings Handling

$callableResolver = $app->getCallableResolver();

/** @var SettingsInterface $settings */
$settings = $container->get(SettingsInterface::class);
$displayErrorDetails = $settings->get('displayErrorDetails');
$logError = $settings->get('logError');
$logErrorDetails = $settings->get('logErrorDetails');

// Create Request object from globals
$request = (ServerRequestCreatorFactory::create())->createServerRequestFromGlobals();

// Create Error Handler
$responseFactory = $app->getResponseFactory();
$errorHandler = new HttpErrorHandler($callableResolver, $responseFactory);

// Create Shutdown Handler
$shutdownHandler = new ShutdownHandler($request, $errorHandler, $settings->get('displayErrorDetails'));
register_shutdown_function($shutdownHandler);

// Add Error Middleware
$errorMiddleware = $app->addErrorMiddleware($displayErrorDetails, $logError, $logErrorDetails);
$errorMiddleware->setDefaultErrorHandler($errorHandler);


// Run App & Emit Response
$response = $app->handle($request);
$responseEmitter = new ResponseEmitter();
$responseEmitter->emit($response);
