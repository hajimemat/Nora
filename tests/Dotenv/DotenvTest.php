<?php
namespace Nora\Dotenv;

use Nora\Dotenv\Exception\EnvFileNotFound;
use PHPUnit\Framework\TestCase;

class DotenvTest extends TestCase
{
    /**
     * @test
     */
    public function ファイルの読み込みテスト()
    {
        $dotenv = (new NewDotenv)(dirname(__DIR__));
        $originalUser = getenv('USER');
        $dotenv->load();
        $this->assertSame($originalUser, getenv('USER'));
    }

    /**
     * @test
     */
    public function 環境変数のオーバライド()
    {
        $originalUser = getenv('USER');
        $dotenv = (new NewDotenv)(dirname(__DIR__));
        $originalUser = getenv('USER');
        $dotenv->override();
        $this->assertNotEquals($originalUser, getenv('USER'));
        $this->assertSame("hajime", getenv('USER'));
    }

    /**
     * @test
     */
    public function ファイルが存在しなければ例外()
    {
        $this->expectException(EnvFileNotFound::class);
        $dotenv = (new NewDotenv)(__DIR__);
        $dotenv->load();
    }
}
