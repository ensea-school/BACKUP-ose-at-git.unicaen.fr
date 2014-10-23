<?php

namespace OSETest\Service\Process;
use OSETest\BaseTestCase;

/**
 * @group Formule
 */
class FormuleTest extends BaseTestCase {

    protected $etape;
    protected $source;
    protected $annee;
    protected $structure;
    protected $corps;
    protected $civilite;
    protected $dateNaissance;
    protected $email;
    protected $etablissement;
    protected $typeVolumeHoraire;
    protected $fonctionReferentiel;
    protected $entities = array();

    protected function setUp()
    {
        parent::setUp();

        $params = [
            'etape' => 1983,
            'structure' => 8474,
            'corps' => 905,
            'civilite' => 1,
            'type_volume_horaire' => 1,
            'fonction_referentiel' => 65,
            'date_naissance' => '1980-08-05',
            'email' => 'p.n@unicaen.fr',
        ];

        $parametres = $this->getServiceManager()->get('applicationParametres');
        $this->etape                = $this->getServiceManager()->get('applicationEtape')->get($params['etape']);
        $this->source               = $this->getServiceManager()->get('applicationSource')->getTest();
        $this->annee                = $this->getEntityManager()->find('Application\Entity\Db\Annee', $parametres->annee);
        $this->etablissement        = $this->getEntityManager()->find('Application\Entity\Db\Etablissement', $parametres->etablissement);
        $this->structure            = $this->getServiceManager()->get('applicationStructure')->get($params['structure']);
        $this->corps                = $this->getEntityManager()->find('Application\Entity\Db\Corps', $params['corps']);
        $this->civilite             = $this->getEntityManager()->find('Application\Entity\Db\Civilite', $params['civilite']);
        $this->typeVolumeHoraire    = $this->getEntityManager()->find('Application\Entity\Db\TypeVolumeHoraire', $params['type_volume_horaire']);
        $this->fonctionReferentiel  = $this->getEntityManager()->find('Application\Entity\Db\FonctionReferentiel', $params['fonction_referentiel']);
        $this->dateNaissance        = new \DateTime($params['date_naissance']);
        $this->email                = $params['email'];
    }

    protected function save($entity)
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush($entity);
        $this->entities[] = $entity;
        return $entity;
    }

    /**
     *
     * @param string $code
     * @param string $libelle
     * @param boolean $foad
     * @param boolean $fc
     * @param string[]|\Application\Entity\Db\Modulateur[]|null $modulateurs Liste de modulateurs ou de codes de modulateurs ou rien
     * @return \Application\Entity\Db\ElementPedagogique
     */
    protected function addElement( $code, $libelle, $foad, $fc, array $modulateurs=[] )
    {
        $e = (new \Application\Entity\Db\ElementPedagogique)
            ->setEtape($this->etape)
            ->setStructure($this->structure)
            ->setSource($this->source)
            ->setSourceCode($code)
            ->setLibelle($libelle)
            ->setTauxFoad($foad ? 1 : 0)
            ->setFc(true)
        ;
        $result = $this->save($e);
        foreach($modulateurs as $modulateur){
            $this->addModulateur($e, $modulateur);
        }
        return $result;
    }

    /**
     *
     * @param \Application\Entity\Db\ElementPedagogique|int $element Objet ou ID
     * @param \Application\Entity\Db\Modulateur|string $modulateur Objet ou Code du modulateur
     * @return \Application\Entity\Db\ElementModulateur
     */
    protected function addModulateur( $element, $modulateur )
    {
        if (! $element instanceof \Application\Entity\Db\ElementPedagogique){
            $element = $this->getServiceManager()->get('applicationElementPedagogique')->getRepo()->findOneBy(['sourceCode'=>$element]);
        }
        if (! $modulateur instanceof \Application\Entity\Db\Modulateur){
            $modulateur = $this->getServiceManager()->get('applicationModulateur')->getRepo()->findOneBy(['code'=>$modulateur]);
        }

        $e = (new \Application\Entity\Db\ElementModulateur)
            ->setAnnee($this->annee)
            ->setElement($element)
            ->setModulateur($modulateur)
        ;
        return $this->save($e);
    }

    /**
     *
     * @param string $code
     * @param string $nom
     * @param string $prenom
     * @param \Application\Entity\Db\StatutIntervenant|string $statut Objet ou Code
     * @param array $services Liste des services (clés = element, etablissement, heures)
     * @return \Application\Entity\Db\Intervenant
     */
    protected function addIntervenant( $code, $nom, $prenom, $statut, array $services=[] )
    {
        if (! $statut instanceof \Application\Entity\Db\StatutIntervenant){
            $statut = $this->getServiceManager()->get('applicationStatutIntervenant')->getRepo()->findOneBy(['sourceCode'=>$statut]);
        }
        /* @var $statut \Application\Entity\Db\StatutIntervenant */

        if (\Application\Entity\Db\TypeIntervenant::CODE_PERMANENT == $statut->getTypeIntervenant()->getCode()){
            $e = new \Application\Entity\Db\IntervenantPermanent;
            $e->setCorps($this->corps);
        }else{
            $e = new \Application\Entity\Db\IntervenantExterieur;
        }
        $e->setSource($this->source)
          ->setSourceCode($code)
          ->setStatut( $statut )
          ->setCivilite($this->civilite)
          ->setNomUsuel($nom)
          ->setNomPatronymique($nom)
          ->setPrenom($prenom)
          ->setPaysNaissanceCodeInsee(100)
          ->setPaysNaissanceLibelle('FRANCE')
          ->setPaysNationaliteCodeInsee(100)
          ->setPaysNationaliteLibelle('FRANCE')      
          ->setStructure($this->structure)
          ->setDateNaissance($this->dateNaissance)
          ->setEmail($this->email)
          ->setValiditeDebut(new \DateTime)
        ;
        $result = $this->save($e);
        foreach( $services as $service ){
            $element       = isset($service['element'])         ? $service['element'] : null;
            $etablissement = isset($service['etablissement'])   ? $service['etablissement'] : null;
            $heures        = isset($service['heures'])          ? $service['heures'] : array();
            $this->addService( $e, $element, $etablissement, $heures );
        }
        return $result;
    }

    /**
     *
     * @param \Application\Entity\Db\Intervenant|int $intervenant Objet ou ID
     * @param \Application\Entity\Db\ElementPedagogique|string $element Objet ou CODE ou NULL
     * @param \Application\Entity\Db\Etablissement|int $etablissement Objet ou ID ou NULL
     * @param integer[][] $heures Liste, par Période et par Type d'intervention, des heures de service
     * @return \Application\Entity\Db\Service
     */
    protected function addService($intervenant, $element=null, $etablissement=null, array $heures=array())
    {
        if (! $intervenant instanceof \Application\Entity\Db\Intervenant){
            $intervenant = $this->getServiceManager()->get('applicationIntervenant')->getRepo()->findOneBy(['id'=>$intervenant]);
        }
        if (null !== $element && ! $element instanceof \Application\Entity\Db\ElementPedagogique){
            $element = $this->getServiceManager()->get('applicationElementPedagogique')->getRepo()->findOneBy(['sourceCode'=>$element]);
        }
        if (null === $etablissement){
            $etablissement = $this->etablissement;
        }elseif(! $etablissement instanceof \Application\Entity\Db\Etablissement){
            $etablissement = $this->getServiceManager()->get('applicationEtablissement')->getRepo()->findOneBy(['sourceCode'=>$etablissement]);
        }
        $e = (new \Application\Entity\Db\Service)
            ->setIntervenant($intervenant)
            ->setStructureAff( $intervenant->getStructure() )
            ->setElementPedagogique($element)
            ->setAnnee($this->annee)
            ->setEtablissement($etablissement)
            ->setValiditeDebut(new \DateTime);
        ;
        if (null !== $element){
            $e->setStructureEns( $element->getStructure() );
        }
        $result = $this->save($e);
        foreach( $heures as $periode => $typesInt ){
            foreach( $typesInt as $typeIntervention => $h ){
                $this->addVolumeHoraire($e, $periode, $typeIntervention, $h);
            }
        }
        return $result;
    }

    /**
     *
     * @param \Application\Entity\Db\Intervenant|int $intervenant Objet ou ID
     * @param float $heures
     * @return type
     */
    protected function addServiceReferentiel($intervenant, $heures)
    {
        if (! $intervenant instanceof \Application\Entity\Db\Intervenant){
            $intervenant = $this->getServiceManager()->get('applicationIntervenant')->getRepo()->findOneBy(['id'=>$intervenant]);
        }

        $e = (new \Application\Entity\Db\ServiceReferentiel)
            ->setFonction($this->fonctionReferentiel)
            ->setIntervenant($intervenant)
            ->setAnnee($this->annee)
            ->setHeures($heures)
            ->setValiditeDebut(new \DateTime)
        ;
        return $this->save($e);
    }

    /**
     *
     * @param \Application\Entity\Db\Service|integer $service Objet ou ID
     * @param \Application\Entity\Db\Periode|integer $periode Objet ou ID
     * @param \Application\Entity\Db\TypeIntervention $typeIntervention Objet ou Code
     * @param integer $heures
     * @return \Application\Entity\Db\VolumeHoraire
     */
    protected function addVolumeHoraire($service, $periode, $typeIntervention, $heures)
    {
        if (! $service instanceof \Application\Entity\Db\Service){
            $service = $this->getServiceManager()->get('applicationService')->getRepo()->findOneBy(['id'=>$service]);
        }
        if (! $periode instanceof \Application\Entity\Db\Periode){
            $periode = $this->getServiceManager()->get('applicationPeriode')->getRepo()->findOneBy(['code'=>$periode]);
        }
        if (! $typeIntervention instanceof \Application\Entity\Db\TypeIntervention){
            $typeIntervention = $this->getServiceManager()->get('applicationTypeIntervention')->getRepo()->findOneBy(['code'=>$typeIntervention]);
        }

        $e = (new \Application\Entity\Db\VolumeHoraire)
            ->setService($service)
            ->setPeriode($periode)
            ->setTypeIntervention($typeIntervention)
            ->setHeures($heures)
            ->setTypeVolumeHoraire($this->typeVolumeHoraire)
        ;
        return $this->save($e);
    }

    protected function tearDown()
    {
        foreach( $this->entities as $e ){
            $this->getEntityManager()->remove($e);
        }
    //    $this->getEntityManager()->flush();
        parent::tearDown();
    }

    public function testFormule()
    {
        $this->markTestSkipped("A revoir...");
        
        //$this->addIntervenant('TEST03', 'TEST03', 'Testeur2', 'ENS_CH');
        $this->addServiceReferentiel(605, 2);
        return null;

        $this->addElement('TF1', 'TF1', true, true, ['FCN2','ECR'] );
        $this->addElement('TF2', 'TF2', true, true, ['FCN1','ACT'] );
        $this->addElement('TF3', 'TF3', false, false );
//        $this->addElement('TF4', 'TF4', false, true, ['FCN1'] );
//        $this->addElement('TF5', 'TF5', false, true, ['FCN1'] );
//        $this->addElement('TF6', 'TF6', false, true, ['FCN1'] );

        $i = $this->addIntervenant('TEST01', 'TEST01', 'Testeur', 'ENS_CH', [
            [ 'element'=>'TF1', 'heures'=>[ 'S1' => ['CM'=>12, 'TD'=>12, 'TP'=>12] ] ],
            [ 'element'=>'TF2', 'heures'=>[ 'S1' => ['CM'=>10, 'TD'=>82, 'TP'=>172] ] ],
            [ 'element'=>'TF3', 'heures'=>[ 'S1' => ['CM'=>10, 'TD'=>10, 'TP'=>10] ] ],
//            [ 'element'=>'TF4', 'heures'=>[ 'S1' => ['CM'=>0, 'TD'=>0, 'TP'=>0] ] ],
//            [ 'element'=>'TF5', 'heures'=>[ 'S1' => ['CM'=>0, 'TD'=>0, 'TP'=>0] ] ],
//            [ 'element'=>'TF6', 'heures'=>[ 'S1' => ['CM'=>0, 'TD'=>0, 'TP'=>0] ] ],
        ]); // 192 plafond
        $this->addServiceReferentiel($i, 2);


        $formule = $this->getServiceManager()->get('ProcessFormuleHetd');

        //var_dump( $formule->getHeuresComplementaires($i) );

        $sqls = [
            'SELECT * FROM V_FORMULE_SERVICE_DU WHERE intervenant_id = :intervenant',
            'SELECT * FROM V_FORMULE_MODIF_SERVICE_DU WHERE intervenant_id = :intervenant',
            'SELECT * FROM V_FORMULE_SERVICE_EXT WHERE intervenant_id = :intervenant',
            'SELECT * FROM V_FORMULE_SERVICE_REFERENTIEL WHERE intervenant_id = :intervenant',
            'SELECT * FROM V_FORMULE_SERVICE_RESTANT WHERE intervenant_id = :intervenant',

            'SELECT * FROM V_FORMULE_PONDERATION_ELEMENT',
            'SELECT * FROM V_FORMULE_SERVICE WHERE intervenant_id = :intervenant',
            'SELECT * FROM V_FORMULE_VOLUME_HORAIRE WHERE intervenant_id = :intervenant',
            
            'SELECT * FROM V_FORMULE_VENTILATION WHERE intervenant_id = :intervenant',
            'SELECT * FROM V_FORMULE_REEVAL_RESTEAPAYER WHERE intervenant_id = :intervenant',
            'SELECT * FROM V_FORMULE_HEURES_COMP WHERE intervenant_id = :intervenant',
        ];
        foreach( $sqls as $sql){
            var_dump( $sql );
            $result = $this->getEntityManager()->getConnection()->executeQuery($sql, array('intervenant' => $i->getId()))->fetchAll();
            var_dump($result);
        }

    }
}