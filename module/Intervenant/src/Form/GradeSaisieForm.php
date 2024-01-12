<?php

namespace Intervenant\Form;

use Application\Form\AbstractForm;
use Intervenant\Entity\Db\Corps;
use Intervenant\Entity\Db\Grade;
use Laminas\Form\FormInterface;
use UnicaenImport\Service\Traits\SchemaServiceAwareTrait;


/**
 * Description of GradeForm
 *
 * @author Florian JORIOT <florian.joriot at unicaen.fr>
 */
class GradeSaisieForm extends AbstractForm
{
    use SchemaServiceAwareTrait;

    public function init()
    {
        $ignore = ["sourceCode", "source"];
        $this->spec(Grade::class, $ignore);
        $this->build();
        $this->get('libelleCourt')->setLabel('Libellé court');
        $this->get('libelleLong')->setLabel('Libellé long');

        $this->setValueOptions('corps', 'SELECT c FROM ' . Corps::class . ' c WHERE c.histoDestruction IS NULL');

        $this->addSecurity();
        $this->addSubmit();

        return $this;
    }



    public function bind($object, $flags = FormInterface::VALUES_NORMALIZED)
    {
        /* @var $object Grade */
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