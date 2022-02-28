<?php

namespace Application\View\Helper\Service;

use Application\Provider\Privilege\Privileges;
use Application\View\Helper\AbstractViewHelper;
use Application\Entity\Db\Service;
use Application\Entity\Db\Traits\ServiceAwareTrait;
use Application\Service\Traits\ContextServiceAwareTrait;

/**
 * Aide de vue permettant d'afficher une ligne de service
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Ligne extends AbstractViewHelper
{
    use ServiceAwareTrait;
    use ContextServiceAwareTrait;

    /**
     * @var Liste
     */
    protected $liste;

    /**
     * forcedReadOnly
     *
     * @var boolean
     */
    protected $forcedReadOnly = false;



    /**
     * Helper entry point.
     *
     * @param Liste   $liste
     * @param Service $service
     *
     * @return self
     */
    final public function __invoke(Liste $liste, Service $service)
    {
        $this->setListe($liste);
        $this->setService($service);

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
     * @return string
     */
    public function getRefreshUrl()
    {
        $typesIntervention = [];
        foreach ($this->getListe()->getTypesIntervention() as $typeIntervention) {
            $typesIntervention[] = $typeIntervention->getCode();
        }

        $url = $this->getView()->url(
            'service/rafraichir-ligne',
            [
                'service' => $this->getService()->getId(),
            ],
            ['query' => [
                'only-content' => 1,
                'read-only'    => $this->getReadOnly() ? '1' : '0',
            ]]);

        return $url;
    }



    /**
     * Génère le code HTML.
     *
     * @param boolean $details
     *
     * @return string
     */
    public function render($details = false)
    {
        $liste   = $this->getListe();
        $service = $this->getService();
        $element = $service->getElementPedagogique();

        $vhl = $service->getVolumeHoraireListe()->setTypeVolumeHoraire($liste->getTypeVolumeHoraire());

        $typesIntervention = $this->getListe()->getTypesIntervention();

        $out = '';
        if ($liste->getColumnVisibility('intervenant')) {
            $out .= '<td>' . $this->renderIntervenant($service->getIntervenant()) . '</td>';
        }
        if ($liste->getColumnVisibility('structure-aff')) {
            if ($service->getIntervenant()->getStatut()->estPermanent()) {
                $out .= '<td>' . $this->renderStructure($service->getIntervenant()->getStructure()) . "</td>\n";
            } else {
                $out .= "<td>&nbsp;</td>\n";
            }
        }
        if (!empty($element)) {
            if ($liste->getColumnVisibility('structure-ens')) {
                $out .= '<td>' . $this->renderStructure($element ? $element->getStructure() : null) . "</td>\n";
            }
            if ($liste->getColumnVisibility('formation')) {
                $out .= '<td>';
                if ($element && $element->getCheminPedagogique()->count() > 1) {
                    $out .= '<small class="badge" style="font-size:8pt" title="Enseignement mutualisé entre plusieures formations. Seule la formation principale est présentée ci-dessous">Enseignement mutualisé</small><br />';
                }
                $out .= $this->renderEtape($element ? $element->getEtape() : null) . "</td>\n";
            }
            if ($liste->getColumnVisibility('periode')) {
                $out .= '<td style="text-align:center">' . $this->renderPeriode($element ? $element->getPeriode() : null) . "</td>\n";
            }
            if ($liste->getColumnVisibility('enseignement')) {
                $out .= '<td>' . $this->getView()->elementPedagogique($element)->renderLink() . "</td>\n";
            }
            if ($liste->getColumnVisibility('foad')) {
                $out .= '<td style="text-align:center">' . $this->renderFOAD($element) . "</td>\n";
            }
            if ($liste->getColumnVisibility('regimes-inscription')) {
                $out .= '<td style="text-align:center">' . $this->renderRegimeInscription($element) . "</td>\n";
            }
        } else {
            $colspan = 0;
            if ($liste->getColumnVisibility('structure-ens')) $colspan++;
            if ($liste->getColumnVisibility('formation')) $colspan++;
            if ($liste->getColumnVisibility('periode')) $colspan++;
            if ($colspan > 0) {
                $out .= '<td colspan="' . $colspan . '">' . $this->renderEtablissement($service->getEtablissement()) . "</td>\n";
            }

            $colspan = 0;
            if ($liste->getColumnVisibility('enseignement')) $colspan++;
            if ($liste->getColumnVisibility('foad')) $colspan++;
            if ($liste->getColumnVisibility('regimes-inscription')) $colspan++;
            if ($colspan > 0) {
                $out .= '<td colspan="' . $colspan . '">' . $service->getDescription() . "</td>\n";
            }
        }
        if ($liste->getColumnVisibility('annee')) {
            $out .= '<td>' . $this->renderAnnee($element ? $element->getAnnee() : null) . "</td>\n";
        }
        foreach ($typesIntervention as $ti) {
            $out .= $this->renderTypeIntervention($vhl->setTypeIntervention($ti));
        }

        $out .= '<td class="actions">';
        if (!$this->getReadOnly()) {
            $out .= $this->renderModifier();
            $out .= $this->renderSupprimer();
        }
        $out .= $this->renderDetails($details);
        $out .= '</td>';

        return $out;
    }



    protected function renderIntervenant($intervenant)
    {
        return $this->getView()->intervenant($intervenant)->renderLink();
    }



    protected function renderStructure($structure)
    {
        if (!$structure) return '';

        $url = $this->getView()->url('structure/voir', ['structure' => $structure->getId()]);
        $out = '<a href="' . $url . '" class="ajax-modal">' . $structure . '</a>';

        return $out;
    }



    protected function renderEtape($etape)
    {
        return $this->getView()->etape()->setEtape($etape)->renderLink();
    }



    protected function renderPeriode($periode)
    {
        if ($periode) {
            return $periode->getLibelleCourt();
        } else {
            return '';
        }
    }



    protected function renderFOAD($element)
    {
        if (!$element) return '';
        $out = (bool)$element->getTauxFoad() ? "Oui" : "Non";

        return $out;
    }



    protected function renderRegimeInscription($element)
    {
        if (!$element) return '';

        return $element->getRegimesInscription(true);
    }



    protected function renderAnnee($annee)
    {
        $out = $annee->getLibelle();

        return $out;
    }



    protected function renderEtablissement($etablissement)
    {
        return $this->getView()->etablissement()->setEtablissement($etablissement)->renderLink();
    }



    protected function renderTypeIntervention(\Application\Entity\VolumeHoraireListe $liste)
    {
        $liste  = $liste->setMotifNonPaiement(false);
        $heures = $liste->getHeures();

        $hasForbiddenPeriodes   = $liste->hasForbiddenPeriodes();
        $hasBadTypeIntervention =
            $heures > 0
            && $liste->getService()->getElementPedagogique()
            && !$liste->getService()->getElementPedagogique()->getTypeIntervention()->contains($liste->getTypeIntervention());

        $display = $this->getListe()->getTypeInterventionVisibility($liste->getTypeIntervention()) ? '' : ';display:none';

        $attribs = [
            'class'                       => 'heures type-intervention ' . $liste->getTypeIntervention()->getCode(),
            'style'                       => 'text-align:right' . $display,
            'id'                          => 'service-' . $liste->getService()->getId() . '-ti-' . $liste->getTypeIntervention()->getId(),
            'data-value'                  => $heures,
            'data-type-intervention-code' => $liste->getTypeIntervention()->getCode(),
        ];
        $out     = '<td ' . $this->htmlAttribs($attribs) . '>';
        if ($hasForbiddenPeriodes) $out .= '<abbr class="bg-danger" title="Des heures sont renseignées sur une période non conforme à la période de l\'enseignement">';
        if ($hasBadTypeIntervention) $out .= '<abbr class="bg-danger" title="Ce type d\'intervention n\'est pas appliquable à cet enseignement">';

        $out .= \UnicaenApp\Util::formattedNumber($heures);

        if ($hasBadTypeIntervention) $out .= '</abbr>';
        if ($hasForbiddenPeriodes) $out .= '</abbr>';
        $out .= "</td>\n";

        return $out;
    }



    protected function renderModifier()
    {
        $query = [
            'type-volume-horaire' => $this->getListe()->getTypeVolumeHoraire()->getId(),
        ];
        if ($this->getListe()->getIntervenant()) {
            $query['intervenant'] = $this->getListe()->getIntervenant()->getId();
        }
        $url = $this->getView()->url('service/saisie', ['id' => $this->getService()->getId()], ['query' => $query]);

        return '<a class="ajax-modal" data-event="service-modify-message" href="' . $url . '" title="Modifier l\'enseignement"><span class="glyphicon glyphicon-pencil"></span></a>';
    }



    protected function renderSupprimer()
    {
        $url = $this->getView()->url('service/suppression', ['service' => $this->getService()->getId()], ['query' => ['type-volume-horaire' => $this->getListe()->getTypeVolumeHoraire()->getId()]]);

        return $this->getView()->tag('a', [
            'class'        => 'pop-ajax service-delete',
            'data-title'   => 'Suppression d\'enseignement',
            'data-content' => 'Souhaitez-vous vraiment supprimer ces heures d\'enseignement ?',
            'data-confirm' => 'true',
            'data-id'      => $this->getService()->getId(),
            'href'         => $url,
            'title'        => 'Supprimer cet enseignement',
        ])->html('<i class="fa-solid fa-trash-can"></i>');
    }



    protected function renderDetails($details = false)
    {
        $out =
            '<a class="service-details-button" title="Détail des heures">'
            . '<span class="glyphicon glyphicon-chevron-' . ($details ? 'up' : 'down') . '"></span>'
            . '</a>';

        return $out;
    }



    protected function toQuery($param)
    {
        if (null === $param) {
            return null;
        } elseif (false === $param) return 'false';
        elseif (true === $param) return 'true';
        elseif (method_exists($param, 'getId')) return $param->getId();
        else throw new \LogicException('Le paramètre n\'est pas du bon type');
    }



    /**
     *
     * @return Liste
     */
    function getListe()
    {
        return $this->liste;
    }



    /**
     *
     * @param Liste $liste
     *
     * @return self
     */
    function setListe(Liste $liste)
    {
        $this->liste = $liste;

        return $this;
    }



    public function getReadOnly()
    {
        return $this->getListe()->getReadOnly() || $this->forcedReadOnly;
    }



    /**
     *
     * @param Service $service
     *
     * @return self
     */
    public function setService(Service $service = null)
    {
        $service->setTypeVolumeHoraire($this->getListe()->getTypeVolumeHoraire());
        $this->forcedReadOnly = !$this->getView()->isAllowed($service, Privileges::ENSEIGNEMENT_EDITION);
        $this->service        = $service;

        return $this;
    }

}