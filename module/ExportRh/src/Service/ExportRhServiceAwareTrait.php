<?php

namespace ExportRh\Service;


/**
 * Description of ExportRhServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait ExportRhServiceAwareTrait
{
    protected ?ExportRhService $serviceExportRh = null;



    /**
     * @param ExportRhService $serviceExportRh
     *
     * @return self
     */
    public function setServiceExportRh(?ExportRhService $serviceExportRh)
    {
        $this->serviceExportRh = $serviceExportRh;

        return $this;
    }



    public function getServiceExportRh(): ?ExportRhService
    {
        if (empty($this->serviceExportRh)) {
            $this->serviceExportRh =\Unicaen\Framework\Application\Application::getInstance()->container()->get(ExportRhService::class);
        }

        return $this->serviceExportRh;
    }
}