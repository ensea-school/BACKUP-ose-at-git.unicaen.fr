<?php

namespace Application\Service\Process;

use Application\Service\AbstractService;
use Application\Entity\Db\IntervenantExterieur;
use Application\Service\StatutIntervenant as StatutIntervenantService;
use Application\Service\TypePieceJointe as TypePieceJointeService;
use Application\Service\TypePieceJointeStatut as TypePieceJointeStatutService;
use Application\Service\PieceJointe as PieceJointeService;
use Application\Entity\Db\TypePieceJointe;
use Application\Entity\Db\TypePieceJointeStatut;
use Application\Entity\Db\PieceJointe;

/**
 * Processus de gestion de la liste de pièces à fournir pour un dossier vacataire.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PieceJointeProcess extends AbstractService
{
    /**
     * @var array
     */
    private $typesPieceJointeStatut;
    
    /**
     * @var array
     */
    private $typesPieceJointeAttendus;
    
    /**
     * @var array
     */
    private $typesPieceJointeFournis;
    
    /**
     * @var array
     */
    private $piecesJointesFournies;
    
    /**
     * 
     * @param string[] $typesPieceJointeIds Ids des type de pj à instancier.
     * @return \Application\Service\Process\DossierProcess
     */
    public function updatePiecesJointes($typesPieceJointeIds)
    {
        $typesPieceJointeIds = (array) $typesPieceJointeIds;
        $em                  = $this->getEntityManager();
        
        $attendusIds         = array_keys($this->getTypesPieceJointeAttendus());
        $fournisIds          = array_keys($this->getTypesPieceJointeFournis());
        $typesPieceJointeIds = array_intersect($typesPieceJointeIds, $attendusIds); // exclut les ids non attendus
        
        $disparusIds = array_diff($fournisIds, $typesPieceJointeIds);
        $apparusIds  = array_diff($typesPieceJointeIds, $fournisIds);

//        var_dump('$attendusIds', $attendusIds, '$fournisIds', $fournisIds, '$typesPieceJointeIds', $typesPieceJointeIds,
//                '$disparusIds', $disparusIds, '$apparusIds', $apparusIds);
        
        // suppression des pj disparues
        foreach ($disparusIds as $typeId) {
            $entity = $this->getPieceJointeFournie($typeId);
            $em->remove($entity);
        }
        
        // création des pj apparues
        foreach ($apparusIds as $typeId) {
            $type = $this->getTypesPieceJointeAttendus()[$typeId];
            $pieceJointe = $this->getServicePieceJointe()->newEntity(); /* @var $pieceJointe \Application\Entity\Db\PieceJointe */
            $pieceJointe
                    ->setType($type)
                    ->setDossier($this->getDossier())
                    ->setUrl(null);
            $em->persist($pieceJointe);
        }
        
        $em->flush();
        
        return $this;
    }
    
    /**
     * @return \Zend\Form\Form
     */
    public function getFormPiecesJointes()
    {
        $form           = new \Zend\Form\Form();
        $basePathHelper = $this->getServiceLocator()->get('ViewHelperManager')->get('basePath');
        
        $valueOptions = array();
        foreach ($this->getTypesPieceJointeStatut() as $tpjs) { /* @var $tpjs TypePieceJointeStatut */
            $totalHETD   = $this->getTotalHETDIntervenant();
            $obligatoire = $tpjs->getObligatoireToString($totalHETD);
            
            $link = null;
            if (($url = $tpjs->getType()->getUrlModeleDoc())) {
                $href = $basePathHelper($url);
                $fileName = ltrim(strrchr($href, '/'), '/');
                $link = '<br /><a class="modele-doc" title="Cliquez pour télécharger le document à remplir" href="' 
                        . $href . '"><span class="glyphicon glyphicon-file"></span> ' . $fileName . '</a>';
            }
    
            $type = (string) $tpjs->getType();
            if ($tpjs->getType()->getCode() === TypePieceJointe::CARTE_ETUD) {
                $annee = $this->getContextProvider()->getGlobalContext()->getAnnee();
                $type .= " $annee";
            }
            $label = sprintf('%s <span class="text-warning">%s</span>%s', $type, $obligatoire, $link);
            $valueOptions[] = array(
                'value' => $tpjs->getType()->getId(),
                'label' => $label,
                'selected' => $this->isTypePieceJointeFourni($tpjs->getType()),
                'attributes' => array(
                    'class' => 'form-control required',
                ),
                'label_attributes' => array(
                    'class' => 'required',
                ),
            );
        }
        
        $form->add(array(
            'name' => "pj",
            'type'  => 'MultiCheckbox',
            'options' => array(
                'label' => "Les pièces justificatives cochées ont été fournies :",
                'value_options' => $valueOptions,
            ),
            'attributes' => array(
            ),
        ));
        $form->get('pj')->setLabelOption('disable_html_escape', true);
        
        /**
         * Csrf
         */
        $form->add(new \Zend\Form\Element\Csrf('security'));
        
        /**
         * Submit
         */
        $form->add(array(
            'name' => 'submit',
            'type'  => 'Submit',
            'attributes' => array(
                'value' => "Enregistrer la checklist",
            ),
        ));
        
        $form->getInputFilter()->get('pj')->setRequired(false);
        
        return $form;
    }
    
    /**
     * 
     * @return array id => TypePieceJointeStatut
     */
    private function getTypesPieceJointeStatut()
    {
        if (null === $this->typesPieceJointeStatut) {
            $qb = $this->getServiceTypePieceJointeStatut()->finderByStatutIntervenant($this->getStatut());
            $qb = $this->getServiceTypePieceJointeStatut()->finderByPremierRecrutement($this->getDossier()->getPremierRecrutement(), $qb);
            $this->typesPieceJointeStatut = $this->getServiceTypePieceJointeStatut()->getList($qb);
        }
        
        return $this->typesPieceJointeStatut;
    }
    
    /**
     * 
     * @return array id => TypePieceJointe
     */
    public function getTypesPieceJointeAttendus()
    {
        if (null === $this->typesPieceJointeAttendus) {
            $this->typesPieceJointeAttendus = array();
            foreach ($this->getTypesPieceJointeStatut() as $typePieceJointeStatut) { /* @var $typePieceJointeStatut TypePieceJointeStatut */
                $type = $typePieceJointeStatut->getType();
                $this->typesPieceJointeAttendus[$type->getId()] = $type;
            }
        }
        
        return $this->typesPieceJointeAttendus;
    }
    
    /**
     * 
     * @return array id => TypePieceJointe
     */
    public function getTypesPieceJointeFournis()
    {
        if (null === $this->typesPieceJointeFournis) {
            $this->typesPieceJointeFournis = array();
            foreach ($this->getPiecesJointesFournies() as $pj) { /* @var $pj PieceJointe */
                $type = $pj->getType();
                $this->typesPieceJointeFournis[$type->getId()] = $type;
            }
        }
        
        return $this->typesPieceJointeFournis;
    }
    
    /**
     * 
     * @param int|TypePieceJointe $typePieceJointe
     * @return array id => PieceJointe
     */
    public function getPiecesJointesFournies($typePieceJointe = null)
    {
        if ($typePieceJointe && !$typePieceJointe instanceof TypePieceJointe) {
            $typePieceJointe = $this->getServiceTypePieceJointe()->get($typePieceJointe);
        }
        
        if (null === $this->piecesJointesFournies) {
            $qb = $this->getServicePieceJointe()->finderByDossier($this->getDossier());
            if ($typePieceJointe) {
                $this->getServicePieceJointe()->finderByType($typePieceJointe, $qb);
            }
            $this->piecesJointesFournies = $this->getServicePieceJointe()->getList($qb);
        }
        
        return $this->piecesJointesFournies;
    }
    
    /**
     * 
     * @param int|TypePieceJointe $type
     * @return PieceJointe|null
     */
//    public function getPieceJointeFournie($type)
//    {
//        $type = $type instanceof TypePieceJointe ? $type->getId() : $type;
//        
//        foreach ($this->getPiecesJointesFournies() as $pj) { /* @var $pj PieceJointe */
//            if ($type === $pj->getType()->getId()) {
//                return $pj;
//            }
//        }
//        
//        return null;
//    }

    /**
     * Teste si un type de pj est attendu.
     * 
     * @param int|TypePieceJointe $type
     * @return bool
     */
    public function isTypePieceJointeAttendu($type)
    {
        if ($type instanceof TypePieceJointe) {
            $type = $type->getId();
        }
        
        return in_array($type, array_keys($this->getTypesPieceJointeAttendus()));
    }
    
    /**
     * 
     * @param int|TypePieceJointe $type
     * @return PieceJointe|false
     */
    public function isTypePieceJointeFourni($type)
    {
        if (!$type instanceof TypePieceJointe) {
            $type = $this->getServiceTypePieceJointe()->get($type);
        }
        
        // recherche d'une pj du type spécifié et liée au dossier de l'intervenant
        $qb = $this->getServicePieceJointe()->finderByType($type);
        $qb = $this->getServicePieceJointe()->finderByDossier($this->getDossier(), $qb);
        
        return $qb->getQuery()->getOneOrNullResult() ?: false;
    }
    
    /**
     * 
     * @param int|TypePieceJointe $type
     * @return bool
     */
    public function isTypePieceJointeObligatoire($type)
    {
        if ($type instanceof TypePieceJointe) {
            $type = $type->getId();
        }
        
        $typePieceJointeStatut = $this->getTypesPieceJointeStatut()[$type]; /* @var $typePieceJointeStatut TypePieceJointeStatut */
        
        return $typePieceJointeStatut->isObligatoire($this->getTotalHETDIntervenant());
    }
    
    /**
     * @deprecated Implémenter le vrai calcul d'HETD ?
     */
    private function getTotalHETDIntervenant()
    {
        return $this->getServiceService()->getTotalHeuresReelles($this->getIntervenant());
    }
    
    /**
     * Recherche les destinataires des pièces justificatives : ce sont les responsables admins
     * de la structure d'affectation principale de l'intervenant.
     * 
     * NB: on retourne des entités Role et non des entités Personnel : cela permet de disposer
     * ultérieurement du type de rôle.
     * 
     * @return \Application\Entity\Db\Role[]
     */
    public function getRolesDestinatairesPiecesJointes()
    {
        $service = $this->getServiceRole();
        $structure = $this->getIntervenant()->getStructure();
        
        do {
            $qb = $service->finderByTypeRole(\Application\Entity\Db\TypeRole::CODE_RA);
            $service->finderByStructure($structure, $qb);
            $roles = $service->getList($qb);
            $structure = $structure->getParente();
        }
        while (!count($roles) && $structure);
        
        return $roles;
    }
    
    /**
     * @return StatutIntervenantService
     */
    private function getServiceStatut()
    {
        return $this->getServiceLocator()->get('applicationStatutIntervenant');
    }
    
    /**
     * @return TypePieceJointeService
     */
    private function getServiceTypePieceJointe()
    {
        return $this->getServiceLocator()->get('applicationTypePieceJointe');
    }
    
    /**
     * @return TypePieceJointeStatutService
     */
    private function getServiceTypePieceJointeStatut()
    {
        return $this->getServiceLocator()->get('applicationTypePieceJointeStatut');
    }
    
    /**
     * @return PieceJointeService
     */
    private function getServicePieceJointe()
    {
        return $this->getServiceLocator()->get('applicationPieceJointe');
    }
    
    /**
     * @return \Application\Service\Service
     */
    protected function getServiceService()
    {
        return $this->getServiceLocator()->get('ApplicationService');
    }
    
    /**
     * @return \Application\Service\Role
     */
    private function getServiceRole()
    {
        return $this->getServiceLocator()->get('applicationRole');
    }
    
    /**
     * @var IntervenantExterieur
     */
    private $intervenant;
    
    /**
     * 
     * @param \Application\Entity\Db\IntervenantExterieur $intervenant
     * @return \Application\Service\DossierProcess
     * @throws \Common\Exception\PieceJointe\AucuneAFournirException
     */
    public function setIntervenant(IntervenantExterieur $intervenant)
    {
        $this->intervenant = $intervenant;
        
        if (!$this->getTypesPieceJointeStatut()) {
            throw new \Common\Exception\PieceJointe\AucuneAFournirException(
                    "Aucun type de pièce justificative à fournir n'a été trouvé pour l'intervenant {$this->getIntervenant()} "
                    . "(dont le statut est '{$this->getStatut()}').");
        }
        
        return $this;
    }
    
    /**
     * @return IntervenantExterieur
     */
    public function getIntervenant()
    {
        return $this->intervenant;
    }

    /**
     * @return \Application\Entity\Db\Dossier
     */
    private function getDossier()
    {
        $dossier = $this->getIntervenant()->getDossier();
        if (!$dossier) {
            throw new \Common\Exception\LogicException("L'intervenant spécifié n'a pas de données personnelles enregistrées.");
        }
        return $dossier;
    }
    
    /**
     * @return \Application\Entity\Db\StatutIntervenant
     */
    public function getStatut()
    {
        return $this->getDossier()->getStatut();
    }
}
