<?php

namespace Service\Controller;

use Application\Controller\AbstractController;
use Application\Provider\Privileges;
use Intervenant\Service\TypeIntervenantServiceAwareTrait;
use Service\Form\CampagneSaisieFormAwareTrait;
use Service\Service\CampagneSaisieServiceAwareTrait;
use Service\Service\TypeVolumeHoraireServiceAwareTrait;


/**
 * Description of CampagneSaisieController
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class CampagneSaisieController extends AbstractController
{
    use CampagneSaisieServiceAwareTrait;
    use CampagneSaisieFormAwareTrait;
    use TypeIntervenantServiceAwareTrait;
    use TypeVolumeHoraireServiceAwareTrait;

    public function campagnesSaisieAction()
    {
        $typeIntervenant   = $this->context()->typeIntervenantFromPost();
        $typeVolumeHoraire = $this->context()->typeVolumeHoraireFromPost();

        $typesIntervenants    = $this->getServiceTypeIntervenant()->getList();
        $typesVolumesHoraires = $this->getServiceTypeVolumeHoraire()->getList();

        $canEdit = $this->isAllowed(Privileges::getResourceId(Privileges::PARAMETRES_CAMPAGNES_SAISIE_EDITION));

        foreach ($typesIntervenants as $ti) {
            foreach ($typesVolumesHoraires as $tvh) {
                $campagne = $this->getServiceCampagneSaisie()->getBy($ti, $tvh);
                $form     = $this->getFormCampagneSaisie();

                if (!$canEdit) {
                    foreach ($form->getElements() as $element) {
                        $element->setAttribute('disabled', true);
                    }
                }

                $form->bind($campagne);
                $forms[$ti->getId()][$tvh->getId()] = $form;

                if ($canEdit && $ti->getId() == $typeIntervenant && $tvh->getId() == $typeVolumeHoraire) {
                    $form->requestSave($this->getRequest(), function () use ($campagne) {
                        if (!$campagne->getDateDebut() && !$campagne->getDateFin() && !$campagne->getMessageIntervenant() && !$campagne->getMessageAutres()) {
                            $this->getServiceCampagneSaisie()->delete($campagne);
                        } elseif (!$campagne->getMessageIntervenant() && ($campagne->getDateDebut() || $campagne->getDateFin() || $campagne->getMessageAutres())) {
                            $this->flashMessenger()->addErrorMessage('Il est obligatoire de saisir un message à destination des intervenants');
                        } else {
                            $this->getServiceCampagneSaisie()->save($campagne);
                        }
                    });
                }
            }
        }

        return compact('typesIntervenants', 'typesVolumesHoraires', 'canEdit', 'forms');
    }

}