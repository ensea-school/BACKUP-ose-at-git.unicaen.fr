<?php

namespace Paiement\View\Helper;

use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\ParametresServiceAwareTrait;
use Formule\Entity\Db\FormuleResultatService;
use Formule\Entity\Db\FormuleResultatServiceReferentiel;
use Laminas\View\Helper\AbstractHtmlElement;
use Lieu\Entity\Db\Structure;
use Mission\Entity\Db\Mission;
use OffreFormation\Entity\Db\TypeHeures;
use OffreFormation\Service\Traits\TypeHeuresServiceAwareTrait;
use Paiement\Controller\BudgetController;
use Paiement\Entity\Db\DomaineFonctionnel;
use Paiement\Entity\Db\MiseEnPaiement;
use Paiement\Entity\Db\ServiceAPayerInterface;
use Paiement\Entity\Db\TypeRessource;
use Paiement\Service\DomaineFonctionnelServiceAwareTrait;
use UnicaenApp\View\Helper\TagViewHelper;
use UnicaenPrivilege\Guard\PrivilegeController;

/**
 * Description of DemandeMiseEnPaiementViewHelper
 *
 * @author Laurent LECLUSE <laurent.lecluse at unicaen.fr>
 */
class DemandeMiseEnPaiementViewHelper extends AbstractHtmlElement
{
    use DomaineFonctionnelServiceAwareTrait;
    use TypeHeuresServiceAwareTrait;
    use ContextServiceAwareTrait;
    use ParametresServiceAwareTrait;

    private $servicesAPayer = [];

    /**
     *
     * @var \Laminas\Form\Form
     */
    private $form;

    private static $miseEnPaiementListeIdSequence = 1;

    /**
     * Mise lecture seule
     *
     * @var boolean
     */
    private $readOnly = false;

    /**
     * @var array
     */
    private $budget = [];

    /**
     * Liste des domaines fonctionnels
     *
     * @var array
     */
    protected $domainesFonctionnels;

    /**
     * @var integer
     */
    protected $changeIndex;



    /**
     * Helper entry point.
     *
     * @param ServiceAPayerInterface[] $servicesAPayer
     *
     * @return self
     */
    final public function __invoke(array $servicesAPayer, $changeIndex = null)
    {
        $this->setServicesAPayer($servicesAPayer);
        $this->changeIndex = $changeIndex;

        return $this;
    }



    /**
     * Retourne le code HTML généré par cette aide de vue.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }



    /**
     *
     * @return \Laminas\Form\Form
     */
    public function getForm()
    {
        if (null === $this->form) {
            $this->form = new \Laminas\Form\Form;
            $this->form->add(new \Laminas\Form\Element\Hidden('changements'));
            $this->form->add(new \Laminas\Form\Element\Hidden('change-index'));
            $this->form->add([
                'name'       => 'submit',
                'type'       => 'Submit',
                'attributes' => [
                    'value' => 'Enregistrer les demandes de paiement',
                    'class' => 'btn btn-primary sauvegarde',
                ],
            ]);

            $this->form->get('change-index')->setValue($this->changeIndex);
            $this->form->setAttribute('action', $this->getView()->url(null, [], [], true));
        }

        return $this->form;
    }



    public function render()
    {
        $canDemande = $this->getView()->isAllowed(Privileges::getResourceId(Privileges::MISE_EN_PAIEMENT_DEMANDE));

        $servicesAPayer = $this->getServicesAPayer();
        $attrs = [
            'id'          => $this->getId(),
            'class'       => 'demande-mise-en-paiement',
            'data-params' => json_encode($this->getParams()),
        ];
        $out = '';
        if ($canDemande && !empty($this->budget)) {
            $out .= $this->renderBudget();
        }
        $out .= '<div ' . $this->htmlAttribs($attrs) . '>';
        if ((!$this->getReadOnly()) && $canDemande) {
            $out .= '<div style="padding-bottom:1em"><button type="button" class="btn btn-secondary toutes-heures-non-dmep">Demander le paiement de toutes les HETD</button></div>';
        }
        foreach ($servicesAPayer as $serviceAPayer) {
            $out .= $this->renderServiceAPayer($serviceAPayer);
        }
        if (!$this->getReadOnly() && $canDemande) {
            $out .= '<div class="sauvegarde">';
            $out .= $this->getView()->form()->openTag($this->getForm());
            $out .= $this->getView()->formHidden($this->getForm()->get('changements'));
            $out .= $this->getView()->formHidden($this->getForm()->get('change-index'));
            $out .= $this->getView()->formRow($this->getForm()->get('submit'));
            $out .= $this->getView()->form()->closeTag();
            $out .= '</div>';
            $out .= '<div class="depassement-budget">';
            $out .= '<div class="alert alert-danger" role="alert">
                <i class="fas fa-circle-minus"></i>
                <h1>Dépassement de budget!!</h1>
                Les demandes de mise en paiement que vous avez saisies engendrent un ou plusieurs dépassements budgétaires.
                Vous ne pouvez donc pas enregistrer votre saisie en l\'état.
                </div>';
            $out .= '</div>';
        }
        $out .= '</div>';
        $out .= '<script type="text/javascript">';
        $out .= '$(function() { DemandeMiseEnPaiement.get("' . $this->getId() . '").init(); });';
        $out .= '</script>';

        return $out;
    }



    public function renderBudget()
    {
        $structures = $this->budget['structures'];
        /* @var $structures Structure[] */
        $typesRessources = $this->budget['typesRessources'];
        /* @var $typesRessources TypeRessource[] */
        $h = '';
        $t = $this->getView()->tag();
        /* @var $t TagViewHelper */

        $updadeUrl = $this->getView()->url('budget/get-json');

        $h .= $t('table', ['class' => 'table table-bordered dmep-budget', 'data-update-url' => $updadeUrl]);
        $h .= $t('tr');
        $h .= $t('th')->text('Budget');
        foreach ($typesRessources as $typeRessource) {
            $h .= $t('th')->text($typeRessource);
        }
        $h .= $t('tr')->close();
        foreach ($structures as $structure) {
            $sid = $structure->getId();
            $h .= $t('tr');
            if ($this->getView()->isAllowed(PrivilegeController::getResourceId(BudgetController::class, 'engagement'))) {
                $h .= $t('th')->html($t('a', ['href' => $this->getView()->url('budget/engagement', ['structure' => $structure->getId()])])->text($structure));
            } else {
                $h .= $t('th')->text($structure);
            }

            foreach ($typesRessources as $typeRessource) {
                $trid = $typeRessource->getId();
                if ($this->budget[$sid][$trid]['dotation'] !== 0) {
                    $h .= $t('td')->html(
                        $t('div', [
                            'class'               => 'progress enveloppe',
                            'data-structure'      => $sid,
                            'data-type-ressource' => $trid,
                            'data-dotation'       => $this->budget[$sid][$trid]['dotation'],
                            'data-usage'          => $this->budget[$sid][$trid]['usage'],
                            'style' => 'width: 100%',
                        ])->html(
                            $t('div', [
                                'class'         => 'progress-bar progress-bar-striped',
                                'role'          => 'progressbar',
                                'aria-valuenow' => 0,
                                'aria-valuemin' => 0,
                                'aria-valuemax' => 100,
                                'style'         => 'width:0%',
                            ])->html('<span class="restant">Calcul ...</span>')
                        )
                    );
                } else {
                    $h .= $t('td')->html($t('span', ['class' => 'aucune-dotation'])->text('Aucune dotation'));
                }
            }
            $h .= $t('tr')->close();
        }
        $h .= $t('table')->close();

        return $h;
    }



    public function renderServiceAPayer(ServiceAPayerInterface $serviceAPayer)
    {
        $out = '<div class="service-a-payer" id="' . $this->getServiceAPayerId($serviceAPayer) . '">';
        $out .= $this->renderHead($serviceAPayer);
        $typesHeures = $this->getServiceTypeHeures()->getList($this->getServiceTypeHeures()->finderByServiceaPayer($serviceAPayer));
        $out .= '<div class="row">';
        foreach ($typesHeures as $typeHeures) {
            $out .= $this->renderMiseEnPaiementListe($serviceAPayer, $typeHeures);
        }
        $out .= '</div>';
        $out .= '</div>';

        return $out;
    }



    public function renderHead(ServiceAPayerInterface $serviceAPayer)
    {
        $cartridgeItems = [];

        $cartridgeItems[] = $this->getView()->structure($serviceAPayer->getStructure())->renderLink();
        if ($serviceAPayer instanceof FormuleResultatService) {
            if ($serviceAPayer->getService()->getElementPedagogique()) {
                $cartridgeItems[] = $this->getView()->etape($serviceAPayer->getService()->getElementPedagogique()->getEtape())->renderLink();
                $cartridgeItems[] = $this->getView()->elementPedagogique($serviceAPayer->getService()->getElementPedagogique())->renderLink();
            } else {
                $cartridgeItems[] = 'Enseignements hors ' . $this->getServiceContext()->getEtablissement()->getLibelle();
                $cartridgeItems[] = $this->getView()->etablissement($serviceAPayer->getService()->getEtablissement())->renderLink();
            }
        } elseif ($serviceAPayer instanceof FormuleResultatServiceReferentiel) {
            $cartridgeItems[] = 'Référentiel';
            $cartridgeItems[] = $this->getView()->fonctionReferentiel($serviceAPayer->getServiceReferentiel()->getFonctionReferentiel())->renderLink();
        }

        return $this->getView()->cartridge($cartridgeItems, [
            'theme'      => 'gray',
            'attributes' => ['style' => 'padding-bottom: 5px'],
        ]);
    }



    public function renderMiseEnPaiementListe(ServiceAPayerInterface $serviceAPayer, TypeHeures $typeHeures)
    {
        $params = $this->getServiceAPayerParams($serviceAPayer, $typeHeures);

        $miseEnPaiement = new MiseEnPaiement;
        $miseEnPaiement->setServiceAPayer($serviceAPayer);
        $notAllowed = !$this->getView()->isAllowed($miseEnPaiement, Privileges::MISE_EN_PAIEMENT_DEMANDE);
        $readOnly = $this->getReadOnly() || $notAllowed;
        $saisieTerminee = ($params['heures-dmep'] + $params['heures-non-dmep']) == 0; // s'il reste des heures à positionner ou déjà positionnées

        $attrs = [
            'class' => ['type-heures', 'col-md-6'],
            'id'    => (string)$typeHeures->getId(),
            'style' => ['margin-bottom:.5em'],
        ];
        if ($notAllowed) $attrs['class'][] = 'not-allowed';
        $out = '<div ' . $this->htmlAttribs($attrs) . '>';

        $attrs = [
            'class'       => ['table', 'table-sm', 'table-xs', 'table-bordered', 'mise-en-paiement-liste'],
            'id'          => (string)self::$miseEnPaiementListeIdSequence++,
            'data-params' => json_encode($params),
        ];
        $hattrs = [
            'class' => [],
        ];
        if ($notAllowed && !$saisieTerminee) $hattrs['class'][] = 'bg-warning';
        if ($readOnly) $attrs['class'][] = 'read-only';
        if ($saisieTerminee) $hattrs['class'][] = 'bg-success';
        if (!$serviceAPayer->isPayable()) {
            $out .= '<div class="alert alert-danger" role="alert">Des heures à payer ont été positionnées sur ce service alors que c\'est normalement impossible.</div>';
        }
        $out .= '<table ' . $this->htmlAttribs($attrs) . '>';
        $out .= '<thead ' . $this->htmlAttribs($hattrs) . '><tr><th colspan="' . ($serviceAPayer->isDomaineFonctionnelModifiable() ? 4 : 3) . '">' . $typeHeures->getLibelleLong() . '</th></tr><tr>';
        $out .= '<th style="min-width:8em"><abbr title="Heures équivalent TD">HETD</abbr></th>';
        $out .= '<th>Centre de coûts</th>';
        if ($serviceAPayer->isDomaineFonctionnelModifiable()) {
            $out .= '<th>Domaine fonctionnel</th>';
        }
        $out .= '<th>&nbsp;</th>';
        $out .= '</tr></thead>';

        if ($params['heures-mep'] > 0) {
            $title = [];
            foreach ($params['mises-en-paiement'] as $periode => $heures) {
                $title[] = $periode . ' : ' . strip_tags(\UnicaenApp\Util::formattedNumber($heures)) . ' hetd mis en paiement';
            }
            $title = implode('&#13;', $title);
            $out .= '<tr><td class="nombre"><abbr title="' . $title . '">' . \UnicaenApp\Util::formattedNumber($params['heures-mep']) . '</td><td>HETD déjà mises en paiement</td><td></td></tr>';
        }
        $out .= '<tfoot>';

        if (!$saisieTerminee) {
            $out .= '<tr>';
            $out .= '<td class="nombre">';
            if (!$readOnly) $out .= '<button class="btn btn-secondary heures-non-dmep" type="button" title="Demander ces heures en paiement">';
            $out .= \UnicaenApp\Util::formattedNumber($params['heures-non-dmep']);
            if (!$readOnly) $out .= '</button>';
            $out .= '</td>';
            $out .= '<th>HETD restantes</th>';
            if ($serviceAPayer->isDomaineFonctionnelModifiable()) {
                $out .= '<td>&nbsp;</td>';
            }
            $out .= '<td>&nbsp;</td>';
            $out .= '</tr>';
        }
        $out .= '<tr class="active">';
        $out .= '<td class="nombre heures-total">' . \UnicaenApp\Util::formattedNumber($params['heures-total']) . '</td>';
        $out .= '<th>HETD au total</th>';
        $out .= '<td>&nbsp;</td>';
        if ($serviceAPayer->isDomaineFonctionnelModifiable()) {
            $out .= '<td>&nbsp;</td>';
        }
        $out .= '</tr></tfoot>';
        $out .= '</table>';
        $out .= '</div>';

        return $out;
    }



    public function getId()
    {
        return 'demande-mise-en-paiement';
    }



    /**
     *
     * @return array
     */
    protected function getParams()
    {
        $params = [
        ];

        return $params;
    }



    protected function getServiceAPayerParams(ServiceAPayerInterface $serviceAPayer, TypeHeures $typeHeures)
    {
        $defaultCentreCout = $serviceAPayer->getDefaultCentreCout($typeHeures);
        $defaultDomaineFonctionnel = $serviceAPayer->getDefaultDomaineFonctionnel($this->getServiceDomaineFonctionnel());

        $heuresTotal = $serviceAPayer->getHeuresCompl($typeHeures);
        if ($typeHeures->getCode() === TypeHeures::ENSEIGNEMENT && $this->getServiceParametres()->get('distinction_fi_fa_fc') == 1){
            $heuresTotal = 0.0;
        }

        $params = [
            'centres-cout'                => [],
            'structure-id'                => $serviceAPayer->getStructure()->getId(),
            'domaines-fonctionnels'       => $serviceAPayer->isDomaineFonctionnelModifiable() ? $this->getDomainesFonctionnels() : null,
            'default-centre-cout'         => $defaultCentreCout ? $defaultCentreCout->getId() : null,
            'default-domaine-fonctionnel' => $defaultDomaineFonctionnel ? $defaultDomaineFonctionnel->getId() : null,
            'mises-en-paiement'           => [],
            'demandes-mep'                => [],
            'heures-total'                => $serviceAPayer->isPayable() ? round($heuresTotal,2) : 0.0,
            'heures-mep'                  => 0.0,
            'heures-dmep'                 => 0.0,
            'heures-non-dmep'             => 0.0,
            'mep-defaults'                => [
                'formule-resultat-service-id'             => $serviceAPayer instanceof FormuleResultatService ? $serviceAPayer->getId() : null,
                'formule-resultat-service-referentiel-id' => $serviceAPayer instanceof FormuleResultatServiceReferentiel ? $serviceAPayer->getId() : null,
                'mission-id'                              => $serviceAPayer instanceof Mission ? $serviceAPayer->getId() : null,
                'type-heures-id'                          => $typeHeures->getId(),
            ],
        ];
        $mepBuffer = [];

        $ccCount = 0;
        $ccLast = null;
        foreach ($serviceAPayer->getCentreCout() as $centreCout) {
            if ($centreCout->typeHeuresMatches($typeHeures)) {
                $ccCount++;
                $ccLast = $centreCout->getId();
                $params['centres-cout'][$ccLast] = [
                    'libelle'           => (string)$centreCout,
                    'type-ressource-id' => $centreCout->getTypeRessource()->getId(),
                    'parent'            => $centreCout->getParent() ? $centreCout->getParent()->getId() : null,
                ];
            }
        }
        if ($ccCount == 1) { // un seul choix possible, donc sél. par défaut!
            $params['default-centre-cout'] = $ccLast;
        }

        $misesEnPaiement = $serviceAPayer->getMiseEnPaiement()->filter(function (MiseEnPaiement $miseEnPaiement) use ($typeHeures) {
            $mepth = $miseEnPaiement->getTypeHeures();
            if ($typeHeures->getCode() === TypeHeures::ENSEIGNEMENT){
                return in_array($mepth->getCode(),[TypeHeures::FI,TypeHeures::FA,TypeHeures::FC,TypeHeures::FC_MAJOREES,TypeHeures::ENSEIGNEMENT]);
            }else{
                return $mepth->getCode() === $typeHeures->getCode();
            }
        });
        /* @var $misesEnPaiement MiseEnPaiement[] */
        foreach ($misesEnPaiement as $miseEnPaiement) {
            if (!isset($params['centres-cout'][$miseEnPaiement->getCentreCout()->getId()])) {
                $params['centres-cout'][$miseEnPaiement->getCentreCout()->getId()] = [
                    'libelle'           => (string)$miseEnPaiement->getCentreCout(),
                    'type-ressource-id' => $miseEnPaiement->getCentreCout()->getTypeRessource()->getId(),
                    'parent'            => $miseEnPaiement->getCentreCout()->getParent() ? $miseEnPaiement->getCentreCout()->getParent()->getId() : null,
                    'bad'               => true,
                ];
            }
            /* @var $miseEnPaiement MiseEnPaiement */
            if ($pp = $miseEnPaiement->getPeriodePaiement()) {
                if (!isset($mepBuffer[$pp->getId()])) {
                    $mepBuffer[$pp->getId()] = [
                        'periode' => $pp,
                        'heures'  => 0.0,
                    ];
                }
                $mepBuffer[$pp->getId()]['heures'] += $miseEnPaiement->getHeures(); // mise en buffer pour tri...
                $params['heures-mep'] = round($params['heures-mep'] + $miseEnPaiement->getHeures(),2);
            } else {
                $domaineFonctionnel = $miseEnPaiement->getDomaineFonctionnel();

                $dmepParams = [
                    'centre-cout-id'         => $miseEnPaiement->getCentreCout()->getId(),
                    'domaine-fonctionnel-id' => $domaineFonctionnel ? $domaineFonctionnel->getId() : null,
                    'heures'                 => round($miseEnPaiement->getHeures(),2),
                    'read-only'              => $this->getReadOnly() || !$this->getView()->isAllowed($miseEnPaiement, Privileges::MISE_EN_PAIEMENT_DEMANDE),
                ];
                if ($validation = $miseEnPaiement->getValidation()) {
                    $dmepParams['validation'] = [
                        'date'        => $miseEnPaiement->getDateMiseEnPaiement()->format('d/m/Y'),
                        'utilisateur' => (string)$validation->getHistoCreateur(),
                    ];
                }
                $params['demandes-mep'][$miseEnPaiement->getId()] = $dmepParams;
                $params['heures-dmep'] = round($params['heures-dmep'] + $miseEnPaiement->getHeures(),2);
            }
        }
        $params['heures-non-dmep'] = round((float)$params['heures-total'] - (float)$params['heures-mep'] - (float)$params['heures-dmep'], 2);
        if (abs($params['heures-non-dmep']) < 0.009) $params['heures-non-dmep'] = 0.0;

        // tri du buffer et mise en paramètres
        usort($mepBuffer, function ($a, $b) {
            return $a['periode']->getOrdre() - $b['periode']->getOrdre();
        });
        foreach ($mepBuffer as $mb) {
            $params['mises-en-paiement'][$mb['periode']->getLibelleAnnuel($this->getServiceContext()->getAnnee())] = $mb['heures'];
        }

        return $params;
    }



    /**
     *
     * @param ServiceAPayerInterface $serviceAPayer
     *
     * @return string
     */
    protected function getServiceAPayerId(ServiceAPayerInterface $serviceAPayer)
    {
        $id = '';
        if ($serviceAPayer instanceof FormuleResultatService) {
            $id .= 'service';
        } elseif ($serviceAPayer instanceof FormuleResultatServiceReferentiel) $id .= 'referentiel';
        $id .= '-' . $serviceAPayer->getId();

        return $id;
    }



    public function getReadOnly()
    {
        return $this->readOnly;
    }



    public function setReadOnly($readOnly)
    {
        $this->readOnly = $readOnly;

        return $this;
    }



    /**
     *
     * @return ServiceAPayerInterface[]
     */
    public function getServicesAPayer()
    {
        return $this->servicesAPayer;
    }



    /**
     *
     * @param ServiceAPayerInterface[] $servicesAPayer
     *
     * @return self
     */
    public function setServicesAPayer(array $servicesAPayer)
    {
        $this->servicesAPayer = $servicesAPayer;

        return $this;
    }



    /**
     *
     * @return array
     */
    public function getDomainesFonctionnels()
    {
        if (empty($this->domainesFonctionnels)) {
            $sdf = $this->getServiceDomaineFonctionnel();
            $this->setDomainesFonctionnels($sdf->getList($sdf->finderByHistorique()));
        }

        return $this->domainesFonctionnels;
    }



    /**
     *
     * @param array $domainesFonctionnels
     *
     * @return self
     */
    public function setDomainesFonctionnels($domainesFonctionnels)
    {
        $this->domainesFonctionnels = [];
        foreach ($domainesFonctionnels as $id => $domaineFonctionnel) {
            if ($domaineFonctionnel instanceof DomaineFonctionnel) {
                $this->domainesFonctionnels[$domaineFonctionnel->getId()] = (string)$domaineFonctionnel;
            } else {
                $this->domainesFonctionnels[$id] = (string)$domaineFonctionnel;
            }
        }

        return $this;
    }



    /**
     * @return array
     */
    public function getBudget()
    {
        return $this->budget;
    }



    /**
     * @param array $budget
     *
     * @return DemandeMiseEnPaiementViewHelper
     */
    public function setBudget(array $budget)
    {
        $this->budget = $budget;

        return $this;
    }

}