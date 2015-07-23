<?php

namespace Application\View\Helper;

use UnicaenApp\Entity\HistoriqueAwareInterface;
use Application\Entity\Db\Source;

/**
 * Description of HistoriqueDl
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class HistoriqueDl extends AbstractDl
{
    /**
     * @var HistoriqueAwareInterface
     */
    protected $entity;

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

        $tplDtdd = $this->getTemplateDtDd();
        $html    = '';
        $dtdds   = [];

        $libelleCrea = "Creation";

        if (method_exists($this->entity, 'getSource')
                && ($source = $this->entity->getSource())
                && Source::CODE_SOURCE_OSE !== $source->getCode()) {
            $libelleCrea = "Importation d'$source";
        }

        $dtdds[] = sprintf($tplDtdd,
            "$libelleCrea :",
            sprintf("le %s par %s",
                    $this->entity->getHistoCreation()->format(\Common\Constants::DATETIME_FORMAT),
                    $this->entity->getHistoCreateur()->getDisplayName())
        );

        if ($this->entity->getHistoModification() != $this->entity->getHistoCreation()) {
            $dtdds[] = sprintf($tplDtdd,
                "Modification :",
                sprintf("le %s par %s",
                        $this->entity->getHistoModification()->format(\Common\Constants::DATETIME_FORMAT),
                        $this->entity->getHistoModificateur()->getDisplayName())
            );
        }

        if ($this->entity->getHistoDestruction()) {
            $dtdds[] = sprintf($tplDtdd,
                "Suppression :",
                sprintf("le %s par %s",
                        $this->entity->getHistoDestruction()->format(\Common\Constants::DATETIME_FORMAT),
                        $this->entity->getHistoDestructeur()->getDisplayName())
            );
        }

        $html .= sprintf($this->getTemplateDl('histo histo-details'), implode(PHP_EOL, $dtdds)) . PHP_EOL;

        return $html;
    }
}