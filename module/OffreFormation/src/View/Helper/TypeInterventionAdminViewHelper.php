<?php

namespace OffreFormation\View\Helper;

use Application\Filter\StringFromFloat;
use Application\Service\Traits\ContextServiceAwareTrait;
use Laminas\View\Helper\AbstractHtmlElement;
use OffreFormation\Entity\Db\TypeIntervention;
use OffreFormation\Service\Traits\TypeInterventionServiceAwareTrait;

/**
 * Aide de vue permettant d'afficher une liste de statuts
 */
class TypeInterventionAdminViewHelper extends AbstractHtmlElement
{
    use TypeInterventionServiceAwareTrait;
    use ContextServiceAwareTrait;

    /**
     *
     * @var string
     */
    private $id;

    /**
     * @var TypeIntervention
     */
    private $typeIntervention;



    /**
     *
     * @return TypeIntervention
     */
    public function getTypeIntervention()
    {
        return $this->typeIntervention;
    }



    /**
     *
     * @param TypeIntervention $typeIntervention
     *
     * @return self
     */
    public function setTypeIntervention($typeIntervention)
    {
        $this->typeIntervention = $typeIntervention;

        return $this;
    }



    /**
     * Helper entry point.
     *
     * @param TypeIntervention $TypeIntervention
     *
     * @return self
     */
    final public function __invoke(TypeIntervention $typeIntervention)
    {
        $this->setTypeIntervention($typeIntervention);

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
    public function render($details = false)
    {
        $ti = $this->getTypeIntervention();

        $title   = '';
        $statuts = $ti->getTypeInterventionStatut($this->getServiceContext()->getAnnee());
        foreach ($statuts as $tis) {
            $title .= '∙ ';
            $title .= $tis->getStatut()->getLibelle() . '<br>';
        }
        $etoile = (strlen($title) ? '&#x2605;' : '');

        $url = $this->getView()->url('type-intervention/statut', ['typeIntervention' => $ti->getId()]);

        $html = '<td>';
        $html .= $this->getView()->tag('a', [
            'class'              => 'ajax-modal',
            'data-bs-toggle'     => 'tooltip',
            'data-placement'     => 'bottom',
            'title'              => $title,
            'href'               => $url,
            'data-submit-reload' => 'true',
            'data-bs-html'       => 'true',
        ])->text(StringFromFloat::run($ti->getTauxHetdService()) . $etoile);
        $html .= '</td><td>';

        $html .= $this->getView()->tag('a', [
            'class'              => 'ajax-modal',
            'data-bs-toggle'     => 'tooltip',
            'data-placement'     => 'bottom',
            'title'              => $title,
            'href'               => $url,
            'data-submit-reload' => 'true',
            'data-bs-html'       => 'true',
        ])->text(StringFromFloat::run($ti->getTauxHetdComplementaire()) . $etoile);

        $html .= '</td>';

        return $html;
    }
}