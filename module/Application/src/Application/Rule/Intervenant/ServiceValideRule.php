<?php

namespace Application\Rule\Intervenant;

/**
 * Recherche si les enseignements d'un intervenant au sein d'une structure ont été validés :
 * - soit complètement, autrement dit si tous les volumes horaires ont été validés ;
 * - soit partiellement, autrement si une partie seulement des volumes horaires ont été validés.
 * 
 * NB: Dans le cas d'une recherche partielle, 2 getters permettent de connaître les volumes horaires
 * déjà validés et ceux non encore validés.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ServiceValideRule extends IntervenantRule
{
    use \Application\Traits\TypeValidationAwareTrait;
    use \Application\Traits\StructureAwareTrait;
    use \Application\Service\Initializer\VolumeHoraireServiceAwareTrait;
    
    private $completement = false;
    
    /**
     * 
     * @param \Application\Entity\Db\Intervenant $intervenant
     * @param bool $completement
     */
    public function __construct(\Application\Entity\Db\Intervenant $intervenant, $completement = false)
    {
        parent::__construct($intervenant);
        $this->completement = $completement;
    }
    
    public function execute()
    {
        $validations = $this->getIntervenant()->getValidation($this->getTypeValidation());
        if (!count($validations)) {
            $this->setMessage(sprintf(
                    "Les enseignements de %s au sein de la structure '%s' n'ont fait l'objet d'aucune validation.", 
                    $this->getIntervenant(),
                    $this->getStructure()));
            return false;
        }
        
        // cas où l'on veut savoir si tous les volumes horaires sont validés
        if ($this->completement) {
            
            if (!$this->getServiceVolumeHoraire()) {
                throw new \Common\Exception\LogicException("Anomalie: aucun service VolumeHoraire spécifié.");
            }
            
            $qb = $this->getServiceVolumeHoraire()->finderByIntervenant($this->getIntervenant());
            $qb = $this->getServiceVolumeHoraire()->finderByStructureIntervention($this->getStructure(), $qb);
            
            $this->volumesHorairesNonValides = array();
            $this->volumesHorairesValides    = array();
            foreach ($qb->getQuery()->getResult() as $vh) {
                if (!count($vh->getValidation())) {
                    $this->volumesHorairesNonValides[] = $vh;
                }
                else {
                    $this->volumesHorairesValides[] = $vh;
                }
            }
//            var_dump($this->volumesHorairesNonValides);
//            var_dump($this->volumesHorairesValides);
            
            if (count($this->volumesHorairesNonValides)) {
                $this->setMessage(sprintf(
                        "Tous les volumes horaires d'enseignement de %s au sein de la structure '%s' n'ont pas été validés.",
                        $this->getIntervenant(),
                        $this->getStructure()));
                return false;
            }
        }
        
        return true;
    }
    
    public function isRelevant()
    {
        return !$this->getIntervenant()->getStatut()->estAutre();
    }
    
    private $volumesHorairesValides;
    
    /**
     * Dans le cas d'une recherche partielle, getter permettent de connaître les volumes horaires
     * déjà validés.
     * 
     * @return array|null
     */
    public function getVolumesHorairesValides()
    {
        return $this->volumesHorairesValides;
    }
    
    private $volumesHorairesNonValides;
    
    /**
     * Dans le cas d'une recherche partielle, getter permettent de connaître les volumes horaires
     * non encore validés.
     * 
     * @return array|null
     */
    public function getVolumesHorairesNonValides()
    {
        return $this->volumesHorairesNonValides;
    }


}
