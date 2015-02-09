<?php

namespace Application\View\Helper\Paiement;

use Application\Form\Service\Saisie;
use Zend\View\Helper\AbstractHtmlElement;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;

/**
 * Description of MiseEnPaiementSaisieFormViewHelper
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class MiseEnPaiementSaisieFormViewHelper extends AbstractHtmlElement implements ServiceLocatorAwareInterface, ContextProviderAwareInterface
{
    use ServiceLocatorAwareTrait;
    use ContextProviderAwareTrait;

    /**
     * @var Saisie
     */
    protected $form;

    /**
     *
     * @param Saisie $form
     * @return SaisieForm|string
     */
    public function __invoke(Saisie $form = null)
    {
        $this->form = $form;
        return $this;
    }

    public function __toString()
    {
        return $this->render();
    }

    /**
     * Rendu du formulaire
     *
     * @param Saisie $form
     * @return string
     */
    public function render()
    {
        $res = $this->getView()->form()->openTag($this->form);

        $res .= $this->getView()->formHidden($this->form->get('formule-resultat-service'));
        $res .= $this->getView()->formHidden($this->form->get('formule-resultat-service-referentiel'));
        $res .= $this->getView()->formRow($this->form->get('submit'));
        $res .= $this->getView()->form()->closeTag().'<br />';
        return $res;
    }
}