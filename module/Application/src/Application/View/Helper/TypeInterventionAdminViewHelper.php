<?php

namespace Application\View\Helper;

use Application\Entity\Db\TypeInterventionStatut;
use Application\Entity\Db\TypeIntervention;
use Application\Service\Traits\TypeInterventionServiceAwareTrait;
use Application\View\Helper\AbstractViewHelper;

/**
 * Aide de vue permettant d'afficher une liste de statuts
 */
class TypeInterventionAdminViewHelper extends AbstractViewHelper
{
    use TypeInterventionServiceAwareTrait;

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
        $statuts = $ti->getTypeInterventionStatut();
        foreach ($statuts as $tis) {
            if ($title) $title .= ' - ';
            $title .= $tis->getStatutIntervenant()->getLibelle();
        }

        $url = $this->getView()->url('type-intervention/statut', ['typeIntervention' => $ti->getId()]);

        return $this->getView()->tag('a', [
            'class'              => 'ajax-modal',
            'data-toggle'        => 'tooltip',
            'data-placement'     => 'bottom',
            'title'              => $title,
            'href'               => $url,
            'data-submit-reload' => 'true',
        ])->text($ti->getLibelle());
    }
}
