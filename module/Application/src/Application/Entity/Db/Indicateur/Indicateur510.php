<?php

namespace Application\Entity\Db\Indicateur;

class Indicateur510 extends AbstractIndicateur
{
    /**
     * @var string
     */
    private $elements;



    /**
     * @return string
     */
    public function getElements()
    {
        return $this->elements;
    }



    /**
     * @param string $elements
     *
     * @return Indicateur510
     */
    public function setElements($elements)
    {
        $this->elements = $elements;

        return $this;
    }



    /**
     * Retourne les détails concernant l'indicateur
     *
     * @return string|null
     */
    public function getDetails()
    {
        $elements = explode('||', $this->getElements());
        $out = '<ul>';
        foreach( $elements as $element ){
            $out .= "<li>&Eacute;lément &laquo; $element &raquo;</li>";
        }
        $out .= '</ul>';

        return $out;
    }

}
