<?php

namespace Workflow\Form;

use Application\Entity\Db\Perimetre;
use Application\Form\AbstractForm;
use Intervenant\Entity\Db\TypeIntervenant;
use Workflow\Entity\Db\WorkflowEtapeDependance;
use Workflow\Service\WorkflowServiceAwareTrait;


class DependanceForm extends AbstractForm
{
    use WorkflowServiceAwareTrait;

    private WorkflowEtapeDependance $dependance;



    public function init2(WorkflowEtapeDependance $dependance): self
    {
        $this->dependance = $dependance;

        $this->setAttribute('data-etablissement-etapes', json_encode($this->getEtablissementEtapes()));
        $this->setAttribute('data-avancements', json_encode($this->getAvancements()));
        $ignore = ["etapeSuivante"];
        $this->spec(WorkflowEtapeDependance::class, $ignore);
        $this->spec(['avancement' => ['type' => 'Select'], 'typeIntervenant' => ['input' => ['required' => false]]]);
        $this->build();

        $this->addSecurity();
        $this->addSubmit();

        $this->get('etapePrecedante')->setAttribute('onchange', 'affWFDepChange(this)');
        $this->get('etapePrecedante')->setAttribute('class', 'wf-dep-etape-precedante');

        $this->setValueOptions('etapePrecedante', $this->getEtapesPrecedantes());
        $this->setValueOptions('perimetre', 'SELECT p FROM ' . Perimetre::class . ' p ORDER BY p.libelle');

        $this->get('typeIntervenant')->setEmptyOption("Sans restriction");
        $this->setValueOptions('typeIntervenant', 'SELECT ti FROM ' . TypeIntervenant::class . ' ti ORDER BY ti.code DESC');

        $this->setValueOptions('avancement', [
            WorkflowEtapeDependance::AVANCEMENT_DEBUTE                => 'Débuté',
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_PARTIELLEMENT => 'Terminé partiellement',
            WorkflowEtapeDependance::AVANCEMENT_TERMINE_INTEGRALEMENT => 'Terminé intégralement',
        ]);

        $labels = [
            'etapePrecedante' => 'Étape Précédente',
            'typeIntervenant' => 'Type d\'intervenant',
            'perimetre'       => 'Périmètre',
            'avancement'      => 'Règle de franchissement',
        ];
        $this->setLabels($labels);

        return $this;
    }



    protected function getEtapesPrecedantes(): array
    {
        $res    = [];
        $etapes = $this->getServiceWorkflow()->getEtapes();
        foreach ($etapes as $etape) {
            $ok = $etape->getOrdre() < $this->dependance->getEtapeSuivante()->getOrdre();

            if ($ok) {
                $res[$etape->getId()] = $etape->getLibelleAutres();
            }
        }

        foreach ($this->dependance->getEtapeSuivante()->getDependances() as $dependance) {
            if ($dependance != $this->dependance) {
                unset($res[$dependance->getEtapePrecedante()->getId()]);
            }
        }

        return $res;
    }



    protected function getEtablissementEtapes(): array
    {
        $res    = [];
        $etapes = $this->getServiceWorkflow()->getEtapes();
        foreach ($etapes as $etape) {
            if ($etape->getPerimetre()->isEtablissement()) {
                $res[] = $etape->getId();
            }
        }
        return $res;
    }



    protected function getAvancements(): array
    {
        $res    = [];
        $etapes = $this->getServiceWorkflow()->getEtapes();
        foreach ($etapes as $etape) {
            $res[$etape->getId()] = $etape->getAvancements();
        }
        return $res;
    }
}