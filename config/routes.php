<?php
declare(strict_types=1);

use App\Application\Actions\HelloAction;
use App\Application\Actions\HelloAgainAction;
use App\Application\Actions\HelloAgainAgainAction;
use App\Application\Actions\SendEmailAction;
use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\UserCreateAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello world!');
        return $response;
    });

    $app->post('/email', SendEmailAction::class);

    $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
        $group->post('', UserCreateAction::class);
    });

    $app->get('/hello', HelloAction::class);
    $app->get('/hello-again', HelloAgainAction::class);
    $app->get('/hello-again-again', HelloAgainAgainAction::class);
};
