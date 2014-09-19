<?php

namespace Application\Service\Workflow\Step;

use Application\Acl\IntervenantPermanentRole;
use Application\Acl\IntervenantExterieurRole;
use Application\Acl\ComposanteRole;
use Application\Entity\Db\TypeAgrement;

/**
 * Description of AgrementStep
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class AgrementStep extends Step
{
    /**
     * @var TypeAgrement
     */
    private $typeAgrement;
    
    public function __construct(TypeAgrement $typeAgrement)
    {
        $this->typeAgrement = $typeAgrement;
        
        $this->setRouteParams(array('typeAgrement' => $this->typeAgrement->getId()));
        
        $labels = array(
            IntervenantPermanentRole::ROLE_ID => sprintf("Je visualise l'agrément &laquo; %s &raquo;", $this->typeAgrement),
            IntervenantExterieurRole::ROLE_ID => sprintf("Je visualise l'agrément &laquo; %s &raquo;", $this->typeAgrement),
            ComposanteRole::ROLE_ID           => sprintf("Je visualise l'agrément &laquo; %s &raquo; de l'intervenant", $this->typeAgrement),
            'default'                         => sprintf("Je visualise l'agrément &laquo; %s &raquo; de l'intervenant", $this->typeAgrement),
        );
        $descriptions = array(
            IntervenantPermanentRole::ROLE_ID => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis sed sem libero. Nam urna magna, fringilla et blandit aliquam, condimentum a velit. Vivamus sollicitudin blandit augue ut dapibus. Vivamus faucibus quis massa id tempus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis auctor suscipit mauris, in suscipit quam lacinia ut. Nam ac mollis enim, quis tincidunt sem.",
            IntervenantExterieurRole::ROLE_ID => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis sed sem libero. Nam urna magna, fringilla et blandit aliquam, condimentum a velit. Vivamus sollicitudin blandit augue ut dapibus. Vivamus faucibus quis massa id tempus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis auctor suscipit mauris, in suscipit quam lacinia ut. Nam ac mollis enim, quis tincidunt sem.",
            ComposanteRole::ROLE_ID           => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis sed sem libero. Nam urna magna, fringilla et blandit aliquam, condimentum a velit. Vivamus sollicitudin blandit augue ut dapibus. Vivamus faucibus quis massa id tempus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis auctor suscipit mauris, in suscipit quam lacinia ut. Nam ac mollis enim, quis tincidunt sem.",
        );
        
        $this
                
                ->setLabels($labels)
                ->setDescriptions($descriptions)
                ->setRoute('intervenant/agrement/liste');
    }
    
    /**
     * @return TypeAgrement
     */
    public function getTypeAgrement()
    {
        return $this->typeAgrement;
    }
}