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
            $id       = $cc->getId();
            $ccp      = $cc->getParent() ? : null;
            $idParent = $ccp ? $ccp->getId() : null;

            if ($idParent) {
                $result[$idParent]['label']        = (string) $ccp;
                $result[$idParent]['options'][$id] = (string) $cc;
            }
            else {
                $result[$id]['label']        = (string) $cc;
                $result[$id]['options'][$id] = (string) $cc;
            }
        }
        
        // parcours pour supprimer le niveau 2 lorsque le centre de coût n'a pas d'EOTP fils
        foreach ($result as $id => $data) {
            if (isset($data['options']) && count($data['options']) === 1) {
                $result[$id] = $data['label'];
            }
        }
        
        ksort($result);
        
        return $result;
    }
}