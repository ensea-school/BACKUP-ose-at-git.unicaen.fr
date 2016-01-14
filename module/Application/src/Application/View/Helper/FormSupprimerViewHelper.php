<?php

namespace Application\View\Helper;

use Application\Form\Supprimer;
use Zend\View\Helper\AbstractHtmlElement;


/**
 * Description of FormSupprimerViewHelper
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class FormSupprimerViewHelper extends AbstractHtmlElement
{

    protected $form;

    /**
     *
     * @param Supprimer $form
     *
     * @return self
     */
    public function __invoke($form)
    {
        $this->form = $form;

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
        $res = '';
        if ($this->form){
            $res .= $this->getView()->form()->openTag($this->form);
            $res .= $this->getView()->formHidden($this->form->get('id'));
            $res .= $this->getView()->formHidden($this->form->get('security'));
            $res .= $this->getView()->formSubmit($this->form->get('submit')->setAttribute('class', 'btn btn-primary'));
            $res .= $this->getView()->form()->closeTag();
        }
        return $res;
    }

}