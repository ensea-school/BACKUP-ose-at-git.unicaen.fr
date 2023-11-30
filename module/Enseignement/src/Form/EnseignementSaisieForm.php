<?php

namespace Enseignement\Form;

use Application\Entity\Db\Periode;
use Application\Entity\Db\Traits\IntervenantAwareTrait;
use Application\Form\AbstractForm;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\LocalContextServiceAwareTrait;
use Enseignement\Entity\Db\Service;
use Laminas\Form\Element\Hidden;
use Laminas\Form\FormInterface;
use Laminas\Hydrator\HydratorInterface;
use Application\Service\Traits\PeriodeServiceAwareTrait;
use Service\Entity\Db\TypeVolumeHoraireAwareTrait;


/**
 * Description of EnseignementSaisieForm
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class EnseignementSaisieForm extends AbstractForm
{
    use TypeVolumeHoraireAwareTrait;
    use PeriodeServiceAwareTrait;
    use ContextServiceAwareTrait;
    use LocalContextServiceAwareTrait;
    use EnseignementSaisieFieldsetAwareTrait;
    use VolumeHoraireSaisieMultipleFieldsetAwareTrait;
    use IntervenantAwareTrait;


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
     * @param object $object
     * @param int    $flags
     *
     * @return mixed|void
     */
    public function bind($object, $flags = FormInterface::VALUES_NORMALIZED)
    {
        if ($object instanceof Service && $object->getIntervenant()) {
            $this->get('intervenant')->setValue($object->getIntervenant()->getId());
        }

        return parent::bind($object, $flags);
    }



    public function init()
    {
        $this->setName('service')
            ->setAttribute('class', 'service-form');

        $hydrator = new EnseignementSaisieFormHydrator();
        $hydrator->setServicePeriode($this->getServicePeriode());
        $this->setHydrator($hydrator);

        $this->add($this->getFieldsetEnseignementSaisie());


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



    public function initPeriodes()
    {
        // Product Fieldset
        if ($this->getIntervenant()) {
            if ($this->getIntervenant()->getStatut()->isModeEnseignementSemestriel($this->getTypeVolumeHoraire())) {
                foreach ($this->getPeriodes() as $periode) {
                    $pf = $this->getFieldsetVolumeHoraireSaisieMultiple();
                    $pf->setName($periode->getCode());
                    $this->add($pf);
                }
            }
        }else{
            foreach ($this->getPeriodes() as $periode) {
                $pf = $this->getFieldsetVolumeHoraireSaisieMultiple();
                $pf->setName($periode->getCode());
                $this->add($pf);
            }
        }
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
class EnseignementSaisieFormHydrator implements HydratorInterface
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
     * @param array   $data
     * @param Service $object
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
     * @param Service $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $data            = [];
        $data['service'] = $object;
        foreach ($this->getPeriodes() as $periode) {
            $data[$periode->getCode()] = $object->getVolumeHoraireListe($periode);
        }

        return $data;
    }
}