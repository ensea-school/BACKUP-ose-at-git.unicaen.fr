<?php

namespace Application\Form;

use Application\Service\AbstractEntityService;
use Application\Traits\FormFieldsetTrait;
use Laminas\Form\Element\Csrf;
use Laminas\Form\Form;
use Laminas\Http\Request;
use Laminas\InputFilter\InputFilterProviderInterface;
use Laminas\Stdlib\ArrayUtils;


abstract class  AbstractForm extends Form implements InputFilterProviderInterface
{
    use FormFieldsetTrait;


    public function addSubmit(string $value = 'Enregistrer'): self
    {
        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value' => $value,
                'class' => 'btn btn-primary',
            ],
        ]);

        return $this;
    }



    public function addSecurity(): self
    {
        $this->add(new Csrf('security'));

        return $this;
    }



    /**
     * Exécute la sauvegarde d'un formulaire à partir des données Request
     *
     * Dans $saveFnc, l'entité (dont les données ont été mises à jour) est transmise
     *
     * Retourne true si tout s'est bien passé, false sinon.
     * Le message d'erreur pourra être récupéré via le FlashMessenger ou bien via getLastException() pour la traiter ensuite
     *
     * @param                                $entity
     * @param Request                        $request
     * @param AbstractEntityService|function $saveFnc
     * @param string                         $successMessage
     *
     * @return bool
     */
    public function bindRequestSave($entity, Request $request, $saveFnc, string $successMessage = 'Enregistrement effectué'): bool
    {
        $this->bind($entity);
        if ($request->isPost()) {
            $data = ArrayUtils::merge(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $this->setData($data);
            if ($this->isValid()) {
                if ($saveFnc instanceof AbstractEntityService) {
                    try {
                        $saveFnc->save($entity);
                        $this->getControllerPluginFlashMessenger()->addSuccessMessage($successMessage);
                    } catch (\Exception $e) {
                        $this->getControllerPluginFlashMessenger()->addErrorMessage($this->translate($e->getMessage()));
                    }
                } elseif ($saveFnc instanceof \Closure) {
                    try {
                        $saveFnc($entity);
                    } catch (\Exception $e) {
                        $this->getControllerPluginFlashMessenger()->addErrorMessage($e->getMessage());

                        return false;
                    }
                }
            } else {
                $messages = $this->getMessages();
                foreach ($messages as $element => $msgs) {
                    foreach ($msgs as $msg) {
                        $this->getControllerPluginFlashMessenger()->addErrorMessage($msg . ' [' . $element . ']');
                    }
                }
            }
        }

        return true;
    }



    /**
     * Exécute la sauvegarde d'un formulaire à partir des données Request
     *
     * Dans $saveFnc, les données du formulaire sont transmises
     *
     * Retourne true si tout s'est bien passé, false sinon.
     * Le message d'erreur pourra être récupéré via le FlashMessenger ou bien via getLastException() pour la traiter ensuite
     *
     * @param Request $request
     * @param         $saveFnc
     *
     * @return bool
     */
    public function requestSave(Request $request, $saveFnc): bool
    {
        if ($request->isPost()) {
            $this->setData($request->getPost());
            if ($this->isValid()) {
                try {
                    $saveFnc($this->getData());
                } catch (\Exception $e) {
                    $this->getControllerPluginFlashMessenger()->addErrorMessage($e->getMessage());

                    return false;
                }
            } else {
                $messages = $this->getMessages();
                foreach ($messages as $element => $msgs) {
                    foreach ($msgs as $msg) {
                        $this->getControllerPluginFlashMessenger()->addErrorMessage($msg . ' [' . $element . ']');
                    }
                }
            }
        }

        return true;
    }

}