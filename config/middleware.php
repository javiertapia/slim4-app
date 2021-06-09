<?php
declare(strict_types=1);

use App\Application\Middleware\SessionMiddleware;
use Slim\App;

return function (App $app) {
    // add session values
    $app->add(SessionMiddleware::class);

    // Add Routing Middleware
    $app->addRoutingMiddleware();
};
