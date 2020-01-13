<?php
declare(strict_types=1);
namespace Nora\Framework\AOP\Compiler\CodeGen;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Declare_;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Use_;
use PhpParser\NodeVisitorAbstract;

final class CodeVisitor extends NodeVisitorAbstract
{
    /**
     * @var Namespace_
     */
    public $namespace;
    /**
     * @var Declare_[]
     */
    public $declare = [];
    /**
     * @var Use_[]
     */
    public $use = [];
    /**
     * @var null|Class_
     */
    public $class;
    /**
     * @var ClassMethod[]
     */
    public $classMethod = [];
    public function enterNode(Node $node)
    {
        if ($node instanceof Declare_) {
            $this->declare[] = $node;
        }
        if ($node instanceof Use_) {
            $this->use[] = $node;
        }
        if ($node instanceof Namespace_) {
            $this->namespace = $node;
        }
        if ($node instanceof Class_) {
            $this->validateClass($node);
            $this->class = $node;
        }
        if ($node instanceof ClassMethod) {
            $this->classMethod[] = $node;
        }
    }
    private function validateClass(Class_ $class)
    {
        $isClassAlreadyDeclared = $this->class instanceof Class_;
        if ($isClassAlreadyDeclared) {
            $name = $class->name instanceof Node\Identifier ? $class->name->name : '';
            throw new MultipleClassInOneFileException($name);
        }
    }
}
