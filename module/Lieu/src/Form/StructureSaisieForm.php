<?php

namespace Lieu\Form;

use Paiement\Entity\Db\DomaineFonctionnel;
use Application\Form\AbstractForm;
use Laminas\Form\FormInterface;
use Lieu\Entity\Db\AdresseNumeroCompl;
use Lieu\Entity\Db\Pays;
use Lieu\Entity\Db\Structure;
use Lieu\Entity\Db\StructureAwareTrait;
use Lieu\Entity\Db\Voirie;
use Lieu\Service\StructureServiceAwareTrait;
use Paiement\Service\CentreCoutServiceAwareTrait;
use Paiement\Service\DomaineFonctionnelServiceAwareTrait;
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
    use DomaineFonctionnelServiceAwareTrait;
    use StructureServiceAwareTrait;
    use StructureAwareTrait;


    public function init()
    {
        $ignore = ['autre', 'autre1', 'autre2', 'autre3', 'autre4', 'autre5','source'];

        $this->spec(Structure::class, $ignore);
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
        $this->spec(['domaineFonctionnelDefault' => ['type' => 'Select', 'input' => ['required' => false,],]]);

        $this->build();


        $this->setLabels([
            'structure'                 => 'Structure parente',
            'libelleCourt'              => 'Libellé court',
            'libelleLong'               => 'Libellé long',
            'enseignement'              => 'Peut porter des enseignements',
            'affAdresseContrat'         => 'Affichage de l\'adresse sur le contrat de travail',
            'adressePrecisions'         => 'Précisions',
            'adresseNumero'             => 'N°',
            'adresseNumeroCompl'        => 'Compl.',
            'adresseVoirie'             => 'Voirie',
            'adresseVoie'               => 'Voie',
            'adresseLieuDit'            => 'Lieu dit',
            'adresseCodePostal'         => 'Code postal',
            'adresseCommune'            => 'Commune',
            'adressePays'               => 'Pays',
            'centreCoutDefault'         => 'Centre de coût par défaut',
            'domaineFonctionnelDefault' => 'Domaine fonctionnel par défaut',
        ]);

        $this->get('structure')->setEmptyOption('- Structure racine -');


        $this->setValueOptions('domaineFonctionnelDefault', 'SELECT df FROM ' . DomaineFonctionnel::class . ' df WHERE df.histoDestruction IS NULL ORDER BY df.libelle');
        $this->get('domaineFonctionnelDefault')->setEmptyOption('(Sélectionnez un domaine fonctionnel)');

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



    public function initCentreCout()
    {
        $qb = $this->getServiceCentreCout()->finderByHistorique();
        //Si on a une structure précise on filtre les centres de coût disponibles pour cette structure
        if ($this->structure && $this->structure->getCode()) {
            $qb = $this->getServiceCentreCout()->finderByStructure($this->structure, $qb);
        }
        $centresCouts        = $this->getServiceCentreCout()->getList($qb);
        $centresCoutsOrdered = $this->getServiceCentreCout()->formatCentresCouts($centresCouts);

        $this->get('centreCoutDefault')->setValueOptions(['' => '(Sélectionnez un centre de coût)'] + $centresCoutsOrdered);
    }


    public function excludeStructure(): void
    {
        $structure = $this->getStructure();

        if (!$structure) {
            return;
        }

        $structureElement = $this->get('structure');
        $structureOptions = $structureElement->getValueOptions();
        unset($structureOptions[$structure->getId()]);
        $structureElement->setValueOptions($structureOptions);

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
