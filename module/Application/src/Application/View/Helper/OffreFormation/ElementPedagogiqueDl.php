<?php

namespace Application\View\Helper\OffreFormation;

use Application\Entity\Db\ElementPedagogique;
use Application\View\Helper\AbstractDl;

/**
 * Description of ElementPedagogiqueDl
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ElementPedagogiqueDl extends AbstractDl
{
    /**
     * @var ElementPedagogique
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
         * Détails
         */

        $details = [];

        if (!$this->short) {
            $details[] = sprintf($tplDtdd,
                "Code {$this->entity->getSource()->getLibelle()} :",
                $this->entity->getSourceCode()
            );
        }

        $details[] = sprintf($tplDtdd,
            "Libellé :",
            $this->entity->getLibelle()
        );

        $details[] = sprintf($tplDtdd,
            "Structure :",
            $this->entity->getStructure()
        );

        if (($periode = $this->entity->getPeriode())) {
            $details[] = sprintf($tplDtdd,
                "Période d'enseignement :",
                $this->entity->getPeriode()
            );
        }

        if (($autresEtapes = $this->entity->getEtapes(false))) {
            $details[] = sprintf($tplDtdd,
                "Formation principale :",
                $this->entity->getEtape()
            );
            if (!$this->short) {
                $details[] = sprintf($tplDtdd,
                    "Autre(s) formation(s) :",
                    $this->getView()->htmlList($autresEtapes)
                );
            }
        }
        else {
            $details[] = sprintf($tplDtdd,
                "Formation :",
                $this->entity->getEtape()
            );
        }

        $details[] = sprintf($tplDtdd,
            "<span title=\"Formation ouverte à distance\">FOAD</span> :",
            (bool)$this->entity->getTauxFoad() ? "Oui" : "Non"
        );

        $details[] = sprintf($tplDtdd,
            'Régime(s) d\'inscription :',
            $this->entity->getRegimesInscription(true)
        );

        $html .= sprintf($this->getTemplateDl('element element-details'), implode(PHP_EOL, $details)) . PHP_EOL;

        /**
         * Historique
         */

        if (!$this->short) {
            $html .= $this->getView()->historiqueDl($this->entity, $this->horizontal);
        }

        return $html;
    }
}