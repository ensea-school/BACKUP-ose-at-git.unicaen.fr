<?php

namespace Paiement\Form\Paiement;

use Application\Form\AbstractForm;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\PeriodeServiceAwareTrait;


/**
 * Description of MiseEnPaiementForm
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class MiseEnPaiementForm extends AbstractForm
{
    use ContextServiceAwareTrait;
    use PeriodeServiceAwareTrait;


    /**
     *
     */
    public function init()
    {
        $annee = $this->getServiceContext()->getAnnee();

        $periodes            = $this->getServicePeriode()->getList($this->getServicePeriode()->finderByPaiement(true));
        $datesMiseEnPaiement = [];
        foreach ($periodes as $periode) {
            $datesMiseEnPaiement[$periode->getId()] = $periode->getDatePaiement($annee)->format('d/m/Y');
        }

        $paiementTardif = $this->getServicePeriode()->getPaiementTardif();
        $this->setAttribute('method', 'post')
            ->setAttribute('class', 'paiement-mise-en-paiement-form')
            ->setAttribute('data-dates-mise-en-paiement', json_encode($datesMiseEnPaiement))
            ->setAttribute('data-periode-paiement-tardif-id', ($paiementTardif ? $paiementTardif->getId() : null));


        $defaultPeriode = $this->getServicePeriode()->getPeriodePaiement();
        $this->add([
            'type'       => 'Select',
            'name'       => 'periode',
            'options'    => [
                'label'         => 'Période',
                'value_options' => \UnicaenApp\Util::collectionAsOptions($periodes, false, function ($p) use ($annee) {
                    return $p->getLibelleAnnuel($annee);
                }),
            ],
            'attributes' => [
                'value' => $defaultPeriode ? $defaultPeriode->getId() : null,
            ],
        ]);

        $defaultDateMiseEnPaiement = $defaultPeriode ? $defaultPeriode->getDatePaiement($annee) : null;
        $this->add([
            'type'       => 'Date',
            'name'       => 'date-mise-en-paiement',
            'options'    => [
                'label'  => 'Date de mise en paiement',
            ],
            'attributes' => [
                'step'     => '1',
                'disabled' => 'true',
                'value'    => $defaultPeriode ? $defaultDateMiseEnPaiement->format('d/m/Y') : null,
            ],
        ]);

        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value' => 'Effectuer la mise en paiement',
                'class' => 'btn btn-primary',
            ],
        ]);

        $this->setAttribute('action', $this->getCurrentUrl());
    }



    /**
     * Should return an array specification compatible with
     * {@link Laminas\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [
            'periode'               => [
                'required' => true,
            ],
            'date-mise-en-paiement' => [
                'required' => false,
            ],
        ];
    }

}