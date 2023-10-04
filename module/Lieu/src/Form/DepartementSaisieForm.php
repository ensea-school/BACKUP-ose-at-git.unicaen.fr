<?php

namespace Lieu\Form;

use Application\Form\AbstractForm;
use Lieu\Entity\Db\Departement;


/**
 * Description of DepartementForm
 *
 * @author Florian JORIOT <florian.joriot at unicaen.fr>
 */
class DepartementSaisieForm extends AbstractForm
{
    public function init()
    {
        $ignore = ["sourceCode", "source"];
        $this->spec(Departement::class, $ignore);
        $this->build();

        $this->addSecurity();
        $this->addSubmit();

        return $this;
    }
}