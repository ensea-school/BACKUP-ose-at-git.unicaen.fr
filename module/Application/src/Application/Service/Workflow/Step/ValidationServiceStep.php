<?php

namespace Application\Service\Workflow\Step;

use Application\Acl\ComposanteRole;
use Application\Acl\IntervenantRole;

/**
 * Description of ValidationServiceStep
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ValidationServiceStep extends Step
{
    public function __construct()
    {
        $labels = [
            IntervenantRole::ROLE_ID => "Je visualise la validation de mes services prévisionnels",
            ComposanteRole::ROLE_ID  => "Je visualise la validation des services prévisionnels de l'intervenant",
            'default'                => "Je visualise la validation des enseignements prévisionnels",
        ];
        $descriptions = [
            IntervenantRole::ROLE_ID => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis sed sem libero. Nam urna magna, fringilla et blandit aliquam, condimentum a velit. Vivamus sollicitudin blandit augue ut dapibus. Vivamus faucibus quis massa id tempus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis auctor suscipit mauris, in suscipit quam lacinia ut. Nam ac mollis enim, quis tincidunt sem.",
            ComposanteRole::ROLE_ID  => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis sed sem libero. Nam urna magna, fringilla et blandit aliquam, condimentum a velit. Vivamus sollicitudin blandit augue ut dapibus. Vivamus faucibus quis massa id tempus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis auctor suscipit mauris, in suscipit quam lacinia ut. Nam ac mollis enim, quis tincidunt sem.",
        ];

        $this
            ->setLabels($labels)
            ->setDescriptions($descriptions)
            ->setRoute('intervenant/validation-service');
    }
}