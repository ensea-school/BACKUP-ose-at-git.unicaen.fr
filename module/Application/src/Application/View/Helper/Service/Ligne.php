<?php

namespace Application\View\Helper\Service;

use Zend\View\Helper\AbstractHelper;
use Application\Entity\Db\Service;

/**
 * Aide de vue permettant d'afficher une ligne de service
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Ligne extends AbstractHelper
{

    /**
     * @var Service
     */
    protected $service;

    /**
     * Contexte
     *
     * @var array
     */
    protected $context;





    /**
     * Helper entry point.
     *
     * @param Service $service
     * @return self
     */
    final public function __invoke( Service $service, array $context=array() )
    {
        $this->service = $service;
        $this->context = $context;
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
     * Génère le code HTML.
     *
     * @return string
     */
    protected function render(){
        $out = '<tr>';

        $out .= '<td>'.$this->renderId($this->service->getId())."</td>\n";
        if (empty($this->context['intervenant'])){
            $out .= '<td>'.$this->service->getIntervenant()->getNomComplet(true);
            $out .= $this->renderStructure( $this->service->getStructureAff() )."</td>\n";
        }
        $out .= '<td>'.$this->renderStructure( $this->service->getStructureEns() )."</td>\n";
        $out .= '<td>'.$this->renderElementPedagogique( $this->service->getElementPedagogique() )."</td>\n";
        if (empty($this->context['annee'])){
            $out .= '<td>'.$this->renderAnnee( $this->service->getAnnee() )."</td>\n";
        }
        $out .= '<td>'.$this->renderEtablissement( $this->service->getEtablissement() )."</td>\n";
        $out .= '</tr>';
        return $out;
    }

    protected function renderId($id)
    {
        $out = '<a href="#">N° <span class="badge">'.(string)$id.'</span></a>'."\n";
        return $out;
    }

    protected function renderStructure($structure)
    {
        $url = $this->getView()->url('structure/default', array('action' => 'voir', 'id' => $structure->getId()));

        $out = '<a href="'.$url.'" class="modal-action">'.$structure->getLibelleCourt().'</a>';

        return $out;
    }

    protected function renderElementPedagogique($element)
    {
        //$url = $this->getView()->url('structure/default', array('action' => 'voir', 'id' => $structure->getId()));

        //$out = '<a href="'.$url.'" class="modal-action">'.$structure->getLibelleCourt().'</a>';
        $out = $element->getLibelle();

        return $out;
    }

    protected function renderAnnee($annee)
    {
        $out = $annee->getLibelle();
        return $out;
    }

    protected function renderEtablissement($etablissement)
    {
        if (! empty($this->context['etablissement']) && $etablissement == $this->context['etablissement']){
            $url = $this->getView()->url('etablissement/default', array('action' => 'voir', 'id' => $etablissement->getId()));
            $out = '<a href="'.$url.'" class="modal-action">'.$etablissement->getLibelle().'</a>';            
        }else{
            $out = '';
        }
        return $out;
    }


    /**
     *
     * @return Service
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     *
     * @return array
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     *
     * @param Service $service
     * @return self
     */
    public function setService(Service $service)
    {
        $this->service = $service;
        return $this;
    }

    /**
     *
     * @param array $context
     * @return self
     */
    public function setContext($context)
    {
        $this->context = $context;
        return $this;
    }

}