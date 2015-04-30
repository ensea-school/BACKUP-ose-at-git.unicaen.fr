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
        $dtdds   = [];

        if ($this->getIncludeTypeValidation()) {
            $dtdds[] = sprintf($tplDtdd,
                "Type de validation :",
                $entity->getTypeValidation()
            );
        }

        $dtdds[] = sprintf($tplDtdd,
            "Date et auteur :",
            $entity->getHistoModification()->format(\Common\Constants::DATETIME_FORMAT)
                . ' par ' . $this->getView()->mailto($entity->getHistoModificateur())
        );

//        if ($entity->getDateConseilAcademique()) {
//            $dtdds[] = sprintf($tplDtdd,
//                "Date de passage en Conseil Académique :",
//                $entity->getDateConseilAcademique()->format(\Common\Constants::DATE_FORMAT)
//            );
//        }
//
//        if ($entity->getDateConseilRestreint()) {
//            $dtdds[] = sprintf($tplDtdd,
//                "Date de passage en Conseil Restreint de la composante :",
//                $entity->getDateConseilRestreint()->format(\Common\Constants::DATE_FORMAT)
//            );
//        }


        $html .= sprintf($this->getTemplateDl('validation validation-details'), implode(PHP_EOL, $dtdds)) . PHP_EOL;

        return $html;
    }

    /**
     * @var bool
     */
    private $includeTypeValidation = true;

    /**
     * Inclusion du type de validation dans l'affichage ?
     *
     * @return bool
     */
    public function getIncludeTypeValidation()
    {
        return $this->includeTypeValidation;
    }

    /**
     * Inclure ou non le type de validation dans l'affichage.
     *
     * @param bool $includeTypeValidation
     * @return \Application\View\Helper\ValidationDl
     */
    public function setIncludeTypeValidation($includeTypeValidation)
    {
        $this->includeTypeValidation = $includeTypeValidation;
        return $this;
    }
}