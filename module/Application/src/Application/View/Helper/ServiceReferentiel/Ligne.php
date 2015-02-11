<?php

namespace Application\View\Helper\ServiceReferentiel;

use Zend\View\Helper\AbstractHtmlElement;
use Application\Entity\Db\ServiceReferentiel;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;
use Application\Interfaces\ServiceReferentielAwareInterface;
use Application\Traits\ServiceReferentielAwareTrait;
use Application\View\Helper\VolumeHoraireReferentiel\Liste as ListeHelper;

/**
 * Aide de vue permettant d'afficher une ligne de service
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Ligne extends AbstractHtmlElement 
            implements ServiceLocatorAwareInterface, ContextProviderAwareInterface, ServiceReferentielAwareInterface
{
    use ServiceLocatorAwareTrait;
    use ContextProviderAwareTrait;
    use ServiceReferentielAwareTrait;

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
     * @param Liste $liste
     * @param Service $service
     * @return self
     */
    final public function __invoke( Liste $liste, ServiceReferentiel $service )
    {
        $this->setListe($liste);
        $this->setService( $service );
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
                    'serviceReferentiel' => $this->getService()->getId(), 
                ], 
                [
                    'query' => [
                        'only-content' => 1, 
                        'read-only'    => $this->getListe()->getReadOnly() ? '1' : '0' ]
                ]
        );
        return $url;
    }

    /**
     * Génère le code HTML.
     *
     * @param boolean $details
     * @return string
     */
    public function render( $details=false )
    {
        $liste = $this->getListe();
        $service = $this->getService();

        $out = '';
        if ($liste->getColumnVisibility('intervenant')){
            $out .= '<td>'.$this->renderIntervenant($this->getService()->getIntervenant()).'</td>';
        }
        if ($liste->getColumnVisibility('structure')){
            $out .= '<td>'.$this->renderStructure($service->getStructure())."</td>\n";
        }
        if ($liste->getColumnVisibility('fonction')){
            $out .= '<td>'.$this->renderFonction($this->getService()->getFonction())."</td>\n";
        }
        if ($liste->getColumnVisibility('commentaires')){
            $out .= '<td>'.$this->renderCommentaires($this->getService()->getCommentaires())."</td>\n";
        }
        if ($liste->getColumnVisibility('heures')){
            $out .= '<td style="text-align:right">'.$this->renderHeures($this->getService())."</td>\n";
        }
        if ($liste->getColumnVisibility('annee')){
            $out .= '<td>'.$this->renderAnnee( $this->getService()->getAnnee() )."</td>\n";
        }

        $out .= '<td class="actions">';
        if (! $liste->getReadOnly()) {
            $out .= $this->renderModifier();
            $out .= $this->renderSupprimer();
        }
        $out .= '</td>';
        
        return $out;
    }

    protected function renderIntervenant($intervenant)
    {
        $pourl = $this->getView()->url('intervenant/default', array('action' => 'apercevoir', 'intervenant' => $intervenant->getSourceCode()));
        $out = '<a href="'.$pourl.'" data-po-href="'.$pourl.'" class="ajax-modal services">'.$intervenant.'</a>';
        return $out;
    }

    protected function renderStructure($structure)
    {
        if (! $structure) return '';

        $url = $this->getView()->url('structure/default', array('action' => 'voir', 'id' => $structure->getId()));
        $pourl = $this->getView()->url('structure/default', array('action' => 'apercevoir', 'id' => $structure->getId()));
        $out = '<a href="'.$url.'" data-po-href="'.$pourl.'" class="ajax-modal">'.$structure.'</a>';
        return $out;
    }

    protected function renderFonction($fonction)
    {
        if (! $fonction) return '';
        $out = $fonction;
        return $out;
    }

    protected function renderCommentaires($commentaires)
    {
        if (! $commentaires) return '';
        $out = $commentaires;
        return $out;
    }

    protected function renderHeures(ServiceReferentiel $service)
    {
        $out = '';
        
        $vhlListe = $service->getVolumeHoraireReferentielListe();
        $vhlView  = $this->getView()->volumeHoraireReferentielListe($vhlListe);  /* @var $vhlView ListeHelper */
        
        if ($this->isInRealise()) {
            $out .= '<table style="width: 100%">';
            
            /**
             * PREVU, lecture seule
             */
            $vhlListe
                    ->setTypeVolumeHoraire($this->getServiceTypeVolumeHoraire()->getPrevu())
                    ->setEtatVolumeHoraire($this->getServiceEtatVolumeHoraire()->getValide());
            $vhlView->setReadOnly(true);
            $out .= '<tr style="opacity: 0.5"><td><strong>Prévisionnel :</strong></td><td class="heures">' . $vhlView->render() . '</td></tr>';
            
            /**
             * REALISE
             */
            $vhlListe
                    ->setTypeVolumeHoraire($this->getListe()->getTypeVolumeHoraire())
                    ->setEtatVolumeHoraire($this->getServiceEtatVolumeHoraire()->getSaisi());
            $vhlView->setReadOnly($this->getListe()->getReadOnly());
            $out .= '<tr><td><strong>Réalisé :</strong></td><td class="heures">' . $vhlView->render() . '</td></tr>';
            
            $out .= '</table>';
        }
        else {
            $vhlListe
                    ->setTypeVolumeHoraire($this->getListe()->getTypeVolumeHoraire())
                    ->setEtatVolumeHoraire($this->getServiceEtatVolumeHoraire()->getSaisi());
            $vhlView->setReadOnly($this->getListe()->getReadOnly());
            $out .= $vhlView->render();
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
        $url = $this->getView()->url('referentiel/saisie', ['id' => $this->getService()->getId()], ['query' => ['type-volume-horaire' => $this->getListe()->getTypeVolumeHoraire()->getId()]]);
        return '<a class="ajax-modal" data-event="service-referentiel-modify-message" href="'.$url.'" title="Modifier cette ligne de référentiel"><span class="glyphicon glyphicon-edit"></span></a>';
    }

    protected function renderSupprimer()
    {
        $url = $this->getView()->url('referentiel/default', array('action' => 'suppression', 'id' => $this->getService()->getId()), ['query' => ['type-volume-horaire' => $this->getListe()->getTypeVolumeHoraire()->getId()]]);
        return '<a class="ajax-modal referentiel-delete" data-event="service-referentiel-delete-message" data-id="'.$this->getService()->getId().'" href="'.$url.'" title="Supprimer cette ligne de référentiel"><span class="glyphicon glyphicon-remove"></span></a>';
    }

    protected function toQuery($param)
    {
        if (null === $param) return null;
        elseif (false === $param) return 'false';
        elseif( true === $param) return 'true';
        elseif(method_exists($param, 'getId')) return $param->getId();
        else throw new \Common\Exception\LogicException('Le paramètre n\'est pas du bon type');
    }

    /**
     * Détermine si nous sommes en service réalisé ou non
     *
     * @return boolean
     */
    public function isInRealise()
    {
        return $this->getListe()->getTypeVolumeHoraire()->getCode() === \Application\Entity\Db\TypeVolumeHoraire::CODE_REALISE;
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
     * @return self
     */
    function setListe(Liste $liste)
    {
        $this->liste = $liste;
        return $this;
    }

    /**
     *
     * @param ServiceReferentiel $service
     * @return self
     */
    public function setService(ServiceReferentiel $service = null)
    {
        $this->forcedReadOnly = ! $this->getView()->isAllowed($service, 'update');
        $this->service = $service;
        return $this;
    }

    /**
     *
     * @return \Application\Service\TypeVolumeHoraire
     */
    protected function getServiceTypeVolumeHoraire()
    {
        return $this->getServiceLocator()->getServiceLocator()->get('applicationTypeVolumeHoraire');
    }

    /**
     *
     * @return \Application\Service\EtatVolumeHoraire
     */
    protected function getServiceEtatVolumeHoraire()
    {
        return $this->getServiceLocator()->getServiceLocator()->get('applicationEtatVolumeHoraire');
    }
}