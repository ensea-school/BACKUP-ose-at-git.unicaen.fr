<?php

namespace Application\Form\Departement;

use Application\Entity\Db\Departement;
use Application\Form\AbstractForm;


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
        $this->addSubmit();

        return $this;
    }
}