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
    public function getServiceDu( Intervenant $intervenant )
    {
        $sql = 'SELECT heures FROM V_FORMULE_SERVICE_DU WHERE intervenant_id = :intervenant';
        $result = $this->getEntityManager()->getConnection()->executeQuery($sql, array('intervenant' => $intervenant->getId()))->fetchAll();
        if (isset($result[0])){
            return (float)$result[0]['HEURES'];
        }else{
            return 0;
        }
    }

    public function getModifServiceDu( Intervenant $intervenant )
    {
        $sql = 'SELECT heures FROM V_FORMULE_MODIF_SERVICE_DU WHERE intervenant_id = :intervenant';
        $result = $this->getEntityManager()->getConnection()->executeQuery($sql, array('intervenant' => $intervenant->getId()))->fetchAll();
        if (isset($result[0])){
            return (float)$result[0]['HEURES'];
        }else{
            return 0;
        }
    }

    public function getServiceReferentiel( Intervenant $intervenant )
    {
        $sql = 'SELECT heures FROM V_FORMULE_SERVICE_REFERENTIEL WHERE intervenant_id = :intervenant';
        $result = $this->getEntityManager()->getConnection()->executeQuery($sql, array('intervenant' => $intervenant->getId()))->fetchAll();
        if (isset($result[0])){
            return (float)$result[0]['HEURES'];
        }else{
            return 0;
        }
    }

    public function getServiceRestant( Intervenant $intervenant )
    {
        $sql = 'SELECT heures FROM V_FORMULE_SERVICE_RESTANT WHERE intervenant_id = :intervenant';
        $result = $this->getEntityManager()->getConnection()->executeQuery($sql, array('intervenant' => $intervenant->getId()))->fetchAll();
        if (isset($result[0])){
            return (float)$result[0]['HEURES'];
        }else{
            return 0;
        }
    }

    public function getService( Intervenant $intervenant )
    {
        $sql = '
        SELECT
            fs.etablissement_id, fs.service_id, fs.type_service, fs.element_pedagogique_id, fs.heures, NVL(fpe.ponderation_service_du,1) ponderation_service_du, NVL(fpe.ponderation_service_compl,1) ponderation_service_compl
        FROM
            V_FORMULE_SERVICE fs
            LEFT JOIN V_FORMULE_PONDERATION_ELEMENT fpe ON fpe.element_pedagogique_id = fs.element_pedagogique_id
        WHERE
            intervenant_id = :intervenant';
        $result = $this->getEntityManager()->getConnection()->executeQuery($sql, array('intervenant' => $intervenant->getId()))->fetchAll();
        $data = array();
        $elements = array();
        $etablissements = array();
        foreach( $result as $r ){
            if ((int)$r['ETABLISSEMENT_ID']) $etablissements[(int)$r['ETABLISSEMENT_ID']] = true;
            if ((int)$r['ELEMENT_PEDAGOGIQUE_ID']) $elements[(int)$r['ELEMENT_PEDAGOGIQUE_ID']] = true;

            $data[(int)$r['SERVICE_ID']] = [
                'type'                      => $r['TYPE_SERVICE'],
                'element_pedagogique'       => (int)$r['ELEMENT_PEDAGOGIQUE_ID'],
                'etablissement'             => (int)$r['ETABLISSEMENT_ID'],
                'heures'                    => (float)$r['HEURES'],
                'ponderation_service_du'    => (float)$r['PONDERATION_SERVICE_DU'],
                'ponderation_service_compl' => (float)$r['PONDERATION_SERVICE_COMPL'],
                'volumes_horaires'          => []
            ];
        }
        $elements = $this->getServiceLocator()->get('applicationElementPedagogique')->get(array_keys($elements));
        $etablissements = $this->getServiceLocator()->get('applicationEtablissement')->get(array_keys($etablissements));
        foreach( $data as $did => $d ){
            if ($data[$did]['etablissement']) $data[$did]['etablissement'] = $etablissements[$d['etablissement']];
            if ($data[$did]['element_pedagogique']) $data[$did]['element_pedagogique'] = $elements[$d['element_pedagogique']];
        }

        $typesInterventions = $this->getServiceLocator()->get('applicationTypeIntervention')->getTypesIntervention();
        $sql = '
        SELECT
            *
        FROM
            V_FORMULE_VOLUME_HORAIRE
        WHERE
            intervenant_id = :intervenant';
        $result = $this->getEntityManager()->getConnection()->executeQuery($sql, array('intervenant' => $intervenant->getId()))->fetchAll();
        foreach( $result as $r ){
            $data[(int)$r['SERVICE_ID']]['volumes_horaires'][(int)$r['TYPE_INTERVENTION_ID']] = [
                'type_intervention' => $typesInterventions[(int)$r['TYPE_INTERVENTION_ID']],
                'heures' => (float)$r['HEURES'],
                'tx_serv' => (float)$r['TX_SERV'],
                'tx_comp' => (float)$r['TX_COMP'],
                'hetd_serv' => (float)$r['HEURES_SERV'],
                'hetd_comp' => (float)$r['HEURES_COMP'],
            ];
        }
        return $data;
    }

    public function getVentilation( Intervenant $intervenant )
    {
        $sql = 'SELECT * FROM V_FORMULE_VENTILATION WHERE intervenant_id = :intervenant';
        $result = $this->getEntityManager()->getConnection()->executeQuery($sql, array('intervenant' => $intervenant->getId()))->fetchAll();
        
        $typesInterventions = $this->getServiceLocator()->get('applicationTypeIntervention')->getTypesIntervention();
        $data = [ 'heures' => 0 ];

        foreach( $result as $r ){
            $data[$r['TYPE_INTERVENTION_ID']] = [
                'type_intervention' => $typesInterventions[$r['TYPE_INTERVENTION_ID']],
                'heures' => $r['HEURES'],
                'pourc_serv' => $r['POURC_SERV'],
                'pourc_comp' => $r['POURC_COMP'],
            ];
            $data['heures'] += (float)$r['HEURES'];
        }
        return $data;
    }

    public function getReevalResteAPayer( Intervenant $intervenant )
    {
        $sql = 'SELECT * FROM V_FORMULE_REEVAL_RESTEAPAYER WHERE intervenant_id = :intervenant';
        $result = $this->getEntityManager()->getConnection()->executeQuery($sql, array('intervenant' => $intervenant->getId()))->fetchAll();
        if (isset($result[0])){
            return [
                'sum_pourc_serv' => (float)$result[0]['SUM_POURC_SERV'],
                'sum_pourc_comp' => (float)$result[0]['SUM_POURC_COMP'],
                'reeval_serv'    => (float)$result[0]['REEVAL_SERV'],
                'reste_a_payer'  => (float)$result[0]['RESTE_A_PAYER'],
            ];
        }else{
            return [
                'sum_pourc_serv' => 0,
                'sum_pourc_comp' => 0,
                'reeval_serv'    => 0,
                'reste_a_payer'  => 0,
            ];
        }
    }

    /**
     * Retourne les heures complémentaires calculées pour un intervenant à partir de ses services
     * 
     * @param Intervenant|Intervenant[]|integer|integer[]|null $intervenant
     * @return float[]|float
     */
    public function getHeuresComplementaires( $intervenant )
    {
        $sql = 'SELECT * FROM V_FORMULE_HEURES_COMP WHERE '.$this->makeWhere($intervenant);
        $result = $this->getEntityManager()->getConnection()->executeQuery($sql, array())->fetchAll();

        if (is_array($intervenant)){
            $return = array();
            foreach( $result as $r ){
                $return[(int)$r['INTERVENANT_ID']] = (float)$r['HEURES'];
            }
            return $return;
        }else{
            if (isset($result[0])){
                return (float)$result[0]['HEURES'];
            }else{
                return 0;
            }
        }
    }

    /**
     * Retourne les heures éq. TD calculées pour un intervenant à partir de ses services
     *
     * @param Intervenant|Intervenant[]|integer|integer[]|null $intervenant
     * @return float[]|float
     */
    public function getHetd( $intervenant )
    {
        $sql = 'SELECT * FROM V_FORMULE_HEURES_HETD WHERE '.$this->makeWhere($intervenant);
        $result = $this->getEntityManager()->getConnection()->executeQuery($sql, array())->fetchAll();

        if (is_array($intervenant)){
            $return = array();
            foreach( $result as $r ){
                $return[(int)$r['INTERVENANT_ID']] = (float)$r['HEURES'];
            }
            return $return;
        }else{
            if (isset($result[0])){
                return (float)$result[0]['HEURES'];
            }else{
                return 0;
            }
        }
    }

    /**
     * Retourne le montant en euros à payer des heures complémentaires
     *
     * @param Intervenant|Intervenant[]|integer|integer[]|null $intervenant
     * @return float[]|float
     */
    public function getAPayer( $intervenant )
    {
        $sql = 'SELECT * FROM V_FORMULE_A_PAYER WHERE '.$this->makeWhere($intervenant);
        $result = $this->getEntityManager()->getConnection()->executeQuery($sql, array())->fetchAll();

        if (is_array($intervenant)){
            $return = array();
            foreach( $result as $r ){
                $return[(int)$r['INTERVENANT_ID']] = (float)$r['MONTANT'];
            }
            return $return;
        }else{
            if (isset($result[0])){
                return (float)$result[0]['MONTANT'];
            }else{
                return 0;
            }
        }
    }

    /**
     * Retourne lac partie WHERE d'une requête SQL
     *
     * @param Intervenant|Intervenant[]|integer|integer[]|null $intervenant
     * @return string
     */
    protected function makeWhere($intervenant)
    {
        if (is_array($intervenant)){
            foreach( $intervenant as $index => $i ){
                if ($i instanceof Intervenant){
                    $intervenant[$index] = $i->getId();
                }else{
                    $intervenant[$index] = (int)$i;
                }
            }
            $where = 'intervenant_id IN ('.implode(',',$intervenant).')';
        }elseif(empty($intervenant)){
            $where = '1 = 1';
        }elseif($intervenant instanceof Intervenant){
            $where = 'intervenant_id = '.$intervenant->getId();
        }else{
            $where = 'intervenant_id = '.(int)$intervenant;
        }
        return $where;
    }
}