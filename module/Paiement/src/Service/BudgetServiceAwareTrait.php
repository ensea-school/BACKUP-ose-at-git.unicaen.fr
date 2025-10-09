<?php

namespace Paiement\Service;


/**
 * Description of BudgetServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait BudgetServiceAwareTrait
{
    protected ?BudgetService $serviceBudget = null;



    /**
     * @param BudgetService $serviceBudget
     *
     * @return self
     */
    public function setServiceBudget(?BudgetService $serviceBudget)
    {
        $this->serviceBudget = $serviceBudget;

        return $this;
    }



    public function getServiceBudget(): ?BudgetService
    {
        if (empty($this->serviceBudget)) {
            $this->serviceBudget =\Unicaen\Framework\Application\Application::getInstance()->container()->get(BudgetService::class);
        }

        return $this->serviceBudget;
    }
}