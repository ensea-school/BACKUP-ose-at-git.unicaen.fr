<?php

namespace Application\View\Helper;

/**
 * Description of EtablissementDl
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class EtablissementDl extends AbstractDl
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

        $entity = $this->entity; /* @var $entity \Application\Entity\Db\Etablissement */
        $tplDtdd = $this->getTemplateDtDd();
        $html    = '';
        $dtdds   = [];

        $dtdds[] = sprintf($tplDtdd,
            "Libellé :",
            $entity->getLibelle()
        );

        if (!$this->short) {
            $dtdds[] = sprintf($tplDtdd,
                "Localisation :",
                $entity->getLocalisation()
            );

            $dtdds[] = sprintf($tplDtdd,
                "Département :",
                $entity->getDepartement()
            );
        }
        else {
            $dtdds[] = sprintf($tplDtdd,
                "Localisation :",
                $entity->getLocalisation() . " (" . $entity->getDepartement() . ")"
            );
        }

        if (!$this->short) {
            $dtdds[] = sprintf($tplDtdd,
                "N° {$entity->getSource()->getLibelle()} :",
                $entity->getSourceCode()
            );

            $dtdds[] = sprintf($tplDtdd,
                "Historique :",
                $this->getView()->historiqueDl($entity)
            );
        }

        $html .= sprintf($this->getTemplateDl('etablissement etablissement-details'), implode(PHP_EOL, $dtdds)) . PHP_EOL;

        return $html;
    }
}