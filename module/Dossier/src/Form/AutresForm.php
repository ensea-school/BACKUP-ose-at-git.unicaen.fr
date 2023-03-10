<?php

namespace Dossier\Form;

use Application\Form\AbstractForm;
use Dossier\Hydrator\DossierAutreHydrator;
use Dossier\Service\Traits\DossierAutreTypeServiceAwareTrait;
use Laminas\Form\Element\Csrf;


/**
 * Description of AutresForm
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
class AutresForm extends AbstractForm
{

    use DossierAutreTypeServiceAwareTrait;

    public function init()
    {

        $hydrator = new DossierAutreHydrator();
        $this->setHydrator($hydrator);


        $this->setAttributes([
            'action' => $this->getCurrentUrl(),
            'class'  => 'autres-saisir',
        ]);


        $this->add([
            'name'    => 'libelle',
            'options' => [
                'label' => "Libellé",
            ],
            'type'    => 'Text',
        ]);

        $this->add([
            'name'    => 'description',
            'options' => [
                'label' => "Description du champs",
            ],
            'type'    => 'Textarea',
        ]);

        $this->add([
            'name' => 'type',
            'type' => 'Select',
        ]);
        $this->get('type')
            ->setValueOptions(['' => '(Sélectionnez un type de champs...)'] + \UnicaenApp\Util::collectionAsOptions($this->getServiceDossierAutreType()->getList()));

        $this->add([
            'name'    => 'obligatoire',
            'options' => [
                'label' => 'Champs obligatoire',
            ],
            'type'    => 'Checkbox',
        ]);

        $this->add([
            'type'       => 'Textarea',
            'name'       => 'json-value',
            'options'    => [
                'label' => "Liste des choix possibles",
            ],
            'attributes' => [
                'id'   => 'json-value',
                'rows' => '20',
            ],
        ]);

        $this->add([
            'type'       => 'Textarea',
            'name'       => 'sql-value',
            'options'    => [
                'label' => "Liste des choix possibles",
            ],
            'attributes' => [
                'id'   => 'sql-value',
                'rows' => '20',
            ],
        ]);


        $this->add([
            'name' => 'id',
            'type' => 'Hidden',
        ]);

        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value' => 'Enregistrer',
                'class' => 'btn btn-primary',
                'id'    => 'btn-save',
            ],
        ]);

        $this->add(new Csrf('security'));
    }



    public function getInputFilterSpecification()
    {
        return [];
    }
}

