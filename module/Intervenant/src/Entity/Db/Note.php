<?php

namespace Intervenant\Entity\Db;


use Application\Entity\Db\Intervenant;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

/**
 * Description of Statut
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
class Note implements HistoriqueAwareInterface
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
    private $contenu;

    /**
     * @var Intervenant
     */

    private $intervenant;

    /**
     * @var Type
     */
    private $type;



    public function __construct()
    {

    }

}
