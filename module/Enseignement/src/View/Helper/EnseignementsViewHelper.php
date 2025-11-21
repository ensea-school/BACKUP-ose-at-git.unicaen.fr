<?php

namespace Enseignement\View\Helper;

use Administration\Service\ParametresServiceAwareTrait;
use Application\Provider\Privileges;
use Application\Service\Traits\ContextServiceAwareTrait;
use Enseignement\Entity\Db\Service;
use Enseignement\Processus\EnseignementProcessusAwareTrait;
use Intervenant\Entity\Db\Intervenant;
use Intervenant\Entity\Db\IntervenantAwareTrait;
use Intervenant\Processus\IntervenantProcessusAwareTrait;
use Intervenant\Service\IntervenantServiceAwareTrait;
use Laminas\View\Helper\AbstractHtmlElement;
use OffreFormation\Entity\Db\TypeIntervention;
use OffreFormation\Service\Traits\TypeInterventionServiceAwareTrait;
use Service\Entity\Db\EtatVolumeHoraire;
use Service\Entity\Db\TypeVolumeHoraire;
use Service\Entity\Db\TypeVolumeHoraireAwareTrait;
use Service\Service\EtatVolumeHoraireServiceAwareTrait;
use Service\Service\TypeVolumeHoraireServiceAwareTrait as ServiceTypeVolumeHoraireAwareTrait;

/**
 * Aide de vue permettant d'afficher une liste d'enseignements
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class EnseignementsViewHelper extends AbstractHtmlElement
{
    use TypeVolumeHoraireAwareTrait;
    use ContextServiceAwareTrait;
    use TypeInterventionServiceAwareTrait;
    use ServiceTypeVolumeHoraireAwareTrait;
    use EtatVolumeHoraireServiceAwareTrait;
    use IntervenantServiceAwareTrait;
    use IntervenantProcessusAwareTrait;
    use IntervenantAwareTrait;
    use ParametresServiceAwareTrait;
    use EnseignementProcessusAwareTrait;

    private string $id = '';

    private array $totaux = [];

    private array $typesInterventionVisibility = [];

    private bool $addButtonVisibility = true;

    private bool $horodatage = false;

    protected array $services = [];

    private array $columns = [
        'intervenant'         => [
            'visibility' => false,
            'head-text'  => "<th>Intervenant</th>",
        ],
        'structure-aff'       => [
            'visibility' => false,
            'head-text'  => "<th title=\"Structure d'appartenance de l'intervenant\">Structure d'affectation</th>",
        ],
        'structure-ens'       => [
            'visibility' => true,
            'head-text'  => "<th title=\"Structure gestionnaire de l'enseignement\">Composante d'enseignement</th>",
        ],
        'formation'           => [
            'visibility' => true,
            'head-text'  => "<th title=\"Formation\">Formation</th>",
        ],
        'periode'             => [
            'visibility' => true,
            'head-text'  => "<th title=\"Période\">Période</th>",
        ],
        'enseignement'        => [
            'visibility' => true,
            'head-text'  => "<th title=\">Enseignement\">Enseignement</th>",
        ],
        'foad'                => [
            'visibility' => false,
            'head-text'  => "<th title=\"Formation ouverte à distance\">FOAD</th>",
        ],
        'regimes-inscription' => [
            'visibility' => false,
            'head-text'  => "<th title=\"Régime d'inscription\">Rég. d'insc.</th>",
        ],
    ];

    /**
     * Types d'intervention
     *
     * @var TypeIntervention[]
     */
    protected array $typesIntervention = [];

    /**
     * Lecture seule ou non
     */
    private bool $readOnly = false;



    final public function __invoke(TypeVolumeHoraire $typeVolumeHoraire, ?Intervenant $intervenant, array $services): self
    {
        $this->setTypeVolumeHoraire($typeVolumeHoraire);
        $this->setIntervenant($intervenant);
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



    public function render($details = false): string
    {
        $this->totaux = [];
        $typesIntervention = $this->getTypesIntervention();
        $colspan = 2;

        $attribs = [
            'id'          => $this->getId(true),
            'class'       => 'enseignements',
            'data-params' => json_encode($this->exportParams()),
        ];

        echo $this->getView()->inlineScript()->appendFile($this->getView()->basePath() . '/js/service.js');

        $out = '<div ' . $this->htmlAttribs($attribs) . '>';
        if (count($this->getServices()) > 150) {
            return $out . '<div class="alert alert-danger" role="alert">Le nombre de services à afficher est trop important. Merci d\'affiner vos critères de recherche.</div></div>';
        }
        $out .= $this->renderActionButtons();
        $out .= $this->renderShowHide();

        $out .= '<table class="table table-xs table-bordered service">';
        $out .= '<tr>';

        foreach ($this->getColumnsList() as $columnName) {
            if ($this->getColumnVisibility($columnName)) {
                $out .= $this->getColumnHeadText($columnName) . "\n";
                $colspan++;
            }
        }
        foreach ($typesIntervention as $ti) {
            $display = $this->getTypeInterventionVisibility($ti) ? '' : ';display:none';
            $colspan++;
            $out .= "<th class=\"heures type-intervention ti" . $ti->getId() . "\" style=\"width:8%$display\"><abbr title=\"" . $ti->getLibelle() . "\">" . $ti->getCode() . "</abbr></th>\n";
        }
        $out .= "<th>&nbsp;</th>\n";
        $out .= "</tr>\n";

        foreach ($this->services as $service) {
            $out .= $this->renderLigne($service, $details);
        }

        $style = $this->getTotaux()['total_general'] == 0 ? ' style="display:none"' : '';
        $out .= '<tfoot ' . $style . '>' . "\n";
        $out .= $this->renderTotaux();
        $out .= '</tfoot>' . "\n";
        $out .= '</table>' . "\n";
        $out .= $this->renderShowHide();

        if ($this->hasHorodatage() && $this->getIntervenant()) {
            $out .= $this->getView()->horodatage($this->getTypeVolumeHoraire(), $this->getIntervenant(), false);
        }

        $out .= '</div>' . "\n";

        return $out;
    }



    protected function renderActionButtons(): string
    {
        if ($this->getReadOnly()) return '';

        if ($this->getIntervenant()) {
            $service = new Service();
            $service->setIntervenant($this->getIntervenant());
            $service->setTypeVolumeHoraire($this->getTypeVolumeHoraire());
            $canAddService = $this->getView()->isAllowed($service, $this->getTypeVolumeHoraire()->getPrivilegeEnseignementEdition());
        } else {
            $canAddService = $this->getView()->isAllowed(Privileges::getResourceId($this->getTypeVolumeHoraire()->getPrivilegeEnseignementEdition()));
        }

        if (!$canAddService) return '';

        $out = '';

        if ($this->getIntervenant()) {
            if ($this->isInRealise()) {
                $out .= $this->renderActionPrevuToRealise();
            } else {
                $out .= $this->renderActionPrevuToPrevu();
            }
        }

        $out .= $this->renderActionSaisie();

        return $out;
    }



    public function renderActionPrevuToPrevu(): string
    {
        $out = '';

        if ($typeVolumeHoraire = $this->getProcessusEnseignement()->initializePrevu($this->getIntervenant())) {
            $attribs = [
                'class'          => 'btn btn-warning prevu-to-prevu-show',
                'data-bs-toggle' => 'modal',
                'data-bs-target' => '#prevu-to-prevu-modal',
                'title'          => "Initialiser le service prévisionnel avec le service prévisionnel validé l'année dernière",
            ];
            $source = $typeVolumeHoraire->getLibelle();
            $out .= '<button type="button" ' . $this->htmlAttribs($attribs) . '>' . $source . ' ' . $this->getServiceContext()->getAnneePrecedente() . ' <i class="fas fa-arrow-right"></i> Prévisionnel ' . $this->getServiceContext()->getAnnee() . '</button>&nbsp;';
            $out .= '<div class="modal fade" id="prevu-to-prevu-modal" tabindex="-1" role="dialog" aria-hidden="true">';
            $out .= '<div class="modal-dialog modal-md">';
            $out .= '<div class="modal-content">';
            $out .= '<div class="modal-header">';
            $out .= '<h4 class="modal-title">Reporter ici le service ' . strtolower($source) . ' validé de l\'année précédente.</h4>';
            $out .= '</div>';
            $out .= '<div class="modal-body">';
            $out .= '<p>Souhaitez-vous réellement initialiser votre service prévisionnel à partir de votre service ' . strtolower($source) . ' validé de l\'an dernier ?</p>';
            $out .= '<div class="alert alert-info" id="prevu-to-prevu-attente" style="display:none">';
            $out .= '<img src="' . $this->getView()->basePath() . '/images/wait.gif" alt="Attente..."/>';
            $out .= '<div>Reprise des enseignements de l\'année dernière en cours... Merci de patienter.</div>';
            $out .= '</div>';
            $out .= '</div>';
            $out .= '<div class="modal-footer">';
            $out .= '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>';
            $out .= '<button type="button" class="btn btn-primary prevu-to-prevu" data-intervenant="' . $this->getIntervenant()->getId() . '">OK</button>';
            $out .= '</div>';
            $out .= '</div>';
            $out .= '</div>';
            $out .= '</div>';
        }

        return $out;
    }



    public function renderActionPrevuToRealise(): string
    {
        $out = '';

        if ($this->getProcessusEnseignement()->initializeRealise($this->getIntervenant())) {
            $attribs = [
                'class'          => 'btn btn-warning prevu-to-realise-show',
                'data-bs-toggle' => 'modal',
                'data-bs-target' => '#prevu-to-realise-modal',
                'title'          => "Saisir comme réalisées l'ensemble des heures prévisionnelles"
                    . ". Attention toutefois : si des heures réalisées ont déjà été saisies alors ces dernières seront écrasées!",
            ];
            $out .= '<button type="button" ' . $this->htmlAttribs($attribs) . '>Prévu <i class="fas fa-arrow-right"></i> réalisé</button>&nbsp;';
            $out .= '<div class="modal fade" id="prevu-to-realise-modal" tabindex="-1" role="dialog" aria-hidden="true">';
            $out .= '<div class="modal-dialog modal-md">';
            $out .= '<div class="modal-content">';
            $out .= '<div class="modal-header">';
            $out .= '<h4 class="modal-title">Saisir comme réalisées l\'ensemble des heures prévisionnelles</h4>';
            $out .= '</div>';
            $out .= '<div class="modal-body">';
            $out .= '<p>Souhaitez-vous réellement saisir comme réalisées l\'ensemble des heures prévisionnelles ?</p>';
            $out .= '<div class="alert alert-warning" role="alert">Attention : si des heures réalisées ont déjà été saisies alors ces dernières seront écrasées!</div>';
            $out .= '</div>';
            $out .= '<div class="modal-footer">';
            $out .= '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>';
            $out .= '<button type="button" class="btn btn-primary prevu-to-realise">OK</button>';
            $out .= '</div>';
            $out .= '</div>';
            $out .= '</div>';
            $out .= '</div>';
        }

        return $out;
    }



    public function renderActionSaisie(): string
    {
        $attribs = [
            'class'      => 'ajax-modal services btn btn-primary',
            'data-event' => 'service-add-message',
            'title'      => 'Ajouter un nouvel enseignement',
        ];

        $params = [
            'type-volume-horaire-code' => $this->getTypeVolumeHoraire()->getCode(),
        ];
        $query = [];
        if ($this->getIntervenant()) {
            $query['intervenant'] = $this->getIntervenant()->getId();
        }
        $attribs['href'] = $this->getView()->url('enseignement/saisie', $params, ['query' => $query]);

        return '<a ' . $this->htmlAttribs($attribs) . '><i class="fas fa-plus"></i> Je saisis</a>';
    }



    public function renderLigne(Service $service, $details = false, $show = true)
    {
        $tvhPrevu = $this->getServiceTypeVolumeHoraire()->getPrevu();
        $tvhRealise = $this->getServiceTypeVolumeHoraire()->getRealise();
        $evhSaisi = $this->getServiceEtatVolumeHoraire()->getSaisi();
        $evhValide = $this->getServiceEtatVolumeHoraire()->getValide();

        $heures = 0;
        $modeCalendaire = false;
        $intervenant = $service->getIntervenant();
        if (!$intervenant->getStatut()->isModeEnseignementSemestriel($this->getTypeVolumeHoraire())) {

            // Si on n'est pas en semestriel, donc en calendaire, alors on ajoute une heure fictive afin d'afficher
            // tout le temps la ligne
            $modeCalendaire = true;
            $heures++;
        }
        $volumeHoraireListe = $service->getVolumeHoraireListe();
        if ($this->isInRealise()) {
            $volumeHoraireListe->setTypeVolumeHoraire($tvhPrevu);
            $volumeHoraireListe->setEtatVolumeHoraire($evhValide);
            $heures += $volumeHoraireListe->getHeures();
            $volumeHoraireListe->setTypeVolumeHoraire($tvhRealise);
            $volumeHoraireListe->setEtatVolumeHoraire($evhSaisi);
            // on met en absolu pour ne pas que des heures réalisées négatives non validées ne viennent s'annuler avec le nombre équivalent en prévisionnel
            $heures += abs($volumeHoraireListe->getHeures());
        } else {
            $volumeHoraireListe->setTypeVolumeHoraire($tvhPrevu);
            $volumeHoraireListe->setEtatVolumeHoraire($evhSaisi);
            $heures += $volumeHoraireListe->getHeures();
        }
        if ($heures == 0) {
            return ''; // on n'affiche pas les lignes de services avec 0 heures
        }
        $class = ($modeCalendaire) ? 'service-ligne mode-calendaire' : 'service-ligne';
        $attribs = [
            'id'       => 'service-' . $service->getId() . '-ligne',
            'data-id'  => $service->getId(),
            'class'    => $class,
            'data-url' => $this->getView()->ligneEnseignement($this, $service)->getRefreshUrl(),
        ];

        if (!$service->getIntervenant()->getStructure() && !$service->getElementPedagogique()){
            $attribs['class'] .= ' bg-danger';
            $attribs['title'] = 'Il est impossible pour un intervenant n\'ayant pas de structure d\'affectation de faire des heures d\'enseignement hors établissement';
        }

        if (!$show) $attribs['style'] = 'display:none';
        $out = '<tr ' . $this->htmlAttribs($attribs) . '>';
        $out .= $this->renderInterieurLigne($service, $details);
        $out .= '</tr>';
        $out .= '<tr class="volume-horaire" id="service-' . $service->getId() . '-volume-horaire-tr"' . ($details ? '' : ' style="display:none"') . '>';
        if ($this->isInRealise()) {
            $vhlViewHelper = $this->getVhlViewHelper($service, $tvhPrevu, $evhValide);
            $vhlViewHelper->setReadOnly(true);
            $out .= '<td class="volume-horaire" style="padding-left:5em" id="service-' . $service->getId() . '-volume-horaire-td" colspan="999">';
            $out .= '<div class="rappel-volume-horaire-prevu">';
            $out .= sprintf('<div style="float:left;width:30%%"><h5>Prévisionnel %s :</h5></div>', $evhValide);
            $out .= '<div id="vhl-prev" style="width:85%" data-url="' . $vhlViewHelper->getRefreshUrl() . '">' . $vhlViewHelper->render() . '</div>';
            $out .= '</div>';

            $vhlViewHelper = $this->getVhlViewHelper($service, $tvhRealise, $evhSaisi);
            $vhlViewHelper->setReadOnly($this->getReadOnly());
            $out .= '<div style="float:left;width:15%"><h5>Réalisé :</h5></div>';
            $out .= '<div id="vhl" style="width:85%" data-url="' . $vhlViewHelper->getRefreshUrl() . '">' . $vhlViewHelper->render() . '</div>';
        } else {
            $volumeHoraireListe = $this->getVhlViewHelper($service, $tvhPrevu, $evhSaisi);
            $volumeHoraireListe->setReadOnly($this->getReadOnly());
            $out .= '<td class="volume-horaire" style="padding-left:10em" id="service-' . $service->getId() . '-volume-horaire-td" colspan="999">';
            $out .= '<div id="vhl" data-url="' . $volumeHoraireListe->getRefreshUrl() . '">' . $volumeHoraireListe->render() . '</div>';
        }
        $out .= '</td>';
        $out .= '</tr>';

        return $out;
    }



    public function renderInterieurLigne(Service $service, bool $details = false, bool $show = true): string
    {
        $ligneView = $this->getView()->ligneEnseignement($this, $service);

        return $ligneView->render($details);
    }



    /**
     * @param Service $service
     * @param         $typeVolumeHoraire
     *
     * @return \Application\View\Helper\VolumeHoraire\Liste|\Application\View\Helper\VolumeHoraire\ListeCalendaire
     */
    private function getVhlViewHelper(Service $service, TypeVolumeHoraire $typeVolumeHoraire, EtatVolumeHoraire $etatVolumeHoraire)
    {
        $volumeHoraireListe = $service->getVolumeHoraireListe();
        $volumeHoraireListe->setTypeVolumeHoraire($typeVolumeHoraire);
        $volumeHoraireListe->setEtatVolumeHoraire($etatVolumeHoraire);
        $statut = $service->getIntervenant()->getStatut();
        $code = $service->getIntervenant()->getStatut()->getCode();


        if ($statut->isModeEnseignementSemestriel($typeVolumeHoraire)) {
            $vhlvh = $this->getView()->volumeHoraireListe($volumeHoraireListe);
            /* @var $vhlvh \Application\View\Helper\VolumeHoraire\Liste */
        } else {
            $vhlvh = $this->getView()->volumeHoraireListeCalendaire($volumeHoraireListe);
            /* @var $vhlvh \Application\View\Helper\VolumeHoraire\ListeCalendaire */
        }

        return $vhlvh;
    }



    public function renderTotaux()
    {
        $typesIntervention = $this->getTypesIntervention();

        $colspan = 0;
        if ($this->getColumnVisibility('intervenant')) $colspan++;
        if ($this->getColumnVisibility('structure-aff')) $colspan++;
        if ($this->getColumnVisibility('structure-ens')) $colspan++;
        if ($this->getColumnVisibility('formation')) $colspan++;
        if ($this->getColumnVisibility('periode')) $colspan++;
        if ($this->getColumnVisibility('enseignement')) $colspan++;
        if ($this->getColumnVisibility('foad')) $colspan++;
        if ($this->getColumnVisibility('regimes-inscription')) $colspan++;

        $data = $this->getTotaux();

        $out = '<tr>';
        $out .= "<th colspan='$colspan' style=\"text-align:right\">Totaux par type d'intervention :</th>\n";
        $typesInterventionDisplayed = 0;
        foreach ($typesIntervention as $ti) {
            if ($this->getTypeInterventionVisibility($ti)) {
                $display = '';
                $typesInterventionDisplayed++;
            } else {
                $display = ';display:none';
            }
            $out .= "<td id=\"" . $ti->getCode() . "\" class=\"type-intervention ti" . $ti->getId() . "\" style=\"text-align:right$display\">" . \UnicaenApp\Util::formattedNumber($data[$ti->getCode()]) . "</td>\n";
        }
        $out .= "<td>&nbsp;</td>\n";
        $out .= "</tr>\n";
        $out .= '<tr>';
        $out .= "<th colspan=\"$colspan\" style=\"text-align:right\">Total des heures de service :</th>\n";
        $out .= "<td id=\"total-general\" style=\"text-align:right\" data-total=\"" . $data['total_general'] . "\" colspan=\"" . $typesInterventionDisplayed . "\">" . \UnicaenApp\Util::formattedNumber($data['total_general']) . "</td>\n";
        $out .= "<td>&nbsp;</td>\n";
        $out .= "</tr>\n";

        return $out;
    }



    public function renderShowHide()
    {
        return
            '<div class="service-show-hide-buttons">'
            . '<button type="button" class="btn btn-secondary btn-sm service-show-all-details"><i class="fas fa-chevron-down"></i> Tout déplier</button> '
            . '<button type="button" class="btn btn-secondary btn-sm service-hide-all-details"><i class="fas fa-chevron-up"></i> Tout replier</button>'
            . '</div>';
    }



    /**
     * Détermine si nous sommes en service réalisé ou non
     */
    public function isInRealise(): bool
    {
        return $this->getTypeVolumeHoraire()->isRealise();
    }



    public function getId($reNew = false): string
    {
        if (null === $this->id || $reNew) {
            $this->id = uniqid('enseignements-');
        }

        return $this->id;
    }



    protected function getTotaux(): array
    {
        if (!$this->totaux) {
            $typesIntervention = $this->getTypesIntervention();
            $data = [
                'total_general' => 0,
            ];
            foreach ($typesIntervention as $ti) {
                $data[$ti->getCode()] = 0;

                foreach ($this->getServices() as $service) {
                    $h = $service->getVolumeHoraireListe()->setTypeVolumeHoraire($this->getTypeVolumehoraire())->setTypeIntervention($ti)->getHeures();
                    $data[$ti->getCode()] += $h;
                }
            }
            foreach ($this->getServices() as $service) {
                $data['total_general'] += $service->getVolumeHoraireListe()->setTypeVolumeHoraire($this->getTypeVolumehoraire())->setTypeIntervention(false)->getHeures();
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
    public function exportParams(): array
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
     * @return \Application\View\Helper\Service\Enseignements
     */
    public function calcDefaultColumnsVisibility()
    {
        $multiIntervenants = empty($this->getIntervenant());
        $this->setColumnVisibility('intervenant', $multiIntervenants);
        $this->setColumnVisibility('structure-aff', $multiIntervenants);

        // si c'est une composante alors on affiche le détail pour l'enseignement
        $detailsEns = !$this->getServiceContext()->getIntervenant();
        $this->setColumnVisibility('foad', $detailsEns);
        $this->setColumnVisibility('regimes-inscription', $detailsEns);

        return $this;
    }



    /**
     * @param TypeIntervention $typeIntervention
     *
     * @return boolean
     */
    public function getTypeInterventionVisibility(TypeIntervention $typeIntervention)
    {
        if (isset($this->typesInterventionVisibility[$typeIntervention->getCode()])) { // visibilité déterminée
            return $this->typesInterventionVisibility[$typeIntervention->getCode()];
        } else { // visibilité calculée
            $totaux = $this->getTotaux();

            return $typeIntervention->isVisible() || (isset($totaux[$typeIntervention->getCode()]) && $totaux[$typeIntervention->getCode()] > 0);
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
     * @param string $columnName
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
     * @return TypeIntervention[]
     */
    public function getTypesIntervention()
    {
        if (empty($this->typesIntervention)) {
            $qb = $this->getServiceTypeIntervention()->finderByContext();
            $this->getServiceTypeIntervention()->finderByHistorique($qb);
            $this->typesIntervention = $this->getServiceTypeIntervention()->getList($qb);
        }

        return $this->typesIntervention;
    }



    /**
     * @param TypeIntervention[] $typesIntervention
     *
     * @return self
     */
    public function setTypesIntervention($typesIntervention)
    {
        $this->typesIntervention = $typesIntervention;

        return $this;
    }



    public function hasHorodatage(): bool
    {
        return $this->horodatage;
    }



    public function setHorodatage(bool $horodatage): EnseignementsViewHelper
    {
        $this->horodatage = $horodatage;

        return $this;
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
     * @return Service[]
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

}