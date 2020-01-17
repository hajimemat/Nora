<?php
use Doctrine\Common\Annotations\AnnotationRegistry;
use Nora\Framework\Bootstrap;

$loader = include dirname(__DIR__, 3).'/vendor/autoload.php';
AnnotationRegistry::registerLoader([$loader, 'loadClass']);

return (new Bootstrap)('NoraFake', 'app-test');
