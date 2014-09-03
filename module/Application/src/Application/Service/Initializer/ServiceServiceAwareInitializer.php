<?php

namespace Application\Service\Initializer;

/**
 * Transmet à une instance le service Service, ssi sa classe implémente l'interface qui va bien.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 * @see ServiceServiceAwareInterface
 */
class ServiceServiceAwareInitializer extends AbstractEntityServiceAwareInitializer
{
    protected $entityClassName = 'Service';
}