<?php

namespace Application\Service\Initializer;

/**
 * Transmet à une instance le service Agrement, ssi sa classe implémente l'interface qui va bien.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 * @see AgrementServiceAwareInterface
 */
class AgrementServiceAwareInitializer extends AbstractEntityServiceAwareInitializer
{
    protected $entityClassName = 'Agrement';
}