<?php

namespace Application\Form\Departement;

use Application\Entity\Db\Departement;
use Application\Form\AbstractForm;
use Application\Service\Traits\GroupeTypeFormationServiceAwareTrait;
use Application\Service\Traits\SourceServiceAwareTrait;
use UnicaenImport\Service\Traits\SchemaServiceAwareTrait;


/**
 * Description of DepartementForm
 *
 * @author Florian JORIOT <florian.joriot at unicaen.fr>
 */
class DepartementSaisieForm extends AbstractForm
{
    use SourceServiceAwareTrait;
    use GroupeTypeFormationServiceAwareTrait;
    use SchemaServiceAwareTrait;


    public function init()
    {
        $this->spec(Departement::class);
        $this->build();

        $this->addSubmit();

        return $this;
    }
}