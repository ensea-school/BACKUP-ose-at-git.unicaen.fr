<?php

namespace Application\Form\Service;

use Application\Entity\Db\Periode;
use Application\Entity\Db\Service;
use Application\Entity\Db\Traits\TypeVolumeHoraireAwareTrait;
use Application\Form\AbstractForm;
use Application\Form\Service\Traits\SaisieFieldsetAwareTrait;
use Application\Form\VolumeHoraire\Traits\SaisieMultipleFieldsetAwareTrait;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\PeriodeServiceAwareTrait;
use Application\Entity\Db\Etablissement;
use Zend\Form\Element\Hidden;
use Zend\Stdlib\Hydrator\HydratorInterface;


/**
 * Description of Saisie
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Saisie extends AbstractForm
{
    use TypeVolumeHoraireAwareTrait;
    use PeriodeServiceAwareTrait;
    use ContextServiceAwareTrait;
    use SaisieFieldsetAwareTrait;
    use SaisieMultipleFieldsetAwareTrait;



    /**
     * Retourne la liste des périodes d'enseignement
     *
     * @return Periode[]
     */
    public function getPeriodes()
    {
        return $this->getServicePeriode()->getEnseignement();
    }



    /**
     * Bind an object to the form
     *
     * Ensures the object is populated with validated values.
     *
     * @param  object $object
     * @param  int    $flags
     *
     * @return mixed|void
     */
    public function bind($object, $flags = \Zend\Form\FormInterface::VALUES_NORMALIZED)
    {
        if ($object instanceof Service && $object->getTypeVolumeHoraire()) {
            $this->get('type-volume-horaire')->setValue($object->getTypeVolumeHoraire()->getId());
        }
        if ($object instanceof Service && $object->getIntervenant()) {
            $this->get('intervenant')->setValue($object->getIntervenant()->getId());
        }

        return parent::bind($object, $flags);
    }



    public function init()
    {
        $this->setName('service')
            ->setAttribute('class', 'service-form');

        $hydrator = new SaisieHydrator();
        $hydrator->setServicePeriode($this->getServicePeriode());
        $this->setHydrator($hydrator);

        $this->add($this->getFieldsetServiceSaisie());

        // Product Fieldset
        if ($this->getServiceContext()->isModaliteServicesSemestriel($this->getTypeVolumeHoraire())){
            foreach ($this->getPeriodes() as $periode) {
                $pf = $this->getFieldsetVolumeHoraireSaisieMultiple();
                $pf->setName($periode->getCode());
                $this->add($pf);
            }
            $this->add(new Hidden('type-volume-horaire'));
        }
        $this->add(new Hidden('intervenant'));

        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value' => 'Enregistrer',
                'class' => 'btn btn-primary',
            ],
        ]);

        $this->setAttribute('action', $this->getCurrentUrl());
    }



    public function initFromContext()
    {
        $this->get('service')->initFromContext();
    }



    public function saveToContext()
    {
        $this->get('service')->saveToContext();
    }



    public function getInputFilterSpecification()
    {
        return [];
    }
}





/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class SaisieHydrator implements HydratorInterface
{
    use PeriodeServiceAwareTrait;



    /**
     * Retourne la liste des périodes d'enseignement
     *
     * @return Periode[]
     */
    public function getPeriodes()
    {
        $periodes = $this->getServicePeriode()->getEnseignement();

        return $periodes;
    }



    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array   $data
     * @param  Service $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $object = $data['service'];

        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param  Service $object
     *
     * @return array
     */
    public function extract($object)
    {
        $data            = [];
        $data['service'] = $object;
        foreach ($this->getPeriodes() as $periode) {
            $data[$periode->getCode()] = $object->getVolumeHoraireListe($periode);
        }

        return $data;
    }
}