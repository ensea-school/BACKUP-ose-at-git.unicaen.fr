<?php

namespace Application\Form\Pays;

use Application\Entity\Db\Pays;
use Application\Form\AbstractForm;
use Application\Service\Traits\GroupeTypeFormationServiceAwareTrait;
use Application\Service\Traits\SourceServiceAwareTrait;
use UnicaenImport\Service\Traits\SchemaServiceAwareTrait;


/**
 * Description of PaysForm
 *
 * @author Florian JORIOT <florian.joriot at unicaen.fr>
 */
class PaysSaisieForm extends AbstractForm
{
    use SourceServiceAwareTrait;
    use GroupeTypeFormationServiceAwareTrait;
    use SchemaServiceAwareTrait;


    public function init()
    {
        $ignore = ["temoinUe"];
        $this->spec(Pays::class, $ignore);
        $this->build();

        $this->addSubmit();

        return $this;
    }
}