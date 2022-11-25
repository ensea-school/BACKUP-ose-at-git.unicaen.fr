<?php

namespace Application\Form\Corps;

use Application\Entity\Db\Corps;
use Application\Form\AbstractForm;
use Laminas\Form\FormInterface;
use UnicaenImport\Service\Traits\SchemaServiceAwareTrait;


/**
 * Description of CorpsForm
 *
 * @author Florian JORIOT <florian.joriot at unicaen.fr>
 */
class CorpsSaisieForm extends AbstractForm
{
    use SchemaServiceAwareTrait;

    public function init()
    {
        $ignore = ["sourceCode", "source"];
        $this->spec(Corps::class, $ignore);
        $this->build();
        $this->get('libelleCourt')->setLabel('Libellé court');
        $this->get('libelleLong')->setLabel('Libellé long');

        $this->addSecurity();
        $this->addSubmit();

        return $this;
    }



    public function bind($object, $flags = FormInterface::VALUES_NORMALIZED)
    {
        /* @var $object Corps */
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
