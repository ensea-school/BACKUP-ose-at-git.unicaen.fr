<?php

namespace Intervenant\Entity\Db;


use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

/**
 * Description of Statut
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
class TypeNote implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $libelle;

    /**
     * @var string
     */
    private $code;



    public function __construct()
    {

    }

}
