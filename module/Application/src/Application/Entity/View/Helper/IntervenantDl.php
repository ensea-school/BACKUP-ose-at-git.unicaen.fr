<?php

namespace Application\Entity\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Application\Entity\Db\Intervenant;
        
/**
 * Description of IntervenantDl
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class IntervenantDl extends AbstractHelper
{
    /**
     * @var Intervenant
     */
    protected $intervenant;
    
    /**
     * @var bool
     */
    protected $includeVisas = false;
    
    /**
     * @var bool
     */
    protected $horizontal = false;
    
    /**
     * 
     * @param Intervenant $intervenant
     * @param bool $horizontal
     * @return self
     */
    public function __invoke(Intervenant $intervenant = null, $horizontal = false)
    {
        $this->intervenant = $intervenant;
        $this->horizontal  = $horizontal;
        
        return $this;
    }
    
    /**
     * 
     * 
     * @return string Code HTML
     */
    public function __toString()
    {
        return $this->render();
    }
    
    /**
     * 
     * 
     * @return string Code HTML
     */
    public function render()
    {
        if (!$this->intervenant) {
            return '';
        }
        
        $intervenant   = $this->intervenant;
        $tplDtdd = $this->getTemplateDtDd();
        $html    = '';
        $dtdds   = array();
        
        $dtdds[] = sprintf($tplDtdd,
            "Nom :", 
            $intervenant->getNomComplet(true, true)
        );
        $dtdds[] = sprintf($tplDtdd,
            "Date de naissance :", 
            $intervenant->getDateNaissanceToString()
        );
        $dtdds[] = sprintf($tplDtdd,
            "Ville de naissance :", 
            $intervenant->getVilleNaissanceLibelle()
        );
        $dtdds[] = sprintf($tplDtdd,
            "Pays de naissance :", 
            $intervenant->getPaysNaissanceLibelle()
        );
        
        if ($intervenant instanceof \Application\Entity\Db\IntervenantPermanent) {
            $dtdds[] = sprintf($tplDtdd,
                "Corps :", 
                $intervenant->getCorps()
            );
        }
        elseif ($intervenant instanceof \Application\Entity\Db\IntervenantExterieur) {
            $dtdds[] = sprintf($tplDtdd,
                "Régime sécu :", 
                $intervenant->getRegimeSecu()
            );
        }
        
//        $commentaires = sprintf('<span title="%s">%s</span>', 
//                    htmlspecialchars($tmp = $intervenant->getCommentaires(), ENT_NOQUOTES), 
//                    $intervenant->getCommentaires() ? \UnicaenApp\Util::truncatedString($tmp) : "Aucun");
//        $dtdds[] = sprintf($tplDtdd,
//            "Commentaires",
//            $commentaires
//        );
        
        $html .= sprintf($this->getTemplateDl('intervenant-details'), implode(PHP_EOL, $dtdds)) . PHP_EOL;
 
        return $html;
    }
    
    /**
     *
     * @param string $class 
     * @return string
     */
    public function getTemplateDl($class = null)
    {
        $classes = array();
        $classes[] = $this->horizontal ? 'dl-horizontal' : null;
        $classes[] = $class;
        $classes = implode(' ', $classes);
        
        return '<dl class="intervenant ' . $classes . '">' . PHP_EOL . '%s' . PHP_EOL . '</dl>'. PHP_EOL;
    }
    
    /**
     *
     * @return string
     */
    public function getTemplateDtDd()
    {
        return '<dt>' . PHP_EOL . '%s' . PHP_EOL . '</dt>'. PHP_EOL . '<dd>' . PHP_EOL . '%s' . PHP_EOL . '</dd>'. PHP_EOL;
    }
}