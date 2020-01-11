<?php
/**
 * this file is part of Nora
 *
 * @package Kernel
 */
declare(strict_types=1);

namespace Nora\Kernel\Provid\Transfer;

use Nora\Kernel\Extension\Transfer\TransferInterface;
use Nora\Resource\ResourceObjectInterface;

class HttpResponder implements TransferInterface
{
    const HEADER_IN_304 = [
        'Cache-Control',
        'Content-Location',
        'Date',
        'ETag',
        'Expires',
        'Vary'
    ];

    public function __invoke(ResourceObjectInterface $ro, array $server)
    {
        if (!$this->isModified($ro, $server)) {
            $output = $this->getNotModifiedOutput($ro->headers);
        } else {
            $output = $this->getOutput($ro, $server);
        }
        foreach ($output->headers as $label => $value) {
            header("{$label}: {$value}", false);
        }
        http_response_code($output->code);

        echo $output->view;
    }

    private function isModified(ResourceObjectInterface $ro, array $server)
    {
        if (!isset($server['HTTP_IF_NONE_MATCH'], $ro->headers['ETag'])) {
            return true;
        }

        if ($server['HTTP_IF_NONE_MATCH'] === $ro->headers['ETag']) {
            return true;
        }

        return false;
    }

    private function getNotModifiedOutput(array $originalHeaders) : Output
    {
        $headers = [];
        foreach ($originalHeaders as $label => $value) {
            if (!in_array($label, self::HEADER_IN_304, true)) {
                continue;
            }
            $headers[$label] = $value;
        }
        return new Output(304, $headers, '');
    }

    private function getOutput(ResourceObjectInterface $ro, array $server) : Output
    {
        return new Output(
            $ro->code,
            $ro->headers,
            (string) $ro
        );
    }
}
