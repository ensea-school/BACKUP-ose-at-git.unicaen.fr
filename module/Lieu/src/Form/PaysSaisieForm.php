<?php

namespace Lieu\Form;

use Application\Form\AbstractForm;
use Application\Service\Traits\SourceServiceAwareTrait;
use Lieu\Entity\Db\Pays;
use UnicaenImport\Service\Traits\SchemaServiceAwareTrait;


/**
 * Description of PaysForm
 *
 * @author Florian JORIOT <florian.joriot at unicaen.fr>
 */
class PaysSaisieForm extends AbstractForm
{
    use SourceServiceAwareTrait;
    use SchemaServiceAwareTrait;


    public function init()
    {
        $ignore = ["temoinUe"];
        $this->spec(Pays::class, $ignore);
        $this->build();

        $this->addSecurity();
        $this->addSubmit();

        return $this;
    }
}