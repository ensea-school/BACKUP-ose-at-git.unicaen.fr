<?php

namespace Application\View\Helper\Intervenant;

use Application\Constants;
use Application\Entity\Db\Agrement;
use Application\Entity\Db\Contrat;
use Application\Entity\Db\Dossier;
use Application\Entity\Db\Fichier;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\MiseEnPaiement;
use Application\Entity\Db\ModificationServiceDu;
use Application\Entity\Db\PieceJointe;
use Application\Entity\Db\Structure;
use Application\Entity\Db\Validation;
use Application\Entity\Db\VolumeHoraire;
use Application\Entity\Db\VolumeHoraireReferentiel;
use Application\Entity\IntervenantSuppressionData;
use Application\Entity\Traits\IntervenantSuppressionDataAwareTrait;
use Zend\View\Helper\AbstractHtmlElement;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

/**
 * Description of EntityViewHelper
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class IntervenantSuppressionDataViewHelper extends AbstractHtmlElement implements ServiceLocatorAwareInterface
{
    use IntervenantSuppressionDataAwareTrait;
    use ServiceLocatorAwareTrait;


    /**
     *
     * @return self
     */
    public function __invoke(IntervenantSuppressionData $isd = null)
    {
        $this->setIntervenantSuppressionData($isd);

        return $this;
    }



    /**
     * Retourne le code HTML.
     *
     * @return string Code HTML
     */
    public function __toString()
    {
        return $this->render();
    }



    /**
     * @return string
     */
    public function render()
    {
        return $this->renderItem($this->getIntervenantSuppressionData(), true);
    }



    /**
     * @param IntervenantSuppressionData $isd
     * @param bool                       $first
     *
     * @return string
     */
    public function renderItem( IntervenantSuppressionData $isd, $first=false )
    {

        if ($isd->getLabel()){
            $html = $isd->getLabel();
        }elseif($isd->getEntity()){
            $html = $this->renderEntity($isd->getEntity());
        }else{
            $html = 'Inconnu';
        }

        if ($isd->hasChildren()){
            $sHtml = '';
            $isd->order();
            foreach( $isd as $child ){
                $sHtml .= $this->renderItem($child);
            }
            $html .= $this->getView()->tag('ul')->html($sHtml);
        }

        $html = $this->getView()->tag('li')->html($html);

        if ($first){
            $html = $this->getView()->tag('ul')->html($html);
        }

        return $html;
    }



    /**
     *
     *
     * @return string Code HTML
     */
    public function renderEntity($entity)
    {
        switch (true) {
            case $entity instanceof ModificationServiceDu:
                return $this->renderModificationServiceDu($entity);

            case $entity instanceof Intervenant:
                return (string)$this->getView()->intervenant($entity)->renderLink();

            case $entity instanceof Validation:
                return (string)$this->getView()->validation($entity)->renderLabel();

            case $entity instanceof Dossier:
                return $this->renderDossier($entity);

            case $entity instanceof \Application\Entity\Db\Service:
                return $this->renderService($entity);

            case $entity instanceof \Application\Entity\Db\ServiceReferentiel:
                return $this->renderServiceReferentiel($entity);

            case $entity instanceof VolumeHoraire:
                return $this->renderVolumeHoraire($entity);

            case $entity instanceof VolumeHoraireReferentiel:
                return $this->renderVolumeHorairereferentiel($entity);

            case $entity instanceof Structure:
                return $this->getView()->structure($entity)->renderLink();

            case $entity instanceof PieceJointe:
                return $this->renderPieceJointe($entity);

            case $entity instanceof Fichier:
                return $this->renderFichier($entity);

            case $entity instanceof Agrement:
                return $this->getView()->agrement($entity)->renderLabel();

            case $entity instanceof Contrat:
                return $this->renderContrat($entity);

            case $entity instanceof MiseEnPaiement:
                return $this->renderMiseEnPaiement($entity);
        }

        return get_class($entity) . ' ' . $entity->getId();
    }



    protected function renderModificationServiceDu(ModificationServiceDu $msd)
    {
        $params = [];
        if ($msd->getCommentaires()) {
            $params['title'] = $msd->getCommentaires();
        }

        return $this->getView()->tag('span', $params)->html(
            $msd->getMotif() . ' (' . $msd->getHeures() . ' heures)'
        );
    }



    protected function renderDossier(Dossier $dossier)
    {
        return $this->getView()->tag('span')->html(
            'Données personnelles de ' . $dossier->getIntervenant()
        );
    }



    protected function renderService(\Application\Entity\Db\Service $service)
    {
        if ($service->getElementPedagogique()) {
            return $this->getView()->elementPedagogique($service->getElementPedagogique())->renderLink();
        } else {
            return $this->getView()->tag('span', ['title' => $service->getDescription()])->html(
                $this->getView()->etablissement($service->getEtablissement())->renderLink()
            );
        }
    }



    protected function renderPieceJointe(PieceJointe $pieceJointe)
    {
        return $this->getView()->tag('span')->html(
            $pieceJointe->getType()->getLibelle()
        );
    }



    protected function renderServiceReferentiel(\Application\Entity\Db\ServiceReferentiel $service)
    {
        return $this->getView()->fonctionReferentiel($service->getFonction())->renderLink();
    }



    protected function renderVolumeHoraire(VolumeHoraire $volumeHoraire)
    {
        $label = sprintf('%s heures %s, %s',
            $volumeHoraire->getHeures(),
            $volumeHoraire->getTypeIntervention()->getCode(),
            $volumeHoraire->getPeriode()->getLibelleCourt()
        );

        if ($volumeHoraire->getMotifNonPaiement()) {
            $label .= ' non payables (' . $volumeHoraire->getMotifNonPaiement()->getLibelleCourt() . ')';
        }

        return $this->getView()->tag('span')->html(
            $label
        );
    }



    protected function renderVolumeHoraireReferentiel(VolumeHoraireReferentiel $volumeHoraire)
    {
        $label = sprintf('%s heures',
            $volumeHoraire->getHeures()
        );

        return $this->getView()->tag('span')->html(
            $label
        );
    }



    protected function renderFichier(Fichier $fichier)
    {
        $label = sprintf('Fichier : %s',
            $fichier->getNom()
        );

        return $this->getView()->tag('span')->html(
            $label
        );
    }



    protected function renderContrat(Contrat $contrat)
    {
        $label = sprintf('%s%s n° %s%s',
            $contrat->getTypeContrat()->getLibelle(),
            $contrat->getValidation() && $contrat->getValidation()->estNonHistorise() ? '' : '(PROJET)',
            $contrat->getId(),
            $contrat->getNumeroAvenant() ? '.'.$contrat->getNumeroAvenant() : '',
            $contrat->getStructure()->getLibelleCourt()
        );
        if ($drs = $contrat->getDateRetourSigne()){
            $label .= ', retourné signé le '.$drs->format(Constants::DATE_FORMAT);
        }

        return $this->getView()->tag('span')->html(
            $label
        );
    }



    public function renderMiseEnPaiement(MiseEnPaiement $mep)
    {
        $label = sprintf('%s heures %s %s',
            $mep->getHeures(),
            $mep->getTypeHeures()->getLibelleCourt(),
            $mep->getPeriodePaiement()
        );

        $title = [
            'Centre de coûts : '.$mep->getCentreCout()->getSourceCode()
        ];
        if ($mep->getDomaineFonctionnel()){
            $title[] = 'Domaine fonctionnel : '.$mep->getDomaineFonctionnel();
        }

        $attrs = [
            'title' => implode( "\n",$title),
        ];

        return $this->getView()->tag('abbr', $attrs)->html(
            $label
        );
    }
}