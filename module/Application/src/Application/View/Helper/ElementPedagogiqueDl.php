<?php

namespace Application\View\Helper;

use Application\Entity\Db\ElementPedagogique;

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
        
        $details = array();
        
        $details[] = sprintf($tplDtdd,
            "Code {$this->entity->getSource()->getLibelle()} :", 
            $this->entity->getSourceCode()
        );
            
        $details[] = sprintf($tplDtdd,
            "Libellé :", 
            $this->entity->getLibelle()
        );
        
        $details[] = sprintf($tplDtdd,
            "Structure :", 
            $this->entity->getStructure()
        );
        
        if (($autresEtapes = $this->entity->getEtapes(false))) {
            $details[] = sprintf($tplDtdd,
                "Étape princiaple :", 
                $this->entity->getEtape()
            );
            $details[] = sprintf($tplDtdd,
                "Autre(s) étape(s) :", 
                $this->getView()->htmlList($autresEtapes)
            );
        }
        else {
            $details[] = sprintf($tplDtdd,
                "Étape :", 
                $this->entity->getEtape()
            );
        }
    
        if (($periode = $this->entity->getPeriode())) {
            $details[] = sprintf($tplDtdd,
                "Période d'enseignement :", 
                $this->entity->getPeriode()
            );
        }
        
        $html .= sprintf($this->getTemplateDl('element element-details'), implode(PHP_EOL, $details)) . PHP_EOL;
        
        /**
         * Historique
         */
        
        $html .= $this->getView()->historiqueDl($this->entity, $this->horizontal);
        
        return $html;
    }
}