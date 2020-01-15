<?php
namespace Nora\Framework\Kernel\Provide\Vars\DotEnv;

use Nora\Dotenv\Exception\EnvFileNotFound;
use ReflectionClass;

class DotEnv
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $name;


    public function __construct(string $path, string $name = ".env")
    {
        $this->path = $path;
        $this->name = $name;
    }

    /**
     * Load .env file
     *
     * loading .env file and putenv but not override
     * alrady defined key
     *
     * @throw EnvFileNotFound
     */
    public function load()
    {
        $this->updateEnv(false);
    }

    /**
     * Load .env file
     *
     * loading .env file and putenv and override
     * even alrady defined key
     *
     * @throw EnvFileNotFound
     */
    public function override()
    {
        $this->updateEnv(true);
    }

    /**
     * @throw EnvFileNotFound
     */
    private function loadEnvFile()
    {
        $file = $this->path."/".$this->name;
        if (!file_exists($file)) {
            throw new EnvFileNotFound("Env {$file} Not Found");
        }
        $lines = array_filter(
            explode(
                "\n",
                str_replace(
                    ["\r\n","\n\r","\r"],
                    "\n",
                    file_get_contents($file)
                )
            ),
            function ($line) {
                $line = trim($line);
                if (empty($line)) {
                    return false;
                }
                if ($line[0] == "#") {
                    return false;
                }
                return true;
            }
        );

        $env = [];
        foreach ($lines as $line) {
            list($key, $value) = explode("=", $line, 2);
            $env[$key] = $value;
        }

        return $env;
    }


    /**
     * @throw EnvFileNotFound
     */
    private function updateEnv(bool $override)
    {
        $file = $this->loadEnvFile();
        foreach ($file as $key => $value) {
            if ($override || getenv($key) === false) {
                putenv("{$key}={$value}");
                $_ENV[$key] = $value;
                $_SERVER[$key] = $value;
            }
        }
        return true;
    }
}
