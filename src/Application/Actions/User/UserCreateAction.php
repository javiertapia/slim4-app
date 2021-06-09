<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Domain\User\Services\UserCreatorService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class UserCreateAction
{
    private UserCreatorService $userCreatorService;

    public function __construct(UserCreatorService $userCreatorService)
    {
        $this->userCreatorService = $userCreatorService;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        // Collect input from the HTTP request
        $data = (array)$request->getParsedBody();

        // Invoke the Domain with inputs and retain the result
        $userId = $this->userCreatorService->createUser($data);

        // Transform the result into the JSON representation
        $result = [
            'user_id' => $userId
        ];

        // Build the HTTP response
        $response->getBody()->write((string)json_encode($result));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    }
}
