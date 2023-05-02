<?php

namespace Contrat\Form;

use Application\Filter\DateTimeFromString;
use Application\Form\AbstractForm;
use Contrat\Entity\Db\ContratAwareTrait;
use Laminas\Form\Element\Csrf;
use Laminas\Hydrator\HydratorInterface;
use Service\Entity\Db\CampagneSaisie;

/**
 * Formulaire de saisie de la date de retour du contrat/avenant signé.
 *
 */
class ContratRetourForm extends AbstractForm
{
    use ContratAwareTrait;

    public function init2()
    {
        $this->setHydrator(new ContratRetourFormHydrator());
        $this->setAttribute('method', 'POST');
        $this->setAttribute('action', $this->getCurrentUrl());

        $contratToString = lcfirst($this->getContrat()->toString(true, true));

        $this->add([
            'name'       => 'dateRetourSigne',
            'type'       => 'Date',
            'options'    => [
                'label' => "Date de retour $contratToString signé",
            ],
            'attributes' => [
            ],
        ]);

        $this->add(new Csrf('security'));

        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value' => "Enregistrer",
            ],
        ]);

        return $this;
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
            'valide'          => [
                'required' => false,
            ],
            'dateRetourSigne' => [
                'required' => false,
            ],
        ];
    }
}





class ContratRetourFormHydrator implements HydratorInterface
{

    /**
     * @param array          $data
     * @param CampagneSaisie $object
     *
     * @return CampagneSaisie
     */
    public function hydrate(array $data, $object)
    {
        $object->setDateRetourSigne(DateTimeFromString::run($data['dateRetourSigne'] ?? null));

        return $object;
    }



    /**
     * @param CampagneSaisie $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'dateRetourSigne' => $object->getDateRetourSigne(),
        ];

        return $data;
    }
}