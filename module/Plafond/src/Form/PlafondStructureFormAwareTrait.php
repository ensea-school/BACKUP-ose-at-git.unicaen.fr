<?php

namespace Plafond\Form;


/**
 * Description of PlafondStructureFormAwareTrait
 *
 * @author UnicaenCode
 */
trait PlafondStructureFormAwareTrait
{
    /**
     * @var PlafondStructureForm
     */
    protected $formPlafondStructure;



    /**
     * @param PlafondStructureForm $formPlafondStructure
     *
     * @return self
     */
    public function setFormPlafondStructure( PlafondStructureForm $formPlafondStructure )
    {
        $this->formPlafondStructure = $formPlafondStructure;

        return $this;
    }



    /**
     * @return PlafondStructureForm
     */
    public function getFormPlafondStructure(): ?PlafondStructureForm
    {
        if (!$this->formPlafondStructure){
            $this->formPlafondStructure = \Application::$container->get('FormElementManager')->get(PlafondStructureForm::class);
        }

        return $this->formPlafondStructure;
    }
}