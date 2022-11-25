<?php

namespace Application\Form\Employeur;

use Application\Entity\Db\Employeur;
use Application\Form\AbstractForm;
use Laminas\Form\FormInterface;
use UnicaenImport\Service\Traits\SchemaServiceAwareTrait;

class EmployeurSaisieForm extends AbstractForm
{
    use SchemaServiceAwareTrait;

    public function init()
    {
        $ignore = ["identifiantAssociation", "sourceCode", "source", "critereRecherche"];
        $this->spec(Employeur::class, $ignore);
        $this->build();
        $this->get('raisonSociale')->setLabel('Raison sociale');
        $this->get('nomCommercial')->setLabel('Nom commercial');
        $this->addSecurity();
        $this->addSubmit();

        return $this;
    }


    public function bind($object, $flags = FormInterface::VALUES_NORMALIZED)
    {
        /* @var $object Employeur */
        parent::bind($object, $flags);

        if ($object->getSource() && $object->getSource()->getImportable()) {
            foreach ($this->getElements() as $element) {
                if ($this->getServiceSchema()->isImportedProperty($object, $element->getName())) {
                    $element->setAttribute('readonly', true);
                }
            }
        }

        return $this;
    }
}
