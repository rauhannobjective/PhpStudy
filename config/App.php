<?php

namespace PhpStudy\Config;

use DI\Container;
use PhpStudy\Src\Traits\Json;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Factory\AppFactory;
use Slim\Psr7\Response;

class App
{
    use Json;

    private \Slim\App $app;

    public function __construct(array $settings)
    {
        $this->setContainer($settings);
    }

    /**
     * Adicionando dependencias no container da aplicação.
     *
     * @param array $settings
     * @return \Slim\App
     */
    public function setContainer(array $settings): \Slim\App
    {
        $container = new Container();
        AppFactory::setContainer($container);
        $this->setApp(AppFactory::create());
        $this->setRouteMiddleware();

        foreach ($settings as $dependency => $toImport) {
            $container->set($dependency, $toImport);
        }

        $GLOBALS['app'] = $this->getApp();

        return $this->getApp();
    }

    /**
     * Getter da variavel app
     *
     * @return \Slim\App
     */
    public function getApp(): \Slim\App
    {
        return $this->app;
    }

    /**
     * Setter da variavel app
     *
     * @param \Slim\App $app
     * @return void
     */
    public function setApp(\Slim\App $app): void
    {
        $this->app = $app;
    }

    /**
     * Seta middleware de rotas default
     *
     * @return void
     */
    public function setRouteMiddleware(): void
    {
        $app = $this->getApp();
        $app->addRoutingMiddleware();

        $customErrorHandler = function (
            ServerRequestInterface $request,
            \Throwable $exception
        ) use ($app) {
            $payload = ['errors' => [$exception->getMessage()]];
            $code = $exception->getCode() == 0 ? 400 : $exception->getCode();

            $response = $app->getResponseFactory()->createResponse();
            $response->getBody()->write(json_encode($payload, JSON_UNESCAPED_UNICODE));

            return $response->withHeader('Content-Type', 'application/json')->withStatus($code);
        };

        $errorMiddleware = $this->getApp()->addErrorMiddleware(true, true, true);
        $errorMiddleware->setDefaultErrorHandler($customErrorHandler);
    }
}