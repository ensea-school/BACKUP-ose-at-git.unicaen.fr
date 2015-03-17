<?php

namespace Application\Service;

use Application\Entity\Db\MiseEnPaiement as MiseEnPaiementEntity;
use Application\Entity\Paiement\MiseEnPaiementRecherche;
use Application\Entity\Db\Structure as StructureEntity;
use Application\Entity\Db\Periode as PeriodeEntity;
use Doctrine\ORM\QueryBuilder;

/**
 * Description of MiseEnPaiement
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class MiseEnPaiement extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\MiseEnPaiement';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'mep';
    }

    /**
     * Retourne les mises en paiement prêtes à payer (c'est-à-dire validées et non déjà payées
     *
     * @param QueryBuilder|null $queryBuilder
     * @return QueryBuilder
     */
    public function finderByEtat( $etat, QueryBuilder $qb=null, $alias=null )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);

        switch( $etat ){
            case MiseEnPaiementEntity::A_METTRE_EN_PAIEMENT:
                $qb->andWhere("$alias.dateMiseEnPaiement IS NULL");
            break;
            case MiseEnPaiementEntity::MIS_EN_PAIEMENT:
                $qb->andWhere("$alias.dateMiseEnPaiement IS NOT NULL");
            break;
        }
        return $qb;
    }

    public function finderByStructure( StructureEntity $structure, QueryBuilder $qb=null, $alias=null )
    {
        $serviceMIS = $this->getServiceLocator()->get('applicationMiseEnPaiementIntervenantStructure');
        /* @var $serviceMIS MiseEnPaiementIntervenantStructure */

        list($qb,$alias) = $this->initQuery($qb, $alias);

        $this->join( $serviceMIS, $qb, 'miseEnPaiementIntervenantStructure', false, $alias );
        $serviceMIS->finderByStructure( $structure, $qb );

        return $qb;
    }

    public function finderByIntervenants( $intervenants, QueryBuilder $qb=null, $alias=null )
    {
        $serviceMIS = $this->getServiceLocator()->get('applicationMiseEnPaiementIntervenantStructure');
        /* @var $serviceMIS MiseEnPaiementIntervenantStructure */

        list($qb,$alias) = $this->initQuery($qb, $alias);

        $this->join( $serviceMIS, $qb, 'miseEnPaiementIntervenantStructure', false, $alias );
        $serviceMIS->finderByIntervenant( $intervenants, $qb );

        return $qb;
    }

    /**
     * Retourne les données du TBL des mises en paiement en fonction des critères de recherche transmis
     *
     * @param MiseEnPaiementRecherche $recherche
     * @return array
     */
    public function getEtatPaiement( MiseEnPaiementRecherche $recherche, array $options=[] )
    {
        // initialisation
        $defaultOptions = [
            'composante'        => null,            // Composante qui en fait la demande
        ];
        $options = array_merge($defaultOptions, $options );
        $annee = $this->getContextProvider()->getGlobalContext()->getAnnee();

        $defaultTotal = [
            'hetd'                => 0,
            'hetd-pourc'          => 0,
            'hetd-montant'        => 0,
            'rem-fc-d714'         => 0,
            'exercice-aa'         => 0,
            'exercice-aa-montant' => 0,
            'exercice-ac'         => 0,
            'exercice-ac-montant' => 0,
        ];
        $data = [
            'total' => $defaultTotal,
        ];

        // requêtage
        $conditions = [
            'annee_id = '.$annee->getId()
        ];

        if ($e = $recherche->getEtat()){
            $conditions['etat'] = 'etat = \''.$e.'\'';
        }
        if ($p = $recherche->getPeriode()){
            $conditions['periode_id'] = 'periode_paiement_id = '.$p->getId();
        }
        if ($s = $recherche->getStructure()){
            $conditions['structure_id'] = 'structure_id = '.$s->getId();
        }
        if ($recherche->getIntervenants()->count() > 0){
            $iIdList = [];
            foreach( $recherche->getIntervenants() as $intervenant ){
                $iIdList[] = $intervenant->getId();
            }
            $conditions['intervenant_id'] = 'intervenant_id IN ('.implode(',',$iIdList).')';
        }

        if ($options['composante'] instanceof StructureEntity ){
            $conditions['composante'] = "structure_id = ".(int)$options['composante']->getId();
        }

        $sql = 'SELECT * FROM V_ETAT_PAIEMENT WHERE '.implode( ' AND ', $conditions ).' ORDER BY INTERVENANT_NOM, CENTRE_COUT_CODE';
        $stmt = $this->getEntityManager()->getConnection()->executeQuery($sql);
        
        // récupération des données
        while( $d = $stmt->fetch()){
            $ds = [
                'annee-libelle'                 => (string) $annee,

                'intervenant-code'              =>          $d['INTERVENANT_CODE'],
                'intervenant-nom'               =>          $d['INTERVENANT_NOM'],
                'intervenant-numero-insee'      =>          $d['INTERVENANT_NUMERO_INSEE'],

                'centre-cout-code'              =>          $d['CENTRE_COUT_CODE'],
                'domaine-fonctionnel-libelle'   =>          $d['DOMAINE_FONCTIONNEL_LIBELLE'],

                'hetd'                          => (float)  $d['HETD'],
                'hetd-pourc'                    => (float)  $d['HETD_POURC'],
                'hetd-montant'                  => (float)  $d['HETD_MONTANT'],
                'rem-fc-d714'                   => (float)  $d['REM_FC_D714'],
                'exercice-aa'                   => (float)  $d['EXERCICE_AA'],
                'exercice-aa-montant'           => (float)  $d['EXERCICE_AA_MONTANT'],
                'exercice-ac'                   => (float)  $d['EXERCICE_AC'],
                'exercice-ac-montant'           => (float)  $d['EXERCICE_AC_MONTANT'],
            ];

            $iid = $d['INTERVENANT_ID'];

            /* Initialisation éventuelle */
            if (! isset($data[$iid])){
                $data[$iid] = [
                    'hetd' => [
                        'total' => $defaultTotal,
                    ],
                    'rem-fc-d714' => [
                        'total' => $defaultTotal,
                    ],
                ];
            }

            /* Calcul des totaux */
            foreach( $defaultTotal as $col => $null ){
                $data['total'][$col] += $ds[$col];
            }
            if ($ds['hetd'] > 0){
                $data[$iid]['hetd'][] = $ds;
                foreach( $defaultTotal as $col => $null ){
                    $data[$iid]['hetd']['total'][$col] += $ds[$col];
                }
            }
            if ($ds['rem-fc-d714'] > 0){
                $data[$iid]['rem-fc-d714'][] = $ds;
                foreach( $defaultTotal as $col => $null ){
                    $data[$iid]['rem-fc-d714']['total'][$col] += $ds[$col];
                }
            }
        }
        return $data;
    }

    /**
     * Retourne les données du TBL des mises en paiement en fonction des critères de recherche transmis
     *
     * @param MiseEnPaiementRecherche $recherche
     * @return array
     */
    public function getEtatPaiementCsv( MiseEnPaiementRecherche $recherche, array $options=[] )
    {
        // initialisation
        $defaultOptions = [
            'composante'        => null,            // Composante qui en fait la demande
        ];
        $options = array_merge($defaultOptions, $options );
        $annee = $this->getContextProvider()->getGlobalContext()->getAnnee();


        $data = [];

        // requêtage
        $conditions = [
            'annee_id = '.$annee->getId()
        ];

        if ($e = $recherche->getEtat()){
            $conditions['etat'] = 'etat = \''.$e.'\'';
        }
        if ($p = $recherche->getPeriode()){
            $conditions['periode_id'] = 'periode_paiement_id = '.$p->getId();
        }
        if ($s = $recherche->getStructure()){
            $conditions['structure_id'] = 'structure_id = '.$s->getId();
        }
        if ($recherche->getIntervenants()->count() > 0){
            $iIdList = [];
            foreach( $recherche->getIntervenants() as $intervenant ){
                $iIdList[] = $intervenant->getId();
            }
            $conditions['intervenant_id'] = 'intervenant_id IN ('.implode(',',$iIdList).')';
        }

        if ($options['composante'] instanceof StructureEntity ){
            $conditions['composante'] = "structure_id = ".(int)$options['composante']->getId();
        }

        $sql = 'SELECT * FROM V_ETAT_PAIEMENT WHERE '.implode( ' AND ', $conditions ).' ORDER BY INTERVENANT_NOM, CENTRE_COUT_CODE';
        $stmt = $this->getEntityManager()->getConnection()->executeQuery($sql);
        
        // récupération des données
        while( $d = $stmt->fetch()){
            $ds = [
                'annee-libelle'                 => (string) $annee,
                'etat'                          =>          $d['ETAT'],
                'structure-libelle'             =>          $d['STRUCTURE_LIBELLE'],
                'date-mise-en-paiement'         => empty($d['DATE_MISE_EN_PAIEMENT']) ? null : \DateTime::createFromFormat('Y-m-d', substr($d['DATE_MISE_EN_PAIEMENT'],0,10)),
                'periode-paiement-libelle'      =>          $d['PERIODE_PAIEMENT_LIBELLE'],
                'intervenant-code'              =>          $d['INTERVENANT_CODE'],
                'intervenant-nom'               =>          $d['INTERVENANT_NOM'],
                'intervenant-numero-insee'      =>          $d['INTERVENANT_NUMERO_INSEE'],

                'centre-cout-code'              =>          $d['CENTRE_COUT_CODE'],
                'centre-cout-libelle'           =>          $d['CENTRE_COUT_LIBELLE'],
                'domaine-fonctionnel-code'      =>          $d['DOMAINE_FONCTIONNEL_CODE'],
                'domaine-fonctionnel-libelle'   =>          $d['DOMAINE_FONCTIONNEL_LIBELLE'],

                'hetd'                          => (float)  $d['HETD'],
                'hetd-pourc'                    => (float)  $d['HETD_POURC'],
                'hetd-montant'                  => (float)  $d['HETD_MONTANT'],
                'rem-fc-d714'                   => (float)  $d['REM_FC_D714'],
                'exercice-aa'                   => (float)  $d['EXERCICE_AA'],
                'exercice-aa-montant'           => (float)  $d['EXERCICE_AA_MONTANT'],
                'exercice-ac'                   => (float)  $d['EXERCICE_AC'],
                'exercice-ac-montant'           => (float)  $d['EXERCICE_AC_MONTANT'],
            ];
            $data[] = $ds;
        }
        return $data;
    }

    /**
     * Retourne les données de l'export Winpaie des mises en paiement en fonction des critères de recherche transmis
     *
     * @param MiseEnPaiementRecherche $recherche
     * @return array
     */
    public function getExportWinpaie( MiseEnPaiementRecherche $recherche, array $options=[] )
    {
        // initialisation
        $defaultOptions = [
            'composante'        => null,            // Composante qui en fait la demande
        ];
        $options = array_merge($defaultOptions, $options );
        $annee = $this->getContextProvider()->getGlobalContext()->getAnnee();

        $data = [];

        // requêtage
        $conditions = [
            'annee_id = '.$annee->getId()
        ];

        if ($p = $recherche->getPeriode()){
            $conditions['periode_id'] = 'periode_paiement_id = '.$p->getId();
        }
        if ($s = $recherche->getStructure()){
            $conditions['structure_id'] = 'structure_id = '.$s->getId();
        }
        if ($recherche->getIntervenants()->count() > 0){
            $iIdList = [];
            foreach( $recherche->getIntervenants() as $intervenant ){
                $iIdList[] = $intervenant->getId();
            }
            $conditions['intervenant_id'] = 'intervenant_id IN ('.implode(',',$iIdList).')';
        }

        if ($options['composante'] instanceof StructureEntity ){
            $conditions['composante'] = "structure_id = ".(int)$options['composante']->getId();
        }

        $sql = '
        SELECT
            INSEE,
            NOM,
            CARTE,
            CODE_ORIGINE, 
            RETENUE, 
            SENS, 
            MC, 
            SUM(NBU) NBU,
            MONTANT, 
            LIBELLE
        FROM
            V_EXPORT_PAIEMENT_WINPAIE
        WHERE
            '.implode( ' AND ', $conditions ).'
        GROUP BY
            INSEE,
            NOM,
            CARTE,
            CODE_ORIGINE,
            RETENUE,
            SENS,
            MC,
            MONTANT,
            LIBELLE
        ORDER BY
            NOM, CODE_ORIGINE
        ';
        $stmt = $this->getEntityManager()->getConnection()->executeQuery($sql);

        // récupération des données
        while( $d = $stmt->fetch()){
            $nbu = (float) $d['NBU'];
            $libelle = $d['LIBELLE'];
            $nbuLimit = 99;
            $ds = [
                'insee'         =>         $d['INSEE'],
                'nom'           =>         $d['NOM'],
                'carte'         =>         $d['CARTE'],
                'code-origine'  =>         $d['CODE_ORIGINE'],
                'retenue'       =>         $d['RETENUE'],
                'sens'          =>         $d['SENS'],
                'mc'            =>         $d['MC'],
                'nbu'           =>         null,
                'montant'       => (float) $d['MONTANT'],
                'libelle'       =>         null,
            ];

            $occ = floor( $nbu / $nbuLimit );
            $mod = $nbu - $nbuLimit * $occ;

            if ($occ > 0){
                for( $i=0;$i<$occ;$i++ ){
                    $ds['nbu'] = (float)$nbuLimit;
                    $ds['libelle'] = $libelle.' '.((int)$nbuLimit).' H';
                    $data[] = $ds;
                }
            }
            if ($mod > 0){
                $ds['nbu'] = $mod;
                $ds['libelle'] = $libelle.' '.sprintf( '%02d', floor($mod) ).' H';
                $av = (round($mod-floor($mod),2))*100;
                if ($av > 0) $ds['libelle'] .= sprintf( ' %02d', floor($av) );
                $data[] = $ds;
            }
        }
        return $data;
    }

    /**
     * Sauvegarde tous les changements intervenus dans un ensemble de mises en paiement
     *
     * @param array $changements
     */
    public function saveChangements( $changements )
    {
        foreach( $changements as $miseEnPaiementId => $data ){
            if (0 === strpos($miseEnPaiementId, 'new')){ // insert
                $miseEnPaiement = $this->newEntity();
                /* @var $miseEnPaiement MiseEnPaiementEntity */
                $this->hydrateFromChangements($miseEnPaiement, $data);
                $this->save($miseEnPaiement);
            }else{
                $miseEnPaiement = $this->get( $miseEnPaiementId );
                if (null == $data || 'removed' == $data){ // delete
                    $this->delete($miseEnPaiement);
                }else{ // update
                    $this->hydrateFromChangements($miseEnPaiement, $data);
                    $this->save($miseEnPaiement);
                }
            }
        }
    }

    /**
     *
     * @param StructureEntity $structure
     * @param \Application\Entity\Db\Intervenant[] $intervenants
     * @param PeriodeEntity $periodePaiement
     * @param \DateTime $dateMiseEnPaiement
     */
    public function mettreEnPaiement( StructureEntity $structure, $intervenants, PeriodeEntity $periodePaiement, \DateTime $dateMiseEnPaiement )
    {
        list($qb,$alias) = $this->initQuery();
        $this->finderByEtat( MiseEnPaiementEntity::A_METTRE_EN_PAIEMENT, $qb );
        $this->finderByStructure($structure, $qb);
        $this->finderByIntervenants($intervenants, $qb);
        $mepList = $this->getList( $qb );
        foreach( $mepList as $mep ){
            /* @var $mep MiseEnPaiementEntity */
            $mep->setPeriodePaiement( $periodePaiement );
            $mep->setDateMiseEnPaiement($dateMiseEnPaiement);
            $this->save( $mep );
        }
    }

    private function hydrateFromChangements( MiseEnPaiementEntity $object, $data )
    {
        if (isset($data['heures'])){
            $object->setHeures( (float)$data['heures'] );
        }

        if (isset($data['centre-cout-id'])){
            $serviceCentreCout = $this->getServiceLocator()->get('applicationCentreCout');
            /* @var $serviceCentreCout CentreCout */
            $object->setCentreCout( $serviceCentreCout->get( (integer)$data['centre-cout-id'] ) );
        }

        if (isset($data['formule-resultat-service-id'])){
            $serviceFormuleResultatService = $this->getServiceLocator()->get('ApplicationFormuleResultatService');
            /* @var $serviceFormuleResultatService FormuleResultatService */
            $object->setFormuleResultatService( $serviceFormuleResultatService->get( (integer)$data['formule-resultat-service-id'] ) );
        }

        if (isset($data['formule-resultat-service-referentiel-id'])){
            $serviceFormuleResultatServiceReferentiel = $this->getServiceLocator()->get('ApplicationFormuleResultatServiceReferentiel');
            /* @var $serviceFormuleResultatServiceReferentiel FormuleResultatServiceReferentiel */
            $object->setFormuleResultatServiceReferentiel( $serviceFormuleResultatServiceReferentiel->get( (integer)$data['formule-resultat-service-referentiel-id'] ) );
        }

        if (isset($data['type-heures-id'])){
            $serviceTypeHeures = $this->getServiceLocator()->get('applicationTypeHeures');
            /* @var $serviceTypeHeures TypeHeures */
            $object->setTypeHeures( $serviceTypeHeures->get( (integer)$data['type-heures-id'] ) );
        }
    }

}