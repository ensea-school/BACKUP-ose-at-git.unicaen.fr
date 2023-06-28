<?php

namespace Application\Form\Tag;

use Psr\Container\ContainerInterface;


/**
 * Description of TagSaisieFormFactory
 *
 * @author Antony Le Courtes  <antony.lecourtes at unicaen.fr>
 */
class TagSaisieFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return TagSaisieForm
     */
    public function __invoke (ContainerInterface $container, $requestedName, $options = null): TagSaisieForm
    {
        $form = new TagSaisieForm();

        /* Injectez vos dépendances ICI */

        return $form;
    }
}