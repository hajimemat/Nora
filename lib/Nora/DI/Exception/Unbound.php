<?php
/**
 * this file is part of Nora
 *
 * @package Dotenv
 */
declare(strict_types=1);

namespace Nora\DI\Exception;

use Exception;
use LogicException;

class Unbound extends LogicException
{
    public function __toString()
    {
        $messages = [sprintf("- %s\n", $this->getMessage())];
        $e = $this->getPrevious();
        if (!$e instanceof Exception) {
            return $this->getMainMessage($this);
        }

        if ($e instanceof self) {
            return $this->buildMessage($e, $messages). "\n" . $e->getTraceAsString();
        }
        return parent::__toString();
    }

    public function buildMessage(self $e, array $messages)
    {
        $lastE = $e;
        while ($e instanceof self) {
            $messages[] = sprintf("- %s\n", $e->getMessage());
            $lastE = $e;
            $e = $e->getPrevious();
        }
        array_pop($messages);
        $messages = array_reverse($messages);
        return $this->getMainMessage($lastE).implode('', $messages);
    }

    public function getMainMessage(self $e)
    {
        return sprintf(
            "exception '%s' with message '%s'\n",
            get_class($e),
            $e->getMessage()
        );
    }
}
