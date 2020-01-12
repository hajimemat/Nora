<?php
/**
 * this file is part of Nora
 *
 * @package Resource
 */
declare(strict_types=1);

namespace Nora\Resource\Renderer;

use InvalidArgumentException;
use Nora\DI\Annotation\Named;
use Nora\DI\Interceptor\WeavedInterface;
use Nora\Resource\ResourceObjectInterface;
use ReflectionClass;

class HtmlRenderer implements RendererInterface
{
    /**
     * @Named("viewDirectories=template_dirs")
     */
    public function __construct(array $viewDirectories)
    {
        $this->viewDirectories = $viewDirectories;
    }

    public function render(ResourceObjectInterface $ro)
    {
        // ファイルを取得する
        $file = (new ReflectionClass($ro))->getFileName();
        if ($ro instanceof WeavedInterface) {
            $file = (new ReflectionClass($ro))->getParentClass()->getFileName();
        }
        $file = str_replace('.php', '.html.php', $file);
        $pos  = strpos($file, '/Resource/');
        $name = substr($file, $pos + (strlen('/Resource/')));

        foreach ($this->viewDirectories as $viewDir) {
            $file = $viewDir.'/'.$name;
            if (file_exists($file)) {
                extract($vars = $ro->body);
                ob_start();
                include $file;
                $contents = ob_get_contents();
                ob_end_clean();
                return $contents;
            }
        }

        throw new InvalidArgumentException("Templete not \"{$name}\" found");
    }
}
