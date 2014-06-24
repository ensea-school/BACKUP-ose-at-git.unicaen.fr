<?php

namespace Application\View\Helper;
        
/**
 * Description of ValidationDl
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ValidationDl extends AbstractDl
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
        
        $entity = $this->entity; /* @var $entity \Application\Entity\Db\Validation */
        $tplDtdd = $this->getTemplateDtDd();
        $html    = '';
        $dtdds   = array();
        
        $dtdds[] = sprintf($tplDtdd,
            "Type de validation :", 
            $entity->getTypeValidation()
        );
        
        $dtdds[] = sprintf($tplDtdd,
            "Date et auteur :", 
            $entity->getHistoModification()->format(\Common\Constants::DATETIME_FORMAT) 
                . ' par ' . $this->getView()->mailto($entity->getHistoModificateur())
        );
        
        if ($entity->getDateCommission()) {
            $dtdds[] = sprintf($tplDtdd,
                "Date de passage en commission :", 
                $entity->getDateCommission()->format(\Common\Constants::DATETIME_FORMAT)
            );
        }
        
        $html .= sprintf($this->getTemplateDl('validation validation-details'), implode(PHP_EOL, $dtdds)) . PHP_EOL;
 
        return $html;
    }
}