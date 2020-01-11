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
        extract($ro->body);

        // ファイルを取得する
        $file = (new ReflectionClass($ro))->getFileName();
        $file = str_replace('.php', '.html.php', $file);
        $pos  = strpos($file, '/Resource/');
        $name = substr($file, $pos + (strlen('/Resource/')));

        foreach ($this->viewDirectories as $viewDir) {
            $file = $viewDir.'/'.$name;
            if (file_exists($file)) {
                return include $file;
            }
            var_dump($file);
        }

        throw new InvalidArgumentException("Templete not found");
    }
}
