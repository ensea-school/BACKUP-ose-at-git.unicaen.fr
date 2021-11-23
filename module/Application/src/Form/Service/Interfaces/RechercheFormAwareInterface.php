<?php

namespace Application\Form\Service\Interfaces;

use Application\Form\Service\RechercheForm;
use RuntimeException;

/**
 * Description of RechercheFormAwareInterface
 *
 * @author UnicaenCode
 */
interface RechercheFormAwareInterface
{
    /**
     * @param RechercheForm $formServiceRecherche
     * @return self
     */
    public function setFormServiceRecherche( RechercheForm $formServiceRecherche );



    /**
     * @return RechercheFormAwareInterface
     * @throws RuntimeException
     */
    public function getFormServiceRecherche();
}