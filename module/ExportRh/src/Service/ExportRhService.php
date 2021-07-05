<?php

namespace ExportRh\Service;

use Application\Service\AbstractService;
use Application\Service\Traits\IntervenantServiceAwareTrait;
use Application\Service\Traits\ParametresServiceAwareTrait;
use ExportRh\Entity\IntervenantRHExportParams;

/**
 * Description of FonctionReferentiel
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ExportRhService extends AbstractService
{
    use ParametresServiceAwareTrait;
    use IntervenantServiceAwareTrait;

    /**
     * @var IntervenantRHExportParams
     */
    private   $intervenantEportParams;

    protected $connecteur;



    public function __construct($connecteur)
    {
        $this->connecteur = $connecteur;
    }



    public function getListIntervenantRh($nomUsuel, $prenom, $insee)
    {

        $listIntervenantRh  = $this->connecteur->rechercherIntervenantRH($nomUsuel, $prenom, $insee);
        $intervenantService = $this->getServiceIntervenant();
        if (!empty($listIntervenantRh)) {
            foreach ($listIntervenantRh as $key => $intervenantRh) {
                $intervenant = $intervenantService->getByCodeRh($intervenantRh->getCodeRh());
                if ($intervenant) {
                    $intervenantRh->setIntervenant($intervenant);
                    $listIntervenantRh[$key] = $intervenantRh;
                }
            }
        }

        return $listIntervenantRh;
    }



    public function getIntervenantRh($intervenant)
    {
        $intervenantRh = $this->connecteur->trouverIntervenantRh($intervenant);

        return $intervenantRh;
    }



    public function getIntervenantRHExportParams(): IntervenantRHExportParams
    {
        if (!$this->intervenantEportParams) {
            $this->intervenantEportParams = new IntervenantRHExportParams();
            $iep                          = $this->getServiceParametres()->get('export_rh_intervenant');
            if ($iep) {
                $this->intervenantEportParams->fromArray((array)json_decode($iep));
            }
        }

        return $this->intervenantEportParams;
    }



    public function getListeUO()
    {
        return $this->connecteur->recupererListeUO();
    }



    public function getListePositions()
    {
        return $this->connecteur->recupererListePositions();
    }



    public function getListeEmplois()
    {
        return $this->connecteur->recupererListeEmplois();
    }



    public function getIntervenantRHParamsDescription(): array
    {
        $desc = [
            'Codifications'         => [
                'code'            => 'Code',
                'codeRh'          => 'Code RH',
                'utilisateurCode' => 'Code Utilisateur',
                'sourceCode'      => 'Code Source',
            ],
            'Validité'              => [
                'validiteDebut' => 'Début de validité',
                'validiteFin'   => 'Fin de validité',
            ],
            'Identité'              => [
                'civilite'             => 'Civilite',
                'nomUsuel'             => 'Nom usuel',
                'prenom'               => 'Prénom',
                'dateNaissance'        => 'Date de naissance',
                'nomPatronymique'      => 'Nom patronymique',
                'communeNaissance'     => 'Commune de naissance',
                'paysNaissance'        => 'Pays de naissance',
                'departementNaissance' => 'Département de naissance',
                'paysNationalite'      => 'Nationalité',
            ],
            'Situation'             => [
                'structure'  => 'Composante',
                'statut'     => 'Statut',
                'grade'      => 'Grade',
                'discipline' => 'Discipline',
            ],
            'Coordonnées'           => [
                'telPro'     => 'Téléphone professionnel',
                'telPerso'   => 'Téléphone personnel',
                'emailPro'   => 'Email professionnel',
                'emailPerso' => 'Email personnel',
            ],
            'Adresse'               => [
                'adressePrecisions'  => 'Précisions',
                'adresseNumero'      => 'Numéro',
                'adresseNumeroCompl' => 'Complément au numéro',
                'adresseVoirie'      => 'Voirie',
                'adresseVoie'        => 'Voie',
                'adresseLieuDit'     => 'Lieu dit',
                'adresseCodePostal'  => 'Code postal',
                'adresseCommune'     => 'Commune',
                'adressePays'        => 'Pays',
            ],
            'INSEE'                 => [
                'numeroInsee'           => 'Numéro INSEE',
                'numeroInseeProvisoire' => 'Numéro INSEE provisoire',
            ],
            'Coordonnées bancaires' => [
                'IBAN'        => 'IBAN',
                'BIC'         => 'BIC',
                'ribHorsSepa' => 'RIB hors SEPA',
            ],
            'Employeur'             => [
                'employeur' => 'Employeur',
            ],
            'Autres données'        => [
                'autre1' => 'Autre 1',
                'autre2' => 'Autre 2',
                'autre3' => 'Autre 3',
                'autre4' => 'Autre 4',
                'autre5' => 'Autre 5',
            ],
        ];

        $sql = "SELECT id, libelle FROM dossier_champ_autre";
        $dca = $this->getEntityManager()->getConnection()->fetchAll($sql);
        foreach ($dca as $ca) {
            $desc['Autres données']['autre' . $ca['ID']] = $ca['LIBELLE'];
        }

        return $desc;
    }



    public function saveIntervenantExportParams(): self
    {
        $iep = json_encode($this->getIntervenantExportParams()->toArray());
        $this->getServiceParametres()->set('export_rh_intervenant', $iep);

        return $this;
    }

}