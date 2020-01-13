<?php
/**
 * this file is part of Nora
 *
 * @package DI
 */
declare(strict_types=1);

namespace Nora\Framework\DI\Injector;

use Nora\Framework\DI\Container;
use Nora\Framework\DI\Exception\Unbound;
use Nora\Framework\DI\Exception\Untargeted;
use Nora\Framework\DI\ValueObject\Name;
use ReflectionMethod;

final class Arguments
{
    private $arguments = [];

    public function __construct(ReflectionMethod $method, Name $name)
    {
        $parameters = $method->getParameters();
        foreach ($parameters as $parameter) {
            $this->arguments[] = new Argument($parameter, $name($parameter));
        }
    }

    public function inject(Container $container) : array
    {
        $parameters = $this->arguments;
        foreach ($parameters as &$parameter) {
            $parameter = $this->getParameter($container, $parameter);
        }
        return $parameters;
    }

    public function getParameter(Container $container, Argument $argument)
    {
        // $this->bindInjectionPoint($container, $argument);
        try {
            return $container->getDependency((string) $argument);
        } catch (Unbound $e) {
            if ($argument->isDefaultAvailable()) {
                return $argument->getDefaultValue();
            }
            throw new Unbound($argument->getMeta(), 0, $e);
        } catch (Untargeted $e) {
            if ($argument->isDefaultAvailable()) {
                return $argument->getDefaultValue();
            }
            throw new Untargeted($argument->getMeta(), 0, $e);
        }
    }
}
