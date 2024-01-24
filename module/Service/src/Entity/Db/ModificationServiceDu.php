<?php

namespace Service\Entity\Db;

use Intervenant\Entity\Db\IntervenantAwareTrait;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenApp\Util;

class ModificationServiceDu implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;
    use IntervenantAwareTrait;

    private ?int                        $id           = null;

    private ?MotifModificationServiceDu $motif        = null;

    private ?float                      $heures       = null;

    private ?string                     $commentaires = null;



    public function getId(): ?int
    {
        return $this->id;
    }



    public function getMotif(): ?\Service\Entity\Db\MotifModificationServiceDu
    {
        return $this->motif;
    }



    public function setMotif(?\Service\Entity\Db\MotifModificationServiceDu $motif): ModificationServiceDu
    {
        $this->motif = $motif;

        return $this;
    }



    public function getHeures(): ?float
    {
        return $this->heures;
    }



    public function setHeures(?float $heures): ModificationServiceDu
    {
        $this->heures = $heures;

        return $this;
    }



    public function getCommentaires(): ?string
    {
        return $this->commentaires;
    }



    public function setCommentaires(?string $commentaires): ModificationServiceDu
    {
        $this->commentaires = $commentaires;

        return $this;
    }



    public function __toString(): string
    {
        $heures = Util::formattedFloat($this->getHeures(), \NumberFormatter::DECIMAL, -1);

        return sprintf("%s (%sh)%s",
            $this->getMotif(),
            $heures,
            ($com = $this->getCommentaires()) ? " - $com" : null);
    }

}
