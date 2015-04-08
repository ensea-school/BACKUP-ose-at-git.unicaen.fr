<?php

namespace Application\Form\Intervenant;

use Zend\Form\Form;
use Zend\Form\FormInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

/**
 * Formulaire de modification du dossier d'un intervenant extérieur.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class Dossier extends Form implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait,
        \Application\Service\Traits\StatutIntervenantAwareTrait;

    /**
     * This function is automatically called when creating element with factory. It
     * allows to perform various operations (add elements...)
     */
    public function init()
    {
        $defaultStatut = $this->getServiceStatutIntervenant()->getRepo()->findOneBySourceCode(\Application\Entity\Db\StatutIntervenant::CHARG_ENS_1AN);

        $this->setHydrator(new DossierHydrator($defaultStatut));

        $fs = new DossierFieldset('dossier');
        $fs
                ->setServiceLocator($this->getServiceLocator())
                ->init();

        $this->add($fs);

        /**
         * Csrf
         */
        $this->add(new \Zend\Form\Element\Csrf('security'));

        /**
         * Submit
         */
        $this->add([
            'name' => 'submit',
            'type'  => 'Submit',
            'attributes' => [
                'value' => "Enregistrer",
            ],
        ]);
    }

    /**
     *
     * @param \Application\Entity\Db\IntervenantExterieur $object
     * @param type $flags
     * @return type
     * @throws \Common\Exception\LogicException
     */
    public function bind($object, $flags = FormInterface::VALUES_NORMALIZED)
    {
        if (!$object instanceof \Application\Entity\Db\IntervenantExterieur) {
            throw new \Common\Exception\LogicException("Ce formulaire ne peut être bindé qu'à un IntervenantExterieur.");
        }

        return parent::bind($object, $flags);
    }
}
