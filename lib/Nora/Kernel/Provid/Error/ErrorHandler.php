<?php
/**
 * this file is part of Nora
 *
 * @package Kernel
 */
declare(strict_types=1);

namespace Nora\Kernel\Provid\Error;

use Exception;
use Nora\DI\Exception\NotFound;
use Nora\DI\Module\AbstractModule;
use Nora\Http\HttpStatusCode;
use Nora\Kernel\Context\Context;
use Nora\Kernel\Extension\Error\ErrorHandlerInterface;
use Nora\Kernel\Extension\Transfer\TransferInterface;
use Nora\Resource\Renderer\RendererInterface;

class ErrorHandler implements ErrorHandlerInterface
{
    public function __construct(TransferInterface $transfer, Context $context, RendererInterface $renderer)
    {
        $this->transfer = $transfer;
        $this->context = $context;
        $this->errorPage = new Resource\ErrorPage;
        $this->errorPage->setRenderer($renderer);
    }

    public function handle(Exception $e)
    {
        if (!$this->canHandle($e)) {
            $this->errorPage->code = 500;
            $this->errorPage->body = [
                'code' => $this->errorPage->code,
                'message' => '500 Server Error',
                'exception' => (string) $e
            ];
            error_log((string) $e);
            return $this;
        }

        $this->errorPage->code = (int) $e->getCode();
        $this->errorPage->body = [
            'code' => $this->errorPage->code,
            'message' => (new HttpStatusCode)->statusText[$e->getCode()]
        ];

        return $this;
    }

    public function canHandle(Exception $e)
    {
        if ($e instanceof NotFound) {
            return true;
        }
        return array_key_exists($e->getCode(), (new HttpStatusCode)->statusText);
    }

    public function transfer()
    {
        return ($this->transfer)($this->errorPage, $this->context->server);
    }
}
