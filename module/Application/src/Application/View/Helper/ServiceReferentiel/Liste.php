<?php

namespace Application\View\Helper\ServiceReferentiel;

use Application\Entity\Db\Intervenant;
use Application\Entity\Db\VolumeHoraireReferentiel;
use Application\Service\Traits\ContextAwareTrait;
use Application\Service\Traits\ServiceAwareTrait;
use Application\Service\Traits\ServiceReferentielAwareTrait;
use Zend\View\Helper\AbstractHtmlElement;
use Application\Entity\Db\ServiceReferentiel;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Entity\Db\Interfaces\TypeVolumeHoraireAwareInterface;
use Application\Entity\Db\Traits\TypeVolumeHoraireAwareTrait;
use Application\Service\Traits\TypeVolumeHoraireAwareTrait as ServiceTypeVolumeHoraireAwareTrait;

/**
 * Aide de vue permettant d'afficher une liste de services
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Liste extends AbstractHtmlElement implements ServiceLocatorAwareInterface, TypeVolumeHoraireAwareInterface
{
    use ServiceLocatorAwareTrait;
    use ContextAwareTrait;
    use TypeVolumeHoraireAwareTrait;
    use ServiceAwareTrait;
    use ServiceTypeVolumeHoraireAwareTrait;
    use ServiceReferentielAwareTrait;

    /**
     *
     * @var string
     */
    private $id;

    /**
     *
     * @var array
     */
    private $totaux;

    /**
     *
     * @var boolean
     */
    private $addButtonVisibility;

    /**
     *
     * @var array
     */
    private $columns = [
        'intervenant'  => [
            'visibility' => false,
            'head-text'  => "<th>Intervenant</th>",
        ],
        'structure'    => [
            'visibility' => true,
            'head-text'  => "<th title=\"Structure\">Structure</th>",
        ],
        'fonction'     => [
            'visibility' => true,
            'head-text'  => "<th title=\"Fonction référentiel\">Fonction</th>",
        ],
        'commentaires' => [
            'visibility' => true,
            'head-text'  => "<th title=\">Commentaires éventuels\">Commentaires</th>",
        ],
        'heures'       => [
            'visibility' => true,
            'head-text'  => "<th title=\"Nombre d'heures\">Heures</th>",
        ],
        'annee'        => [
            'visibility' => false,
            'head-text'  => "<th>Année univ.</th>",
        ],
    ];

    /**
     * Lecture seule ou non
     *
     * @var boolean
     */
    private $readOnly = false;

    /**
     * @var Intervenant|null
     */
    private $prevuToPrevu;



    /**
     * Helper entry point.
     *
     * @param ServiceReferentiel[] $services
     *
     * @return self
     */
    final public function __invoke($services)
    {
        $this->setServices($services);
        $this->calcDefaultColumnsVisibility();

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



    public function getTotalRefreshUrl()
    {
        $localContext = $this->getServiceLocator()->getServiceLocator()->get('applicationLocalContext');
        /* @var $localContext \Application\Service\LocalContext */

        if (($intervenant = $localContext->getIntervenant())) {
            if ($this->isInRealise()) {
                $route = 'intervenant/referentiel-realise';
            } else {
                $route = 'intervenant/referentiel';
            }

            return $this->getView()->url($route,
                [
                    'intervenant' => $intervenant->getRouteParam(),
                ],
                [
                    'query' => ['totaux' => 1],
                ],
                true);
        }

        return null;
    }



    public function getAddUrl()
    {
        return $this->getView()->url('referentiel/saisie', [], ['query' => ['type-volume-horaire' => $this->getTypeVolumeHoraire()->getId()]]);
    }



    /**
     * Génère le code HTML.
     *
     * @return string
     */
    public function render($details = false)
    {
        $colspan = 2;

        $attribs = [
            'id'          => $this->getId(true),
            'class'       => 'service-referentiel-liste',
            'data-params' => json_encode($this->exportParams()),
        ];

        $out = '<div ' . $this->htmlAttribs($attribs) . '>';
        if (count($this->getServices()) > 150) {
            return $out . '<div class="alert alert-danger" role="alert">Le nombre de lignes à afficher est trop important. Merci d\'affiner vos critères de recherche.</div></div>';
        }
        if ($this->getAddButtonVisibility() && !$this->getReadOnly()) {
            $out .= $this->renderAddButton();
        }

        $out .= '<table class="table table-bordered table-extra-condensed service-referentiel">';
        $out .= '<tr>';

        foreach ($this->getColumnsList() as $columnName) {
            if ($this->getColumnVisibility($columnName)) {
                $out .= $this->getColumnHeadText($columnName) . "\n";
                $colspan++;
            }
        }

        $out .= "<th>&nbsp;</th>\n";
        $out .= "</tr>\n";

        foreach ($this->services as $service) {
            if ($this->mustRenderLigne($service)) {
                $out .= $this->renderLigne($service, $details);
            }
        }
        $out .= '<tfoot data-url="' . $this->getTotalRefreshUrl() . '">' . "\n";
        $out .= $this->renderTotaux();
        $out .= '</tfoot>' . "\n";
        $out .= '</table>' . "\n";
        $out .= '</div>' . "\n";

        return $out;
    }



    public function renderAddButton()
    {
        $out = '';

        if ($this->isInRealise()) {
            $attribs = [
                'class'       => 'btn btn-warning referentiel-prevu-to-realise-show',
                'data-toggle' => 'modal',
                'data-target' => '#referentiel-prevu-to-realise-modal',
                //'data-event'    => 'service-constatation',
                //'href'          => $this->getAddUrl(),
                'title'       => "Saisir comme réalisées l'ensemble des heures prévisionnelles de référentiel"
                    . ". Attention toutefois : si des heures réalisées ont déjà été saisies alors ces dernières seront écrasées!",
            ];
            $out .= '<button type="button" ' . $this->htmlAttribs($attribs) . '>Prévu <span class="glyphicon glyphicon-arrow-right"></span> réalisé</button>&nbsp;';
            $out .= '<div class="modal fade" id="referentiel-prevu-to-realise-modal" tabindex="-1" role="dialog" aria-hidden="true">';
            $out .= '<div class="modal-dialog modal-md">';
            $out .= '<div class="modal-content">';
            $out .= '<div class="modal-header">';
            $out .= '<button type="button" class="close" data-dismiss="modal" aria-label="Annuler"><span aria-hidden="true">&times;</span></button>';
            $out .= '<h4 class="modal-title">Saisir comme réalisées l\'ensemble des heures prévisionnelles</h4>';
            $out .= '</div>';
            $out .= '<div class="modal-body">';
            $out .= '<p>Souhaitez-vous réellement saisir comme réalisées l\'ensemble des heures prévisionnelles ?</p>';
            $out .= '<div class="alert alert-warning" role="alert">Attention : si des heures réalisées ont déjà été saisies alors ces dernières seront écrasées!</div>';
            $out .= '</div>';
            $out .= '<div class="modal-footer">';
            $out .= '<button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>';
            $out .= '<button type="button" class="btn btn-primary referentiel-prevu-to-realise">OK</button>';
            $out .= '</div>';
            $out .= '</div>';
            $out .= '</div>';
            $out .= '</div>';
        } elseif ($this->prevuToPrevu && $this->getServiceServiceReferentiel()->getPrevusFromPrevusData($this->prevuToPrevu)) {
            $attribs = [
                'class'       => 'btn btn-warning referentiel-prevu-to-prevu-show',
                'data-toggle' => 'modal',
                'data-target' => '#referentiel-prevu-to-prevu-modal',
                //'data-event'    => 'service-constatation',
                //'href'          => $this->getAddUrl(),
                'title'       => "Initialiser le service référentiel prévisionnel avec le service référentiel prévisionnel validé l'année dernière",
            ];
            $out .= '<button type="button" ' . $this->htmlAttribs($attribs) . '>Prévu ' . $this->getServiceContext()->getAnneePrecedente() . ' <span class="glyphicon glyphicon-arrow-right"></span> Prévu ' . $this->getServiceContext()->getAnnee() . '</button>&nbsp;';
            $out .= '<div class="modal fade" id="referentiel-prevu-to-prevu-modal" tabindex="-1" role="dialog" aria-hidden="true">';
            $out .= '<div class="modal-dialog modal-md">';
            $out .= '<div class="modal-content">';
            $out .= '<div class="modal-header">';
            $out .= '<button type="button" class="close" data-dismiss="modal" aria-label="Annuler"><span aria-hidden="true">&times;</span></button>';
            $out .= '<h4 class="modal-title">Reporter ici le service prévisionnel validé de l\'année précédente.</h4>';
            $out .= '</div>';
            $out .= '<div class="modal-body">';
            $out .= '<p>Souhaitez-vous réellement initialiser votre service prévisionnel à partir de votre service prévisionnel validé de l\'an dernier ?</p>';
            $out .= '</div>';
            $out .= '<div class="modal-footer">';
            $out .= '<button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>';
            $out .= '<button type="button" class="btn btn-primary referentiel-prevu-to-prevu" data-intervenant="' . $this->prevuToPrevu->getRouteParam() . '">OK</button>';
            $out .= '</div>';
            $out .= '</div>';
            $out .= '</div>';
            $out .= '</div>';
        }
        $attribs = [
            'class'      => 'ajax-modal services btn btn-primary',
            'data-event' => 'service-referentiel-add-message',
            'href'       => $this->getAddUrl(),
            'title'      => 'Ajouter une nouvelle fonction',
        ];
        $out .= '<a ' . $this->htmlAttribs($attribs) . '><span class="glyphicon glyphicon-plus"></span> Je saisis</a>';

        return $out;
    }



    public function mustRenderLigne(ServiceReferentiel $service)
    {
        $vhSum = 0;
        $vhSum2 = 0;

        $vhs = $service->getVolumeHoraireReferentiel();
        foreach ($vhs as $vh) {
            /* @var $vh VolumeHoraireReferentiel */
            if ($service->getTypeVolumeHoraire()->isPrevu()) {
                if ($vh->getTypeVolumeHoraire()->isPrevu()) {
                    $vhSum += $vh->getHeures();
                }
            } elseif ($service->getTypeVolumeHoraire()->isRealise()) {
                if ($vh->getTypeVolumeHoraire()->isPrevu() && $vh->hasValidation()) {
                    $vhSum += $vh->getHeures();
                } elseif ($vh->getTypeVolumeHoraire()->isRealise()) {
                    $vhSum2 += $vh->getHeures();
                }
            }
        }

        return $vhSum != 0 || $vhSum2 != 0;
    }



    public function renderLigne(ServiceReferentiel $service, $details = false, $show = true)
    {
        $ligneView = $this->getView()->serviceReferentielLigne($this, $service);
        /* @var $ligneView Ligne */
        $attribs = [
            'id'       => 'referentiel-' . $service->getId() . '-ligne',
            'data-id'  => $service->getId(),
            'class'    => 'referentiel-ligne',
            'data-url' => $ligneView->getRefreshUrl(),
        ];
        if (!$show) $attribs['style'] = 'display:none';
        $out = '<tr ' . $this->htmlAttribs($attribs) . '>';
        $out .= $ligneView->render($details);
        $out .= '</tr>';

        return $out;
    }



    public function renderTotaux()
    {
        $colspan = 0;
        if ($this->getColumnVisibility('intervenant')) $colspan++;
        if ($this->getColumnVisibility('structure')) $colspan++;
        if ($this->getColumnVisibility('fonction')) $colspan++;
        if ($this->getColumnVisibility('commentaires')) $colspan++;
//        if ($this->getColumnVisibility('heures'       ))  $colspan ++;
        if ($this->getColumnVisibility('annee')) $colspan++;

        $data = $this->getTotaux();

        $typesInterventionDisplayed = 0;
        $out                        = '';
        $out .= '<tr>';
        $out .= "<th colspan=\"$colspan\" style=\"text-align:right\">Total des heures de référentiel :</th>\n";
        $out .= "<td id=\"total-referentiel\" style=\"text-align:right\" colspan=\"" . $typesInterventionDisplayed . "\">" . \UnicaenApp\Util::formattedNumber($data['total_general']) . "</td>\n";
        $out .= "<td>&nbsp;</td>\n";
        $out .= "</tr>\n";

        return $out;
    }



    public function renderShowHide()
    {
        return
            '<div class="service-show-hide-buttons">'
            . '<button type="button" class="btn btn-default btn-xs service-show-all-details"><span class="glyphicon glyphicon-chevron-down"></span> Tout déplier</button> '
            . '<button type="button" class="btn btn-default btn-xs service-hide-all-details"><span class="glyphicon glyphicon-chevron-up"></span> Tout replier</button>'
            . '</div>';
    }



    /**
     * Détermine si nous sommes en service réalisé ou non
     *
     * @return boolean
     */
    public function isInRealise()
    {
        return $this->getTypeVolumeHoraire()->getCode() === \Application\Entity\Db\TypeVolumeHoraire::CODE_REALISE;
    }



    /**
     * @return string
     */
    public function getId($reNew = false)
    {
        if (null === $this->id || $reNew) {
            $this->id = uniqid('referentiel-liste-');
        }

        return $this->id;
    }



    protected function getTotaux()
    {
        if (!$this->totaux) {
            $data = [
                'total_general' => 0,
            ];
            foreach ($this->getServices() as $service) {
                $h = $service->getVolumeHoraireReferentielListe()->setTypeVolumeHoraire($this->getTypeVolumehoraire())->getHeures();
                $data['total_general'] += $h;
            }
            $this->totaux = $data;
        }

        return $this->totaux;
    }



    /**
     * Retourne les paramètres de configuration du View Helper sous forme de tableau transformable en JSON
     *
     * @return array
     */
    public function exportParams()
    {
        $params = [
            'read-only'           => $this->getReadOnly(),
            'type-volume-horaire' => $this->getTypeVolumeHoraire()->getId(),
            'columns-visibility'  => [],
            'in-realise'          => $this->isInRealise(),
        ];
        foreach ($this->getColumnsList() as $columnName) {
            $params['columns-visibility'][$columnName] = $this->getColumnVisibility($columnName);
        }

        return $params;
    }



    /**
     * Copnfigure le View Helper selon les paramètres transmis
     *
     * @param array $params
     *
     * @return self
     */
    public function applyParams(array $params)
    {
        if (isset($params['read-only'])) {
            $this->setReadOnly(filter_var($params['read-only'], FILTER_VALIDATE_BOOLEAN));
        }
        if (isset($params['type-volume-horaire'])) {
            $this->setTypeVolumeHoraire($this->getServiceTypeVolumeHoraire()->get((int)$params['type-volume-horaire']));
        }
        if (isset($params['columns-visibility']) && is_array($params['columns-visibility'])) {
            foreach ($params['columns-visibility'] as $columnName => $visibility) {
                $this->setColumnVisibility($columnName, filter_var($visibility, FILTER_VALIDATE_BOOLEAN));
            }
        }

        return $this;
    }



    /**
     * Calcule la visibilité par défaut des colonnes en fonction des données transmises!!
     *
     * @return \Application\View\Helper\Service\Liste
     */
    public function calcDefaultColumnsVisibility()
    {
        $services = $this->getServices();

        // si plusieurs années différentes sont détectées alors on prévoit d'afficher la colonne année par défaut
        // si plusieurs intervenants différents alors on prévoit d'afficher la colonne intervenant par défaut
        $annee             = null;
        $multiAnnees       = false;
        $intervenant       = null;
        $multiIntervenants = false;
        foreach ($services as $service) {
            if (empty($intervenant)) {
                $intervenant = $service->getIntervenant();
            } elseif ($intervenant !== $service->getIntervenant()) {
                $multiIntervenants = true;
                break;
            }

            if (empty($annee)) {
                $annee = $service->getIntervenant()->getAnnee();
            } elseif ($annee !== $service->getIntervenant()->getAnnee()) {
                $multiAnnees = true;
                break;
            }
        }
        $this->setColumnVisibility('annee', $multiAnnees);
        $this->setColumnVisibility('intervenant', $multiIntervenants);

        return $this;
    }



    /**
     * Détermine si le bouton prévu => prévu doit être affiché ou non.
     *
     * @param $intervenant
     */
    public function showPrevuToPrevu($intervenant)
    {
        if ($intervenant instanceof Intervenant) {
            $this->prevuToPrevu = $intervenant;
        } else {
            $this->prevuToPrevu = null;
        }
    }



    /**
     * @return @string[]
     */
    public function getColumnsList()
    {
        return array_keys($this->columns);
    }



    /**
     *
     * @param string  $columnName
     * @param boolean $visibility
     *
     * @return self
     */
    public function setColumnVisibility($columnName, $visibility)
    {
        $this->columns[$columnName]['visibility'] = (boolean)$visibility;

        return $this;
    }



    /**
     *
     * @param string $columnName
     *
     * @return boolean
     */
    public function getColumnVisibility($columnName)
    {
        if (!array_key_exists($columnName, $this->columns)) {
            throw new \LogicException('La colonne "' . $columnName . '" n\'existe pas.');
        }

        return $this->columns[$columnName]['visibility'];
    }



    /**
     *
     * @param string $columnName
     *
     * @return string
     * @throws \LogicException
     */
    public function getColumnHeadText($columnName)
    {
        if (!array_key_exists($columnName, $this->columns)) {
            throw new \LogicException('La colonne "' . $columnName . '" n\'existe pas.');
        }

        return $this->columns[$columnName]['head-text'];
    }



    /**
     * Retourne le type de volume horaire concerné.
     *
     * @return TypeVolumeHoraire
     */
    public function getTypeVolumeHoraire()
    {
        if (empty($this->typeVolumeHoraire)) {
            $this->typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->getPrevu();
        }

        return $this->typeVolumeHoraire;
    }



    /**
     *
     * @return boolean
     */
    public function getReadOnly()
    {
        return $this->readOnly;
    }



    /**
     *
     * @param boolean $readOnly
     *
     * @return self
     */
    public function setReadOnly($readOnly)
    {
        $this->readOnly = $readOnly;

        return $this;
    }



    /**
     *
     * @return ServiceReferentiel[]
     */
    public function getServices()
    {
        return $this->services;
    }



    /**
     *
     * @param Service[] $services
     *
     * @return self
     */
    public function setServices(array $services)
    {
        $this->services = $services;

        return $this;
    }



    /**
     *
     * @return boolean
     */
    function getAddButtonVisibility()
    {
        return $this->addButtonVisibility;
    }



    /**
     *
     * @param boolean $addButtonVisibility
     *
     * @return self
     */
    function setAddButtonVisibility($addButtonVisibility)
    {
        $this->addButtonVisibility = $addButtonVisibility;

        return $this;
    }

}