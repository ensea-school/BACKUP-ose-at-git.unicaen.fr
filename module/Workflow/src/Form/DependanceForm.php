<?php

namespace Workflow\Form;

use Application\Entity\Db\Perimetre;
use Application\Form\AbstractForm;
use Intervenant\Entity\Db\TypeIntervenant;
use Workflow\Entity\Db\WorkflowEtape;
use Workflow\Entity\Db\WorkflowEtapeDependance;


class DependanceForm extends AbstractForm
{
    public function init2(WorkflowEtape $etapeSuivante)
    {
        $ignore = ["etapeSuivante"];
        $this->spec(WorkflowEtapeDependance::class, $ignore);
        $this->spec(['avancement' => ['type' => 'Select'], 'typeIntervenant' => ['input' => ['required' => false]]]);
        $this->build();

        $this->addSecurity();
        $this->addSubmit();

        $params = ['ordre' => $etapeSuivante->getOrdre()];
        $this->setValueOptions('etapePrecedante', 'SELECT we FROM ' . WorkflowEtape::class . ' we WHERE we.ordre < :ordre ORDER BY we.ordre', $params);

        $this->setValueOptions('perimetre', 'SELECT p FROM ' . Perimetre::class . ' p ORDER BY p.libelle');

        $this->get('typeIntervenant')->setEmptyOption("Sans restriction");
        $this->setValueOptions('typeIntervenant', 'SELECT ti FROM ' . TypeIntervenant::class . ' ti ORDER BY ti.code DESC');

        $this->setValueOptions('avancement', [
            WorkflowEtapeDependance::AVANCEMENT_DESACTIVE             => 'Désactivé',
            WorkflowEtapeDependance::AVANCEMENT_DEBUTE                => 'Débuté',
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_PARTIELLEMENT => 'Terminé partiellement',
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_INTEGRALEMENT => 'Terminé intégralement',
        ]);

        $labels = [
            'etapePrecedante' => 'Étape Précédente',
            'typeIntervenant' => 'Type d\'intervenant',
            'perimetre'       => 'Périmètre',
        ];
        $this->setLabels($labels);

        return $this;
    }
}