<?php

namespace Application\View\Helper;

/**
 * Description of IntervenantDl
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class IntervenantDl extends AbstractDl
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
        
        $entity   = $this->entity; /* @var $entity \Application\Entity\Db\IntervenantInterface */
        $tplDtdd = $this->getTemplateDtDd();
        $html    = '';
        $dtdds   = array();
        
        $dtdds[] = sprintf($tplDtdd,
            "Nom prénom :", 
            $entity
        );
        
        $dtdds[] = sprintf($tplDtdd,
            "N° {$entity->getSourceToString()} :", 
            $entity->getSourceCode()
        );
            
        if ($entity instanceof \Application\Entity\Db\Intervenant) {
            $dtdds[] = sprintf($tplDtdd,
                "N° INSEE :", 
                $entity->getNumeroInsee()
            );
        }
        
        $dtdds[] = sprintf($tplDtdd,
            "Email :", 
            $entity->getEmail()
        );
        
        $dtdds[] = sprintf($tplDtdd,
            "Date de naissance :", 
            $entity->getDateNaissanceToString()
        );
            
        if ($entity instanceof \Application\Entity\Db\Intervenant) {
            $dtdds[] = sprintf($tplDtdd,
                "Ville de naissance :", 
                $entity->getVilleNaissanceLibelle()
            );
            $dtdds[] = sprintf($tplDtdd,
                "Pays de naissance :", 
                $entity->getPaysNaissanceLibelle()
            );
            $dtdds[] = sprintf($tplDtdd,
                "Téléphone mobile :", 
                $entity->getTelMobile()
            );
            $dtdds[] = sprintf($tplDtdd,
                "Téléphone pro :", 
                $entity->getTelPro()
            );
        }
        
        if ($entity instanceof \Application\Entity\Db\IntervenantPermanent) {
            $dtdds[] = sprintf($tplDtdd,
                "Corps :", 
                $entity->getCorps()
            );
        }
        elseif ($entity instanceof \Application\Entity\Db\IntervenantExterieur) {
            $dtdds[] = sprintf($tplDtdd,
                "Régime sécu :", 
                $entity->getRegimeSecu()
            );
        }
        
        if ($entity instanceof \Application\Entity\Db\Intervenant) {
            $dtdds[] = sprintf($tplDtdd,
                "Prime d'excellence scientifique :", 
                $entity->getPrimeExcellenceScientifique() ? 'Oui' : 'Non'
            );
            $dtdds[] = sprintf($tplDtdd,
                "Section CNU :", 
                $entity->getSectionCnu() ? implode(' ; ', $entity->getSectionCnu()) : "Aucune"
            );
        }
        
//        $commentaires = sprintf('<span title="%s">%s</span>', 
//                    htmlspecialchars($tmp = $entity->getCommentaires(), ENT_NOQUOTES), 
//                    $entity->getCommentaires() ? \UnicaenApp\Util::truncatedString($tmp) : "Aucun");
//        $dtdds[] = sprintf($tplDtdd,
//            "Commentaires",
//            $commentaires
//        );
        
        $html .= sprintf($this->getTemplateDl('intervenant intervenant-details'), implode(PHP_EOL, $dtdds)) . PHP_EOL;
 
        return $html;
    }
}