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

        $identite = array();

        $identite[] = sprintf($tplDtdd,
            "Numéro :",
            $this->entity->getId()
        );

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

        $identite[] = sprintf($tplDtdd,
            "Année :",
            $this->entity->getAnnee()
        );

        $identite[] = sprintf($tplDtdd,
            "Nombre d'heures :",
            \UnicaenApp\Util::formattedFloat($this->entity->getHeures(), \NumberFormatter::DECIMAL, -1)
        );

        $html .= sprintf($this->getTemplateDl('service-referentiel service-referentiel-identite'), implode(PHP_EOL, $identite)) . PHP_EOL;

        /**
         * Historique
         */

        $html .= $this->getView()->historiqueDl($this->entity, $this->horizontal);

        return $html;
    }
}