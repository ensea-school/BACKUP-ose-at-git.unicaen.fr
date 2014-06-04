<?php

namespace Application\View\Helper\Service;

use Application\Form\Service\Saisie;
use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;

/**
 * Description of SaisieForm
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class SaisieForm extends AbstractHelper implements ServiceLocatorAwareInterface, ContextProviderAwareInterface
{
    use ServiceLocatorAwareTrait;
    use ContextProviderAwareTrait;

    /**
     * @var Saisie
     */
    protected $form;

    /**
     *
     * @return \Application\Service\Periode[]
     */
    public function getPeriodes()
    {
        $sp = $this->getServiceLocator()->getServiceLocator()->get('applicationPeriode');
        /* @var $sp \Application\Service\Periode */
        return $sp->getList( $sp->finderByEnseignement() );
    }

    /**
     *
     * @param Saisie $form
     * @return SaisieForm|string
     */
    public function __invoke(Saisie $form = null)
    {
        if (null === $form) {
            return $this;
        }

        return $this->render($form);
    }

    /**
     * Rendu du formulaire
     *
     * @param Saisie $form
     * @return string
     */
    public function render(Saisie $form)
    {
        $fservice = $form->get('service');

        $interne = $fservice->get('interne-externe')->getValue() == 'service-interne';

        $form->prepare();

        $res = $this->getView()->form()->openTag($form);
        if (! $this->getContextProvider()->getSelectedIdentityRole() instanceof \Application\Acl\IntervenantRole){
            $res .= $this->getView()->formControlGroup($fservice->get('intervenant'));
        }
        $res .= $this->getView()->formControlGroup($fservice->get('interne-externe'), 'formButtonGroup');
        $res .= '<div id="element-interne" '.(($interne) ? '' : 'style="display:none"').'>'.$this->getView()->fieldsetElementPedagogiqueRecherche($fservice->get('element-pedagogique')).'</div>';
        $res .= '<div id="element-externe" '.(($interne) ? 'style="display:none"' : '').'>'.$this->getView()->formControlGroup($fservice->get('etablissement')).'</div>';
        foreach( $this->getPeriodes() as $periode ){
            $res .= $this->getView()->volumeHoraireSaisieMultipleFieldset(
                                            $form->get($periode->getCode()),
                                            $this->getServiceLocator()->getServiceLocator()->get('applicationService')->getPeriode($fservice->getObject())
                    );
        }
        $res .= '<br />';
        $res .= $this->getView()->formRow($form->get('submit'));
        $res .= $this->getView()->formHidden($fservice->get('id'));
        $res .= $this->getView()->form()->closeTag().'<br />';
        return $res;
    }
}