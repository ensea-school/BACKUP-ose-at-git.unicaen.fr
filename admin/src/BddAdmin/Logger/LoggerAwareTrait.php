<?php

namespace BddAdmin\Logger;

trait LoggerAwareTrait
{

    /**
     * @var LoggerInterface
     */
    protected $logger;



    /**
     * @return LoggerInterface|null
     */
    public function getLogger(): ?LoggerInterface
    {
        return $this->logger;
    }



    /**
     * @param LoggerInterface|null $logger
     *
     * @return self
     */
    public function setLogger(?LoggerInterface $logger): self
    {
        $this->logger = $logger;

        return $this;
    }



    public function logError($e)
    {
        if ($this->logger) $this->logger->error($e);
    }



    public function logBegin(string $title)
    {
        if ($this->logger) $this->logger->begin($title);
    }



    public function logEnd(?string $msg = null)
    {
        if ($this->logger) $this->logger->end($msg);
    }



    public function logMsg($message, bool $rewrite = false)
    {
        if ($this->logger) $this->logger->msg($message, $rewrite);
    }
}