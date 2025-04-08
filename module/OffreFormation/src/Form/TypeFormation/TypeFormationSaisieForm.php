<?php

namespace OffreFormation\Form\TypeFormation;

use Application\Form\AbstractForm;
use Application\Service\Traits\SourceServiceAwareTrait;
use Laminas\Form\FormInterface;
use OffreFormation\Entity\Db\TypeFormation;
use OffreFormation\Service\Traits\GroupeTypeFormationServiceAwareTrait;
use UnicaenImport\Service\Traits\SchemaServiceAwareTrait;


/**
 * Description of TypeFormationForm
 *
 * @author Florian JORIOT <florian.joriot at unicaen.fr>
 */
class TypeFormationSaisieForm extends AbstractForm
{
    use SourceServiceAwareTrait;
    use GroupeTypeFormationServiceAwareTrait;
    use SchemaServiceAwareTrait;


    public function init ()
    {
        $ignore = ['autre', 'autre1', 'autre2', 'autre3', 'autre4', 'autre5','source'];
        $this->spec(TypeFormation::class, $ignore);
        $this->build();

        $this->get('libelleCourt')->setLabel('Libellé court');
        $this->get('libelleLong')->setLabel('Libellé long');
        $this->setValueOptions('groupe', $this->getServiceGroupeTypeFormation()->getList());

        $this->addSecurity();
        $this->addSubmit();

        return $this;
    }



    public function bind ($object, $flags = FormInterface::VALUES_NORMALIZED)
    {
        /* @var $object TypeFormation */
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
