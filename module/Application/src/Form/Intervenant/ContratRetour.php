<?php

namespace Application\Form\Intervenant;

use Application\Constants;
use Application\Entity\Db\Traits\ContratAwareTrait;
use Application\Form\AbstractForm;
use Laminas\Hydrator\HydratorInterface;
use UnicaenApp\Hydrator\Strategy\DateStrategy;
use Laminas\Form\Element\Csrf;
use Laminas\Hydrator\ClassMethodsHydrator;

/**
 * Formulaire de saisie de la date de retour du contrat/avenant signé.
 *
 */
class ContratRetour extends AbstractForm
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
            'type'       => 'DateTime',
            'options'    => [
                'label'  => "Date de retour $contratToString signé",
                'format' => Constants::DATE_FORMAT,
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
            'valide' => [
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
        $object->setDateRetourSigne($data['dateRetourSigne'] ? \DateTime::createFromFormat(Constants::DATE_FORMAT, $data['dateRetourSigne']) : null);

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
            'dateRetourSigne' => $object->getDateRetourSigne() ? $object->getDateRetourSigne()->format(Constants::DATE_FORMAT) : null,
        ];

        return $data;
    }
}