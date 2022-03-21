<?php

namespace Intervenant\Form;

use Psr\Container\ContainerInterface;


/**
 * Description of Note
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
class NoteSaisieFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return NoteSaisieForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): NoteSaisieForm
    {
        $form = new NoteSaisieForm;

        /* Injectez vos dépendances ICI */

        return $form;
    }
}