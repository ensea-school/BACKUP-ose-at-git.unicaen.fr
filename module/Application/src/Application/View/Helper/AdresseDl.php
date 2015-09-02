<?php

namespace Application\View\Helper;

use Application\Entity\Db\AdresseIntervenant;

/**
 * Description of AdresseDl
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class AdresseDl extends AbstractDl
{
    /**
     * @var AdresseIntervenant
     */
    protected $entity;

    /**
     * @var bool
     */
    protected $condensed;

    /**
     *
     * @param mixed $entity
     * @param bool $horizontal
     * @param bool $condensed
     * @return self
     */
    public function __invoke($entity = null, $horizontal = false, $condensed = false)
    {
        parent::__invoke($entity, $horizontal);

        $this->condensed = $condensed;

        return $this;
    }

    /**
     *
     *
     * @return string Code HTML
     */
    public function render()
    {
        if (!$this->entity) {
            return '';
        }

        $tplDtdd = $this->getTemplateDtDd();
        $html    = '';
        $dtdds   = [];

        if ($this->condensed) {
            $complement = implode(', ', array_filter([$this->entity->getMentionComplementaire(), $this->entity->getBatiment()]));
            $dtdds[] = sprintf($tplDtdd,
                sprintf("Adresse %s:", $this->entity->getPrincipale() ? "principale" : null),
                sprintf("%s %s%s", $this->entity->getNoVoie(), $this->entity->getNomVoie(), $complement ? " ($complement)" : null)
            );
        }
        else {
            $dtdds[] = sprintf($tplDtdd,
                "N° voie :",
                $this->entity->getNoVoie()
            );

            $dtdds[] = sprintf($tplDtdd,
                "Nom voie :",
                $this->entity->getNomVoie()
            );

            $dtdds[] = sprintf($tplDtdd,
                "Mention complementaire :",
                $this->entity->getMentionComplementaire()
            );

            $dtdds[] = sprintf($tplDtdd,
                "Bâtiment :",
                $this->entity->getBatiment()
            );
        }

        if ($this->entity->getLocalite()) {
            $dtdds[] = sprintf($tplDtdd,
                "Localité :",
                $this->entity->getLocalite()
            );
        }

        if ($this->condensed) {
            $dtdds[] = sprintf($tplDtdd,
                "Ville :",
                sprintf("%s %s (%s)", $this->entity->getCodePostal(), $this->entity->getVille(), $this->entity->getPaysLibelle())
            );
        }
        else {
            $dtdds[] = sprintf($tplDtdd,
                "Code postal :",
                $this->entity->getCodePostal()
            );

            $dtdds[] = sprintf($tplDtdd,
                "Ville :",
                $this->entity->getVille()
            );

            $dtdds[] = sprintf($tplDtdd,
                "Pays :",
                sprintf("%s (%s)", $this->entity->getPaysLibelle(), $this->entity->getPaysCodeInsee())
            );

            $dtdds[] = sprintf($tplDtdd,
                "Historique :",
                $this->getView()->historique($this->entity)
            );
        }

        $html .= sprintf($this->getTemplateDl('adresse adresse-details'), implode(PHP_EOL, $dtdds)) . PHP_EOL;

        return $html;
    }
}