<?php

namespace Intervenant\Form;

use Application\Form\AbstractForm;
use Intervenant\Entity\Db\Statut;
use Application\Service\Traits\ParametresServiceAwareTrait;
use Application\Service\Traits\TypeAgrementServiceAwareTrait;
use Application\Service\Traits\TypeIntervenantServiceAwareTrait;

/**
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class StatutSaisieForm extends AbstractForm
{
    use TypeIntervenantServiceAwareTrait;
    use TypeAgrementServiceAwareTrait;
    use ParametresServiceAwareTrait;

    public function init()
    {

        $this->setAttribute('action', $this->getCurrentUrl());

        $this->spec(Statut::class);
        $this->specBuild();

        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value' => "Enregistrer",
                'class' => 'btn btn-primary',
            ],
        ]);

        // peuplement liste des types d'intervenants
        $this->get('typeIntervenant')
            ->setValueOptions(\UnicaenApp\Util::collectionAsOptions($this->getServiceTypeIntervenant()->getList()));

        return $this;
    }

}
