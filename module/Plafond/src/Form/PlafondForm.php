<?php

namespace Plafond\Form;

use Application\Form\AbstractForm;
use Application\Hydrator\GenericHydrator;
use Plafond\Entity\Db\PlafondPerimetre;
use Plafond\Service\PlafondServiceAwareTrait;
use Laminas\InputFilter\InputFilterProviderInterface;


/**
 * Description of PlafondForm
 *
 * @author UnicaenCode
 */
class PlafondForm extends AbstractForm implements InputFilterProviderInterface
{
    use PlafondServiceAwareTrait;

    protected $hydratorElements = [
        'id'               => ['type' => 'int'],
        'code'             => ['type' => 'string'],
        'libelle'          => ['type' => 'string'],
        'plafondPerimetre' => ['type' => PlafondPerimetre::class],
        'requete'          => ['type' => 'string'],
    ];



    public function init()
    {
        $hydrator = new PlafondFormHydrator($this->getServicePlafond()->getEntityManager(), $this->hydratorElements);
        $this->setHydrator($hydrator);

        $this->setAttributes(['action' => $this->getCurrentUrl(), 'class' => 'plafond-form']);

        $this->add([
            'name'    => 'code',
            'options' => [
                'label' => 'Code',
            ],
            'type'    => 'Text',
        ]);

        $this->add([
            'name'    => 'libelle',
            'options' => [
                'label' => 'Libellé',
            ],
            'type'    => 'Text',
        ]);

        $this->add([
            'name'       => 'plafondPerimetre',
            'options'    => [
                'label'         => 'Périmètre',
                'value_options' => \UnicaenApp\Util::collectionAsOptions($this->getServicePlafond()->getPerimetres()),
                'empty_option'  => 'Sélectionner un périmètre...',
            ],
            'attributes' => [
                'class'            => 'selectpicker',
                'data-live-search' => 'true',
            ],
            'type'       => 'Select',
        ]);

        $this->add([
            'type'       => 'Textarea',
            'name'       => 'requete',
            'options'    => [
                'label' => "Requête SQL",
            ],
            'attributes' => [
                'id'   => 'requete',
                'rows' => '20',
            ],
        ]);

        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value' => 'Enregistrer',
                'class' => 'btn btn-primary btn-save',
            ],
        ]);
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
            'code'             => [
                'required' => true,
            ],
            'libelle'          => [
                'required' => true,
            ],
            'plafondPerimetre' => [
                'required' => true,
            ],
            'requete'          => [
                'required' => true,
            ],
        ];
    }
}





class PlafondFormHydrator extends GenericHydrator
{
    protected $noGenericParse = [];



    /**
     * @param array                              $data
     * @param \Application\Entity\Db\Intervenant $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        parent::hydrate($data, $object);
    }



    /**
     * @param \Application\Entity\Db\Intervenant $object
     *
     * @return array
     */
    public function extract($object)
    {
        $res = parent::extract($object);

        return $res;
    }
}