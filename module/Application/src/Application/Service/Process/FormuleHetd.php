<?php

namespace Application\Service\Process;

use Application\Service\AbstractService;
use Application\Entity\Db\Intervenant;

/**
 * Processus de gestion de la formule de Kerbeyrie
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class FormuleHetd extends AbstractService
{
    /**
     * Retourne les heures complémentaires calculées pour un intervenant à partir de ses services
     * 
     * @param Intervenant $intervenant
     * @return float
     */
    public function getHeuresComplementaires( Intervenant $intervenant )
    {
        $sql = 'SELECT heures FROM V_FORMULE_HEURES_COMP WHERE intervenant_id = :intervenant';
        $result = $this->getEntityManager()->getConnection()->executeQuery($sql, array('intervenant' => $intervenant->getId()))->fetchAll();
        if (isset($result[0])){
            return (float)$result[0]['HEURES'];
        }else{
            return 0;
        }
        return ;
    }

    /**
     *
     * @param \Application\Entity\Db\Intervenant $intervenant
     */
    public function calculServiceDu( Intervenant $intervenant )
    {
        $smsd = $this->getServiceLocator()->get('applicationModificationServiceDu');
        /* @var $smsd \Application\Service\ModificationServiceDu */
        
        $qb = $smsd->finderByContext();
        $smsd->finderByIntervenant( $intervenant, $qb );
        $modifications = $smsd->getTotal( $qb );
        
        $plafond = $intervenant->getStatut()->getServiceStatutaire();

        return $plafond + $modifications;
    }
}