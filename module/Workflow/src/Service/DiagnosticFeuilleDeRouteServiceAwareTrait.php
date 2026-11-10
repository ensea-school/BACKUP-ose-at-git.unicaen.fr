<?php

namespace Workflow\Service;

trait DiagnosticFeuilleDeRouteServiceAwareTrait
{
    protected ?DiagnosticFeuilleDeRouteService $serviceDiagnosticFeuilleDeRoute = null;



    public function setServiceDiagnosticFeuilleDeRoute(?DiagnosticFeuilleDeRouteService $serviceDiagnosticFeuilleDeRoute): self
    {
        $this->serviceDiagnosticFeuilleDeRoute = $serviceDiagnosticFeuilleDeRoute;

        return $this;
    }



    public function getServiceDiagnosticFeuilleDeRoute(): ?DiagnosticFeuilleDeRouteService
    {
        if (empty($this->serviceDiagnosticFeuilleDeRoute)) {
            $this->serviceDiagnosticFeuilleDeRoute = \Unicaen\Framework\Application\Application::getInstance()->container()->get(DiagnosticFeuilleDeRouteService::class);
        }

        return $this->serviceDiagnosticFeuilleDeRoute;
    }
}
