<?php

namespace Application\Form\OffreFormation;

use Application\Entity\Db\CentreCout;
use Application\Entity\Db\ElementPedagogique;
use Application\Form\AbstractForm;
use Application\Form\OffreFormation\Traits\ElementModulateursFieldsetAwareTrait;
use Application\Service\Traits\CentreCoutServiceAwareTrait;
use Application\Service\Traits\TypeModulateurServiceAwareTrait;
use Application\Service\Traits\ElementPedagogiqueServiceAwareTrait;
use Laminas\Form\Element\Select;

/**
 * Description of ElementModulateurSaisie
 *
 * @author Antony Le Courtes <antony.lecourtes@unicaen.fr>
 */
class ElementModulateurCentreCoutForm extends AbstractForm
{
    use TypeModulateurServiceAwareTrait;
    use ElementPedagogiqueServiceAwareTrait;
    use TypeModulateurServiceAwareTrait;
    use ElementModulateursFieldsetAwareTrait;
    use CentreCoutServiceAwareTrait;

    /**
     * Element
     *
     * @var Element
     */
    protected $elementPedagogiquePedagogique;



    public function __construct($name = null, $options = [])
    {
        if (!$name) $name = "element-modulateur-cc-saisie";
        parent::__construct($name, $options);
    }



    public function init()
    {

        $this->setAttribute('class', 'element-modulateur-cc');
        $this->setAttribute('action', $this->getCurrentUrl());

        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value' => 'Enregistrer',
                'class' => 'btn btn-primary',
            ],
        ]);
    }



    public function buildElements()
    {
        $elementPedagogique = $this->getElementPedagogique();
        if (!$elementPedagogique) {
            throw new \RuntimeException('Element non spécifiée : construction du formulaire impossible');
        }
        //Formulaire partie Modulateur de l'élément pédagogique en cours
        $typeModulateurs = $this->getServiceTypeModulateur()->finderByElementPedagogique($elementPedagogique)->getQuery()->getResult();
        if (!empty($typeModulateurs)) {
            foreach ($typeModulateurs as $typeModulateur) {
                $values = $typeModulateur->getModulateur();
                foreach ($values as $value) {
                    $modulateursValues[$typeModulateur->getCode()][$value->getCode()] = $value->getLibelle();
                }
                $selectName       = 'modulateur-' . $typeModulateur->getCode();
                $selectModulateur = new Select($selectName);
                $selectModulateur->setLabel($typeModulateur->getLibelle() . " : ");
                $selectModulateur->setValueOptions(['' => '(Aucun)'] + $modulateursValues[$typeModulateur->getCode()]);
                $elementsModulateurs = $elementPedagogique->getElementModulateur();
                foreach ($elementsModulateurs as $elementModulateur) {
                    $typeModulateur = $elementModulateur->getModulateur()->getTypeModulateur();
                    $modulateur     = $elementModulateur->getModulateur();
                    $selectModulateur->setValue($modulateur->getCode());
                }
                $this->add($selectModulateur);
            }
        }

        //Formulaire partie Centres de coût de l'élément pédagogique en cours
        $centresCoutsEp     = $elementPedagogique->getCentreCoutEp();
        $centresCoutsValues = [];
        foreach ($centresCoutsEp as $centreCoutEp) {
            $codeTypeHeures                      = $centreCoutEp->getTypeHeures()->getCode();
            $centreCout                          = $centreCoutEp->getCentreCout();
            $centresCoutsValues[$codeTypeHeures] = $centreCout->getCode();
        }
        $typeHeuresEp = $elementPedagogique->getTypeHeures()->getValues();
        foreach ($typeHeuresEp as $typeHeures) {
            $selectCentreCout = $this->createSelectElementCentreCout($typeHeures, $elementPedagogique);
            if (array_key_exists($typeHeures->getCode(), $centresCoutsValues)) {
                $selectCentreCout->setValue($centresCoutsValues[$typeHeures->getCode()]);
            }
            $this->add($selectCentreCout);
        }


        $this->add([
            'name' => 'id',
            'type' => 'Hidden',
        ]);
    }



    private function createSelectElementCentreCout(\Application\Entity\Db\TypeHeures $th, ElementPedagogique $elementPedagogique)
    {
        $filter       = function (CentreCout $centreCout) use ($th) {
            return $centreCout->getTypeHeures()->contains($th);
        };
        $centresCouts = $elementPedagogique->getStructure()->getCentreCout()->filter($filter);
        $valueOptions = [];
        foreach ($centresCouts as $centreCout) {
            $valueOptions[$centreCout->getCode()] = $centreCout->getCode() . ' - ' . $centreCout->getLibelle();
        }
        $element = new Select($th->getCode());
        $element
            ->setLabel($th->getLibelleCourt())
            ->setValueOptions(['' => '(Aucun)'] + $valueOptions)
            ->setAttribute('class', 'type-heures selectpicker')
            ->setAttribute('data-live-search', 'true');

        return $element;
    }



    /**
     * Retourne la liste des types de modulateurs de l'element
     *
     * @return \Application\Entity\Db\Modulateur[]
     */
    public function getTypesModulateurs()
    {

        if (!$this->elementPedagogique) {
            throw new \RuntimeException('Element non spécifié');
        }

        return $this->getServiceTypeModulateur()->getList($this->getServiceTypeModulateur()->finderByElementPedagogique($this->elementPedagogique));
    }



    public function getElementPedagogique()
    {
        return $this->elementPedagogique;
    }



    public function setElementPedagogique(ElementPedagogique $elementPedagogique)
    {
        $this->elementPedagogique = $elementPedagogique;

        return $this;
    }



    public function getInputFilterSpecification()
    {

    }

}