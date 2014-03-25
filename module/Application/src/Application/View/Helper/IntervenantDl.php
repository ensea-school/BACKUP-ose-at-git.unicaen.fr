<?php

namespace Application\View\Helper;

use Application\Entity\Db\Intervenant;

/**
 * Description of IntervenantDl
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class IntervenantDl extends AbstractDl
{
    /**
     * @var Intervenant
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
            "NOM prénom :", 
            $this->entity
        );
        
        $identite[] = sprintf($tplDtdd,
            "Civilité :", 
            $this->entity->getCiviliteToString()
        );
        
        $identite[] = sprintf($tplDtdd,
            "Date de naissance :", 
            $this->entity->getDateNaissanceToString()
        );
        
        $identite[] = sprintf($tplDtdd,
            "Ville de naissance :", 
            $this->entity->getVilleNaissanceLibelle() ?: "(Inconnue)"
        );
        
        $identite[] = sprintf($tplDtdd,
            "Pays de naissance :", 
            $this->entity->getPaysNaissanceLibelle()
        );
            
        $identite[] = sprintf($tplDtdd,
            "N° INSEE :", 
            $this->entity->getNumeroInsee()
        );
        
        if ($this->entity instanceof \Application\Entity\Db\IntervenantExterieur) {
            $identite[] = sprintf($tplDtdd,
                "Situation familiale :", 
                $this->entity->getSituationFamiliale() ?: "(Inconnue)"
            );
        }
        
        $html .= sprintf($this->getTemplateDl('intervenant intervenant-identite'), implode(PHP_EOL, $identite)) . PHP_EOL;
        
        /**
         * Coordonnées 
         */
        
        $coord    = array();
        
        $coord[] = sprintf($tplDtdd,
            "Email :", 
            $this->entity->getEmail() ?: "(Inconnu)"
        );
        
        $coord[] = sprintf($tplDtdd,
            "Téléphone mobile :", 
            $this->entity->getTelMobile() ?: "(Inconnu)"
        );
        
        $coord[] = sprintf($tplDtdd,
            "Téléphone pro :", 
            $this->entity->getTelPro() ?: "(Inconnu)"
        );
        
        $html .= sprintf($this->getTemplateDl('intervenant intervenant-coord'), implode(PHP_EOL, $coord)) . PHP_EOL;
        
        /**
         * Adresses 
         */
        
        foreach ($this->entity->getAdresse() as $adresse) {
            $html .= $this->getView()->adresseDl($adresse, true, true) . PHP_EOL;
        }
        
        /**
         * Métier
         */
        
        $metier   = array();
        
        $metier[] = sprintf($tplDtdd,
            "Type d'intervenant :", 
            $this->entity->getType()
        );
        
        $metier[] = sprintf($tplDtdd,
            "N° {$this->entity->getSourceToString()} :", 
            $this->entity->getSourceCode()
        );
            
        $metier[] = sprintf($tplDtdd,
            "Affectation principale :", 
            $this->entity->getStructure() ?: "(Inconnue)"
        );
            
        $metier[] = sprintf($tplDtdd,
            "Affectation recherche :", 
            count($aff = $this->entity->getAffectation()) ? implode(" ; ", $aff->toArray()) : "(Inconnue)"
        );

        if ($this->entity instanceof \Application\Entity\Db\IntervenantPermanent) {
           $metier[] = sprintf($tplDtdd,
                "Section CNU :",
                $this->entity->getSectionCnu() ? implode(' ; ', $this->entity->getSectionCnu()) : "(Inconnue)"
            );
        }

        if ($this->entity instanceof \Application\Entity\Db\IntervenantPermanent) {
            $metier[] = sprintf($tplDtdd,
                "Corps :", 
                $this->entity->getCorps()
            );
        }
        elseif ($this->entity instanceof \Application\Entity\Db\IntervenantExterieur) {
            $metier[] = sprintf($tplDtdd,
                "Régime sécu :", 
                $this->entity->getRegimeSecu() ?: "(Inconnu)"
            );
        }

        $metier[] = sprintf($tplDtdd,
            "Prime d'excell. scientif. :", 
            null !== ($pes = $this->entity->getPrimeExcellenceScient()) ? ($pes ? 'Oui' : 'Non') : "(Inconnue)"
        );
        
        $html .= sprintf($this->getTemplateDl('intervenant intervenant-metier'), implode(PHP_EOL, $metier)) . PHP_EOL;
 
        /**
         * Fonctions référentiel
         */
        
        $fonctions = array();
        
        if ($this->entity instanceof \Application\Entity\Db\IntervenantPermanent) {
            $serviceRef = "Aucun";
            if (($services = $this->entity->getServiceReferentielToStrings())) {
                $serviceRef = $this->getView()->htmlList($services);
            }
            $fonctions[] = sprintf($tplDtdd,
                "Service référentiel :", 
                $serviceRef
            );
        }
        
        $html .= sprintf($this->getTemplateDl('intervenant intervenant-fonction'), implode(PHP_EOL, $fonctions)) . PHP_EOL;
        
        /**
         * Historique
         */
        
        $html .= $this->getView()->historiqueDl($this->entity, $this->horizontal);
        
        return $html;
    }
}