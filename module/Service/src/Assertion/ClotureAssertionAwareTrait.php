<?php

namespace Service\Assertion;

/**
 * Description of ClotureAssertionAwareTrait
 *
 * @author UnicaenCode
 */
trait ClotureAssertionAwareTrait
{
    protected ?ClotureAssertion $assertionCloture = null;



    /**
     * @param ClotureAssertion $assertionCloture
     *
     * @return self
     */
    public function setAssertionCloture(?ClotureAssertion $assertionCloture)
    {
        $this->assertionCloture = $assertionCloture;

        return $this;
    }



    public function getAssertionCloture(): ?ClotureAssertion
    {
        if (empty($this->assertionCloture)) {
            $this->assertionCloture =\Unicaen\Framework\Application\Application::getInstance()->container()->get(ClotureAssertion::class);
        }

        return $this->assertionCloture;
    }
}