<?php
declare(strict_types=1);

namespace App\Application\Actions;

use App\Application\Factories\ViewRendererFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Twig\Environment;

final class HelloAction
{
    private ViewRendererFactory $renderer;

    public function __construct(ViewRendererFactory $renderer)
    {
        $this->renderer = $renderer;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface      $response
    ): ResponseInterface
    {
        try {
            $viewData = [
                'name' => 'World',
                'notifications' => [
                    'message' => 'You are good!'
                ],
            ];

            return $this->renderer->render('hello.twig', $viewData);

        } catch (\Throwable $e) {
            throw $e;
        }
    }
}
