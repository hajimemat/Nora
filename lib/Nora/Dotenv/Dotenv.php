<?php
namespace Nora\Dotenv;

use Nora\Dotenv\Exception\EnvFileNotFound;
use ReflectionClass;

class Dotenv
{
    /**
     * @var string
     */
    private $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    private function loadEnvFile()
    {
        $file = $this->path."/.env";
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

    public function load()
    {
        $this->updateEnv(false);
    }

    public function override()
    {
        $this->updateEnv(true);
    }

    private function updateEnv(bool $override)
    {
        $file = $this->loadEnvFile();
        foreach ($file as $key => $value) {
            if (getenv($key) === false || $override) {
                putenv("{$key}={$value}");
            }
        }
        return true;
    }
}
