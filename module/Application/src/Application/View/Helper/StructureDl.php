<?php

namespace Application\View\Helper;

/**
 * Description of StructureDl
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
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
        $dtdds   = [];

        $dtdds[] = sprintf($tplDtdd,
            "Libellé long :",
            $entity->getLibelleLong()
        );

        if (!$this->short) {
            $dtdds[] = sprintf($tplDtdd,
                "Libellé court :",
                $entity->getLibelleCourt()
            );

            $dtdds[] = sprintf($tplDtdd,
                "Type de structure :",
                $entity->getType()->getLibelle()
            );
        }

        if (!$this->short) {
            $dtdds[] = sprintf($tplDtdd,
                "N° {$entity->getSource()->getLibelle()} :",
                $entity->getSourceCode()
            );
        }

        if ($entity->getParente()) {
            $dtdds[] = sprintf($tplDtdd,
                "Structure mère :",
                $entity->getParente()->getLibelleLong()
            );
        }

        $html .= sprintf($this->getTemplateDl('structure structure-details'), implode(PHP_EOL, $dtdds)) . PHP_EOL;

        if (!$this->short) {
            $html .= $this->getView()->historique($entity, $this->horizontal);
        }

        return $html;
    }
}
