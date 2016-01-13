<?php

namespace Application\View\Renderer;

/**
 * Description of PhpRenderer
 *
 * Permet d'utiliser les aides de vue avec de l'auto-complétion et de rendre le Refactoring des aides de vues efficace
 *
 * @method \UnicaenAuth\View\Helper\AppConnection appConnection()
 * @method \UnicaenApp\View\Helper\Messenger messenger()
 * @method string modalAjaxDialog($dialogDivId = null)
 * @method \UnicaenApp\View\Helper\ConfirmHelper confirm($message = null)
 * @method \UnicaenApp\View\Helper\ToggleDetails toggleDetails($detailsDivId, $title = null, $iconClass = null)
 * @method string multipageFormFieldset()
 * @method string multipageFormNav(\UnicaenApp\Form\Element\MultipageFormNav $element)
 * @method \UnicaenApp\Form\View\Helper\MultipageFormRow multipageFormRow(\Zend\Form\ElementInterface $element = null, $labelPosition = null, $renderErrors = null, $partial = null)
 * @method string multipageFormRecap()
 * @method \UnicaenApp\Form\View\Helper\FormControlGroup formControlGroup(\Zend\Form\ElementInterface $element = null, $pluginClass = 'formElement')
 * @method \UnicaenApp\Form\View\Helper\FormDate formDate(\UnicaenApp\Form\Element\Date $element = null, $dateReadonly = false)
 * @method \UnicaenApp\Form\View\Helper\FormDateInfSup formDateInfSup(\UnicaenApp\Form\Element\DateInfSup $element = null, $dateInfReadonly = false, $dateSupReadonly = false)
 * @method \UnicaenApp\Form\View\Helper\FormRowDateInfSup formRowDateInfSup(\Zend\Form\ElementInterface $element = null, $labelPosition = null, $renderErrors = null, $partial = null)
 * @method \UnicaenApp\Form\View\Helper\FormSearchAndSelect formSearchAndSelect(\Zend\Form\ElementInterface $element = null)
 * @method \UnicaenApp\Form\View\Helper\FormLdapPeople formLdapPeople(\Zend\Form\ElementInterface $element = null)
 * @method \UnicaenApp\Form\View\Helper\FormErrors formErrors(\Zend\Form\Form $form = null, $message = null)
 * @method \UnicaenApp\View\Helper\MessageCollectorHelper messageCollector()
 * @method \UnicaenApp\View\Helper\HeadScript headScript($mode = 'FILE', $spec = null, $placement = 'APPEND', array $attrs = [], $type = 'text/javascript')
 * @method \UnicaenApp\View\Helper\InlineScript inlineScript($mode = 'FILE', $spec = null, $placement = 'APPEND', array $attrs = [], $type = 'text/javascript')
 * @method \UnicaenApp\View\Helper\HeadLink headLink(array $attributes = null, $placement = 'APPEND')
 * @method \UnicaenApp\View\Helper\Upload\UploaderHelper uploader()
 * @method \UnicaenApp\Form\View\Helper\FormAdvancedMultiCheckbox formAdvancedMultiCheckbox(\Zend\Form\ElementInterface $element = null, $labelPosition = null)
 * @method \UnicaenApp\View\Helper\HistoriqueViewHelper historique(\UnicaenApp\Entity\HistoriqueAwareInterface $entity = null)
 * @method \UnicaenApp\View\Helper\TabAjax\TabAjaxViewHelper tabajax($tabs = null)
 * @method \UnicaenApp\View\Helper\TagViewHelper tag($name = null, array $attributes = [])
 * @method \Common\View\Helper\CartridgeViewHelper cartridge(array $items, array $options = [])
 * @method \Common\Form\View\Helper\FormButtonGroup formButtonGroup(\Zend\Form\ElementInterface $element = null, $labelPosition = null)
 * @method \Application\View\Helper\ValidationViewHelper validation(\Application\Entity\Db\Validation $validation = null)
 * @method string utilisateur(\Application\Entity\Db\Utilisateur $utilisateur, $title = null, $subject = null, $body = null)
 * @method \Application\View\Helper\Intervenant\TotauxHetdViewHelper formuleTotauxHetd(\Application\Entity\Db\FormuleResultat $formuleResultat)
 * @method \Application\View\Helper\Intervenant\IntervenantViewHelper intervenant(\Application\Entity\Db\Intervenant $intervenant = null)
 * @method \Application\View\Helper\StructureViewHelper structure(\Application\Entity\Db\Structure $structure = null)
 * @method \Application\View\Helper\EtablissementViewHelper etablissement(\Application\Entity\Db\Etablissement $etablissement = null)
 * @method \Application\View\Helper\Service\SaisieForm serviceSaisieForm(\Application\Form\Service\Saisie $form = null)
 * @method \Application\View\Helper\ServiceReferentiel\FormSaisie formServiceReferentielSaisie(\Application\Form\ServiceReferentiel\Saisie $form = null)
 * @method \Application\View\Helper\Service\Resume serviceResume($resumeServices)
 * @method \Application\View\Helper\ServiceReferentiel\FonctionReferentielViewHelper fonctionReferentiel(\Application\Entity\Db\FonctionReferentiel $fonctionReferentiel = null)
 * @method \Application\View\Helper\VolumeHoraire\Liste volumeHoraireListe(\Application\Entity\VolumeHoraireListe $volumeHoraireListe)
 * @method \Application\View\Helper\VolumeHoraireReferentiel\Liste volumeHoraireReferentielListe(\Application\Entity\VolumeHoraireReferentielListe $volumeHoraireListe)
 * @method \Application\View\Helper\OffreFormation\EtapeModulateursSaisieForm etapeModulateursSaisieForm(\Application\Form\OffreFormation\EtapeModulateursSaisie $form = null)
 * @method \Application\View\Helper\OffreFormation\ElementModulateursSaisieFieldset elementModulateursSaisieFieldset(\Application\Form\OffreFormation\ElementModulateursFieldset $fieldset = null)
 * @method \Application\View\Helper\OffreFormation\ElementPedagogiqueViewHelper elementPedagogique(\Application\Entity\Db\ElementPedagogique $elementPedagogique = null)
 * @method \Application\View\Helper\OffreFormation\EtapeViewHelper etape(\Application\Entity\Db\Etape $etape = null)
 * @method \Application\View\Helper\OffreFormation\EtapeCentreCoutFormViewHelper etapeCentreCoutForm(\Application\Form\OffreFormation\EtapeCentreCout\EtapeCentreCoutForm $form = null)
 * @method \Application\View\Helper\OffreFormation\ElementCentreCoutFieldsetViewHelper elementCentreCoutFieldset(\Application\Form\OffreFormation\EtapeCentreCout\ElementCentreCoutFieldset $fieldset = null)
 * @method \Application\View\Helper\OffreFormation\FieldsetElementPedagogiqueRecherche fieldsetElementPedagogiqueRecherche(\Application\Form\OffreFormation\ElementPedagogiqueRechercheFieldset $fieldset = null)
 * @method \Application\View\Helper\AgrementViewHelper agrement(\Application\Entity\Db\Agrement $agrement = null)
 * @method \Application\View\Helper\Workflow workflow(\Application\Entity\Db\Intervenant $intervenant, \Zend\Permissions\Acl\Role\RoleInterface $role)
 * @method \Application\View\Helper\Paiement\DemandeMiseEnPaiementViewHelper demandeMiseEnPaiement(array $servicesAPayer, $changeIndex = null)
 * @method \Application\View\Helper\Paiement\TypeHeuresViewHelper typeHeures(\Application\Entity\Db\TypeHeures $typeHeures = null)
 * @method \Import\View\Helper\DifferentielListe differentielListe($lignes)
 * @method \Import\View\Helper\DifferentielLigne\DifferentielLigne differentielLigne(\Import\Entity\Differentiel\Ligne $ligne)
 * @method \UnicaenApp\View\Helper\AppInfos appInfos()
 * @method \Application\View\Helper\AppLink appLink($title = null, $subtitle = null)
 * @method \UnicaenAuth\View\Helper\UserProfileSelect userProfileSelect()
 * @method \Application\Service\Message\View\Helper\MessageHelper message()
 * @method \UnicaenAuth\View\Helper\UserConnection userConnection()
 * @method \UnicaenAuth\View\Helper\UserCurrent userCurrent($affectationFineSiDispo = false)
 * @method \UnicaenAuth\View\Helper\UserStatus userStatus($displayConnectionLink = true)
 * @method \UnicaenAuth\View\Helper\UserProfile userProfile($userProfileSelectable = false)
 * @method \UnicaenAuth\View\Helper\UserInfo userInfo($affectationPrincipale = false)
 * @method \Application\View\Helper\UserProfileSelectRadioItem userProfileSelectRadioItem(\Zend\Permissions\Acl\Role\RoleInterface $role, $selected = false)
 * @method \Application\View\Helper\Service\Liste serviceListe($services)
 * @method \Application\View\Helper\Service\Ligne serviceLigne(\Application\View\Helper\Service\Liste $liste, \Application\Entity\Db\Service $service)
 * @method \Application\View\Helper\ServiceReferentiel\Liste serviceReferentielListe($services)
 * @method \Application\View\Helper\ServiceReferentiel\Ligne serviceReferentielLigne(\Application\View\Helper\ServiceReferentiel\Liste $liste, \Application\Entity\Db\ServiceReferentiel $service)
 *
 * @author UnicaenCode
 */
class PhpRenderer extends \Zend\View\Renderer\PhpRenderer {



}