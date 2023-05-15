<?php

namespace Paiement\Form\Modulateur;

use Psr\Container\ContainerInterface;



/**
 * Description of TypeModulateurStructureSaisieFormFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class TypeModulateurStructureSaisieFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return TypeModulateurStructureSaisieForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): TypeModulateurStructureSaisieForm
    {
        $form = new TypeModulateurStructureSaisieForm;

        /* Injectez vos dépendances ICI */

        return $form;
    }
}