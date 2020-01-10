<?php

use Doctrine\Common\Annotations\AnnotationRegistry;

$loader = include dirname(__DIR__).'/vendor/autoload.php';
AnnotationRegistry::registerLoader([$loader, 'loadClass']);
