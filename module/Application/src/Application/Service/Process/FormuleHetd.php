<?php

namespace Application\Service\Process;

use Application\Service\AbstractService;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\TypeVolumeHoraire;
use Application\Entity\Db\Annee;
use Application\Interfaces\IntervenantAwareInterface;
use Application\Traits\IntervenantAwareTrait;
use Application\Interfaces\TypeVolumeHoraireAwareInterface;
use Application\Traits\TypeVolumeHoraireAwareTrait;
use Application\Interfaces\AnneeAwareInterface;
use Application\Traits\AnneeAwareTrait;

/**
 * Processus de gestion de la formule de Kerbeyrie
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class FormuleHetd extends AbstractService implements IntervenantAwareInterface, TypeVolumeHoraireAwareInterface, AnneeAwareInterface
{
    use IntervenantAwareTrait;
    use TypeVolumeHoraireAwareTrait;
    use AnneeAwareTrait;

    /**
     * Paramètres de sortie de la formule de calcul
     *
     * @var array
     */
    protected $params;

    /**
     * Spécifie l'intervenant concerné.
     *
     * @param Intervenant $intervenant inervenant concerné
     * @return self
     */
    public function setIntervenant(Intervenant $intervenant)
    {
        $this->intervenant = $intervenant;
        $this->params = [];
        return $this;
    }

    /**
     * Spécifie l'annee concerné.
     *
     * @param Annee $annee Annee concernée
     */
    public function setAnnee(Annee $annee)
    {
        $this->annee = $annee;
        $this->params = [];
        return $this;
    }

    /**
     * Spécifie le type de volume horaire concerné.
     *
     * @param TypeVolumeHoraire $typeVolumeHoraire Type de rôle concerné
     */
    public function setTypeVolumeHoraire(TypeVolumeHoraire $typeVolumeHoraire = null)
    {
        $this->typeVolumeHoraire = $typeVolumeHoraire;
        $this->params = [];
        return $this;
    }


    public function getParams()
    {
        if (empty($this->params)) $this->calculer();
        return $this->params;
    }

    protected function calculer()
    {
        if (! $this->getIntervenant()){
            throw new \Common\Exception\LogicException('Impossible de calculer la formule : intervenant non spécifié');
        }
        if (! $this->getTypeVolumeHoraire()){
            $this->setTypeVolumeHoraire( $this->getServiceTypeVolumeHoraire()->getPrevu() );
        }
        if (! $this->getAnnee()){
            $this->setAnnee( $this->getContextProvider()->getGlobalContext()->getAnnee() );
        }

        $sql = 'SELECT MAX(valeur) FROM TAUX_HORAIRE_HETD thh '
              .'WHERE SYSDATE BETWEEN thh.validite_debut AND NVL(thh.validite_fin,SYSDATE) AND thh.histo_destruction IS NULL';
        $tauxHoraireHetd = (float)$this->getEntityManager()->getConnection()->executeQuery($sql)->fetchColumn(0);


        $params = [
            'service-du'                => 0,
            'service-du-modifications'  => 0,
            'service-du-modifie'        => 0,
            'service-referentiel'       => 0,
            'service-du-restant'        => 0,
            'service'                   => [
                'type-intervention'         => [],
                'element-pedagogique'       => []
            ],
            'total-heures-reelles'      => 0,
            'total-heures-hetd'         => 0,
            'total-heures-hetd-service' => 0,
            'total-heures-hetd-comp'    => 0,
            'taux-ponderation-service'  => 0,
            'taux-ponderation-comp'     => 0,
            'reevaluation-service'      => 0,
            'reste-a-payer'             => 0,
            'heures-complementaires'    => 0,
            'taux-horaire-hetd'         => $tauxHoraireHetd,
            'a-payer'                   => 0,
        ];

        $params['service-du'] = $this->getIntervenant()->getStatut()->getServiceStatutaire();
        if ($this->getIntervenant()->estPermanent()){
            $msds = $this->getIntervenant()->getModificationServiceDu($this->getAnnee());
            foreach( $msds as $msd ){
                $params['service-du-modifications'] += $msd->getHeures();
            }

            $srs = $this->getIntervenant()->getServiceReferentiel($this->getAnnee());
            foreach( $srs as $sr ){
                $params['service-referentiel'] += $sr->getHeures();
            }
        }

        $vhl = [];
        foreach( $this->getIntervenant()->getService($this->getAnnee()) as $service ){/* @var $service \Application\Entity\Db\Service */
            foreach( $service->getVolumeHoraire() as $vh ){ /* @var $vh \Application\Entity\Db\VolumeHoraire */
                if ($vh->getMotifNonPaiement() === null && $vh->getTypeVolumeHoraire() === $this->getTypeVolumeHoraire()){
                    $vhl[] = $vh;
                }
            }
        }

        foreach( $vhl as $vh ){
            $element = $vh->getService()->getElementPedagogique();
            $tauxHetdServiceDu   = $vh->getTypeIntervention()->getTauxHetdService();
            $tauxHetdServiceComp = $vh->getTypeIntervention()->getTauxHetdComplementaire();
            if ($element){
                foreach( $element->getElementModulateur() as $elementModulateur ){ /* @var $elementModulateur \Application\Entity\Db\ElementModulateur */
                    if ($elementModulateur->getAnnee() == $this->getAnnee()){
                        $tauxHetdServiceDu *= $elementModulateur->getModulateur()->getPonderationServiceDu();
                        $tauxHetdServiceComp *= $elementModulateur->getModulateur()->getPonderationServiceCompl();
                    }
                }
            }
            $params['total-heures-reelles']      += $vh->getHeures();
            $params['total-heures-hetd-service'] += $vh->getHeures() * $tauxHetdServiceDu;
            $params['total-heures-hetd-comp']    += $vh->getHeures() * $tauxHetdServiceComp;
            if (! isset($params['service']['type-intervention'][$vh->getTypeIntervention()->getCode()])){
                $params['service']['type-intervention'][$vh->getTypeIntervention()->getCode()] = [
                    'heures'         => 0,
                    'heures-service' => 0,
                    'heures-comp'    => 0,
                    'pourc-service'  => 0,
                    'pourc-comp'     => 1,
                ];
            }
            $elId = isset($element) ? $element->getId() : 0;
            if (! isset($params['service']['element-pedagogique'][$elId])){
                $params['service']['element-pedagogique'][$elId] = [
                    'heures'            => 0,
                    'type-intervention' => [],
                ];
            }
            if (! isset($params['service']['element-pedagogique'][$elId]['type-intervention'][$vh->getTypeIntervention()->getCode()])){
                $params['service']['element-pedagogique'][$elId]['type-intervention'][$vh->getTypeIntervention()->getCode()] = [
                    'heures'         => 0,
                    'heures-service' => 0,
                    'heures-comp'    => 0,
                ];
            }
            $params['service']['element-pedagogique'][$elId]['heures'] += $vh->getHeures();
            $params['service']['type-intervention'][$vh->getTypeIntervention()->getCode()]['heures']            += $vh->getHeures();
            $params['service']['type-intervention'][$vh->getTypeIntervention()->getCode()]['heures-service']    += $vh->getHeures() * $tauxHetdServiceDu;
            $params['service']['type-intervention'][$vh->getTypeIntervention()->getCode()]['heures-comp']       += $vh->getHeures() * $tauxHetdServiceComp;
            $params['service']['element-pedagogique'][$elId]['type-intervention'][$vh->getTypeIntervention()->getCode()]['heures']          += $vh->getHeures();
            $params['service']['element-pedagogique'][$elId]['type-intervention'][$vh->getTypeIntervention()->getCode()]['heures-service']  += $vh->getHeures() * $tauxHetdServiceDu;
            $params['service']['element-pedagogique'][$elId]['type-intervention'][$vh->getTypeIntervention()->getCode()]['heures-comp']     += $vh->getHeures() * $tauxHetdServiceComp;
        }

        /* Ventilation */
        foreach( $params['service']['type-intervention'] as $tiCode => $tiParams ){
            $total = $params['total-heures-reelles'];

            if ($total > 0){
                $params['service']['type-intervention'][$tiCode]['pourc-service'] = $tiParams['heures-service'] / $total;
                $params['service']['type-intervention'][$tiCode]['pourc-comp'] = $tiParams['heures-comp'] / $total;

                $params['taux-ponderation-service'] += $tiParams['heures-service'] / $total;
                $params['taux-ponderation-comp'] += $tiParams['heures-comp'] / $total;
            }
        }

        if (empty($params['service']['type-intervention'])){ // 1 par défaut, c'est-à-dire s'il n'y a aucun service!!
            $params['taux-ponderation-service'] = 1;
            $params['taux-ponderation-comp']    = 1;
        }

        $params['service-du-modifie']       = $params['service-du'] - $params['service-du-modifications'];
        $params['service-du-restant']       = $params['service-du-modifie'] - $params['service-referentiel'];
        $params['reevaluation-service']     = $params['service-du-modifie'] / $params['taux-ponderation-service'];
        $params['reste-a-payer']            = $params['total-heures-reelles'] - $params['reevaluation-service'];
        if ($params['reste-a-payer'] < 0 ){
            $params['heures-complementaires'] = $params['taux-ponderation-service'] * $params['total-heures-reelles'] - $params['service-du-restant'];
        }else{
            $params['heures-complementaires'] = $params['reste-a-payer'] * $params['taux-ponderation-comp'] + $params['service-referentiel'];
        }
        
        if($params['heures-complementaires'] >= 0){
            $params['total-heures-hetd']      = $params['service-du-modifie'] + $params['heures-complementaires'];
            $params['a-payer']                = $params['heures-complementaires'] * $params['taux-horaire-hetd'];
        }else{
            $params['total-heures-hetd']      = $params['service-du-modifie'];
        }
        $this->params = $params;
        return $this;
    }




    /**
     * @deprecated
     * @param Intervenant $intervenant
     * @return int
     */
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

    /**
     * @deprecated
     * @param Intervenant $intervenant
     * @return int
     */
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

    /**
     * @deprecated
     * @param Intervenant $intervenant
     * @return int
     */
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

    /**
     * @deprecated
     * @param Intervenant $intervenant
     * @return int
     */
    public function getServiceTotal( Intervenant $intervenant )
    {
        $sql = 'SELECT heures FROM V_FORMULE_VOLUME_HORAIRE_TOTAL WHERE intervenant_id = :intervenant';
        $result = $this->getEntityManager()->getConnection()->executeQuery($sql, array('intervenant' => $intervenant->getId()))->fetchAll();
        if (isset($result[0])){
            return (float)$result[0]['HEURES'];
        }else{
            return 0;
        }
    }

    /**
     * @deprecated
     * @param Intervenant $intervenant
     * @return int
     */
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

    /**
     * @deprecated
     * @param Intervenant $intervenant
     *
     */
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
        if (! empty($elements)) $elements = $this->getServiceElementPedagogique()->get(array_keys($elements));
        if (! empty($etablissements)) $etablissements = $this->getServiceEtablissement()->get(array_keys($etablissements));
        foreach( $data as $did => $d ){
            if ($data[$did]['etablissement']) $data[$did]['etablissement'] = $etablissements[$d['etablissement']];
            if (isset($data[$did]['element_pedagogique']) && $data[$did]['element_pedagogique']) $data[$did]['element_pedagogique'] = isset($elements[$d['element_pedagogique']]) ? $elements[$d['element_pedagogique']] : null;
        }

        $typesInterventions = $this->getServiceTypeIntervention()->getList();
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

    /**
     * @deprecated
     * @param Intervenant $intervenant
     *
     */
    public function getVentilation( Intervenant $intervenant )
    {
        $sql = 'SELECT * FROM V_FORMULE_VENTILATION WHERE intervenant_id = :intervenant';
        $result = $this->getEntityManager()->getConnection()->executeQuery($sql, array('intervenant' => $intervenant->getId()))->fetchAll();
        
        $typesInterventions = $this->getServiceTypeIntervention()->getList();
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

    /**
     * @deprecated
     * @param Intervenant $intervenant
     *
     */
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
     * @deprecated
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
     * @deprecated
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
     * @deprecated
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
     * @deprecated
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

    /**
     * @return \Application\Entity\Db\TypeIntervention
     */
    protected function getServiceTypeIntervention()
    {
        return $this->getServiceLocator()->get('applicationTypeIntervention');
}

    /**
     * @return \Application\Service\ElementPedagogique
     */
    protected function getServiceElementPedagogique()
    {
        return $this->getServiceLocator()->get('applicationElementPedagogique');
    }

    /**
     * @return \Application\Service\Etablissement
     */
    protected function getServiceEtablissement()
    {
        return $this->getServiceLocator()->get('applicationEtablissement');
    }

    /**
     * @return \Application\Service\TypeVolumeHoraire
     */
    protected function getServiceTypeVolumeHoraire()
    {
        return $this->getServiceLocator()->get('applicationTypeVolumeHoraire');
    }
}