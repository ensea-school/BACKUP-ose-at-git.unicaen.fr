<?php

namespace Application\Service;

use Application\Entity\Db\CentreCout as CentreCoutEntity;
use UnicaenApp\Util;

/**
 * Description of CentreCout
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class CentreCout extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\CentreCout';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'cc';
    }
    
    /**
     * Formatte une liste d'entités CentreCout (centres de coûts et éventuels EOTP fils) 
     * en tableau attendu par l'aide de vue FormSelect.
     * 
     * NB: la liste en entrée doit être triées par code parent (éventuel) PUIS par code.
     * 
     * @param CentreCoutEntity[] $centresCouts
     */
    public function formatCentresCouts($centresCouts)
    {
        $result = [];

        foreach ($centresCouts as $cc) {
            $id         = $cc->getId();
            $code       = $cc->getSourceCode();
            
            $ccp        = $cc->getParent() ? : null;
            $codeParent = $ccp ? $ccp->getSourceCode() : null;

            if ($codeParent) {
                $result[$codeParent]['label']        = (string) $ccp;
                $result[$codeParent]['options'][$id] = (string) $cc;
            }
            else {
                $result[$code]['label']        = (string) $cc;
                $result[$code]['options'][$id] = (string) $cc;
            }
        }
        
        ksort($result);
        
        return $result;
    }
}