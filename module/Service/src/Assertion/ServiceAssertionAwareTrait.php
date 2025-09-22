<?php

namespace Service\Assertion;

/**
 * Description of ServiceAssertionAwareTrait
 *
 * @author UnicaenCode
 */
trait ServiceAssertionAwareTrait
{
    protected ?ServiceAssertion $assertionService = null;



    /**
     * @param ServiceAssertion $assertionService
     *
     * @return self
     */
    public function setAssertionService(?ServiceAssertion $assertionService)
    {
        $this->assertionService = $assertionService;

        return $this;
    }



    public function getAssertionService(): ?ServiceAssertion
    {
        if (empty($this->assertionService)) {
            $this->assertionService = \Framework\Application\Application::getInstance()->container()->get(ServiceAssertion::class);
        }

        return $this->assertionService;
    }
}