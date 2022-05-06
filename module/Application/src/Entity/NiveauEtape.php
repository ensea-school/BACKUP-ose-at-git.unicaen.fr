<?php

namespace Application\Entity;

use Application\Entity\Db\Etape;

/**
 * Description of NiveauEtape
 *
 */
class NiveauEtape
{
    /**
     * @var Etape
     */
    protected $etape;

    /**
     * @var string
     */
    protected $niv;

    /**
     * @var string
     */
    protected $lib;

    protected $pertinence;

    /**
     *
     * @param \Application\Entity\Db\Etape $etape
     */
    static public function getInstanceFromEtape(Etape $etape)
    {
        $i = new self();
        $i->setEtape($etape);

        return $i;
    }

    /**
     *
     * @param string $lib
     * @param string $niv
     * @return NiveauEtape
     */
    static public function getInstance($lib, $niv = null)
    {
        $i = new self();
        $i->setLib($lib)->setNiv($niv);

        return $i;
    }

    /**
     *
     * @param \Traversable $etapes
     * @return NiveauEtape[]
     */
    static public function getInstancesFromEtapes($etapes)
    {
        $instances = [];

        foreach ($etapes as $e) {
            $n = static::getInstanceFromEtape($e);
            $instances[$n->__toString()] = $n;
        }

        return $instances;
    }

    public function __toString()
    {
        return $this->getLib() . $this->getNiv();
    }

    public function getId()
    {
        return sprintf("%s-%s", $this->getLib(), $this->getNiv());
    }

    public function getEtape()
    {
        return $this->etape;
    }

    public function getNiv()
    {
        return $this->niv;
    }

    public function getLib()
    {
        return $this->lib;
    }

    public function getPertinence()
    {
        return $this->pertinence;
    }

    public function setNiv($niv)
    {
        $this->niv = $niv;
        return $this;
    }

    public function setLib($lib)
    {
        $this->lib = $lib;
        return $this;
    }

    public function setPertinence($pertinence)
    {
        $this->pertinence = $pertinence;
        return $this;
    }

    public function setEtape(Etape $etape)
    {
        $this->etape = $etape;
        $this->niv = $this->etape->getNiveau();
        $this->lib = $this->etape->getTypeFormation()->getGroupe()->getLibelleCourt();
        $this->pertinence = $this->etape->getTypeFormation()->getGroupe()->getPertinenceNiveau();
        return $this;
    }
}