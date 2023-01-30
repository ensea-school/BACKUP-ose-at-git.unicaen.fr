<?php

namespace Application\Form\Tag;

use Application\Entity\Db\Tag;
use Application\Form\AbstractForm;

/**
 * Description of TagSaisieForm
 *
 * @author Antony LE COURTES <antony.lecourtes at unicaen.fr>
 */
class TagSaisieForm extends AbstractForm
{

    public function init()
    {
        $this->spec(Tag::class);
        $this->build();

        $this->get('libelleCourt')->setLabel('Libellé court');
        $this->get('libelleLong')->setLabel('Libellé long');

        $this->addSecurity();
        $this->addSubmit();

        return $this;
    }
}