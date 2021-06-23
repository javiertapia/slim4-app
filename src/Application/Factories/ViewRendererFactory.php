<?php
namespace App\Application\Factories;

use MtHaml\Support\Twig\Extension;
use MtHaml\Support\Twig\Loader;
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

        $haml = new \MtHaml\Environment('twig', ['enable_escaper' => false]);
        $hamlLoader = new Loader($haml, $this->loader);
        $twigEnvironment = new Environment($hamlLoader, $twigOptions);
        $twigEnvironment->addExtension(new Extension());
        return $twigEnvironment;
    }

    public function render(string $template, array $viewData): ResponseInterface
    {
        $html = $this->environment->render($template, $viewData);
        $this->response->getBody()->write($html);
        return $this->response;
    }

    public function addPathToLoader(string $path = null): void
    {
        if (empty($path)) {
            return;
        }
        $this->loader->addPath($path);
    }
}
