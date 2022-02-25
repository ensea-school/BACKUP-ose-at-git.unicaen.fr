<?php

namespace Application\Controller;

use Application\Filter\FloatFromString;
use Application\Filter\StringFromFloat;
use Application\Form\CampagneSaisieFieldset;
use Application\Form\Traits\CampagneSaisieFormAwareTrait;
use Application\Form\Traits\ParametresFormAwareTrait;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\AnneeServiceAwareTrait;
use Application\Service\Traits\CampagneSaisieServiceAwareTrait;
use Application\Service\Traits\EtablissementServiceAwareTrait;
use Application\Service\Traits\ParametresServiceAwareTrait;
use Intervenant\Service\TypeIntervenantServiceAwareTrait;
use Application\Service\Traits\TypeVolumeHoraireServiceAwareTrait;
use Laminas\View\Model\JsonModel;


/**
 * Description of ParametreController
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class ParametreController extends AbstractController
{
    use ParametresFormAwareTrait;
    use ParametresServiceAwareTrait;
    use EtablissementServiceAwareTrait;
    use AnneeServiceAwareTrait;
    use TypeIntervenantServiceAwareTrait;
    use TypeVolumeHoraireServiceAwareTrait;
    use CampagneSaisieServiceAwareTrait;
    use CampagneSaisieFormAwareTrait;


    public function indexAction()
    {
        return [];
    }



    public function anneesAction()
    {
        $canEdit = $this->isAllowed(Privileges::getResourceId(Privileges::PARAMETRES_ANNEES_EDITION));

        if ($this->getRequest()->isPost()) {

            $anneeId = $this->params()->fromPost('annee');
            $annee   = $this->getServiceAnnee()->get($anneeId);

            $annee->setActive(!$annee->isActive());
            $this->getServiceAnnee()->save($annee);

            return new JsonModel([
                'message' => 'Action effectuée',
                'status'  => 'success',
            ]);
        } else {
            $annees = $this->getServiceAnnee()->getList();

            return compact('annees', 'canEdit');
        }
    }



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

                if ($canEdit && $ti == $typeIntervenant && $tvh == $typeVolumeHoraire) {
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



    public function generauxAction()
    {
        $sp   = $this->getServiceParametres();
        $form = $this->getFormParametres();

        $canEdit = $this->isAllowed(Privileges::getResourceId(Privileges::PARAMETRES_GENERAL_EDITION));

        if ($canEdit) {
            $posted = $this->params()->fromPost();
            if (isset($posted['pourc_s1_pour_annee_civile'])) {
                $posted['pourc_s1_pour_annee_civile'] = floatToString(FloatFromString::run($posted['pourc_s1_pour_annee_civile']) / 100);
            }
        } else {
            $posted = [];// rien ne peut être modifié!!
        }


        $parametres = $sp->getList();
        foreach ($parametres as $parametre => $value) {
            if (isset($posted[$parametre])) {
                if (is_array($posted[$parametre])) {
                    $posted[$parametre] = $posted[$parametre]['id'];
                }
                if ($posted[$parametre] != $value) {
                    $value = $posted[$parametre];

                    // si c'est l'année en cours alors on l'active le cas échéant...
                    if ($parametre == 'annee') {
                        $annee = $this->getServiceAnnee()->get($value);
                        if (!$annee->isActive()) {
                            $annee->setActive(true);
                            $this->getServiceAnnee()->save($annee);
                        }
                    }

                    $sp->set($parametre, $value);
                }
            }

            if ($parametre == 'etablissement') {
                $value = $this->getServiceEtablissement()->get($value);
            }

            if ($form->has($parametre)) {
                if ($parametre == 'pourc_s1_pour_annee_civile') {
                    $value = StringFromFloat::run(stringToFloat($value) * 100);
                }
                $element = $form->get($parametre);
                if (!$canEdit) $element->setAttribute('disabled', true);
                $element->setValue($value);
                if (!$element->getLabel()) $element->setLabel($sp->getDescription($parametre));
            }
        }

        return compact('form', 'canEdit');
    }

}