<?php

namespace Administration\Controller;

use Administration\Form\ParametresFormAwareTrait;
use Administration\Service\ParametresServiceAwareTrait;
use Application\Controller\AbstractController;
use Application\Filter\FloatFromString;
use Application\Filter\StringFromFloat;
use Application\Provider\Privileges;
use Application\Service\Traits\AnneeServiceAwareTrait;
use Laminas\View\Model\JsonModel;
use Lieu\Service\EtablissementServiceAwareTrait;


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


    public function anneesAction()
    {
        $canEdit = $this->isAllowed(Privileges::getResourceId(Privileges::PARAMETRES_ANNEES_EDITION));

        if ($this->getRequest()->isPost()) {

            $anneeId = $this->params()->fromPost('annee');
            $annee   = $this->getServiceAnnee()->get($anneeId);

            $annee->setActive(!$annee->isActive());
            $this->getServiceAnnee()->save($annee);
            $this->getServiceAnnee()->resetChoixAnnees();

            return new JsonModel([
                'message' => 'Action effectuée',
                'status'  => 'success',
            ]);
        } else {
            $annees = $this->getServiceAnnee()->getList();

            return compact('annees', 'canEdit');
        }
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
            if (isset($posted['pourc_aa_referentiel'])) {
                $posted['pourc_aa_referentiel'] = floatToString(FloatFromString::run($posted['pourc_aa_referentiel']) / 100);
            }
            if (isset($posted['taux_conges_payes'])) {
                $posted['taux_conges_payes'] = floatToString(FloatFromString::run($posted['taux_conges_payes']) / 100);
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

            if ($parametre == 'signature_electronique_parapheur') {
                if ($value == "none") {
                    $sp->set($parametre, null);
                }
            }

            if ($parametre == 'etablissement') {
                $value = $this->getServiceEtablissement()->get($value);
            }

            if ($form->has($parametre)) {
                if ($parametre == 'pourc_s1_pour_annee_civile') {
                    $value = StringFromFloat::run(stringToFloat($value) * 100);
                }
                if ($parametre == 'pourc_aa_referentiel') {
                    $value = StringFromFloat::run(stringToFloat($value) * 100);
                }
                if ($parametre == 'taux_conges_payes') {
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