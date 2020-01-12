<?php
declare(strict_types=1);
namespace Nora\DI;

use Nora\DI\Aop\Bind as AopBind;
use Nora\DI\ValueObject\Name;

final class AspectBind
{
    /**
     * @var AopBind
     */
    private $bind;
    public function __construct(AopBind $bind)
    {
        $this->bind = $bind;
    }
    /**
     * Instantiate interceptors
     */
    public function inject(Container $container) : array
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
