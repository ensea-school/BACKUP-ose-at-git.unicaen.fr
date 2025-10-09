<?php

namespace Administration\Service;


/**
 * Description of GitRepoServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait GitRepoServiceAwareTrait
{
    protected ?GitRepoService $serviceGitRepo = null;



    /**
     * @param GitRepoService $serviceGitRepo
     *
     * @return self
     */
    public function setServiceGitRepo(?GitRepoService $serviceGitRepo)
    {
        $this->serviceGitRepo = $serviceGitRepo;

        return $this;
    }



    public function getServiceGitRepo(): ?GitRepoService
    {
        if (empty($this->serviceGitRepo)) {
            $this->serviceGitRepo = \Unicaen\Framework\Application\Application::getInstance()->container()->get(GitRepoService::class);
        }

        return $this->serviceGitRepo;
    }
}