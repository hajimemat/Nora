<?php
namespace Nora\Dotenv;

use Nora\Dotenv\Exception\EnvFileNotFound;
use PHPUnit\Framework\TestCase;

class DotenvTest extends TestCase
{
    /**
     * 起動テスト
     */
    public function testCreate()
    {
        $dotenv = (new NewDotenv)(dirname(__DIR__));
        $originalUser = getenv('USER');
        $dotenv->load();
        $this->assertSame($originalUser, getenv('USER'));
        $dotenv->override();
        $this->assertNotEquals($originalUser, getenv('USER'));
        $this->assertSame("hajime", getenv('USER'));
    }

    /**
     * .envファイルが存在しなければ例外を投げる
     */
    public function testError()
    {
        $this->expectException(EnvFileNotFound::class);
        $dotenv = (new NewDotenv)(__DIR__);
        $dotenv->load();
    }
}
