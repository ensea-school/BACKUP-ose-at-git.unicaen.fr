<?php

namespace Application\Service\Workflow\Step;

use Application\Acl\IntervenantPermanentRole;
use Application\Acl\IntervenantExterieurRole;
use Application\Acl\ComposanteRole;

/**
 * Description of SaisieReferentielStep
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class SaisieReferentielStep extends Step
{
    public function __construct()
    {
        $labels = array(
            IntervenantPermanentRole::ROLE_ID => "Je visualise mon référentiel",
            IntervenantExterieurRole::ROLE_ID => "Je visualise mon référentiel",
            ComposanteRole::ROLE_ID           => "J'accède aux référentiel de l'intervenant",
            'default'                         => "Référentiel",
        );
        $descriptions = array(
            IntervenantPermanentRole::ROLE_ID => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis sed sem libero. Nam urna magna, fringilla et blandit aliquam, condimentum a velit. Vivamus sollicitudin blandit augue ut dapibus. Vivamus faucibus quis massa id tempus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis auctor suscipit mauris, in suscipit quam lacinia ut. Nam ac mollis enim, quis tincidunt sem.",
            IntervenantExterieurRole::ROLE_ID => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis sed sem libero. Nam urna magna, fringilla et blandit aliquam, condimentum a velit. Vivamus sollicitudin blandit augue ut dapibus. Vivamus faucibus quis massa id tempus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis auctor suscipit mauris, in suscipit quam lacinia ut. Nam ac mollis enim, quis tincidunt sem.",
            ComposanteRole::ROLE_ID           => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis sed sem libero. Nam urna magna, fringilla et blandit aliquam, condimentum a velit. Vivamus sollicitudin blandit augue ut dapibus. Vivamus faucibus quis massa id tempus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis auctor suscipit mauris, in suscipit quam lacinia ut. Nam ac mollis enim, quis tincidunt sem.",
        );
        
        $this
                
                ->setLabels($labels)
                ->setDescriptions($descriptions)
                ->setRoute('intervenant/services');
    }
}