<?php

namespace Formule\Tbl\Process;


use Formule\Entity\FormuleIntervenant;
use Formule\Service\FormulatorServiceAwareTrait;
use Formule\Tbl\Process\Sub\ServiceDataManager;
use UnicaenTbl\Process\ProcessInterface;
use UnicaenTbl\TableauBord;

/**
 * Description of FormuleProcess
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class FormuleProcess implements ProcessInterface
{
    use FormulatorServiceAwareTrait;

    protected ServiceDataManager $serviceDataManager;

    /**
     * @var array|FormuleIntervenant[]
     */
    protected array $data = [];



    public function __construct(ServiceDataManager $serviceDataManager)
    {
        $this->serviceDataManager = $serviceDataManager;
    }



    public function run(TableauBord $tableauBord, array $params = [])
    {
        $this->data = $this->serviceDataManager->load($params);
        foreach ($this->data as $formuleIntervenant) {
            $this->getServiceFormulator()->calculer($formuleIntervenant);
        }
    }

}