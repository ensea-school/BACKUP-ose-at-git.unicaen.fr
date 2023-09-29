<?php

namespace Service\Form;

use Application\Form\AbstractForm;
use Application\Service\Traits\AnneeServiceAwareTrait;
use Service\Entity\Db\Tag;

/**
 * Description of TagSaisieForm
 *
 * @author Antony LE COURTES <antony.lecourtes at unicaen.fr>
 */
class TagSaisieForm extends AbstractForm
{
    use AnneeServiceAwareTrait;

    public function init ()
    {
        $this->spec(Tag::class);


        $this->build();

        $this->setLabels([
            'libelleCourt' => 'Libellé court',
            'libelleLong'  => 'Libellé long',
            'dateDebut'    => 'A partir de',
            'dateFin'      => 'jusqu\'à',

        ]);

        $this->addSecurity();
        $this->addSubmit();

        return $this;
    }
}