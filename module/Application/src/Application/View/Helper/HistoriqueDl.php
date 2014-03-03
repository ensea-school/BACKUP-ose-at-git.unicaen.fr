<?php

namespace Application\View\Helper;

/**
 * Description of HistoriqueDl
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class HistoriqueDl extends AbstractDl
{
    /**
     * 
     * 
     * @return string Code HTML
     */
    public function render()
    {
        if (!$this->entity) {
            return '';
        }
        
        $entity   = $this->entity; /* @var $entity \Application\Entity\Db\HistoriqueAwareInterface */
        $tplDtdd = $this->getTemplateDtDd();
        $html    = '';
        $dtdds   = array();
        
        $dtdds[] = sprintf($tplDtdd,
            "Création :", 
            sprintf("le %s par %s", 
                    $entity->getHistoCreation()->format(\Common\Constants::DATETIME_FORMAT),
                    $entity->getHistoCreateur()->getDisplayName())
        );
        
        if ($entity->getHistoModification() != $entity->getHistoCreation()) {
            $dtdds[] = sprintf($tplDtdd,
                "Modification :", 
                sprintf("le %s par %s", 
                        $entity->getHistoModification()->format(\Common\Constants::DATETIME_FORMAT),
                        $entity->getHistoModificateur()->getDisplayName())
            );
        }
        
        if ($entity->getHistoDestruction()) {
            $dtdds[] = sprintf($tplDtdd,
                "Suppression :", 
                sprintf("le %s par %s", 
                        $entity->getHistoDestruction()->format(\Common\Constants::DATETIME_FORMAT),
                        $entity->getHistoDestructeur()->getDisplayName())
            );
        }
        
        $html .= sprintf($this->getTemplateDl('histo histo-details'), implode(PHP_EOL, $dtdds)) . PHP_EOL;
 
        return $html;
    }
}