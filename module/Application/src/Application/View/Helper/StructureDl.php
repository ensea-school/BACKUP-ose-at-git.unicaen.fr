<?php

namespace Application\View\Helper;
        
/**
 * Description of StructureDl
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class StructureDl extends AbstractDl
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
        
        $entity = $this->entity; /* @var $entity \Application\Entity\Db\Structure */
        $tplDtdd = $this->getTemplateDtDd();
        $html    = '';
        $dtdds   = array();
        
        $dtdds[] = sprintf($tplDtdd,
            "Libellé long :", 
            $entity->getLibelleLong()
        );
        
        $dtdds[] = sprintf($tplDtdd,
            "Libellé court :", 
            $entity->getLibelleCourt()
        );
        
        $dtdds[] = sprintf($tplDtdd,
            "Type de entity :", 
            $entity->getType()->getLibelle()
        );
        
        $dtdds[] = sprintf($tplDtdd,
            "N° {$entity->getSource()->getLibelle()} :", 
            $entity->getSourceCode()
        );
        
        $dtdds[] = sprintf($tplDtdd,
            "Établissement :", 
            $entity->getEtablissement()->getLibelle()
        );
        
        $dtdds[] = sprintf($tplDtdd,
            "Structure mère :", 
            $entity->getParente()->getLibelleLong()
        );
        
        $dtdds[] = sprintf($tplDtdd,
            "Historique :", 
            $this->getView()->historiqueDl($entity)
        );
        
        $html .= sprintf($this->getTemplateDl('structure structure-details'), implode(PHP_EOL, $dtdds)) . PHP_EOL;
 
        return $html;
    }
}