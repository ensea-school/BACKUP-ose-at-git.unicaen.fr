<?php

namespace Application\Form\Paiement;

use Application\Service\Traits\ContextAwareTrait;
use Application\Service\Traits\PeriodeAwareTrait;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

/**
 * Description of MiseEnPaiementForm
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class MiseEnPaiementForm extends Form implements InputFilterProviderInterface, ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;
    use ContextAwareTrait;
    use PeriodeAwareTrait;



    /**
     *
     */
    public function init()
    {
        $url = $this->getServiceLocator()->getServiceLocator()->get('viewhelpermanager')->get('url');
        /* @var $url \Zend\View\Helper\Url */

        $annee = $this->getServiceContext()->getAnnee();

        $periodes            = $this->getServicePeriode()->getList($this->getServicePeriode()->finderByPaiement(true));
        $datesMiseEnPaiement = [];
        foreach ($periodes as $periode) {
            $datesMiseEnPaiement[$periode->getId()] = $periode->getDatePaiement($annee)->format('d/m/Y');
        }


        $this->setAttribute('method', 'post')
            ->setAttribute('class', 'paiement-mise-en-paiement-form')
            ->setAttribute('data-dates-mise-en-paiement', json_encode($datesMiseEnPaiement))
            ->setAttribute('data-periode-paiement-tardif-id', $this->getServicePeriode()->getPaiementTardif()->getId());


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
            'type'       => 'UnicaenApp\Form\Element\Date',
            'name'       => 'date-mise-en-paiement',
            'options'    => [
                'label'  => 'Date de mise en paiement',
                'format' => 'd/m/Y',
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

        $this->setAttribute('action', $url(null, [], [], true));
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
            'periode'               => [
                'required' => true,
            ],
            'date-mise-en-paiement' => [
                'required' => false,
            ],
        ];
    }

}