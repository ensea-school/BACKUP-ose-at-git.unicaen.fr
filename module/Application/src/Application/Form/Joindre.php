<?php

namespace Application\Form;

use Zend\Form\Element\Csrf;
use Zend\Form\Element\Hidden;
use Zend\Form\Form;
use Zend\InputFilter\FileInput;
use Zend\InputFilter\InputFilter;

/**
 * Description of Ajouter
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class Joindre extends Form
{
    /**
     * 
     */
    public function __construct()
    {
        parent::__construct();
        
        $this
                ->setAttribute('id', "upload-form")
                ->addElements()
                ->addInputFilter();
    }
    
    private function addElements()
    {
        /**
         * Id intervenant
         */
        $this->add(new Hidden('id'));
        
        /**
         * Pièces justificatives
         */
        $this->add(array(
            'name' => 'files',
            'type' => 'File',
            'options' => array(
                'label' => "Dépôt de fichier(s) :"
            ),
            'attributes' => array(
                'id' => 'files',
                'multiple' => true,
            ),
        ));
        
        /**
         * Csrf
         */
        $this->add(new Csrf('security'));
        
        /**
         * Submit
         */
        $this->add(array(
            'name' => 'submit',
            'type'  => 'Submit',
            'attributes' => array(
                'value' => "Enregistrer",
            ),
        ));
        
        return $this;
    }

    public function addInputFilter()
    {
        $inputFilter = new InputFilter();

        // File Input
        $fileInput = new FileInput('files');
        $fileInput->setRequired(true);

        // You only need to define validators and filters
        // as if only one file was being uploaded. All files
        // will be run through the same validators and filters
        // automatically.
        $fileInput->getValidatorChain()
            ->attachByName('filesize',      array('max' => 204800))
            ->attachByName('filemimetype',  array('mimeType' => 'image/png,image/x-png'))
            /*->attachByName('fileimagesize', array('maxWidth' => 100, 'maxHeight' => 100))*/;

        // All files will be renamed, i.e.:
        //   ./data/tmpuploads/avatar_4b3403665fea6.png,
        //   ./data/tmpuploads/avatar_5c45147660fb7.png
        $fileInput->getFilterChain()->attachByName(
            'filerenameupload',
            array(
                'target'    => './data/tmpuploads/pj.png',
                'randomize' => true,
            )
        );
        $inputFilter->add($fileInput);

        $this->setInputFilter($inputFilter);
        
        return $this;
    }
}