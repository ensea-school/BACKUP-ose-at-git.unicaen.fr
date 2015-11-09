<?php

namespace Application\Form\Intervenant;

use Application\Entity\Db\Intervenant;
use Application\Service\Traits\StatutIntervenantAwareTrait;
use Common\Exception\LogicException;
use Zend\Form\Element\Csrf;
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
        StatutIntervenantAwareTrait;

    protected $dossierFieldset;



    /**
     * This function is automatically called when creating element with factory. It
     * allows to perform various operations (add elements...)
     */
    public function init()
    {
        $this->setHydrator(new DossierHydrator());

        $this->dossierFieldset = new DossierFieldset('dossier');
        $this->dossierFieldset
            ->setServiceLocator($this->getServiceLocator())
            ->init();

        $this->setAttribute('id', 'dossier');

        $this->add($this->dossierFieldset);

        /**
         * Csrf
         */
        $this->add(new Csrf('security'));

        /**
         * Submit
         */
        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value' => "Enregistrer",
            ],
        ]);
    }



    /**
     * Redéfinition pour tester le type d'objet fourni.
     *
     * @param $object Intervenant
     */
    public function bind($object, $flags = FormInterface::VALUES_NORMALIZED)
    {
        if ($object->estPermanent()) {
            throw new LogicException("Ce formulaire ne peut être bindé qu'à un vacataire.");
        }

        return parent::bind($object, $flags);
    }



    /**
     * Redéfinition pour forcer le témoin "premier recrutement" en cas d'absence
     * de l'élément de formulaire.
     */
    public function setData($data)
    {
        if (!$this->dossierFieldset->has('premierRecrutement')) {
            $data->dossier['premierRecrutement'] = '0';
        }

        return parent::setData($data);
    }
}