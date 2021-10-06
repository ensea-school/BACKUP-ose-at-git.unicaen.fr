<?php

namespace Plafond\Form;

use Application\Entity\Db\Annee;
use Application\Form\AbstractForm;
use Application\Service\Traits\AnneeServiceAwareTrait;
use Application\Service\Traits\ContextServiceAwareTrait;
use Plafond\Entity\Db\Plafond;
use Plafond\Entity\Db\PlafondPerimetre;
use Plafond\Service\PlafondServiceAwareTrait;
use UnicaenApp\Util;


/**
 * Description of PlafondStructureForm
 *
 * @author UnicaenCode
 */
class PlafondStructureForm extends AbstractForm
{
    use PlafondServiceAwareTrait;
    use AnneeServiceAwareTrait;
    use ContextServiceAwareTrait;

    protected $hydratorElements = [
        'id'         => ['type' => 'int'],
        'plafond'    => ['type' => Plafond::class],
        'anneeDebut' => ['type' => Annee::class],
        'anneeFin'   => ['type' => Annee::class],
        'heures'     => ['type' => 'float'],
    ];



    public function init()
    {
        $this->setAttribute('action', $this->getCurrentUrl());
        $this->useGenericHydrator($this->hydratorElements);

        $this->add([
            'type'       => 'Select',
            'name'       => 'plafond',
            'options'    => [
                'label'         => 'Plafond',
                'value_options' => Util::collectionAsOptions($this->getPlafonds()),
            ],
            'attributes' => [
                'class'            => 'selectpicker',
                'data-live-search' => 'true',
            ],
        ]);

        $this->add([
            'type'       => 'Select',
            'name'       => 'anneeDebut',
            'options'    => [
                'label'         => 'Année de début',
                'value_options' => Util::collectionAsOptions($this->getAnnees()),
                'empty_option'  => 'Depuis toujours',
            ],
            'attributes' => [
                'class'            => 'selectpicker',
                'data-live-search' => 'true',
            ],
        ]);

        $this->add([
            'type'       => 'Select',
            'name'       => 'anneeFin',
            'options'    => [
                'label'         => 'Année de fin',
                'value_options' => Util::collectionAsOptions($this->getAnnees()),
                'empty_option'  => 'Sans fin',
            ],
            'attributes' => [
                'class'            => 'selectpicker',
                'data-live-search' => 'true',
            ],
        ]);

        $this->add([
            'name'       => 'heures',
            'type'       => 'Text',
            'options'    => [
                'label' => "Heures",
            ],
            'attributes' => [
                'title' => "Nombre d'heures",
            ],
        ]);

        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value' => 'Enregistrer',
                'class' => 'btn btn-primary',
            ],
        ]);
    }



    /**
     * @return Plafond[]
     */
    protected function getPlafonds(): array
    {
        $plafonds = $this->getServicePlafond()->getList(
            $this->getServicePlafond()->finderByPlafondPerimetre($this->getServicePlafond()->getPerimetre(PlafondPerimetre::STRUCTURE))
        );

        return $plafonds;
    }



    private function getAnnees($min = null, $max = null)
    {
        $annee = $this->getServiceContext()->getAnnee()->getId();
        $as    = $this->getServiceAnnee()->getList();

        $annees = [];
        foreach ($as as $ak => $av) {
            if ($ak >= $annee - 10 && $ak <= $annee + 10) {
                if ((!$min || $ak > $min->getId()) && (!$max || $ak < $max->getId())) {
                    $annees[$ak] = $av->getLibelle();
                }
            }
        }

        return $annees;
    }



    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [
            'plafond'    => ['required' => true],
            'anneeDebut' => ['required' => false],
            'anneeFin'   => ['required' => false],
            'heures'     => ['required' => true],
        ];
    }

}