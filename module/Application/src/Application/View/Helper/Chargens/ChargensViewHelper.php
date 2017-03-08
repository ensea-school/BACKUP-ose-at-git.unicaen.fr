<?php

namespace Application\View\Helper\Chargens;

use Application\Form\Chargens\FiltreForm;
use Application\Service\Traits\TypeHeuresAwareTrait;
use Application\Service\Traits\TypeInterventionAwareTrait;
use Application\View\Helper\AbstractViewHelper;
use Zend\View\Resolver\TemplatePathStack;

/**
 * Description of ChargensViewHelper
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class ChargensViewHelper extends AbstractViewHelper
{
    use TypeHeuresAwareTrait;
    use TypeInterventionAwareTrait;

    private $buffer = [];

    /**
     * @var FiltreForm;
     */
    private $formFiltre;



    /**
     *
     * @return self
     */
    public function __invoke()
    {
        $this->getView()->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/view"]]));

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
     *
     *
     * @return string Code HTML
     */
    public function render()
    {
        $t = $this->getView()->tag();

        return (string)$t('div', [
            'class'                       => 'chargens',
            'data-type-heures'            => $this->getTypeHeuresArray(),
            'data-type-intervention'      => $this->getTypeInterventionsArray(),
            'data-url-json-etape'         => $this->getView()->url('chargens/json/etape'),
            'data-url-enregistrer'        => $this->getView()->url('chargens/enregistrer'),
            'data-url-scenario-dupliquer' => $this->getView()->url('chargens/scenario/dupliquer'),
        ])->html(
            $t('div', [
                'class' => 'controles',
            ])->html(
                $this->getView()->render("controles.phtml", [
                    'typeHeures'        => $this->getTypeHeuresArray(),
                    'typeInterventions' => $this->getTypeInterventionsArray(),
                    'filtre'            => $this->getFormFiltre(),
                ])
            )
            . $t('div', [
                'id'    => uniqid('chargens-'),
                'class' => 'dessin',
            ])->text()
        );
    }



    private function getTypeHeuresArray()
    {
        if (!isset($this->buffer[__METHOD__])) {
            $qb = $this->getServiceTypeHeures()->finderByHistorique();
            $this->getServiceTypeHeures()->finderByEnseignement(true, $qb);

            $typesHeures = $this->getServiceTypeHeures()->getList($qb);
            $data        = [];
            foreach ($typesHeures as $th) {
                $data[$th->getId()] = $th->getLibelleCourt();
            }
            $this->buffer[__METHOD__] = $data;
        }

        return $this->buffer[__METHOD__];
    }



    private function getTypeInterventionsArray()
    {
        if (!isset($this->buffer[__METHOD__])) {
            $qb = $this->getServiceTypeIntervention()->finderByHistorique();
            $this->getServiceTypeIntervention()->finderByEnseignement(true, $qb);

            $typesIntervention = $this->getServiceTypeIntervention()->getList($qb);
            $data              = [];
            foreach ($typesIntervention as $ti) {
                $data[$ti->getId()] = $ti->getCode();
            }

            $this->buffer[__METHOD__] = $data;
        }

        return $this->buffer[__METHOD__];
    }



    /**
     * @return FiltreForm
     */
    public function getFormFiltre()
    {
        return $this->formFiltre;
    }



    /**
     * @param FiltreForm $formFiltre
     *
     * @return ChargensViewHelper
     */
    public function setFormFiltre(FiltreForm $formFiltre = null)
    {
        $this->formFiltre = $formFiltre;

        return $this;
    }
}