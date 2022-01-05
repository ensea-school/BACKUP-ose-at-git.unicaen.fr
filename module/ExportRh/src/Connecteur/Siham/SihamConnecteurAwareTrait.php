<?php

namespace ExportRh\Connecteur\Siham;


/**
 * Description of SihamConnecteurAwareTrait
 *
 * @author Antony LE COURTES <antony.lecourtes at unicaen.fr>
 */
trait SihamConnecteurAwareTrait
{
    /**
     * @var SihamConnecteur
     */
    protected $sihamConnecteur;



    /**
     * @param SihamConnecteur $exportRhService
     *
     * @return self
     */
    public function setSihamConnecteur(SihamConnecteur $sihamConnecteur)
    {
        $this->sihamConnecteur = $sihamConnecteur;

        return $this;
    }



    /**
     * @return SihamConnecteur
     */
    public function getSihamConnecteur(): SihamConnecteur
    {
        if (!$this->sihamConnecteur) {
            $this->sihamConnecteur = \Application::$container->get(SihamConnecteur::class);
        }

        return $this->sihamConnecteur;
    }
}
