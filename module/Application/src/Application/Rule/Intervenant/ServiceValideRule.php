<?php

namespace Application\Rule\Intervenant;

/**
 * Recherche si les enseignements d'un intervenant au sein d'une structure ont été validés.
 * 
 * Cette règle renvoit :
 * - <code>true</code> si tous les volumes horaires ont été validés ;
 * - <code>false</code> si des volumes horaires n'ont pas encore été validés.
 * 
 * Dans le cas d'une recherche partielle, cette règle renvoit :
 * - <code>true</code> si tout ou partie des volumes horaires ont été validés ;
 * - <code>false</code> si aucun volume horaire n'a été validé.
 * 
 * NB: 2 getters permettent de connaître les services et volumes horaires 
 * déjà validés et ceux non encore validés.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ServiceValideRule extends IntervenantRule
{
    use \Application\Traits\TypeValidationAwareTrait;
    use \Application\Traits\StructureAwareTrait;
    use \Application\Service\Initializer\VolumeHoraireServiceAwareTrait;
    
    /**
     * Flag indiquant si l'on se satisfait d'une validation partielle des services.
     * Autrement dit, avec ce flag à <code>true</code>, les services seront considérés comme validés
     * (i.e. cette règle retournera <code>true</code>) si au moins un volume horaire est validé.
     *  
     * @var boolean
     */
    private $memePartiellement = false;
    
    /**
     * 
     * @param \Application\Entity\Db\Intervenant $intervenant
     * @param bool $memePartiellement Flag indiquant si l'on se satisfait d'une validation partielle des services.
     */
    public function __construct(\Application\Entity\Db\Intervenant $intervenant, $memePartiellement = false)
    {
        parent::__construct($intervenant);
        
        $this->memePartiellement = $memePartiellement;
    }
    
    public function execute()
    {
        if (!$this->getServiceVolumeHoraire()) {
            throw new \Common\Exception\LogicException("Anomalie: aucun service VolumeHoraire spécifié.");
        }

        $qb = $this->getServiceVolumeHoraire()->finderByIntervenant($this->getIntervenant());
        if ($this->getStructure()) {
            $qb = $this->getServiceVolumeHoraire()->finderByStructureIntervention($this->getStructure(), $qb);
        }
        
        $this->volumesHorairesNonValides = [];
        $this->volumesHorairesValides    = [];
        
        foreach ($qb->getQuery()->getResult() as $vh) { /* @var $vh \Application\Entity\Db\VolumeHoraire */
            if (!count($vh->getValidation($this->getTypeValidation()))) {
                $this->volumesHorairesNonValides[] = $vh;
            }
            else {
                $this->volumesHorairesValides[] = $vh;
            }
        }

        if (!count($this->volumesHorairesValides)) {
            $this->setMessage(sprintf(
                    "Les enseignements de %s%s n'ont fait l'objet d'aucune validation.", 
                    $this->getIntervenant(),
                    $this->getStructure() ? sprintf(" au sein de la structure &laquo; %s &raquo;", $this->getStructure()) : null
            ));
            return false;
        }
        
        if (!$this->memePartiellement && count($this->volumesHorairesNonValides)) {
            $this->setMessage(sprintf(
                    "Tous les volumes horaires d'enseignement de %s%s n'ont pas été validés.",
                    $this->getIntervenant(),
                    $this->getStructure() ? sprintf(" au sein de la structure &laquo; %s &raquo;", $this->getStructure()) : null
            ));
            return false;
        }
        
        if (!count($this->volumesHorairesNonValides)) {
            $this->setMessage(sprintf(
                    "Tous les volumes horaires d'enseignement de %s%s ont été validés.",
                    $this->getIntervenant(),
                    $this->getStructure() ? sprintf(" au sein de la structure &laquo; %s &raquo;", $this->getStructure()) : null
            ));
        }
        else {
            $this->setMessage(sprintf(
                    "Les enseignements de %s%s ont été validés PARTIELLEMENT.",
                    $this->getIntervenant(),
                    $this->getStructure() ? sprintf(" au sein de la structure &laquo; %s &raquo;", $this->getStructure()) : null
            ));
        }
        
        return true;
    }
    
    public function isRelevant()
    {
        return !$this->getIntervenant()->getStatut()->estAutre();
    }
    
    private $servicesValides;
    
    /**
     * Retourne les services déjà validés.
     * 
     * @return array|null
     */
    public function getServicesValides()
    {
        if (null === $this->servicesValides) {
            $this->servicesValides = [];
            foreach ($this->getVolumesHorairesValides() as $vh) { /* @var $vh \Application\Entity\Db\VolumeHoraire */
                $this->servicesValides[$vh->getService()->getId()] = $vh->getService();
            }
        }
        return $this->servicesValides;
    }
    
    private $servicesNonValides;
    
    /**
     * Retourne les services non encore validés.
     * 
     * @return array|null
     */
    public function getServicesNonValides()
    {
        if (null === $this->servicesNonValides) {
            $this->servicesNonValides = [];
            foreach ($this->getVolumesHorairesNonValides() as $vh) { /* @var $vh \Application\Entity\Db\VolumeHoraire */
                $this->servicesNonValides[$vh->getService()->getId()] = $vh->getService();
            }
        }
        return $this->servicesNonValides;
    }
    
    private $volumesHorairesValides;
    
    /**
     * Retourne les volumes horaires déjà validés.
     * 
     * @return array|null
     */
    public function getVolumesHorairesValides()
    {
        return $this->volumesHorairesValides ?: [];
    }
    
    private $volumesHorairesNonValides;
    
    /**
     * Retourne les volumes horaires non encore validés.
     * 
     * @return array|null
     */
    public function getVolumesHorairesNonValides()
    {
        return $this->volumesHorairesNonValides ?: [];
    }


}
