<?php

namespace Application\View\Renderer;

/**
 * Description of PhpRenderer
 *
 * Permet d'utiliser les aides de vue avec de l'auto-complétion et de rendre le Refactoring des aides de vues efficace
 * Commande : php public/index.php UnicaenCode
 *
 * @method \Application\Form\View\Helper\FormSearchAndSelect formSearchAndSelect(?Laminas\Form\ElementInterface $element = null)
 * @method \Application\View\Helper\AgrementViewHelper agrement(?\Application\Entity\Db\Agrement $agrement = null, ?\Application\Entity\Db\TblAgrement $tblAgrement = null)
 * @method \Application\View\Helper\AppLink appLink($title = null, $subtitle = null)
 * @method \Application\View\Helper\CartridgeViewHelper cartridge(array $items, array $options = [])
 * @method \Application\View\Helper\Chargens\ChargensViewHelper chargens()
 * @method \Application\View\Helper\EtablissementViewHelper etablissement(?\Application\Entity\Db\Etablissement $etablissement = null)
 * @method \Application\View\Helper\FormButtonGroupViewHelper formButtonGroup(?Laminas\Form\ElementInterface $element = null, ?string $labelPosition = null)
 * @method \Application\View\Helper\FormSupprimerViewHelper formSupprimer($form)
 * @method \Application\View\Helper\Intervenant\FeuilleDeRouteViewHelper feuilleDeRoute(?\Application\Entity\Db\Intervenant $intervenant = null)
 * @method \Application\View\Helper\Intervenant\IntervenantViewHelper intervenant(?\Application\Entity\Db\Intervenant $intervenant = null)
 * @method \Application\View\Helper\Intervenant\TotauxHetdViewHelper formuleTotauxHetd(\Application\Entity\Db\FormuleResultat $formuleResultat)
 * @method \Application\View\Helper\OffreFormation\ElementCentreCoutFieldsetViewHelper elementCentreCoutFieldset(?\Application\Form\OffreFormation\EtapeCentreCout\ElementCentreCoutFieldset $fieldset = null)
 * @method \Application\View\Helper\OffreFormation\ElementModulateursSaisieFieldset elementModulateursSaisieFieldset(?\Application\Form\OffreFormation\ElementModulateursFieldset $fieldset = null)
 * @method \Application\View\Helper\OffreFormation\ElementPedagogiqueViewHelper elementPedagogique(?\Application\Entity\Db\ElementPedagogique $elementPedagogique = null)
 * @method \Application\View\Helper\OffreFormation\ElementTauxMixiteFieldsetViewHelper elementTauxMixiteFieldset(?\Application\Form\OffreFormation\TauxMixite\TauxMixiteFieldset $fieldset = null)
 * @method \Application\View\Helper\OffreFormation\EtapeCentreCoutFormViewHelper etapeCentreCoutForm(?\Application\Form\OffreFormation\EtapeCentreCout\EtapeCentreCoutForm $form = null)
 * @method \Application\View\Helper\OffreFormation\EtapeModulateursSaisieForm etapeModulateursSaisieForm(?\Application\Form\OffreFormation\EtapeModulateursSaisie $form = null)
 * @method \Application\View\Helper\OffreFormation\EtapeTauxMixiteFormViewHelper etapeTauxMixiteForm(?\Application\Form\OffreFormation\TauxMixite\TauxMixiteForm $form = null)
 * @method \Application\View\Helper\OffreFormation\EtapeViewHelper etape(?\Application\Entity\Db\Etape $etape = null)
 * @method \Application\View\Helper\OffreFormation\FieldsetElementPedagogiqueRecherche fieldsetElementPedagogiqueRecherche(?\Application\Form\OffreFormation\ElementPedagogiqueRechercheFieldset $fieldset = null)
 * @method \Application\View\Helper\Paiement\DemandeMiseEnPaiementViewHelper demandeMiseEnPaiement(array $servicesAPayer, $changeIndex = null)
 * @method \Application\View\Helper\Paiement\TypeHeuresViewHelper typeHeures(?\Application\Entity\Db\TypeHeures $typeHeures = null)
 * @method \Application\View\Helper\ServiceReferentiel\FonctionReferentielViewHelper fonctionReferentiel(?\Referentiel\Entity\Db\FonctionReferentiel $fonctionReferentiel = null)
 * @method \Application\View\Helper\ServiceReferentiel\FormSaisieViewHelper formServiceReferentielSaisie(?\Referentiel\Form\Saisie $form = null)
 * @method \Application\View\Helper\ServiceReferentiel\LigneViewHelper serviceReferentielLigne(\Application\View\Helper\ServiceReferentiel\ListeViewHelper $liste, \Referentiel\Entity\Db\ServiceReferentiel $service)
 * @method \Application\View\Helper\ServiceReferentiel\ListeViewHelper serviceReferentielListe($services)
 * @method \Application\View\Helper\Service\ResumeViewHelper serviceResume($resumeServices)
 * @method \Application\View\Helper\Service\SaisieForm serviceSaisieForm(?\Application\Form\Service\Saisie $form = null)
 * @method \Application\View\Helper\StructureViewHelper structure(?\Application\Entity\Db\Structure $structure = null)
 * @method \Application\View\Helper\TreeViewHelper tree(\Application\Model\TreeNode $tree, array $attributes = [])
 * @method \Application\View\Helper\TypeInterventionAdminViewHelper typeInterventionAdmin(\Application\Entity\Db\TypeIntervention $typeIntervention)
 * @method \Application\View\Helper\UserCurrent userCurrent($affectationFineSiDispo = false)
 * @method \Application\View\Helper\UserProfileSelectRadioItem userProfileSelectRadioItem(Laminas\Permissions\Acl\Role\RoleInterface $role, $selected = false)
 * @method string utilisateur(\Application\Entity\Db\Utilisateur $utilisateur, $title = null, $subject = null, $body = null)
 * @method \Application\View\Helper\ValidationViewHelper validation(?\Application\Entity\Db\Validation $validation = null)
 * @method \Application\View\Helper\VolumeHoraire\Liste volumeHoraireListe(\Enseignement\Entity\VolumeHoraireListe $volumeHoraireListe)
 * @method \Application\View\Helper\VolumeHoraire\ListeCalendaire volumeHoraireListeCalendaire(\Enseignement\Entity\VolumeHoraireListe $volumeHoraireListe)
 * @method \Enseignement\View\Helper\EnseignementsViewHelper enseignements($services)
 * @method \Enseignement\View\Helper\LigneEnseignementViewHelper ligneEnseignement(\Enseignement\View\Helper\EnseignementsViewHelper $enseignements, \Enseignement\Entity\Db\Service $service)
 * @method \Laminas\Form\View\Helper\Captcha\Dumb captchaDumb(?Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\Captcha\Dumb formCaptchaDumb(?Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\Captcha\Figlet captchaFiglet(?Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\Captcha\Figlet formCaptchaFiglet(?Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\Captcha\Image captchaImage(?Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\Captcha\Image formCaptchaImage(?Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\Captcha\ReCaptcha captchaRecaptcha(?Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\Captcha\ReCaptcha formCaptchaRecaptcha(?Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\File\FormFileApcProgress formFileApcProgress(?Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\File\FormFileSessionProgress formFileSessionProgress(?Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\File\FormFileUploadProgress formFileUploadProgress(?Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\Form form(?Laminas\Form\FormInterface $form = null)
 * @method \Laminas\Form\View\Helper\FormButton formButton(?Laminas\Form\ElementInterface $element = null, ?string $buttonContent = null)
 * @method \Laminas\Form\View\Helper\FormCaptcha formCaptcha(?\Laminas\Form\Element\Captcha $element = null)
 * @method \Laminas\Form\View\Helper\FormCheckbox formCheckbox(?Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\FormCollection formCollection(?Laminas\Form\ElementInterface $element = null, bool $wrap = true)
 * @method \Laminas\Form\View\Helper\FormColor formColor(?Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\FormDateSelect formDateSelect(?Laminas\Form\ElementInterface $element = null, int $dateType = 1, ?string $locale = null)
 * @method \Laminas\Form\View\Helper\FormDateTimeLocal formDateTimeLocal(?Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\FormDateTimeSelect formDateTimeSelect(?Laminas\Form\ElementInterface $element = null, int $dateType = 1, int $timeType = 1, ?string $locale = null)
 * @method \Laminas\Form\View\Helper\FormElement formElement(?\Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\FormElementErrors formElementErrors(?Laminas\Form\ElementInterface $element = null, array $attributes = [])
 * @method \Laminas\Form\View\Helper\FormEmail formEmail(?Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\FormFile formFile(?Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\FormHidden formHidden(?Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\FormImage formImage(?Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\FormInput formInput(?Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\FormLabel formLabel(?Laminas\Form\ElementInterface $element = null, ?string $labelContent = null, ?string $position = null)
 * @method \Laminas\Form\View\Helper\FormMonth formMonth(?Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\FormMonthSelect formMonthSelect(?Laminas\Form\ElementInterface $element = null, int $dateType = 1, ?string $locale = null)
 * @method \Laminas\Form\View\Helper\FormMultiCheckbox formMultiCheckbox(?Laminas\Form\ElementInterface $element = null, ?string $labelPosition = null)
 * @method \Laminas\Form\View\Helper\FormNumber formNumber(?Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\FormPassword formPassword(?Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\FormRadio formRadio(?Laminas\Form\ElementInterface $element = null, ?string $labelPosition = null)
 * @method \Laminas\Form\View\Helper\FormRange formRange(?Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\FormReset formReset(?Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\FormRow formRow(?Laminas\Form\ElementInterface $element = null, ?string $labelPosition = null, ?bool $renderErrors = null, ?string $partial = null)
 * @method \Laminas\Form\View\Helper\FormSearch formSearch(?Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\FormSelect formSelect(?Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\FormSubmit formSubmit(?Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\FormTel formTel(?Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\FormText formText(?Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\FormTextarea formTextarea(?Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\FormTextarea formTextArea(?Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\FormTime formTime(?Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\FormUrl formUrl(?Laminas\Form\ElementInterface $element = null)
 * @method \Laminas\Form\View\Helper\FormWeek formWeek(?Laminas\Form\ElementInterface $element = null)
 * @method string currencyFormat($number, $currencyCode = null, $showDecimals = null, $locale = null, $pattern = null)
 * @method string dateFormat($date, $dateType = -1, $timeType = -1, $locale = null, $pattern = null)
 * @method string numberFormat($number, $formatStyle = null, $formatType = null, $locale = null, $decimals = null, ?array $textAttributes = null)
 * @method string plural($strings, $number)
 * @method string translate($message, $textDomain = null, $locale = null)
 * @method string translatePlural($singular, $plural, $number, $textDomain = null, $locale = null)
 * @method \Laminas\Mvc\Plugin\FlashMessenger\View\Helper\FlashMessenger flashMessenger($namespace = null)
 * @method \Laminas\Mvc\Plugin\FlashMessenger\View\Helper\FlashMessenger zendviewhelperflashmessenger($namespace = null)
 * @method \Laminas\Mvc\Plugin\FlashMessenger\View\Helper\FlashMessenger laminasviewhelperflashmessenger($namespace = null)
 * @method \Laminas\View\Helper\Asset asset($asset)
 * @method \Laminas\View\Helper\Asset zendviewhelperasset($asset)
 * @method string basePath($file = null)
 * @method string zendviewhelperbasepath($file = null)
 * @method \Laminas\View\Helper\Cycle cycle(array $data = [], $name = 'default')
 * @method \Laminas\View\Helper\Cycle zendviewhelpercycle(array $data = [], $name = 'default')
 * @method \Laminas\View\Helper\DeclareVars declareVars()
 * @method \Laminas\View\Helper\DeclareVars zendviewhelperdeclarevars()
 * @method \Laminas\View\Helper\Doctype doctype($doctype = null)
 * @method \Laminas\View\Helper\Doctype zendviewhelperdoctype($doctype = null)
 * @method \Laminas\View\Helper\EscapeCss escapeCss($value, $recurse = 0)
 * @method \Laminas\View\Helper\EscapeCss zendviewhelperescapecss($value, $recurse = 0)
 * @method \Laminas\View\Helper\EscapeHtml escapeHtml($value, $recurse = 0)
 * @method \Laminas\View\Helper\EscapeHtml zendviewhelperescapehtml($value, $recurse = 0)
 * @method \Laminas\View\Helper\EscapeHtmlAttr escapeHtmlAttr($value, $recurse = 0)
 * @method \Laminas\View\Helper\EscapeHtmlAttr zendviewhelperescapehtmlattr($value, $recurse = 0)
 * @method \Laminas\View\Helper\EscapeJs escapeJs($value, $recurse = 0)
 * @method \Laminas\View\Helper\EscapeJs zendviewhelperescapejs($value, $recurse = 0)
 * @method \Laminas\View\Helper\EscapeUrl escapeUrl($value, $recurse = 0)
 * @method \Laminas\View\Helper\EscapeUrl zendviewhelperescapeurl($value, $recurse = 0)
 * @method \Laminas\View\Helper\Gravatar gravatar($email = '', $options = [], $attributes = [])
 * @method \Laminas\View\Helper\Gravatar zendviewhelpergravatar($email = '', $options = [], $attributes = [])
 * @method \Laminas\View\Helper\HeadLink headLink(?array $attributes = null, $placement = 'APPEND')
 * @method \Laminas\View\Helper\HeadLink zendviewhelperheadlink(?array $attributes = null, $placement = 'APPEND')
 * @method \Laminas\View\Helper\HeadMeta headMeta($content = null, $keyValue = null, $keyType = 'name', $modifiers = [], $placement = 'APPEND')
 * @method \Laminas\View\Helper\HeadMeta zendviewhelperheadmeta($content = null, $keyValue = null, $keyType = 'name', $modifiers = [], $placement = 'APPEND')
 * @method \Laminas\View\Helper\HeadScript headScript($mode = 'FILE', $spec = null, $placement = 'APPEND', array $attrs = [], $type = 'text/javascript')
 * @method \Laminas\View\Helper\HeadScript zendviewhelperheadscript($mode = 'FILE', $spec = null, $placement = 'APPEND', array $attrs = [], $type = 'text/javascript')
 * @method \Laminas\View\Helper\HeadStyle headStyle($content = null, $placement = 'APPEND', $attributes = [])
 * @method \Laminas\View\Helper\HeadStyle zendviewhelperheadstyle($content = null, $placement = 'APPEND', $attributes = [])
 * @method \Laminas\View\Helper\HeadTitle headTitle($title = null, $setType = null)
 * @method \Laminas\View\Helper\HeadTitle zendviewhelperheadtitle($title = null, $setType = null)
 * @method \Laminas\View\Helper\HtmlAttributes htmlAttributes(iterable $attributes = [])
 * @method string htmlFlash($data, array $attribs = [], array $params = [], $content = null)
 * @method string zendviewhelperhtmlflash($data, array $attribs = [], array $params = [], $content = null)
 * @method \Laminas\View\Helper\HtmlList htmlList(array $items, $ordered = false, $attribs = false, $escape = true)
 * @method \Laminas\View\Helper\HtmlList zendviewhelperhtmllist(array $items, $ordered = false, $attribs = false, $escape = true)
 * @method string htmlObject($data = null, $type = null, array $attribs = [], array $params = [], $content = null)
 * @method string zendviewhelperhtmlobject($data = null, $type = null, array $attribs = [], array $params = [], $content = null)
 * @method string htmlPage($data, array $attribs = [], array $params = [], $content = null)
 * @method string zendviewhelperhtmlpage($data, array $attribs = [], array $params = [], $content = null)
 * @method string htmlQuicktime($data, array $attribs = [], array $params = [], $content = null)
 * @method string zendviewhelperhtmlquicktime($data, array $attribs = [], array $params = [], $content = null)
 * @method \Laminas\View\Helper\HtmlTag htmlTag(array $attribs = [])
 * @method \Laminas\View\Helper\HtmlTag zendviewhelperhtmltag(array $attribs = [])
 * @method \Laminas\View\Helper\Identity identity()
 * @method \Laminas\View\Helper\Identity zendviewhelperidentity()
 * @method \Laminas\View\Helper\InlineScript inlineScript($mode = 'FILE', $spec = null, $placement = 'APPEND', array $attrs = [], $type = 'text/javascript')
 * @method \Laminas\View\Helper\InlineScript zendviewhelperinlinescript($mode = 'FILE', $spec = null, $placement = 'APPEND', array $attrs = [], $type = 'text/javascript')
 * @method \Laminas\View\Helper\Json json($data, array $jsonOptions = [])
 * @method \Laminas\View\Helper\Json zendviewhelperjson($data, array $jsonOptions = [])
 * @method \Laminas\View\Helper\Layout layout($template = null)
 * @method \Laminas\View\Helper\Layout zendviewhelperlayout($template = null)
 * @method string paginationControl(?\Laminas\Paginator\Paginator $paginator = null, $scrollingStyle = null, $partial = null, $params = null)
 * @method string zendviewhelperpaginationcontrol(?\Laminas\Paginator\Paginator $paginator = null, $scrollingStyle = null, $partial = null, $params = null)
 * @method \Laminas\View\Helper\Partial partial($name = null, $values = null)
 * @method \Laminas\View\Helper\Partial zendviewhelperpartial($name = null, $values = null)
 * @method \Laminas\View\Helper\PartialLoop partialLoop($name = null, $values = null)
 * @method \Laminas\View\Helper\PartialLoop zendviewhelperpartialloop($name = null, $values = null)
 * @method \Laminas\View\Helper\Placeholder placeholder($name = null)
 * @method \Laminas\View\Helper\Placeholder zendviewhelperplaceholder($name = null)
 * @method string renderChildModel($child)
 * @method string zendviewhelperrenderchildmodel($child)
 * @method \Laminas\View\Helper\RenderToPlaceholder renderToPlaceholder($script, $placeholder)
 * @method \Laminas\View\Helper\RenderToPlaceholder zendviewhelperrendertoplaceholder($script, $placeholder)
 * @method string serverUrl($requestUri = null)
 * @method string zendviewhelperserverurl($requestUri = null)
 * @method \Laminas\View\Helper\Url url($name = null, $params = [], $options = [], $reuseMatchedParams = false)
 * @method \Laminas\View\Helper\Url zendviewhelperurl($name = null, $params = [], $options = [], $reuseMatchedParams = false)
 * @method \Laminas\View\Helper\ViewModel viewModel()
 * @method \Laminas\View\Helper\ViewModel zendviewhelperviewmodel()
 * @method \Plafond\View\Helper\PlafondConfigElementViewHelper plafondConfig(?Plafond\Interfaces\PlafondConfigInterface $plafondConfig = null)
 * @method \Plafond\View\Helper\PlafondsViewHelper plafonds(\Application\Entity\Db\Structure|\Application\Entity\Db\Intervenant|\Application\Entity\Db\ElementPedagogique|\Enseignement\Entity\Db\VolumeHoraire|\Referentiel\Entity\Db\FonctionReferentiel $entity, \Service\Entity\Db\TypeVolumeHoraire $typeVolumeHoraire)
 * @method \UnicaenApp\Form\View\Helper\FormAdvancedMultiCheckbox formAdvancedMultiCheckbox(?Laminas\Form\ElementInterface $element = null, ?string $labelPosition = null)
 * @method \UnicaenApp\Form\View\Helper\FormControlGroup formControlGroup(?Laminas\Form\ElementInterface $element = null, $pluginClass = 'formElement')
 * @method \UnicaenApp\Form\View\Helper\FormDate formDate(?\UnicaenApp\Form\Element\Date $element = null, $dateReadonly = false)
 * @method \UnicaenApp\Form\View\Helper\FormDateInfSup formDateInfSup(?\UnicaenApp\Form\Element\DateInfSup $element = null, $dateInfReadonly = false, $dateSupReadonly = false)
 * @method \UnicaenApp\Form\View\Helper\FormDateTime formDateTime(?Laminas\Form\ElementInterface $element = null)
 * @method \UnicaenApp\Form\View\Helper\FormErrors formErrors(?\Laminas\Form\Form $form = null, $message = null)
 * @method \UnicaenApp\Form\View\Helper\FormLdapPeople formLdapPeople(?Laminas\Form\ElementInterface $element = null)
 * @method \UnicaenApp\Form\View\Helper\FormRowDateInfSup formRowDateInfSup(?Laminas\Form\ElementInterface $element = null, ?string $labelPosition = null, ?bool $renderErrors = null, ?string $partial = null)
 * @method \UnicaenApp\Form\View\Helper\MultipageFormFieldset multipageFormFieldset(?\Laminas\Form\Fieldset $fieldset = null)
 * @method \UnicaenApp\Form\View\Helper\MultipageFormNav multipageFormNav(?\UnicaenApp\Form\Fieldset\MultipageFormNavFieldset $fieldset = null)
 * @method \UnicaenApp\Form\View\Helper\MultipageFormRecap multipageFormRecap(?\UnicaenApp\Form\MultipageForm $form = null)
 * @method \UnicaenApp\Form\View\Helper\MultipageFormRow multipageFormRow(?Laminas\Form\ElementInterface $element = null, $labelPosition = null, $renderErrors = null, $partial = null)
 * @method \UnicaenApp\Message\View\Helper\MessageHelper message()
 * @method \UnicaenApp\View\Helper\AppInfos appInfos()
 * @method \UnicaenApp\View\Helper\ConfirmHelper confirm($message = null)
 * @method \UnicaenApp\View\Helper\HistoriqueViewHelper historique(?UnicaenApp\Entity\HistoriqueAwareInterface $entity = null)
 * @method \UnicaenApp\View\Helper\InstadiaViewHelper instadia()
 * @method \UnicaenApp\View\Helper\MessageCollectorHelper messageCollector($severity = null)
 * @method \UnicaenApp\View\Helper\Messenger messenger()
 * @method string modalAjaxDialog($dialogDivId = null)
 * @method \UnicaenApp\View\Helper\QueryParams queryParams()
 * @method \UnicaenApp\View\Helper\TabAjax\TabAjaxViewHelper tabajax($tabs = null, $selected = null)
 * @method \UnicaenApp\View\Helper\TagViewHelper tag($name = null, array $attributes = [])
 * @method \UnicaenApp\View\Helper\ToggleDetails toggleDetails($detailsDivId, $rememberState = true)
 * @method \UnicaenApp\View\Helper\Upload\UploaderHelper uploader()
 * @method \UnicaenAuth\View\Helper\AppConnection appConnection()
 * @method \UnicaenAuth\View\Helper\CasConnectViewHelper casConnect(\Laminas\Form\Form $form)
 * @method \UnicaenAuth\View\Helper\ConnectViewHelper connect(string $type, \Laminas\Form\Form $form)
 * @method \UnicaenAuth\View\Helper\DbConnectViewHelper dbConnect(\Laminas\Form\Form $form)
 * @method \UnicaenAuth\View\Helper\LdapConnectViewHelper ldapConnect(\Laminas\Form\Form $form)
 * @method \UnicaenAuth\View\Helper\LocalConnectViewHelper localConnect(\Laminas\Form\Form $form)
 * @method \UnicaenAuth\View\Helper\ShibConnectViewHelper shibConnect(\Laminas\Form\Form $form)
 * @method \UnicaenAuth\View\Helper\UserConnection userConnection()
 * @method \UnicaenAuth\View\Helper\UserInfo userInfo($affectationPrincipale = false)
 * @method \UnicaenAuth\View\Helper\UserProfile userProfile($userProfileSelectable = false)
 * @method \UnicaenAuth\View\Helper\UserProfileSelect userProfileSelect()
 * @method \UnicaenAuth\View\Helper\UserStatus userStatus($displayConnectionLink = true)
 * @method \UnicaenAuth\View\Helper\UserUsurpationHelper userUsurpation()
 * @method \UnicaenImport\View\Helper\DifferentielLigne\DifferentielLigne differentielLigne(\UnicaenImport\Entity\Differentiel\Ligne $ligne)
 * @method \UnicaenImport\View\Helper\DifferentielListe differentielListe($lignes)
 *
 * @author UnicaenCode
 */
class PhpRenderer extends \Laminas\View\Renderer\PhpRenderer
{

}