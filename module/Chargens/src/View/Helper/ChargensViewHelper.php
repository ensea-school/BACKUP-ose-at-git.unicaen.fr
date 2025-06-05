<?php

namespace Chargens\View\Helper;

use Chargens\Form\FiltreForm;
use Laminas\View\Helper\AbstractHtmlElement;
use OffreFormation\Entity\Db\TypeHeures;
use OffreFormation\Service\Traits\TypeHeuresServiceAwareTrait;
use OffreFormation\Service\Traits\TypeInterventionServiceAwareTrait;


/**
 * Description of ChargensViewHelper
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class ChargensViewHelper extends AbstractHtmlElement
{
    use TypeHeuresServiceAwareTrait;
    use TypeInterventionServiceAwareTrait;

    private $buffer = [];

    /**
     * @var FiltreForm;
     */
    private $formFiltre;



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
            'data-url-json-etape'         => $this->getView()->url('chargens/formation/json'),
            'data-url-enregistrer'        => $this->getView()->url('chargens/formation/enregistrer'),
            'data-url-scenario-dupliquer' => $this->getView()->url('chargens/scenario/dupliquer'),
        ])->html(
            $t('div', [
                'class' => 'controles',
            ])->html(
                $this->getView()->render("chargens/chargens/controles", [
                    'typeHeures'        => $this->getTypeHeuresArray(),
                    'typeInterventions' => $this->getTypeInterventionsArray(),
                    'filtre'            => $this->getFormFiltre(),
                ])
            )
            . $t('div', ['id' => 'chargens-attente', 'class' => 'alert alert-info', 'style' => 'display:none'])->html(
                'Construction du diagramme en cours. Veuillez patienter... '
                . $t('img', ['src' => $this->getView()->basePath() . '/images/wait.gif', 'alt' => 'Attente...'])->openClose()

            )
            . $t('div', [
                'id'    => (string)uniqid('chargens-'),
                'class' => 'dessin',
            ])->text()
        );
    }



    private function getTypeHeuresArray()
    {
        if (!isset($this->buffer[__METHOD__])) {
            $dql = "
            SELECT
                th
            FROM
                ".TypeHeures::class." th
            WHERE
                th.enseignement = true
            ORDER BY
                th.ordre
            ";

            /** @todo fournir l'EntityManager via la factory */
            $em = $this->getServiceTypeIntervention()->getEntityManager();

            $typesHeures = $em->createQuery($dql)->getResult();
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
            $this->getServiceTypeIntervention()->finderByContext($qb);

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