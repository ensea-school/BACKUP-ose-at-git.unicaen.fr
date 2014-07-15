<?php

namespace Application\Service\Process;

use Application\Acl\ComposanteDbRole;
use Application\Entity\Db\TypeContrat;
use Application\Entity\Db\TypeValidation;
use Application\Rule\Intervenant\PeutCreerAvenantRule;
use Application\Rule\Intervenant\PeutCreerContratInitialRule;
use Application\Service\AbstractService;
use Application\Service\Contrat as ContratService;
use Application\Traits\IntervenantAwareTrait;
use Common\Exception\LogicException;
use Common\Exception\RuntimeException;
use Application\Entity\Db\Contrat;
use Application\Entity\Db\Structure;

/**
 * Workflow de création des contrats et avenants.
 *
 * @method \Application\Entity\Db\IntervenantExterieur getIntervenant() Description
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ContratProcess extends AbstractService
{
    use IntervenantAwareTrait;
    
    /**
     * 
     * @return \Application\Service\Process\ContratProcess
     */
    public function creer()
    {
        if (($peutCreerContrat = $this->getPeutCreerContratInitialRule()->execute())) {
            $servicesDispos        = $this->getServicesDisposPourContrat();
            $volumesHorairesDispos = $this->getVolumesHorairesDisposPourContrat();
            
            if (!count($volumesHorairesDispos)) {
                throw new RuntimeException("Anomalie : aucun volume horaire validé sans contrat n'a été trouvé pour créer le contrat.");
            }
            
            $this->creerContrat($volumesHorairesDispos);
            
            $this->messages[] = sprintf("Contrat de %s enregistré avec succès.", $this->getIntervenant());
        }
        elseif (($peutCreerAvenant = $this->getPeutCreerAvenantRule()->execute())) {
            $servicesDispos        = $this->getServicesDisposPourAvenant();
            $volumesHorairesDispos = $this->getVolumesHorairesDisposPourAvenant();
            
            if (!count($volumesHorairesDispos)) {
                throw new RuntimeException("Anomalie : aucun volume horaire validé sans contrat n'a été trouvé pour créer l'avenant.");
            }
            
            $this->creerAvenant($volumesHorairesDispos);
            
            $this->messages[] = sprintf("Avenant de %s enregistré avec succès.", $this->getIntervenant());
        }
        
        return $this;
    }
    
    /**
     * Crée un projet de contrat, c'est à dire un contrat non encore validé.
     * 
     * @return Contrat Contrat créé
     * @throws RuntimeException Si aucun volume horaire candidat n'est trouvé
     */
    private function creerContrat($volumesHoraires)
    {
//        // recherche des volumes horaires validés sans contrat, qui seront rattachés au contrat
////        $serviceValidation = $this->getServiceValidation();
////        $qb = $serviceValidation->finderByType($code = TypeValidation::CODE_SERVICES_PAR_COMP);
////        $qb = $serviceValidation->finderByIntervenant($this->getIntervenant(), $qb);
////        $validation = $serviceValidation->finderByStructureIntervention($this->getStructure(), $qb)->getQuery()->getOneOrNullResult();
////        $volumesHoraires = $validation->getVolumeHoraire();
//        $serviceVolumeHoraire = $this->getServiceVolumeHoraire();
//        $qb = $serviceVolumeHoraire->finderByIntervenant($this->getIntervenant());
//        $serviceVolumeHoraire->finderByStructureIntervention($this->getStructure(), $qb);
//        $serviceVolumeHoraire->finderByTypeValidation($this->getTypeValidation(), $qb);
//        $serviceVolumeHoraire->finderByContrat(false, $qb);
//        $volumesHoraires = $serviceVolumeHoraire->getList($qb);
//        
//        if (!count($volumesHoraires)) {
//            throw new RuntimeException("Anomalie : aucun volume horaire validé sans contrat n'a été trouvé.");
//        }

        $this->contrat = $this->getServiceContrat()->newEntity(TypeContrat::CODE_CONTRAT)
                ->setIntervenant($this->getIntervenant())
                ->setStructure($this->getStructure())
                ->setContrat(null) // le contrat initial, c'est lui!
                ->setValidation(null);
        
        foreach ($volumesHoraires as $volumeHoraire) {
            $this->contrat->addVolumeHoraire($volumeHoraire);
            $volumeHoraire->setContrat($this->contrat);
            $this->getEntityManager()->persist($volumeHoraire);
        }
        
        $this->getEntityManager()->persist($this->contrat);
        $this->getEntityManager()->flush();
        
        return $this->contrat;
    }
    
    /**
     * Crée un projet d'avenant, c'est à dire un avenant non encore validé.
     * 
     * @return Contrat Avenant créé
     */
    private function creerAvenant($volumesHoraires)
    {
        $avenant = $this->getServiceContrat()->newEntity(TypeContrat::CODE_AVENANT)
                ->setIntervenant($this->getIntervenant())
                ->setStructure($this->getStructure())
                ->setContrat($this->getContratInitial()) // lien vers le contrat initial
                ->setValidation(null);
        
        foreach ($volumesHoraires as $volumeHoraire) {
            $avenant->addVolumeHoraire($volumeHoraire);
            $volumeHoraire->setContrat($avenant);
            $this->getEntityManager()->persist($volumeHoraire);
        }
        
        $this->getEntityManager()->persist($avenant);
        $this->getEntityManager()->flush();
        
        return $avenant;
    }

    /**
     * Détermine si le projet de contrat spécifié nécessite d'être requalifé en avenant.
     * C'est le cas lorsqu'il existe un projet de contrat validé.
     * 
     * @param \Application\Entity\Db\Contrat $contrat
     * @return boolean
     */
    public function getDeviendraAvenant(Contrat $contrat)
    {
        if ($contrat->estUnAvenant() || $contrat->getValidation()) {
            return false;
        }
        if (!$this->getContratValide()) {
            return false;
        }
        
        return true;
    }
    
    private $contratValide;
    
    /**
     * Recherche s'il existe un contrat validé concernant l'intervenant dans
     * n'importe quelle composante.
     * 
     * @return Contrat|null
     */
    public function getContratValide()
    {
        if (null === $this->contratValide) {
            $serviceContrat = $this->getServiceContrat();
            $qb = $serviceContrat->finderByType($this->getTypeContrat());
            $qb = $serviceContrat->finderByIntervenant($this->getIntervenant(), $qb);
            $qb = $serviceContrat->finderByTypeValidation(TypeValidation::CODE_CONTRAT_PAR_COMP, $qb);
            // NB: pas de filtre sur la structure

            $this->contratValide = $qb->getQuery()->getOneOrNullResult();
        }
        
        return $this->contratValide;
    }
    
    /**
     * @var array
     */
    private $messages = array();
    
    /**
     * 
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }
    
    /**
     * 
     * @return boolean
     */
    public function getPeutCreerContratInitial()
    {
        $peut = $this->getPeutCreerContratInitialRule()->execute();
        
        if (!$peut) {
            $this->validationContratInitial = $this->getPeutCreerContratInitialRule()->getValidation();
        }
        
        return $peut;
    }
    
    /**
     * 
     * @return boolean
     */
    public function getPeutCreerAvenant()
    {
        return $this->getPeutCreerAvenantRule()->execute();
    }
    
    private $validationContratInitial;
    
    /**
     * 
     * @return \DateTime
     */
    public function getValidationContratInitial()
    {
        return $this->validationContratInitial;
    }
    
    private $peutCreerContratRule;
    
    /**
     * 
     * @return PeutCreerContratInitialRule
     */
    private function getPeutCreerContratInitialRule()
    {
        if (null === $this->peutCreerContratRule) {
            $this->peutCreerContratRule = new PeutCreerContratInitialRule($this->getIntervenant());
            $this->peutCreerContratRule
                    ->setStructure($this->getStructure())
                    ->setTypeContrat($this->getTypeContrat())
                    ->setTypeValidation($this->getTypeValidation())
                    ->setServiceVolumeHoraire($this->getServiceVolumeHoraire());
        }
        
        return $this->peutCreerContratRule;
    }

    private $peutCreerAvenantRule;
    
    /**
     * 
     * @return PeutCreerAvenantRule
     */
    private function getPeutCreerAvenantRule()
    {
        if (null === $this->peutCreerAvenantRule) {
            $this->peutCreerAvenantRule = new PeutCreerAvenantRule($this->getIntervenant());
            $this->peutCreerAvenantRule
                    ->setStructure($this->getStructure())
                    ->setTypeContrat($this->getTypeAvenant())
                    ->setTypeValidation($this->getTypeValidation())
                    ->setServiceVolumeHoraire($this->getServiceVolumeHoraire());
        }
        
        return $this->peutCreerAvenantRule;
    }
    
    private $servicesDisposPourContrat;
    
    public function getServicesDisposPourContrat()
    {
        if (null === $this->servicesDisposPourContrat) {
            $this->servicesDisposPourContrat = $this->getPeutCreerContratInitialRule()->getServicesDispos();
        }
        
        return $this->servicesDisposPourContrat;
    }
    
    private $volumesHorairesDisposPourContrat;
    
    public function getVolumesHorairesDisposPourContrat()
    {
        if (null === $this->volumesHorairesDisposPourContrat) {
            $this->volumesHorairesDisposPourContrat = $this->getPeutCreerContratInitialRule()->getVolumesHorairesDispos();
        }
        
        return $this->volumesHorairesDisposPourContrat;
    }
    
    private $servicesDisposPourAvenant;
    
    public function getServicesDisposPourAvenant()
    {
        if (null === $this->servicesDisposPourAvenant) {
            $this->servicesDisposPourAvenant = $this->getPeutCreerAvenantRule()->getServicesDispos();
        }
        
        return $this->servicesDisposPourAvenant;
    }
    
    private $volumesHorairesDisposPourAvenant;
    
    public function getVolumesHorairesDisposPourAvenant()
    {
        if (null === $this->volumesHorairesDisposPourAvenant) {
            $this->volumesHorairesDisposPourAvenant = $this->getPeutCreerAvenantRule()->getVolumesHorairesDispos();
        }
        
        return $this->volumesHorairesDisposPourAvenant;
    }
    
    private function getTypeContrat()
    {
        return $this->getEntityManager()->getRepository('Application\Entity\Db\TypeContrat')
                ->findOneByCode(TypeContrat::CODE_CONTRAT);
    }
    
    private function getTypeAvenant()
    {
        return $this->getEntityManager()->getRepository('Application\Entity\Db\TypeContrat')
                ->findOneByCode(TypeContrat::CODE_AVENANT);
    }
    
    private function getTypeValidation()
    {
        return $this->getEntityManager()->getRepository('Application\Entity\Db\TypeValidation')
                ->findOneByCode(TypeValidation::CODE_SERVICES_PAR_COMP);
    }
    
    private $contrat;
    
    private function getContratInitial()
    {
        if (null === $this->contrat) {
            $serviceContrat = $this->getServiceContrat();
            $qb = $serviceContrat->finderByType($this->getTypeContrat());
            $serviceContrat->finderByIntervenant($this->getIntervenant(), $qb);
            $serviceContrat->finderByStructure($this->getStructure(), $qb);
            $this->contrat = $qb->getQuery()->getOneOrNullResult();
        }
        
        return $this->contrat;
    }
    
    private $avenants;
    
    private function getAvenants()
    {
        if (null === $this->avenants) {
            $serviceContrat = $this->getServiceContrat();
            $qb = $serviceContrat->finderByType($this->getTypeAvenant());
            $serviceContrat->finderByIntervenant($this->getIntervenant(), $qb);
            $serviceContrat->finderByStructure($this->getStructure(), $qb);
            $this->avenants = $qb->getQuery()->getResult();
        }

        return $this->avenants;
    }
    
    private $structure;

    private function getStructure()
    {
        if (null === $this->structure) {
            $role = $this->getContextProvider()->getSelectedIdentityRole();
            if (!$role instanceof ComposanteDbRole) {
                throw new LogicException("Rôle courant inattendu.");
            }
            $this->structure = $role->getStructure();
        }

        return $this->structure;
    }

    /**
     * @return ContratService
     */
    private function getServiceContrat()
    {
        return $this->getServiceLocator()->get('ApplicationContrat');
    }
    
    /**
     * @return \Application\Service\Validation
     */
    private function getServiceValidation()
    {
        return $this->getServiceLocator()->get('ApplicationValidation');
    }
    
    /**
     * @return \Application\Service\VolumeHoraire
     */
    private function getServiceVolumeHoraire()
    {
        return $this->getServiceLocator()->get('ApplicationVolumeHoraire');
    }
    
    /**
     * @return \Application\Service\TypeValidation
     */
    private function getServiceTypeValidation()
    {
        return $this->getServiceLocator()->get('ApplicationTypeValidation');
    }
}
