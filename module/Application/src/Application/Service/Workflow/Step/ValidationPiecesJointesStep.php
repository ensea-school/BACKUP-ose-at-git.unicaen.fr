<?php

namespace Application\Service\Workflow\Step;

use Application\Acl\ComposanteRole;
use Application\Acl\IntervenantRole;

/**
 *
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ValidationPiecesJointesStep extends Step
{
    public function __construct()
    {
        $labels = [
            IntervenantRole::ROLE_ID => "Je visualise la validation des pièces justificatives",
            ComposanteRole::ROLE_ID  => "Validation des pièces justificatives à fournir par l'intervenant",
            'default'                => "Je visualise la validation des pièces justificatives",
        ];
        $descriptions = [
            IntervenantRole::ROLE_ID => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis sed sem libero. Nam urna magna, fringilla et blandit aliquam, condimentum a velit. Vivamus sollicitudin blandit augue ut dapibus. Vivamus faucibus quis massa id tempus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis auctor suscipit mauris, in suscipit quam lacinia ut. Nam ac mollis enim, quis tincidunt sem.",
            ComposanteRole::ROLE_ID  => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis sed sem libero. Nam urna magna, fringilla et blandit aliquam, condimentum a velit. Vivamus sollicitudin blandit augue ut dapibus. Vivamus faucibus quis massa id tempus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis auctor suscipit mauris, in suscipit quam lacinia ut. Nam ac mollis enim, quis tincidunt sem.",
        ];

        $this
            ->setLabels($labels)
            ->setDescriptions($descriptions)
            ->setRoute('piece-jointe/intervenant/validation');
    }
}