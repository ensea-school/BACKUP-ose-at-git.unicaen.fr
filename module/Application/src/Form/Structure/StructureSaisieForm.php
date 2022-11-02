<?php

namespace Application\Form\Structure;

use Application\Entity\Db\Structure;
use Application\Form\AbstractForm;
use UnicaenImport\Service\Traits\SchemaServiceAwareTrait;
use Laminas\Form\FormInterface;

/**
 * Description of StructureSaisieForm
 *
 * @author ZVENIGOROSKY Alexandre <alexandre.zvenigorosky@unicaen.fr>
 */
class StructureSaisieForm extends AbstractForm
{
    use SchemaServiceAwareTrait;


    public function init()
    {
        $this->spec(Structure::class, ['adressePrecisions', 'adresseNumero', 'adresseNumeroCompl', 'adresseVoirie', 'adresseVoie', 'adresseLieuDit', 'adresseCodePostal', 'adresseCommune', 'adressePays']);
        $this->build();

        $this->get('libelleCourt')->setLabel('Libellé court');
        $this->get('libelleLong')->setLabel('Libellé long');
        $this->get('enseignement')->setLabel('Peut porter des enseignements');
        $this->get('affAdresseContrat')->setLabel('Affichage de l\'adresse sur le contrat de travail');

        $this->addSecurity();
        $this->addSubmit();

        return $this;
    }



    public function bind($object, $flags = FormInterface::VALUES_NORMALIZED)
    {
        /* @var $object Structure */
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