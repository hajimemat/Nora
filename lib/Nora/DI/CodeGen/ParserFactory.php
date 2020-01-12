<?php
declare(strict_types=1);
namespace Nora\DI\CodeGen;

use PhpParser\ParserFactory as PhpParserFactory;

class ParserFactory
{
    public function newInstance()
    {
        return (new PhpParserFactory)->create(PhpParserFactory::PREFER_PHP7);
    }
}
