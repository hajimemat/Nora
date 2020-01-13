<?php
namespace NoraFake;

/**
 * @Annotation
 * @Target("METHOD")
 */
class FakeTrace
{
    public $rel;
    public $src;
}
