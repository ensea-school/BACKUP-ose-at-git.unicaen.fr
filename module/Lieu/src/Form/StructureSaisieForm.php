<?php

namespace Lieu\Form;

use Application\Form\AbstractForm;
use Laminas\Form\FormInterface;
use Lieu\Entity\Db\AdresseNumeroCompl;
use Lieu\Entity\Db\Pays;
use Lieu\Entity\Db\Structure;
use Lieu\Entity\Db\Voirie;
use Lieu\Service\AdresseNumeroComplServiceAwareTrait;
use UnicaenImport\Service\Traits\SchemaServiceAwareTrait;

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
        $this->spec(Structure::class);
        $this->spec(['structure' => ['input' => ['required' => false]]]);
        $this->spec(['adressePrecisions' => ['input' => ['required' => false]]]);
        $this->spec(['adresseNumero' => ['input' => ['required' => false]]]);
        $this->spec(['adresseNumeroCompl' => ['input' => ['required' => false]]]);
        $this->spec(['adresseVoirie' => ['input' => ['required' => false]]]);
        $this->spec(['adresseVoie' => ['input' => ['required' => false]]]);
        $this->spec(['adresseLieuDit' => ['input' => ['required' => false]]]);
        $this->spec(['adresseCodePostal' => ['input' => ['required' => false]]]);
        $this->spec(['adresseCommune' => ['input' => ['required' => false]]]);
        $this->spec(['adressePays' => ['input' => ['required' => false]]]);
        $this->build();

        $this->setLabels([
            'structure'         => 'Structure parente',
            'libelleCourt'      => 'Libellé court',
            'libelleLong'       => 'Libellé long',
            'enseignement'      => 'Peut porter des enseignements',
            'affAdresseContrat' => 'Affichage de l\'adresse sur le contrat de travail',

            'adressePrecisions'  => 'Précisions',
            'adresseNumero'      => 'N°',
            'adresseNumeroCompl' => 'Compl.',
            'adresseVoirie'      => 'Voirie',
            'adresseVoie'        => 'Voie',
            'adresseLieuDit'     => 'Lieu dit',
            'adresseCodePostal'  => 'Code postal',
            'adresseCommune'     => 'Commune',
            'adressePays'        => 'Pays',
        ]);

        $this->setValueOptions('structure', 'SELECT str FROM ' . Structure::class . ' str WHERE str.histoDestruction IS NULL ORDER BY str.libelleCourt');
        $this->get('structure')->setEmptyOption('- Structure racine -');

        $this->setValueOptions('adresseNumeroCompl', 'SELECT anc FROM ' . AdresseNumeroCompl::class . ' anc ORDER BY anc.id');
        $this->get('adresseNumeroCompl')->setEmptyOption('');


        $this->setValueOptions('adresseVoirie', 'SELECT v FROM ' . Voirie::class . ' v WHERE v.histoDestruction IS NULL ORDER BY v.libelle');
        $this->get('adresseVoirie')->setEmptyOption('');

        $this->setValueOptions('adressePays', 'SELECT p FROM ' . Pays::class . ' p WHERE p.histoDestruction IS NULL ORDER BY p.libelle');
        $this->get('adressePays')->setEmptyOption('');

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