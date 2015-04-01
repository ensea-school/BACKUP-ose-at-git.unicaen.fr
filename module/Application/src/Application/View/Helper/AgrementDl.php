<?php

namespace Application\View\Helper;

use Application\Entity\Db\Agrement;
use Common\Constants;

/**
 * Description of AgrementDl
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class AgrementDl extends AbstractDl
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

        $entity = $this->entity; /* @var $entity Agrement */
        $tplDtdd = $this->getTemplateDtDd();
        $html    = '';
        $dtdds   = [];

        if ($this->getIncludeTypeAgrement()) {
            $dtdds[] = sprintf($tplDtdd,
                "Type d'agrément :",
                $entity->getType()
            );
        }

        $dtdds[] = sprintf($tplDtdd,
            "Date de la décision :",
            $entity->getDateDecision()->format(Constants::DATE_FORMAT)
        );

        $dtdds[] = sprintf($tplDtdd,
            "Date et auteur de l'enregistrement :",
            $entity->getHistoModification()->format(Constants::DATETIME_FORMAT)
                . ' par ' . $this->getView()->mailto($entity->getHistoModificateur())
        );

        $html .= sprintf($this->getTemplateDl('agrement agrement-details'), implode(PHP_EOL, $dtdds)) . PHP_EOL;

        return $html;
    }

    /**
     * @var bool
     */
    private $includeTypeAgrement = true;

    /**
     * Inclusion du type d'agrément dans l'affichage ?
     *
     * @return bool
     */
    public function getIncludeTypeAgrement()
    {
        return $this->includeTypeAgrement;
    }

    /**
     * Inclure ou non le type d'agrément dans l'affichage.
     *
     * @param bool $includeTypeAgrement
     * @return AgrementDl
     */
    public function setIncludeTypeAgrement($includeTypeAgrement)
    {
        $this->includeTypeAgrement = $includeTypeAgrement;
        return $this;
    }
}