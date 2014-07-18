<?php

namespace Application\View\Helper\OffreFormation;

use Application\Entity\Db\Etape;
use Application\View\Helper\AbstractDl;

/**
 * Description of EtapeDl
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class EtapeDl extends AbstractDl
{
    /**
     * @var Etape
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
        
        $details[] = sprintf($tplDtdd,
            "Type de formation :", 
            $this->entity->getTypeFormation()
        );
        
        if (($niveau = $this->entity->getNiveau())) {
            $details[] = sprintf($tplDtdd,
                "Niveau :", 
                $niveau
            );
        }
        
        $details[] = sprintf($tplDtdd,
            "Spécif. échanges :", 
            $this->entity->getSpecifiqueEchanges() ? 'Oui' : 'Non'
        );
        
        $html .= sprintf($this->getTemplateDl('etape etape-details'), implode(PHP_EOL, $details)) . PHP_EOL;
        
        /**
         * Historique
         */
        
        $html .= $this->getView()->historiqueDl($this->entity, $this->horizontal);
        
        return $html;
    }
}