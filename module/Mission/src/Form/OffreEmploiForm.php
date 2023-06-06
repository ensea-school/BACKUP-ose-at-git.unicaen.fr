<?php

namespace Mission\Form;

use Application\Entity\Db\Structure;
use Application\Form\AbstractForm;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\StructureServiceAwareTrait;
use Laminas\Form\FormInterface;
use Mission\Entity\Db\Mission;
use Mission\Entity\Db\OffreEmploi;
use OffreFormation\Entity\Db\ElementPedagogique;
use Paiement\Entity\Db\TauxRemu;
use Mission\Entity\Db\TypeMission;


/**
 * Description of OffreEmploiForm
 *
 * @author Antony Le Courtes  <antony.lecourtes at unicaen.fr>
 */
class OffreEmploiForm extends AbstractForm
{
    use ContextServiceAwareTrait;
    use StructureServiceAwareTrait;

    public function init()
    {
        $this->spec(OffreEmploi::class, ['intervenant', 'autoValidation', 'validation']);

        $this->spec(['description' => ['type' => 'Textarea']]);

        $this->build();

        $tmDql       = "SELECT tm FROM " . TypeMission::class . " tm WHERE tm.histoDestruction IS NULL AND tm.annee = :annee";
        $tmDqlParams = ['annee' => $this->getServiceContext()->getAnnee()];
        $this->setValueOptions('typeMission', $tmDql, $tmDqlParams);


        $sDql = $this->getServiceStructure()->finderByHistorique();
        $this->setValueOptions('structure', $sDql);
        $this->get('structure')->setAttributes(['class' => 'selectpicker', 'data-live-search' => true]);

        $this->setLabels([
            'structure'    => 'Composante proposant l\'offre',
            'typeMission'  => 'Type de mission',
            'dateDebut'    => 'Date de début',
            'dateFin'      => 'Date de fin',
            'nombreHeures' => 'Nombre d\'heure(s)',
            'nombrePostes' => 'Nombre de poste(s)',
            'description'  => 'Descriptif de l\'offre',
            'titre'        => 'Titre de l\'offre',
        ]);

        $this->addSubmit();
    }

}