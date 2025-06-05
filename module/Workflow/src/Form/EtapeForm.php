<?php

namespace Workflow\Form;

use Application\Form\AbstractForm;
use Workflow\Entity\Db\WorkflowEtape;


/**
 * Description of EtapeForm
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class EtapeForm extends AbstractForm
{

    public function init(): void
    {
        $this->setAttribute('action', $this->getCurrentUrl());

        $ignore = ['ordre'];
        $this->spec(WorkflowEtape::class, $ignore);
        $this->build();

        $labels = [
            'libelleIntervenant' => 'Libellé visible par les intervenants',
            'libelleAutres'      => 'Libellé visible côté gestionnaires',
            'descNonFranchie'    => 'Description affichée si l\'étape n\'a pas été franchie',
            'descSansObjectif'   => 'Texte expliquant pourquoi l\'étape n\'est pas accessible',
        ];
        $this->setLabels($labels);

        $this->addSubmit();
    }
}