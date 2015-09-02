<?php

namespace Application\View\Helper\ServiceReferentiel;

use Application\View\Helper\AbstractDl;
use Application\Entity\Db\ServiceReferentiel;

/**
 * Description of Dl
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Dl extends AbstractDl
{
    /**
     * @var ServiceReferentiel
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

        $tplDtdd  = $this->getTemplateDtDd();
        $html     = '';

        /**
         * Identité
         */

        $identite = [];

        if (!$this->short) {
            $identite[] = sprintf($tplDtdd,
                "Numéro :",
                $this->entity->getId()
            );
        }

        $identite[] = sprintf($tplDtdd,
            "Intervenant :",
            $this->entity->getIntervenant()
        );

        if ($this->entity->getStructure()) {
            $identite[] = sprintf($tplDtdd,
                "Structure :",
                $this->entity->getStructure()
            );
        }

        $identite[] = sprintf($tplDtdd,
            "Fonction :",
            $this->entity->getFonction()
        );

        $html .= sprintf($this->getTemplateDl('service-referentiel service-referentiel-identite'), implode(PHP_EOL, $identite)) . PHP_EOL;

        /**
         * Historique
         */

        if (!$this->short) {
            $html .= $this->getView()->historique($this->entity, $this->horizontal);
        }

        return $html;
    }
}