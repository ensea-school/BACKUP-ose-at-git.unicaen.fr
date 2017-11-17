<?php

namespace Application\Service;

use Application\Service\Traits\ElementPedagogiqueAwareTrait;
use Application\Service\Traits\EtapeAwareTrait;
use Application\Service\Traits\IntervenantAwareTrait;
use Application\Service\Traits\NiveauEtapeAwareTrait;
use Application\Service\Traits\StructureServiceAwareTrait;
use UnicaenApp\Traits\SessionContainerTrait;
use Application\Entity\Db\Intervenant as EntityIntervenant;
use Application\Entity\Db\Structure;
use Application\Entity\Db\Etape as EntityEtape;
use Application\Entity\NiveauEtape as EntityNiveauEtape;
use Application\Entity\Db\ElementPedagogique as EntityElementPedagogique;


/**
 * Classe regroupant des données locales (filtres, etc.)
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class LocalContext extends AbstractService
{
    use IntervenantAwareTrait;
    use StructureServiceAwareTrait;
    use EtapeAwareTrait;
    use ElementPedagogiqueAwareTrait;
    use NiveauEtapeAwareTrait;
    use SessionContainerTrait;

    /**
     * Intervenant
     *
     * @var EntityIntervenant
     */
    protected $intervenant;

    /**
     * @var Structure
     */
    protected $structure;

    /**
     * @var EntityNiveauEtape
     */
    protected $niveau;

    /**
     * @var EntityEtape
     */
    protected $etape;

    /**
     * @var EntityElementPedagogique
     */
    protected $elementPedagogique;



    /**
     * @return EntityIntervenant
     */
    public function getIntervenant()
    {
        if (empty($this->intervenant)) {
            $this->intervenant = $this->getSessionContainer()->intervenant;
            if ($this->intervenant && !$this->intervenant instanceof EntityIntervenant) {
                $this->intervenant = $this->getServiceIntervenant()->get($this->intervenant);
            }
        }

        return $this->intervenant;
    }



    /**
     * @return Structure
     */
    public function getStructure()
    {
        if (empty($this->structure)) {
            $this->structure = $this->getSessionContainer()->structure;
            if ($this->structure && !$this->structure instanceof Structure) {
                $this->structure = $this->getServiceStructure()->get($this->structure);
            }
        }

        return $this->structure;
    }



    /**
     * @return NiveauEtape
     */
    public function getNiveau()
    {
        if (empty($this->niveau)) {
            $this->niveau = $this->getSessionContainer()->niveau;
            if ($this->niveau && !$this->niveau instanceof EntityNiveauEtape) {
                $this->niveau = $this->getServiceNiveauEtape()->get($this->niveau);
            }
        }

        return $this->niveau;
    }



    /**
     * @return EntityEtape
     */
    public function getEtape()
    {
        if (empty($this->etape)) {
            $this->etape = $this->getSessionContainer()->etape;
            if ($this->etape && !$this->etape instanceof EntityEtape) {
                $this->etape = $this->getServiceEtape()->get($this->etape);
            }
        }

        return $this->etape;
    }



    /**
     * @return EntityElementPedagogique
     */
    public function getElementPedagogique()
    {
        if (empty($this->elementPedagogique)) {
            $this->elementPedagogique = $this->getSessionContainer()->elementPedagogique;
            if ($this->elementPedagogique && !$this->elementPedagogique instanceof EntityElementPedagogique) {
                $this->elementPedagogique = $this->getServiceElementPedagogique()->get($this->elementPedagogique);
            }
        }

        return $this->elementPedagogique;
    }



    /**
     *
     * @param EntityIntervenant $intervenant
     *
     * @return self
     */
    public function setIntervenant(EntityIntervenant $intervenant = null)
    {
        $this->intervenant                        = $intervenant;
        $this->getSessionContainer()->intervenant = $intervenant ? $intervenant->getId() : null;

        return $this;
    }



    /**
     *
     * @param Structure $structure
     *
     * @return self
     */
    public function setStructure(Structure $structure = null)
    {
        $this->structure                        = $structure;
        $this->getSessionContainer()->structure = $structure ? $structure->getId() : null;

        return $this;
    }



    /**
     *
     * @param EntityNiveauEtape $niveau
     *
     * @return self
     */
    public function setNiveau(EntityNiveauEtape $niveau = null)
    {
        $this->niveau                        = $niveau;
        $this->getSessionContainer()->niveau = $niveau ? $niveau->getId() : null;

        return $this;
    }



    /**
     *
     * @param EntityEtape $etape
     *
     * @return self
     */
    public function setEtape(EntityEtape $etape = null)
    {
        $this->etape                        = $etape;
        $this->getSessionContainer()->etape = $etape ? $etape->getId() : null;

        return $this;
    }



    /**
     *
     * @param EntityElementPedagogique $elementPedagogique
     *
     * @return self
     */
    public function setElementPedagogique(EntityElementPedagogique $elementPedagogique = null)
    {
        $this->elementPedagogique                        = $elementPedagogique;
        $this->getSessionContainer()->elementPedagogique = $elementPedagogique ? $elementPedagogique->getId() : null;

        return $this;
    }



    public function fromArray(array $context = [])
    {
        $this->setStructure(isset($context['structureEns']) ? $context['structureEns'] : null);

        return parent::fromArray($context);
    }



    public function debug()
    {
        var_dump("intervenant = " . $this->getIntervenant());
        var_dump("structure = " . $this->getStructure());
        var_dump("niveau = " . $this->getNiveau());
        var_dump("etape = " . $this->getEtape());
        var_dump("élément pédagogique = " . $this->getElementPedagogique());
    }
}