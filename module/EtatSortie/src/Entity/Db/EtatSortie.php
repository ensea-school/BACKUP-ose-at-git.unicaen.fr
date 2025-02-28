<?php


namespace EtatSortie\Entity\Db;

use Laminas\Json\Json;
use UnicaenSignature\Entity\Db\SignatureFlow;

/**
 * EtatSortie
 */
#[\AllowDynamicProperties]
class EtatSortie
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $libelle;

    /**
     * @var string
     */
    protected $cle;

    /**
     * @var string
     */
    protected $csvParams;

    /**
     * @var string
     */
    protected $pdfTraitement;

    /**
     * @var string
     */
    protected $csvTraitement;

    /**
     * @var bool
     */
    protected $autoBreak = true;

    /**
     * @var string
     */
    protected $fichier = null;

    /**
     * @var string
     */
    protected $requete;

    /**
     * @var string
     */
    protected $bloc1Nom;

    /**
     * @var string
     */
    protected $bloc1Zone;

    /**
     * @var string
     */
    protected $bloc1Requete;

    /**
     * @var string
     */
    protected $bloc2Nom;

    /**
     * @var string
     */
    protected $bloc2Zone;

    /**
     * @var string
     */
    protected $bloc2Requete;

    /**
     * @var string
     */
    protected $bloc3Nom;

    /**
     * @var string
     */
    protected $bloc3Zone;

    /**
     * @var string
     */
    protected $bloc3Requete;

    /**
     * @var string
     */
    protected $bloc4Nom;

    /**
     * @var string
     */
    protected $bloc4Zone;

    /**
     * @var string
     */
    protected $bloc4Requete;

    /**
     * @var string
     */
    protected $bloc5Nom;

    /**
     * @var string
     */
    protected $bloc5Zone;

    /**
     * @var string
     */
    protected $bloc5Requete;

    /**
     * @var string
     */
    protected $bloc6Nom;

    /**
     * @var string
     */
    protected $bloc6Zone;

    /**
     * @var string
     */
    protected $bloc6Requete;

    /**
     * @var string
     */
    protected $bloc7Nom;

    /**
     * @var string
     */
    protected $bloc7Zone;

    /**
     * @var string
     */
    protected $bloc7Requete;

    /**
     * @var string
     */
    protected $bloc8Nom;

    /**
     * @var string
     */
    protected $bloc8Zone;

    /**
     * @var string
     */
    protected $bloc8Requete;

    /**
     * @var string
     */
    protected $bloc9Nom;

    /**
     * @var string
     */
    protected $bloc9Zone;

    /**
     * @var string
     */
    protected $bloc9Requete;

    /**
     * @var string
     */
    protected $bloc10Nom;

    /**
     * @var string
     */
    protected $bloc10Zone;

    /**
     * @var string
     */
    protected $bloc10Requete;

    protected bool $signatureActivation = false;

    protected ?SignatureFlow $signatureCircuit = null;



    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }



    /**
     * @param int $id
     *
     * @return EtatSortie
     */
    public function setId($id): EtatSortie
    {
        $this->id = $id;

        return $this;
    }



    /**
     * @return string
     */
    public function getCle()
    {
        return $this->cle;
    }



    /**
     * @param string $cle
     *
     * @return EtatSortie
     */
    public function setCle($cle): EtatSortie
    {
        $this->cle = $cle;

        return $this;
    }



    /**
     * @return string
     */
    public function getCsvParams()
    {
        return $this->csvParams;
    }



    /**
     * @param string $csvParams
     *
     * @return EtatSortie
     */
    public function setCsvParams($csvParams): EtatSortie
    {
        $this->csvParams = $csvParams;

        return $this;
    }



    /**
     * @return array
     */
    public function getCsvParamsArray(): array
    {
        if ($this->csvParams) {
            return Json::decode($this->csvParams, Json::TYPE_ARRAY);
        } else {
            return [];
        }
    }



    /**
     * @return string
     */
    public function getPdfTraitement()
    {
        $fichierGenerique = getcwd() . '/' . $this->pdfTraitement;
        if ($this->pdfTraitement && strlen($fichierGenerique) < 512 && file_exists($fichierGenerique)) {
            $this->pdfTraitement = substr(file_get_contents($fichierGenerique), 5);
        }

        return $this->pdfTraitement;
    }



    /**
     * @param string $pdfTraitement
     *
     * @return EtatSortie
     */
    public function setPdfTraitement($pdfTraitement): EtatSortie
    {
        $this->pdfTraitement = $pdfTraitement;

        return $this;
    }



    /**
     * @return string
     */
    public function getCsvTraitement()
    {
        $fichierGenerique = getcwd() . '/' . $this->csvTraitement;
        if ($this->csvTraitement && strlen($fichierGenerique) < 512 && file_exists($fichierGenerique)) {
            $this->csvTraitement = substr(file_get_contents($fichierGenerique), 5);
        }

        return $this->csvTraitement;
    }



    /**
     * @param string $csvTraitement
     *
     * @return EtatSortie
     */
    public function setCsvTraitement($csvTraitement): EtatSortie
    {
        $this->csvTraitement = $csvTraitement;

        return $this;
    }



    /**
     * @return bool
     */
    public function isAutoBreak(): bool
    {
        return $this->autoBreak;
    }



    /**
     * @param bool $autoBreak
     *
     * @return EtatSortie
     */
    public function setAutoBreak(bool $autoBreak): EtatSortie
    {
        $this->autoBreak = $autoBreak;

        return $this;
    }



    /**
     * @return string
     */
    public function getFichier()
    {
        if (is_resource($this->fichier)) {
            return stream_get_contents($this->fichier, -1, 0);
        } elseif ($this->fichier) {
            return $this->fichier;
        } else {
            $fichierGenerique = getcwd() . '/data/Etats de sortie/' . $this->getCode() . '.odt';
            if (file_exists($fichierGenerique)) {
                return file_get_contents($fichierGenerique);
            }
        }

        return null;
    }



    /**
     * @param string $fichier
     *
     * @return EtatSortie
     */
    public function setFichier($fichier): EtatSortie
    {
        $this->fichier = $fichier;

        return $this;
    }



    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }



    /**
     * @param string $code
     *
     * @return EtatSortie
     */
    public function setCode($code): EtatSortie
    {
        $this->code = $code;

        return $this;
    }



    /**
     * @return bool
     */
    public function hasFichier(): bool
    {
        if (is_resource($this->fichier)) {
            return !empty(stream_get_contents($this->fichier, 1));
        } elseif (!empty($this->fichier)) {
            return true;
        } else {
            $fichierGenerique = getcwd() . '/data/Etats de sortie/' . $this->getCode() . '.odt';

            return file_exists($fichierGenerique);
        }
    }



    /**
     * @return string
     */
    public function getRequete()
    {
        return $this->requete;
    }



    /**
     * @param string $requete
     *
     * @return EtatSortie
     */
    public function setRequete($requete): EtatSortie
    {
        $this->requete = $requete;

        return $this;
    }



    public function getBlocs(): array
    {
        $blocs = [];

        for ($i = 1; $i <= 10; $i++) {
            $nomVar     = "bloc$i" . "Nom";
            $zoneVar    = "bloc$i" . "Zone";
            $requeteVar = "bloc$i" . "Requete";

            if ($this->{$nomVar} && $this->{$requeteVar}) {
                $blocs[$this->{$nomVar}] = [
                    'nom'     => $this->{$nomVar},
                    'numero'  => $i,
                    'requete' => $this->{$requeteVar},
                    'zone'    => $this->{$zoneVar},
                ];
            }
        }

        return $blocs;
    }



    public function setBlocs(array $blocs): EtatSortie
    {
        $i = 1;
        foreach ($blocs as $nom => $boptions) {
            $nomVar     = "bloc$i" . "Nom";
            $zoneVar    = "bloc$i" . "Zone";
            $requeteVar = "bloc$i" . "Requete";

            $this->{$nomVar}     = $boptions['nom'];
            $this->{$zoneVar}    = $boptions['zone'];
            $this->{$requeteVar} = $boptions['requete'];
            $i++;
        }
        for (null; $i <= 10; $i++) { // on vide le reste!!
            $nomVar     = "bloc$i" . "Nom";
            $zoneVar    = "bloc$i" . "Zone";
            $requeteVar = "bloc$i" . "Requete";

            $this->{$nomVar}     = null;
            $this->{$zoneVar}    = null;
            $this->{$requeteVar} = null;
        }

        return $this;
    }



    /**
     * Retourne la représentation littérale de cet objet.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getLibelle();
    }



    /**
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }



    /**
     * @param string $libelle
     *
     * @return EtatSortie
     */
    public function setLibelle($libelle): EtatSortie
    {
        $this->libelle = $libelle;

        return $this;
    }



    public function isSignatureActivation(): bool
    {
        return $this->signatureActivation;
    }



    public function setSignatureActivation(?bool $signatureActivation): EtatSortie
    {
        if ($signatureActivation == null) {
            $signatureActivation = false;
        }
        $this->signatureActivation = $signatureActivation;

        return $this;
    }



    public function getSignatureCircuit(): ?SignatureFlow
    {
        return $this->signatureCircuit;
    }



    public function setSignatureCircuit(?SignatureFlow $signatureCircuit): EtatSortie
    {
        $this->signatureCircuit = $signatureCircuit;

        return $this;
    }

}
