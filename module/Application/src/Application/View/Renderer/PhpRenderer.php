<?php

namespace Application\View\Renderer;


/**
 * Description of PhpRenderer
 *
 * Permet d'utiliser les aides de vue avec de l'auto-complétion et de rendre le Refactoring des aides de vues efficace
 *
 * @method \Application\View\Helper\AgrementViewHelper agrement(\Application\Entity\Db\Agrement $agrement = null)
 * @method \Application\View\Helper\AppLink appLink($title = null, $subtitle = null)
 * @method \Application\View\Helper\CartridgeViewHelper cartridge(array $items, array $options = [])
 * @method \Application\View\Helper\Chargens\ChargensViewHelper chargens()
 * @method \Application\View\Helper\EtablissementViewHelper etablissement(\Application\Entity\Db\Etablissement $etablissement = null)
 * @method \Application\View\Helper\FormButtonGroupViewHelper formButtonGroup(\Zend\Form\ElementInterface $element = null, $labelPosition = null)
 * @method \Application\View\Helper\FormSupprimerViewHelper formSupprimer($form)
 * @method \Application\View\Helper\Intervenant\FeuilleDeRouteViewHelper feuilleDeRoute(\Application\Entity\Db\Intervenant $intervenant = null)
 * @method \Application\View\Helper\Intervenant\IntervenantViewHelper intervenant(\Application\Entity\Db\Intervenant $intervenant = null)
 * @method \Application\View\Helper\Intervenant\TotauxHetdViewHelper formuleTotauxHetd(\Application\Entity\Db\FormuleResultat $formuleResultat)
 * @method \Application\View\Helper\OffreFormation\ElementCentreCoutFieldsetViewHelper elementCentreCoutFieldset(\Application\Form\OffreFormation\EtapeCentreCout\ElementCentreCoutFieldset $fieldset = null)
 * @method \Application\View\Helper\OffreFormation\ElementModulateursSaisieFieldset elementModulateursSaisieFieldset(\Application\Form\OffreFormation\ElementModulateursFieldset $fieldset = null)
 * @method \Application\View\Helper\OffreFormation\ElementPedagogiqueViewHelper elementPedagogique(\Application\Entity\Db\ElementPedagogique $elementPedagogique = null)
 * @method \Application\View\Helper\OffreFormation\ElementTauxMixiteFieldsetViewHelper elementTauxMixiteFieldset(\Application\Form\OffreFormation\TauxMixite\TauxMixiteFieldset $fieldset = null)
 * @method \Application\View\Helper\OffreFormation\EtapeCentreCoutFormViewHelper etapeCentreCoutForm(\Application\Form\OffreFormation\EtapeCentreCout\EtapeCentreCoutForm $form = null)
 * @method \Application\View\Helper\OffreFormation\EtapeModulateursSaisieForm etapeModulateursSaisieForm(\Application\Form\OffreFormation\EtapeModulateursSaisie $form = null)
 * @method \Application\View\Helper\OffreFormation\EtapeTauxMixiteFormViewHelper etapeTauxMixiteForm(\Application\Form\OffreFormation\TauxMixite\TauxMixiteForm $form = null)
 * @method \Application\View\Helper\OffreFormation\EtapeViewHelper etape(\Application\Entity\Db\Etape $etape = null)
 * @method \Application\View\Helper\OffreFormation\FieldsetElementPedagogiqueRecherche fieldsetElementPedagogiqueRecherche(\Application\Form\OffreFormation\ElementPedagogiqueRechercheFieldset $fieldset = null)
 * @method \Application\View\Helper\Paiement\DemandeMiseEnPaiementViewHelper demandeMiseEnPaiement(array $servicesAPayer, $changeIndex = null)
 * @method \Application\View\Helper\Paiement\TypeHeuresViewHelper typeHeures(\Application\Entity\Db\TypeHeures $typeHeures = null)
 * @method \Application\View\Helper\ServiceReferentiel\FonctionReferentielViewHelper fonctionReferentiel(\Application\Entity\Db\FonctionReferentiel $fonctionReferentiel = null)
 * @method \Application\View\Helper\ServiceReferentiel\FormSaisie formServiceReferentielSaisie(\Application\Form\ServiceReferentiel\Saisie $form = null)
 * @method \Application\View\Helper\ServiceReferentiel\Ligne serviceReferentielLigne(\Application\View\Helper\ServiceReferentiel\Liste $liste, \Application\Entity\Db\ServiceReferentiel $service)
 * @method \Application\View\Helper\ServiceReferentiel\Liste serviceReferentielListe($services)
 * @method \Application\View\Helper\Service\Ligne serviceLigne(\Application\View\Helper\Service\Liste $liste, \Application\Entity\Db\Service $service)
 * @method \Application\View\Helper\Service\Liste serviceListe($services)
 * @method \Application\View\Helper\Service\Resume serviceResume($resumeServices)
 * @method \Application\View\Helper\Service\SaisieForm serviceSaisieForm(\Application\Form\Service\Saisie $form = null)
 * @method \Application\View\Helper\StructureViewHelper structure(\Application\Entity\Db\Structure $structure = null)
 * @method \Application\View\Helper\TypeInterventionAdminViewHelper typeInterventionAdmin(\Application\Entity\Db\TypeIntervention $typeIntervention)
 * @method \Application\View\Helper\UserProfileSelectRadioItem userProfileSelectRadioItem(\Zend\Permissions\Acl\Role\RoleInterface $role, $selected = false)
 * @method string utilisateur(\Application\Entity\Db\Utilisateur $utilisateur, $title = null, $subject = null, $body = null)
 * @method \Application\View\Helper\ValidationViewHelper validation(\Application\Entity\Db\Validation $validation = null)
 * @method \Application\View\Helper\VolumeHoraire\Liste volumeHoraireListe(\Application\Entity\VolumeHoraireListe $volumeHoraireListe)
 * @method \Application\View\Helper\VolumeHoraire\ListeCalendaire volumeHoraireListeCalendaire(\Application\Entity\VolumeHoraireListe $volumeHoraireListe)
 * @method \BjyAuthorize\View\Helper\IsAllowed isAllowed($resource, $privilege = null)
 * @method \UnicaenApp\Form\View\Helper\FormAdvancedMultiCheckbox formAdvancedMultiCheckbox(\Zend\Form\ElementInterface $element = null, $labelPosition = null)
 * @method \UnicaenApp\Form\View\Helper\FormControlGroup formControlGroup(\Zend\Form\ElementInterface $element = null, $pluginClass = 'formElement')
 * @method \UnicaenApp\Form\View\Helper\FormDate formDate(\UnicaenApp\Form\Element\Date $element = null, $dateReadonly = false)
 * @method \UnicaenApp\Form\View\Helper\FormDateInfSup formDateInfSup(\UnicaenApp\Form\Element\DateInfSup $element = null, $dateInfReadonly = false, $dateSupReadonly = false)
 * @method \UnicaenApp\Form\View\Helper\FormDateTime formDateTime(\Zend\Form\ElementInterface $element = null)
 * @method \UnicaenApp\Form\View\Helper\FormErrors formErrors(\Zend\Form\Form $form = null, $message = null)
 * @method \UnicaenApp\Form\View\Helper\FormLdapPeople formLdapPeople(\Zend\Form\ElementInterface $element = null)
 * @method \UnicaenApp\Form\View\Helper\FormRowDateInfSup formRowDateInfSup(\Zend\Form\ElementInterface $element = null, $labelPosition = null, $renderErrors = null, $partial = null)
 * @method \UnicaenApp\Form\View\Helper\FormSearchAndSelect formSearchAndSelect(\Zend\Form\ElementInterface $element = null)
 * @method string multipageFormFieldset()
 * @method string multipageFormNav(\UnicaenApp\Form\Element\MultipageFormNav $element)
 * @method string multipageFormRecap()
 * @method \UnicaenApp\Form\View\Helper\MultipageFormRow multipageFormRow(\Zend\Form\ElementInterface $element = null, $labelPosition = null, $renderErrors = null, $partial = null)
 * @method \UnicaenApp\Message\View\Helper\MessageHelper message()
 * @method \UnicaenApp\View\Helper\AppInfos appInfos()
 * @method \UnicaenApp\View\Helper\ConfirmHelper confirm($message = null)
 * @method \UnicaenApp\View\Helper\HeadLink headLink(array $attributes = null, $placement = 'APPEND')
 * @method \UnicaenApp\View\Helper\HeadScript headScript($mode = 'FILE', $spec = null, $placement = 'APPEND', array $attrs = [], $type = 'text/javascript')
 * @method \UnicaenApp\View\Helper\HistoriqueViewHelper historique(\UnicaenApp\Entity\HistoriqueAwareInterface $entity = null)
 * @method \UnicaenApp\View\Helper\InlineScript inlineScript($mode = 'FILE', $spec = null, $placement = 'APPEND', array $attrs = [], $type = 'text/javascript')
 * @method \UnicaenApp\View\Helper\InstadiaViewHelper instadia()
 * @method \UnicaenApp\View\Helper\MessageCollectorHelper messageCollector($severity = null)
 * @method \UnicaenApp\View\Helper\Messenger messenger()
 * @method string modalAjaxDialog($dialogDivId = null)
 * @method \UnicaenApp\View\Helper\QueryParams queryParams()
 * @method \UnicaenApp\View\Helper\TabAjax\TabAjaxViewHelper tabajax($tabs = null)
 * @method \UnicaenApp\View\Helper\TagViewHelper tag($name = null, array $attributes = [])
 * @method \UnicaenApp\View\Helper\ToggleDetails toggleDetails($detailsDivId, $rememberState = true)
 * @method \UnicaenApp\View\Helper\Upload\UploaderHelper uploader()
 * @method \UnicaenAuth\View\Helper\AppConnection appConnection()
 * @method \UnicaenAuth\View\Helper\LdapConnectViewHelper ldapConnect(\Zend\Form\Form $form)
 * @method \UnicaenAuth\View\Helper\LocalConnectViewHelper localConnect(\Zend\Form\Form $form)
 * @method \UnicaenAuth\View\Helper\ShibConnectViewHelper shibConnect()
 * @method \UnicaenAuth\View\Helper\UserConnection userConnection()
 * @method \UnicaenAuth\View\Helper\UserCurrent userCurrent($affectationFineSiDispo = false)
 * @method \UnicaenAuth\View\Helper\UserInfo userInfo($affectationPrincipale = false)
 * @method \UnicaenAuth\View\Helper\UserProfile userProfile($userProfileSelectable = false)
 * @method \UnicaenAuth\View\Helper\UserProfileSelect userProfileSelect()
 * @method \UnicaenAuth\View\Helper\UserStatus userStatus($displayConnectionLink = true)
 * @method \UnicaenAuth\View\Helper\UserUsurpationHelper userUsurpation()
 * @method \UnicaenImport\View\Helper\DifferentielLigne\DifferentielLigne differentielLigne(\UnicaenImport\Entity\Differentiel\Ligne $ligne)
 * @method \UnicaenImport\View\Helper\DifferentielListe differentielListe($lignes)
 * @method string formCaptchaDumb(\Zend\Form\ElementInterface $element = null)
 * @method string captchaDumb(\Zend\Form\ElementInterface $element = null)
 * @method string formCaptchaFiglet(\Zend\Form\ElementInterface $element = null)
 * @method string captchaFiglet(\Zend\Form\ElementInterface $element = null)
 * @method string captchaImage(\Zend\Form\ElementInterface $element = null)
 * @method string formCaptchaImage(\Zend\Form\ElementInterface $element = null)
 * @method string captchaRecaptcha(\Zend\Form\ElementInterface $element = null)
 * @method string formCaptchaRecaptcha(\Zend\Form\ElementInterface $element = null)
 * @method string formFileApcProgress(\Zend\Form\ElementInterface $element = null)
 * @method string formFileSessionProgress(\Zend\Form\ElementInterface $element = null)
 * @method string formFileUploadProgress(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\Form form(\Zend\Form\FormInterface $form = null)
 * @method \Zend\Form\View\Helper\FormButton formButton(\Zend\Form\ElementInterface $element = null, $buttonContent = null)
 * @method \Zend\Form\View\Helper\FormCaptcha formCaptcha(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormCheckbox formCheckbox(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormCollection formCollection(\Zend\Form\ElementInterface $element = null, $wrap = true)
 * @method \Zend\Form\View\Helper\FormColor formColor(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormDateSelect formDateSelect(\Zend\Form\ElementInterface $element = null, $dateType = 1, $locale = null)
 * @method \Zend\Form\View\Helper\FormDateTimeLocal formDateTimeLocal(\Zend\Form\ElementInterface $element = null)
 * @method string formDateTimeSelect(\Zend\Form\ElementInterface $element = null, $dateType = 1, $timeType = 1, $locale = null)
 * @method string formElement(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormElementErrors formElementErrors(\Zend\Form\ElementInterface $element = null, array $attributes = [])
 * @method \Zend\Form\View\Helper\FormEmail formEmail(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormFile formFile(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormHidden formHidden(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormImage formImage(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormInput formInput(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormLabel formLabel(\Zend\Form\ElementInterface $element = null, $labelContent = null, $position = null)
 * @method \Zend\Form\View\Helper\FormMonth formMonth(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormMonthSelect formMonthSelect(\Zend\Form\ElementInterface $element = null, $dateType = 1, $locale = null)
 * @method \Zend\Form\View\Helper\FormMultiCheckbox formMultiCheckbox(\Zend\Form\ElementInterface $element = null, $labelPosition = null)
 * @method \Zend\Form\View\Helper\FormNumber formNumber(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormPassword formPassword(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormRadio formRadio(\Zend\Form\ElementInterface $element = null, $labelPosition = null)
 * @method \Zend\Form\View\Helper\FormRange formRange(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormReset formReset(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormRow formRow(\Zend\Form\ElementInterface $element = null, $labelPosition = null, $renderErrors = null, $partial = null)
 * @method \Zend\Form\View\Helper\FormSearch formSearch(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormSelect formSelect(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormSubmit formSubmit(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormTel formTel(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormText formText(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormTextarea formTextarea(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormTime formTime(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormUrl formUrl(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormWeek formWeek(\Zend\Form\ElementInterface $element = null)
 * @method string currencyFormat($number, $currencyCode = null, $showDecimals = null, $locale = null, $pattern = null)
 * @method string dateFormat($date, $dateType = -1, $timeType = -1, $locale = null, $pattern = null)
 * @method string numberFormat($number, $formatStyle = null, $formatType = null, $locale = null, $decimals = null, array $textAttributes = null)
 * @method string plural($strings, $number)
 * @method string translate($message, $textDomain = null, $locale = null)
 * @method string translatePlural($singular, $plural, $number, $textDomain = null, $locale = null)
 * @method \Zend\Mvc\Plugin\FlashMessenger\View\Helper\FlashMessenger zendviewhelperflashmessenger($namespace = null)
 * @method \Zend\Mvc\Plugin\FlashMessenger\View\Helper\FlashMessenger flashMessenger($namespace = null)
 * @method string asset($asset)
 * @method string basePath($file = null)
 * @method \Zend\View\Helper\Cycle cycle(array $data = [], $name = 'default')
 * @method \Zend\View\Helper\DeclareVars declareVars()
 * @method \Zend\View\Helper\Doctype doctype($doctype = null)
 * @method \Zend\View\Helper\EscapeCss escapeCss($value, $recurse = 0)
 * @method \Zend\View\Helper\EscapeHtml escapeHtml($value, $recurse = 0)
 * @method \Zend\View\Helper\EscapeHtmlAttr escapeHtmlAttr($value, $recurse = 0)
 * @method \Zend\View\Helper\EscapeJs escapeJs($value, $recurse = 0)
 * @method \Zend\View\Helper\EscapeUrl escapeUrl($value, $recurse = 0)
 * @method \Zend\View\Helper\Gravatar gravatar($email = '', $options = [], $attributes = [])
 * @method \Zend\View\Helper\HeadMeta headMeta($content = null, $keyValue = null, $keyType = 'name', $modifiers = [], $placement = 'APPEND')
 * @method \Zend\View\Helper\HeadStyle headStyle($content = null, $placement = 'APPEND', $attributes = [])
 * @method \Zend\View\Helper\HeadTitle headTitle($title = null, $setType = null)
 * @method string htmlFlash($data, array $attribs = [], array $params = [], $content = null)
 * @method \Zend\View\Helper\HtmlList htmlList(array $items, $ordered = false, $attribs = false, $escape = true)
 * @method string htmlObject($data = null, $type = null, array $attribs = [], array $params = [], $content = null)
 * @method string htmlPage($data, array $attribs = [], array $params = [], $content = null)
 * @method string htmlQuicktime($data, array $attribs = [], array $params = [], $content = null)
 * @method \Zend\View\Helper\HtmlTag htmlTag(array $attribs = [])
 * @method \Zend\View\Helper\Identity identity()
 * @method \Zend\View\Helper\Json json($data, array $jsonOptions = [])
 * @method \Zend\View\Helper\Layout layout($template = null)
 * @method string paginationControl(\Zend\Paginator\Paginator $paginator = null, $scrollingStyle = null, $partial = null, $params = null)
 * @method \Zend\View\Helper\Partial partial($name = null, $values = null)
 * @method string partialLoop($name = null, $values = null)
 * @method \Zend\View\Helper\Placeholder placeholder($name = null)
 * @method string renderChildModel($child)
 * @method \Zend\View\Helper\RenderToPlaceholder renderToPlaceholder($script, $placeholder)
 * @method string serverUrl($requestUri = null)
 * @method \Zend\View\Helper\Url url($name = null, $params = [], $options = [], $reuseMatchedParams = false)
 * @method \Zend\View\Helper\ViewModel viewModel()
 *
 * @author UnicaenCode
 */
class PhpRenderer extends \Zend\View\Renderer\PhpRenderer
{

}