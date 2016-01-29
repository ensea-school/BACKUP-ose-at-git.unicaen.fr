<?php

namespace Application\View\Renderer;

/**
 * Description of PhpRenderer
 *
 * Permet d'utiliser les aides de vue avec de l'auto-complétion et de rendre le Refactoring des aides de vues efficace
 *
 * @method \Zend\View\Helper\Cycle cycle(array $data = [], $name = 'default')
 * @method \Zend\View\Helper\DeclareVars declarevars()
 * @method \Zend\View\Helper\EscapeHtml escapehtml($value, $recurse = 0)
 * @method \Zend\View\Helper\EscapeHtmlAttr escapehtmlattr($value, $recurse = 0)
 * @method \Zend\View\Helper\EscapeJs escapejs($value, $recurse = 0)
 * @method \Zend\View\Helper\EscapeCss escapecss($value, $recurse = 0)
 * @method \Zend\View\Helper\EscapeUrl escapeurl($value, $recurse = 0)
 * @method \Zend\View\Helper\Gravatar gravatar($email = '', $options = [], $attribs = [])
 * @method \Zend\View\Helper\HtmlTag htmltag(array $attribs = [])
 * @method \Zend\View\Helper\HeadMeta headmeta($content = null, $keyValue = null, $keyType = 'name', $modifiers = [], $placement = 'APPEND')
 * @method \Zend\View\Helper\HeadStyle headstyle($content = null, $placement = 'APPEND', $attributes = [])
 * @method \Zend\View\Helper\HeadTitle headtitle($title = null, $setType = null)
 * @method string htmlflash($data, array $attribs = [], array $params = [], $content = null)
 * @method \Zend\View\Helper\HtmlList htmllist(array $items, $ordered = false, $attribs = false, $escape = true)
 * @method string htmlobject($data = null, $type = null, array $attribs = [], array $params = [], $content = null)
 * @method string htmlpage($data, array $attribs = [], array $params = [], $content = null)
 * @method string htmlquicktime($data, array $attribs = [], array $params = [], $content = null)
 * @method \Zend\View\Helper\Json json($data, array $jsonOptions = [])
 * @method \Zend\View\Helper\Layout layout($template = null)
 * @method string paginationcontrol(\Zend\Paginator\Paginator $paginator = null, $scrollingStyle = null, $partial = null, $params = null)
 * @method string partialloop($name = null, $values = null)
 * @method \Zend\View\Helper\Partial partial($name = null, $values = null)
 * @method \Zend\View\Helper\Placeholder placeholder($name = null)
 * @method string renderchildmodel($child)
 * @method \Zend\View\Helper\RenderToPlaceholder rendertoplaceholder($script, $placeholder)
 * @method string serverurl($requestUri = null)
 * @method \Zend\View\Helper\ViewModel viewmodel()
 * @method \Zend\Form\View\Helper\Form form(\Zend\Form\FormInterface $form = null)
 * @method \Zend\Form\View\Helper\FormButton formbutton(\Zend\Form\ElementInterface $element = null, $buttonContent = null)
 * @method \Zend\Form\View\Helper\FormCaptcha formcaptcha(\Zend\Form\ElementInterface $element = null)
 * @method string captchadumb(\Zend\Form\ElementInterface $element = null)
 * @method string captchafiglet(\Zend\Form\ElementInterface $element = null)
 * @method string captchaimage(\Zend\Form\ElementInterface $element = null)
 * @method string captcharecaptcha(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormCheckbox formcheckbox(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormCollection formcollection(\Zend\Form\ElementInterface $element = null, $wrap = true)
 * @method \Zend\Form\View\Helper\FormColor formcolor(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormDateTime formdatetime(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormDateTimeLocal formdatetimelocal(\Zend\Form\ElementInterface $element = null)
 * @method string formdatetimeselect(\Zend\Form\ElementInterface $element = null, $dateType = 1, $timeType = 1, $locale = null)
 * @method \Zend\Form\View\Helper\FormDateSelect formdateselect(\Zend\Form\ElementInterface $element = null, $dateType = 1, $locale = null)
 * @method \Zend\Form\View\Helper\FormElement formelement(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormElementErrors formelementerrors(\Zend\Form\ElementInterface $element = null, array $attributes = [])
 * @method \Zend\Form\View\Helper\FormEmail formemail(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormFile formfile(\Zend\Form\ElementInterface $element = null)
 * @method string formfileapcprogress(\Zend\Form\ElementInterface $element = null)
 * @method string formfilesessionprogress(\Zend\Form\ElementInterface $element = null)
 * @method string formfileuploadprogress(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormHidden formhidden(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormImage formimage(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormInput forminput(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormLabel formlabel(\Zend\Form\ElementInterface $element = null, $labelContent = null, $position = null)
 * @method \Zend\Form\View\Helper\FormMonth formmonth(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormMonthSelect formmonthselect(\Zend\Form\ElementInterface $element = null, $dateType = 1, $locale = null)
 * @method \Zend\Form\View\Helper\FormMultiCheckbox formmulticheckbox(\Zend\Form\ElementInterface $element = null, $labelPosition = null)
 * @method \Zend\Form\View\Helper\FormNumber formnumber(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormPassword formpassword(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormRadio formradio(\Zend\Form\ElementInterface $element = null, $labelPosition = null)
 * @method \Zend\Form\View\Helper\FormRange formrange(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormReset formreset(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormRow formrow(\Zend\Form\ElementInterface $element = null, $labelPosition = null, $renderErrors = null, $partial = null)
 * @method \Zend\Form\View\Helper\FormSearch formsearch(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormSelect formselect(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormSubmit formsubmit(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormTel formtel(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormText formtext(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormTextarea formtextarea(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormTime formtime(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormUrl formurl(\Zend\Form\ElementInterface $element = null)
 * @method \Zend\Form\View\Helper\FormWeek formweek(\Zend\Form\ElementInterface $element = null)
 * @method string currencyformat($number, $currencyCode = null, $showDecimals = null, $locale = null, $pattern = null)
 * @method string dateformat($date, $dateType = -1, $timeType = -1, $locale = null, $pattern = null)
 * @method string numberformat($number, $formatStyle = null, $formatType = null, $locale = null, $decimals = null)
 * @method string plural($strings, $number)
 * @method string translate($message, $textDomain = null, $locale = null)
 * @method string translateplural($singular, $plural, $number, $textDomain = null, $locale = null)
 * @method string zenddevelopertoolstime($time, $precision = 2)
 * @method string zenddevelopertoolsmemory($size, $precision = 2)
 * @method string zenddevelopertoolsdetailarray($label, array $details, $redundant = false)
 * @method \UnicaenAuth\View\Helper\AppConnection appconnection()
 * @method \UnicaenApp\View\Helper\Messenger messenger()
 * @method string modalajaxdialog($dialogDivId = null)
 * @method \UnicaenApp\View\Helper\ConfirmHelper confirm($message = null)
 * @method \UnicaenApp\View\Helper\ToggleDetails toggledetails($detailsDivId, $title = null, $iconClass = null)
 * @method string multipageformfieldset()
 * @method string multipageformnav(\UnicaenApp\Form\Element\MultipageFormNav $element)
 * @method \UnicaenApp\Form\View\Helper\MultipageFormRow multipageformrow(\Zend\Form\ElementInterface $element = null, $labelPosition = null, $renderErrors = null, $partial = null)
 * @method string multipageformrecap()
 * @method \UnicaenApp\Form\View\Helper\FormControlGroup formcontrolgroup(\Zend\Form\ElementInterface $element = null, $pluginClass = 'formElement')
 * @method \UnicaenApp\Form\View\Helper\FormDate formdate(\UnicaenApp\Form\Element\Date $element = null, $dateReadonly = false)
 * @method \UnicaenApp\Form\View\Helper\FormDateInfSup formdateinfsup(\UnicaenApp\Form\Element\DateInfSup $element = null, $dateInfReadonly = false, $dateSupReadonly = false)
 * @method \UnicaenApp\Form\View\Helper\FormRowDateInfSup formrowdateinfsup(\Zend\Form\ElementInterface $element = null, $labelPosition = null, $renderErrors = null, $partial = null)
 * @method \UnicaenApp\Form\View\Helper\FormSearchAndSelect formsearchandselect(\Zend\Form\ElementInterface $element = null)
 * @method \UnicaenApp\Form\View\Helper\FormLdapPeople formldappeople(\Zend\Form\ElementInterface $element = null)
 * @method \UnicaenApp\Form\View\Helper\FormErrors formerrors(\Zend\Form\Form $form = null, $message = null)
 * @method \UnicaenApp\View\Helper\MessageCollectorHelper messagecollector()
 * @method \UnicaenApp\View\Helper\HeadScript headscript($mode = 'FILE', $spec = null, $placement = 'APPEND', array $attrs = [], $type = 'text/javascript')
 * @method \UnicaenApp\View\Helper\InlineScript inlinescript($mode = 'FILE', $spec = null, $placement = 'APPEND', array $attrs = [], $type = 'text/javascript')
 * @method \UnicaenApp\View\Helper\HeadLink headlink(array $attributes = null, $placement = 'APPEND')
 * @method \UnicaenApp\View\Helper\Upload\UploaderHelper uploader()
 * @method \UnicaenApp\Form\View\Helper\FormAdvancedMultiCheckbox formadvancedmulticheckbox(\Zend\Form\ElementInterface $element = null, $labelPosition = null)
 * @method \UnicaenApp\View\Helper\HistoriqueViewHelper historique(\UnicaenApp\Entity\HistoriqueAwareInterface $entity = null)
 * @method \UnicaenApp\View\Helper\TabAjax\TabAjaxViewHelper tabajax($tabs = null)
 * @method \UnicaenApp\View\Helper\TagViewHelper tag($name = null, array $attributes = [])
 * @method \Application\View\Helper\CartridgeViewHelper cartridge(array $items, array $options = [])
 * @method \Application\View\Helper\FormButtonGroup formbuttongroup(\Zend\Form\ElementInterface $element = null, $labelPosition = null)
 * @method \Application\View\Helper\ValidationViewHelper validation(\Application\Entity\Db\Validation $validation = null)
 * @method string utilisateur(\Application\Entity\Db\Utilisateur $utilisateur, $title = null, $subject = null, $body = null)
 * @method \Application\View\Helper\FormSupprimerViewHelper formsupprimer($form)
 * @method \Application\View\Helper\Intervenant\TotauxHetdViewHelper formuletotauxhetd(\Application\Entity\Db\FormuleResultat $formuleResultat)
 * @method \Application\View\Helper\Intervenant\IntervenantViewHelper intervenant(\Application\Entity\Db\Intervenant $intervenant = null)
 * @method \Application\View\Helper\StructureViewHelper structure(\Application\Entity\Db\Structure $structure = null)
 * @method \Application\View\Helper\EtablissementViewHelper etablissement(\Application\Entity\Db\Etablissement $etablissement = null)
 * @method \Application\View\Helper\Service\SaisieForm servicesaisieform(\Application\Form\Service\Saisie $form = null)
 * @method \Application\View\Helper\ServiceReferentiel\FormSaisie formservicereferentielsaisie(\Application\Form\ServiceReferentiel\Saisie $form = null)
 * @method \Application\View\Helper\Service\Resume serviceresume($resumeServices)
 * @method \Application\View\Helper\ServiceReferentiel\FonctionReferentielViewHelper fonctionreferentiel(\Application\Entity\Db\FonctionReferentiel $fonctionReferentiel = null)
 * @method \Application\View\Helper\VolumeHoraire\Liste volumehoraireliste(\Application\Entity\VolumeHoraireListe $volumeHoraireListe)
 * @method \Application\View\Helper\VolumeHoraireReferentiel\Liste volumehorairereferentielliste(\Application\Entity\VolumeHoraireReferentielListe $volumeHoraireListe)
 * @method \Application\View\Helper\OffreFormation\EtapeModulateursSaisieForm etapemodulateurssaisieform(\Application\Form\OffreFormation\EtapeModulateursSaisie $form = null)
 * @method \Application\View\Helper\OffreFormation\ElementModulateursSaisieFieldset elementmodulateurssaisiefieldset(\Application\Form\OffreFormation\ElementModulateursFieldset $fieldset = null)
 * @method \Application\View\Helper\OffreFormation\ElementPedagogiqueViewHelper elementpedagogique(\Application\Entity\Db\ElementPedagogique $elementPedagogique = null)
 * @method \Application\View\Helper\OffreFormation\EtapeViewHelper etape(\Application\Entity\Db\Etape $etape = null)
 * @method \Application\View\Helper\OffreFormation\EtapeCentreCoutFormViewHelper etapecentrecoutform(\Application\Form\OffreFormation\EtapeCentreCout\EtapeCentreCoutForm $form = null)
 * @method \Application\View\Helper\OffreFormation\ElementCentreCoutFieldsetViewHelper elementcentrecoutfieldset(\Application\Form\OffreFormation\EtapeCentreCout\ElementCentreCoutFieldset $fieldset = null)
 * @method \Application\View\Helper\OffreFormation\FieldsetElementPedagogiqueRecherche fieldsetelementpedagogiquerecherche(\Application\Form\OffreFormation\ElementPedagogiqueRechercheFieldset $fieldset = null)
 * @method \Application\View\Helper\AgrementViewHelper agrement(\Application\Entity\Db\Agrement $agrement = null)
 * @method \Application\View\Helper\Workflow workflow(\Application\Entity\Db\Intervenant $intervenant, \Zend\Permissions\Acl\Role\RoleInterface $role)
 * @method \Application\View\Helper\Paiement\DemandeMiseEnPaiementViewHelper demandemiseenpaiement(array $servicesAPayer, $changeIndex = null)
 * @method \Application\View\Helper\Paiement\TypeHeuresViewHelper typeheures(\Application\Entity\Db\TypeHeures $typeHeures = null)
 * @method \Import\View\Helper\DifferentielListe differentielliste($lignes)
 * @method \Import\View\Helper\DifferentielLigne\DifferentielLigne differentielligne(\Import\Entity\Differentiel\Ligne $ligne)
 *
 * @author UnicaenCode
 */
class PhpRenderer extends \Zend\View\Renderer\PhpRenderer {



}