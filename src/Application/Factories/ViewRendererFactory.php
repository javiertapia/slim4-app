<?php
namespace App\Application\Factories;

use Psr\Http\Message\ResponseInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

final class ViewRendererFactory
{
    private ResponseInterface $response;
    private array $settings;
    private FilesystemLoader $loader;
    private Environment $environment;

    public function __construct(ResponseInterface $response, array $settings)
    {
        $this->response = $response;
        $this->settings = $settings;
        $this->loader = new FilesystemLoader($settings['paths']);
        $this->environment = $this->createEnvironment();
    }

    private function createEnvironment(): Environment
    {
        $cacheEnabled = $this->settings['options']['cache_enabled'] ?? false;
        $cacheLocation = $this->settings['options']['cache_path'] ?? null;
        $twigOptions = ($cacheEnabled && $cacheLocation)
            ? ['cache' => $cacheLocation]
            : ['cache' => false];
        return new Environment($this->loader, $twigOptions);
    }

    public function render(string $template, array $viewData): ResponseInterface
    {
        $html = $this->environment->render($template, $viewData);
        $this->response->getBody()->write($html);
        return $this->response;
    }
}
