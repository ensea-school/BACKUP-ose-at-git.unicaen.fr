<?php

namespace Application\View\Helper\VolumeHoraire;

use Application\View\Helper\AbstractDl;
use Application\Entity\Db\VolumeHoraire;

/**
 * Description of Dl
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Dl extends AbstractDl
{
    /**
     * @var VolumeHoraire
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
            "Période :",
            $this->entity->getPeriode()->getLibelle()
        );

        $identite[] = sprintf($tplDtdd,
            "Type d'intervention :",
            $this->entity->getTypeIntervention()->getLibelle()
        );

        $identite[] = sprintf($tplDtdd,
            "Heures :",
            $this->entity->getHeures()
        );

        if ($this->entity->getMotifNonPaiement()) {
            $identite[] = sprintf($tplDtdd,
                "Motif de non paiement :",
                $this->entity->getMotifNonPaiement()->getLibelleCourt()
            );
        }
        
        $html .= sprintf($this->getTemplateDl('volume-horaire volume-horaire-identite'), implode(PHP_EOL, $identite)) . PHP_EOL;

        /**
         * Historique
         */

        $html .= $this->getView()->historiqueDl($this->entity, $this->horizontal);

        return $html;
    }

    public function __toString()
    {        
        return $this->render();
    }
}