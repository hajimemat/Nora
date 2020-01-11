<?php
namespace Nora\IO\Console;

use Nora\IO\OutputInterface;

class Output implements OutputInterface
{
    public function __construct()
    {
        $this->stderr = STDERR;
        $this->stdout = STDOUT;
    }

    public function errln(string $message)
    {
        $this->err($message."\n");
    }

    public function err(string $message)
    {
        $this->write($this->stderr, $message);
    }

    public function outln(string $message)
    {
        $this->out($message."\n");
    }

    public function out(string $message)
    {
        $this->write($this->stdout, $message);
    }

    protected function write($resource, string $message)
    {
        fwrite($resource, $message);
    }
}
