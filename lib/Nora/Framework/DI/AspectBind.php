<?php
declare(strict_types=1);
namespace Nora\Framework\DI;

use Nora\Framework\AOP\Bind\Bind;
use Nora\Framework\DI\Container\ContainerInterface;
use Nora\Framework\DI\ValueObject\Name;

final class AspectBind
{
    /**
     * @var Bind
     */
    private $bind;
    public function __construct(Bind $bind)
    {
        $this->bind = $bind;
    }
    /**
     * Instantiate interceptors
     */
    public function inject(ContainerInterface $container) : array
    {
        $bindings = $this->bind->getBindings();
        foreach ($bindings as &$interceptors) {
            /* @var string[] $interceptors */
            foreach ($interceptors as &$interceptor) {
                if (\is_string($interceptor)) {
                    $interceptor = $container->getInstance($interceptor, Name::ANY);
                }
            }
        }
        return $bindings;
    }
}
