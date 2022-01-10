<?php

namespace ExportRh\Service;

/**
 * Description of ExportRhServiceAwareTrait
 *
 * @author Antony LE COURTES <antony.lecourtes at unicaen.fr>
 */
trait ExportRhServiceAwareTrait
{
    /**
     * @var ExportRhService
     */
    protected $exportRhService;



    /**
     * @param ExportRhService $exportRhService
     *
     * @return self
     */
    public function setExportRhService(ExportRhService $exportRhService)
    {
        $this->exportRhService = $exportRhService;

        return $this;
    }



    /**
     * @return ExportRhService
     */
    public function getExportRhService(): ExportRhService
    {
        if (!$this->exportRhService) {
            $this->exportRhService = \Application::$container->get(ExportRhService::class);
        }

        return $this->exportRhService;
    }
}
