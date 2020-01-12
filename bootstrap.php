<?php
use Nora\Kernel\DI\KernelInjector;
use Doctrine\Common\Annotations\AnnotationRegistry;

$loader = require __DIR__ . '/vendor/autoload.php';
AnnotationRegistry::registerLoader([$loader, 'loadClass']);

return function (string $context, string $name) : int {
    $kernel = (new KernelInjector($name, $context))(); // Kernelを取り出す
    $match = $kernel->router->match($kernel->context->globals, $kernel->context->server);
    try {
        $response = $kernel
            ->resource
            ->{$match->getMethod()}
            ->uri($match->getPath())($match->getQuery());

        // $response->transfer($kernel->respounder, $kernel->context->server);
        $response->transfer($kernel->respounder, $kernel->context->server);

        return 0;
    } catch (\Exception $e) {
        $kernel->error->handle($e, $match)->transfer();
        return 1;
    }
};
