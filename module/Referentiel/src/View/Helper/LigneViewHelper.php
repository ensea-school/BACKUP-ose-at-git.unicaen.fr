<?php

namespace Referentiel\View\Helper;

use Laminas\View\Helper\AbstractHtmlElement;
use Paiement\Entity\Db\MotifNonPaiement;
use Referentiel\Entity\Db\ServiceReferentiel;
use Referentiel\Entity\Db\ServiceReferentielAwareTrait;
use Service\Service\EtatVolumeHoraireServiceAwareTrait;
use Service\Service\TypeVolumeHoraireServiceAwareTrait;


/**
 * Aide de vue permettant d'afficher une ligne de service
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class LigneViewHelper extends AbstractHtmlElement
{
    use ServiceReferentielAwareTrait;
    use TypeVolumeHoraireServiceAwareTrait;
    use EtatVolumeHoraireServiceAwareTrait;

    /**
     * @var ReferentielsViewHelper
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
     * @param ReferentielsViewHelper $liste
     * @param ServiceReferentiel $service
     *
     * @return self
     */
    final public function __invoke(ReferentielsViewHelper $liste, ServiceReferentiel $service)
    {
        $this->setListe($liste);
        $this->setServiceReferentiel($service);

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
        $url = $this->getView()->url(
            'referentiel/rafraichir-ligne',
            [
                'serviceReferentiel' => $this->getServiceReferentiel()->getId(),
            ],
            [
                'query' => [
                    'only-content' => 1,
                    'read-only'    => $this->getListe()->getReadOnly() ? '1' : '0'],
            ]
        );

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
        $liste = $this->getListe();
        $service = $this->getServiceReferentiel();
        $vhlListe = $service->getVolumeHoraireReferentielListe();
        $heuresTVH = $vhlListe
            ->setTypeVolumeHoraire($this->getListe()->getTypeVolumeHoraire())
            ->setEtatVolumeHoraire($this->getServiceEtatVolumeHoraire()->getSaisi())
            ->getHeures();
        $heuresPrevues = $vhlListe
            ->setTypeVolumeHoraire($this->getServiceTypeVolumeHoraire()->getPrevu())
            ->setEtatVolumeHoraire($this->getServiceEtatVolumeHoraire()->getValide())
            ->getHeures();
        $out = '';
        if ($liste->getColumnVisibility('intervenant')) {
            $out .= '<td>' . $this->renderIntervenant($service->getIntervenant()) . '</td>';
        }
        if ($liste->getColumnVisibility('structure')) {
            $out .= '<td>' . $this->renderStructure($service->getStructure()) . "</td>\n";
        }
        if ($liste->getColumnVisibility('fonction')) {
            $out .= '<td>' . $this->renderFonction($service->getFonctionReferentiel()) . "</td>\n";
        }
        if ($liste->getColumnVisibility('commentaires')) {
            $out .= '<td>' . $this->renderCommentaires($service->getCommentaires()) . "</td>\n";
        }
        if ($liste->getColumnVisibility('motif-non-paiement')) {

            $out .= '<td>' . $this->renderMotifNonPaiement($service->getMotifNonPaiement()) . "</td>\n";
        }
        if ($liste->getColumnVisibility('tags')) {

            $out .= '<td>' . $this->renderTag($service->getTag()) . "</td>\n";
        }

        if ($liste->getColumnVisibility('heures')) {
            $out .= $this->getView()->tag('td', [
                'style'        => "text-align:right",
                'class'        => "sr-heures",
                'data-prevues' => $heuresPrevues,
                'data-value'   => $heuresTVH,
            ])->html($this->renderHeures($service));
        }
        if ($liste->getColumnVisibility('annee')) {
            $out .= '<td>' . $this->renderAnnee($service->getIntervenant()->getAnnee()) . "</td>\n";
        }

        $out .= '<td class="actions">';
        if (!$this->getReadOnly()) {
            $out .= $this->renderModifier();
            $out .= $this->renderSupprimer();
        }
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

        return $this->getView()->structure($structure)->renderLink();
    }


    protected function renderFonction($fonction)
    {
        if (!$fonction) return '';
        $out = $fonction;

        return $out;
    }


    protected function renderCommentaires($commentaires)
    {
        if (!$commentaires) return '';
        $out = $commentaires;

        return $out;
    }


    protected function renderTag($tag)
    {
        if (!$tag) return '';
        $out = $tag->getLibelleLong();

        return $out;
    }

    protected function renderMotifNonPaiement($motifNonPaiement)
    {
        /**
         * @var $motifNonPaiement MotifNonPaiement
         */
        if (!$motifNonPaiement) return '';
        $out = $motifNonPaiement->getLibelleLong();

        return $out;
    }


    protected function renderHeures(ServiceReferentiel $service)
    {
        $out = '';

        $vhlListe = $service->getVolumeHoraireReferentielListe();

        if ($this->isInRealise()) {
            $out .= '<table style="width: 100%">';

            /**
             * PREVU, lecture seule
             */
            $vhlListe
                ->setTypeVolumeHoraire($this->getServiceTypeVolumeHoraire()->getPrevu())
                ->setEtatVolumeHoraire($etat = $this->getServiceEtatVolumeHoraire()->getValide());

            $out .= sprintf(
                '<tr style="opacity: 0.5"><td><strong>Prévisionnel %s :</strong></td><td  class="heures">' . \UnicaenApp\Util::formattedNumber($vhlListe->getHeures()) . '</td></tr>',
                $etat);

            /**
             * REALISE
             */
            $vhlListe
                ->setTypeVolumeHoraire($this->getListe()->getTypeVolumeHoraire())
                ->setEtatVolumeHoraire($this->getServiceEtatVolumeHoraire()->getSaisi());
            $out .= '<tr><td><strong>Réalisé :</strong></td><td id="heures-realises-' . $service->getId() . '" class="heures">' . \UnicaenApp\Util::formattedNumber($vhlListe->getHeures()) . '</td></tr>';

            $out .= '</table>';
        } else {
            $vhlListe
                ->setTypeVolumeHoraire($this->getListe()->getTypeVolumeHoraire())
                ->setEtatVolumeHoraire($this->getServiceEtatVolumeHoraire()->getSaisi());
            $out .= \UnicaenApp\Util::formattedNumber($vhlListe->getHeures());
        }

        return $out;
    }


    protected function renderAnnee($annee)
    {
        $out = $annee->getLibelle();

        return $out;
    }


    protected function renderModifier()
    {
        $url = $this->getView()->url('referentiel/saisie', ['id' => $this->getServiceReferentiel()->getId()], ['query' => ['type-volume-horaire' => $this->getListe()->getTypeVolumeHoraire()->getId()]]);

        return '<a class="ajax-modal" data-event="service-referentiel-modify-message" href="' . $url . '" title="Modifier cette ligne de référentiel"><i class="fas fa-pencil"></i></a>';
    }


    protected function renderSupprimer()
    {
        $url = $this->getView()->url('referentiel/suppression', ['id' => $this->getServiceReferentiel()->getId()], ['query' => ['type-volume-horaire' => $this->getListe()->getTypeVolumeHoraire()->getId()]]);

        return $this->getView()->tag('a', [
            'class'        => 'referentiel-delete',
            'data-title'   => 'Suppression de référentiel',
            'data-content' => 'Souhaitez-vous vraiment supprimer ces heures de référentiel ?',
            'data-confirm' => 'true',
            'data-id'      => $this->getServiceReferentiel()->getId(),
            'href'         => $url,
            'title'        => 'Supprimer cette ligne de référentiel',
        ])->html('<i class="fas fa-trash-can"></i');
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
     * Détermine si nous sommes en service réalisé ou non
     *
     * @return boolean
     */
    public function isInRealise()
    {
        return $this->getListe()->getTypeVolumeHoraire()->getCode() === \Service\Entity\Db\TypeVolumeHoraire::CODE_REALISE;
    }


    /**
     *
     * @return ReferentielsViewHelper
     */
    function getListe()
    {
        return $this->liste;
    }


    /**
     *
     * @param ReferentielsViewHelper $liste
     *
     * @return self
     */
    function setListe(ReferentielsViewHelper $liste)
    {
        $this->liste = $liste;

        return $this;
    }


    /**
     *
     * @return boolean
     */
    public function getReadOnly()
    {
        return $this->getListe()->getReadOnly() || $this->forcedReadOnly;
    }


    /**
     *
     * @param ServiceReferentiel $serviceReferentiel
     *
     * @return self
     */
    public function setServiceReferentiel(?ServiceReferentiel $serviceReferentiel = null)
    {
        $typeVolumeHoraire = $serviceReferentiel->getTypeVolumeHoraire();
        $this->forcedReadOnly = !$this->getView()->isAllowed($serviceReferentiel, $typeVolumeHoraire->getPrivilegeReferentielEdition());
        $this->serviceReferentiel = $serviceReferentiel;

        return $this;
    }

}