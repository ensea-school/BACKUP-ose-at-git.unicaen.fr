<?php
namespace Application\Form\CustomElements;

use Application\Proxy\PaysProxy;
use DoctrineORMModule\Form\Element\EntitySelect;

/**
 * Description of PaysSelect
 *
 * Select personnalisé pour l'entité Pays
 *
 * @author LE COURTES Antony <antony.lecourtes at unicaen.fr>
 */

class PaysSelect extends EntitySelect
{

    public function __construct($name = null, $options = [])
    {
        parent::__construct($name, $options);

        $this->setEmptyOption('Selectionnez un pays....');

        $this->proxy = new PaysProxy();
    }

    public function setFranceDefault()
    {
        foreach ($this->getProxy()->getObjects() as $p) {
            if ($p->isFrance()) {
                $this->setValue($p->getId());
                break;
            }
        }
    }

}
