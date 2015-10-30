<?php

namespace Application\Service\Workflow\Step;

use Application\Acl\IntervenantRole;
use Application\Entity\Db\Interfaces\TypeAgrementAwareInterface;
use Application\Entity\Db\Traits\TypeAgrementAwareTrait;
use Application\Acl\ComposanteRole;
use Application\Entity\Db\TypeAgrement;

/**
 * Description of AgrementStep
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class AgrementStep extends Step implements TypeAgrementAwareInterface
{
    use TypeAgrementAwareTrait;

    public function __construct()
    {
        $descriptions = [
            IntervenantRole::ROLE_ID => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis sed sem libero. Nam urna magna, fringilla et blandit aliquam, condimentum a velit. Vivamus sollicitudin blandit augue ut dapibus. Vivamus faucibus quis massa id tempus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis auctor suscipit mauris, in suscipit quam lacinia ut. Nam ac mollis enim, quis tincidunt sem.",
            ComposanteRole::ROLE_ID  => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis sed sem libero. Nam urna magna, fringilla et blandit aliquam, condimentum a velit. Vivamus sollicitudin blandit augue ut dapibus. Vivamus faucibus quis massa id tempus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis auctor suscipit mauris, in suscipit quam lacinia ut. Nam ac mollis enim, quis tincidunt sem.",
        ];

        $this
                ->setDescriptions($descriptions)
                ->setRoute('intervenant/agrement/liste');
    }

    /**
     * @return self
     */
    protected function init()
    {
        $this->setRouteParams(['typeAgrement' => $this->getTypeAgrement()->getId()]);

        $labels = [
            IntervenantRole::ROLE_ID => sprintf("Je visualise l'agrément '%s'", $this->getTypeAgrement()),
            ComposanteRole::ROLE_ID  => sprintf("Je visualise l'agrément '%s' de l'intervenant", $this->getTypeAgrement()),
            'default'                => sprintf("Je visualise l'agrément '%s'", $this->getTypeAgrement()),
        ];

        $this->setLabels($labels);

        return $this;
    }

    /**
     * Spécifie le type d'agrément concerné.
     *
     * @param TypeAgrement $typeAgrement type d'agrément concerné
     * @return $this
     */
    public function setTypeAgrement(TypeAgrement $typeAgrement = null)
    {
        $this->typeAgrement = $typeAgrement;

        $this->init();

        return $this;
    }
}