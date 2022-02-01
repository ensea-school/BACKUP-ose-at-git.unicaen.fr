<?php

namespace Application\Form\Service\Interfaces;

use Application\Form\Service\RechercheForm;

/**
 * Description of RechercheFormAwareInterface
 *
 * @author UnicaenCode
 */
interface RechercheFormAwareInterface
{
    /**
     * @param RechercheForm|null $formServiceRecherche
     *
     * @return self
     */
    public function setFormServiceRecherche( RechercheForm $formServiceRecherche );



    public function getFormServiceRecherche(): ?RechercheForm;
}