<?php

namespace Lieu\Form;

use Application\Form\AbstractForm;
use Laminas\Form\FormInterface;
use Lieu\Entity\Db\AdresseNumeroCompl;
use Lieu\Entity\Db\Pays;
use Lieu\Entity\Db\Structure;
use Lieu\Entity\Db\Voirie;
use Lieu\Service\AdresseNumeroComplServiceAwareTrait;
use Paiement\Service\CentreCoutServiceAwareTrait;
use UnicaenImport\Service\Traits\SchemaServiceAwareTrait;

/**
 * Description of StructureSaisieForm
 *
 * @author ZVENIGOROSKY Alexandre <alexandre.zvenigorosky@unicaen.fr>
 */
class StructureSaisieForm extends AbstractForm
{
    use SchemaServiceAwareTrait;
    use CentreCoutServiceAwareTrait;

    public function init ()
    {
        $this->spec(Structure::class);
        $this->spec(['structure' => ['type' => \Lieu\Form\Element\Structure::class, 'input' => ['required' => false]]]);
        $this->spec(['adressePrecisions' => ['input' => ['required' => false]]]);
        $this->spec(['adresseNumero' => ['input' => ['required' => false]]]);
        $this->spec(['adresseNumeroCompl' => ['input' => ['required' => false]]]);
        $this->spec(['adresseVoirie' => ['input' => ['required' => false]]]);
        $this->spec(['adresseVoie' => ['input' => ['required' => false]]]);
        $this->spec(['adresseLieuDit' => ['input' => ['required' => false]]]);
        $this->spec(['adresseCodePostal' => ['input' => ['required' => false]]]);
        $this->spec(['adresseCommune' => ['input' => ['required' => false]]]);
        $this->spec(['adressePays' => ['input' => ['required' => false]]]);
        $this->spec(['centreCoutDefault' => ['type' => 'Select', 'input' => ['required' => false,],]]);

        $this->build();

        $this->setLabels([
            'structure'          => 'Structure parente',
            'libelleCourt'       => 'Libellé court',
            'libelleLong'        => 'Libellé long',
            'enseignement'       => 'Peut porter des enseignements',
            'affAdresseContrat'  => 'Affichage de l\'adresse sur le contrat de travail',
            'adressePrecisions'  => 'Précisions',
            'adresseNumero'      => 'N°',
            'adresseNumeroCompl' => 'Compl.',
            'adresseVoirie'      => 'Voirie',
            'adresseVoie'        => 'Voie',
            'adresseLieuDit'     => 'Lieu dit',
            'adresseCodePostal'  => 'Code postal',
            'adresseCommune'     => 'Commune',
            'adressePays'        => 'Pays',
            'centreCoutDefault'  => 'Centre de coût par défaut',
        ]);

        $this->get('structure')->setEmptyOption('- Structure racine -');

        $qb                  = $this->getServiceCentreCout()->finderByHistorique();
        $centresCouts        = $this->getServiceCentreCout()->getList($qb);
        $centresCoutsOrdered = $this->getServiceCentreCout()->formatCentresCouts($centresCouts);

        $this->get('centreCoutDefault')->setValueOptions(['' => '(Sélectionnez un centre de coût)'] + $centresCoutsOrdered);


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



    public function bind ($object, $flags = FormInterface::VALUES_NORMALIZED)
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