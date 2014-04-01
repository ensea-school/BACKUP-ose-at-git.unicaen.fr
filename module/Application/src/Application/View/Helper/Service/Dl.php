<?php

namespace Application\View\Helper\Service;

use Application\View\Helper\AbstractDl;
use Application\Entity\Db\Service;

/**
 * Description of Dl
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Dl extends AbstractDl
{
    /**
     * @var Service
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
            $this->entity->getIntervenant()->getNomComplet()
        );

        $identite[] = sprintf($tplDtdd,
            "Structure de l'intervenant :",
            $this->entity->getStructureAff()->getLibelleCourt()
        );

        $identite[] = sprintf($tplDtdd,
            "Structure d'enseignement :",
            $this->entity->getStructureEns()->getLibelleCourt()
        );

        $identite[] = sprintf($tplDtdd,
            "Elément pédagogique :",
            $this->entity->getElementPedagogique()->getLibelle()
        );

        $identite[] = sprintf($tplDtdd,
            "Année :",
            $this->entity->getAnnee()->getLibelle()
        );

        $identite[] = sprintf($tplDtdd,
            "&Eacute;tablissement :",
            $this->entity->getEtablissement()->getLibelle()
        );

        $html .= sprintf($this->getTemplateDl('service service-identite'), implode(PHP_EOL, $identite)) . PHP_EOL;

        /**
         * Volumes horaires
         */

        $html .= '<h2>Volumes horaires</h2>';
        $html .= $this->getView()->volumeHoraireListe( $this->entity->getVolumeHoraire(), array('service' => $this->entity) );

        /**
         * Historique
         */

        $html .= $this->getView()->historiqueDl($this->entity, $this->horizontal);

        return $html;
    }
}