<?php

namespace OffreFormation\Form\GroupeTypeFormation;

use Application\Form\AbstractForm;
use Application\Service\Traits\SourceServiceAwareTrait;
use Laminas\Form\FormInterface;
use OffreFormation\Entity\Db\GroupeTypeFormation;
use OffreFormation\Service\Traits\GroupeTypeFormationServiceAwareTrait;
use UnicaenImport\Service\Traits\SchemaServiceAwareTrait;


/**
 * Description of GroupeTypeFormationForm
 *
 * @author Florian JORIOT <florian.joriot at unicaen.fr>
 */
class GroupeTypeFormationSaisieForm extends AbstractForm
{
    use SourceServiceAwareTrait;
    use GroupeTypeFormationServiceAwareTrait;
    use SchemaServiceAwareTrait;


    public function init()
    {
        $ignore = ["ordre",
                   "source"];
        $this->spec(GroupeTypeFormation::class, $ignore);
        $this->build();

        $this->get('libelleCourt')->setLabel('Libellé court');
        $this->get('libelleLong')->setLabel('Libellé long');
        $this->get('pertinenceNiveau')->setLabel('Le niveau (1 pour L1, etc...) devra être précisé dans les formations correspondantes');

        $this->addSecurity();
        $this->addSubmit();

        return $this;
    }



    public function bind($object, $flags = FormInterface::VALUES_NORMALIZED)
    {
        /* @var $object GroupeTypeFormation */
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
