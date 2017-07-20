<?php

namespace Application\Service;

use Application\Entity\Db\Intervenant as IntervenantEntity;
use Application\Entity\Db\Annee as AnneeEntity;


/**
 * Description of TableauBordService
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class TableauBordService extends AbstractService
{
    const PKG_AGREMENT                = 'OSE_AGREMENT';
    const PKG_CLOTURE_REALISE         = 'OSE_CLOTURE_REALISE';
    const PKG_CONTRAT                 = 'OSE_CONTRAT';
    const PKG_DOSSIER                 = 'OSE_DOSSIER';
    const PKG_FORMULE                 = 'OSE_FORMULE';
    const PKG_PAIEMENT                = 'OSE_PAIEMENT';
    const PKG_PIECE_JOINTE            = 'OSE_PIECE_JOINTE';
    const PKG_PIECE_JOINTE_DEMANDE    = 'OSE_PIECE_JOINTE_DEMANDE';
    const PKG_PIECE_JOINTE_FOURNIE    = 'OSE_PIECE_JOINTE_FOURNIE';
    const PKG_SERVICE                 = 'OSE_SERVICE';
    const PKG_SERVICE_REFERENTIEL     = 'OSE_SERVICE_REFERENTIEL';
    const PKG_SERVICE_SAISIE          = 'OSE_SERVICE_SAISIE';
    const PKG_VALIDATION_ENSEIGNEMENT = 'OSE_VALIDATION_ENSEIGNEMENT';
    const PKG_VALIDATION_REFERENTIEL  = 'OSE_VALIDATION_REFERENTIEL';
    const PKG_WORKFLOW                = 'OSE_WORKFLOW';

    const PACKAGES = [
        self::PKG_AGREMENT,
        self::PKG_CLOTURE_REALISE,
        self::PKG_CONTRAT,
        self::PKG_DOSSIER,
        self::PKG_FORMULE,
        self::PKG_PAIEMENT,
        self::PKG_PIECE_JOINTE,
        self::PKG_PIECE_JOINTE_DEMANDE,
        self::PKG_PIECE_JOINTE_FOURNIE,
        self::PKG_SERVICE,
        self::PKG_SERVICE_REFERENTIEL,
        self::PKG_SERVICE_SAISIE,
        self::PKG_VALIDATION_ENSEIGNEMENT,
        self::PKG_VALIDATION_REFERENTIEL,
        self::PKG_WORKFLOW,
    ];



    public function calculer($packageName, IntervenantEntity $intervenant, $withDeps = false, $withSucs = true)
    {
        if (!in_array($packageName, self::PACKAGES)) {
            throw new \Exception('Package non valide');
        }

        $deps = $withDeps ? 'true' : 'false';
        $sucs = $withSucs ? 'true' : 'false';
        $this->exec("BEGIN OSE_EVENT.CALCULER(:package, :intervenant, $deps, $sucs); END;", [
            'package'     => $packageName,
            'intervenant' => $intervenant->getId(),
        ]);

        return $this;
    }



    /**
     * @todo ATTENTION : la hiérarchie des packages n'est pas prise en compte!!!
     *
     * @param IntervenantEntity $intervenant
     *
     * @return $this
     */
    public function calculerTous(IntervenantEntity $intervenant)
    {
        foreach (self::PACKAGES as $package) {
            $this->calculer($package, $intervenant, false, false);
        }

        return $this;
    }



    public function calculerTout($packageName, AnneeEntity $annee = null, $withDeps = false, $withSucs = true)
    {
        if (!in_array($packageName, self::PACKAGES)) {
            throw new \Exception('Package non valide');
        }

        $deps = $withDeps ? 'true' : 'false';
        $sucs = $withSucs ? 'true' : 'false';
        if ($annee) {
            $this->exec("BEGIN OSE_EVENT.CALCULER_TOUT(:package, :annee, $deps, $sucs); END;", [
                'package' => $packageName,
                'annee'   => $annee->getId(),
            ]);
        } else {
            $this->exec("BEGIN OSE_EVENT.CALCULER_TOUT(:package, NULL, $deps, $sucs); END;", [
                'package' => $packageName,
            ]);
        }

        return $this;
    }



    public function activer()
    {
        $this->exec("BEGIN OSE_EVENT.SET_ACTIF(TRUE); END;");

        return $this;
    }



    public function desactiver()
    {
        $this->exec("BEGIN OSE_EVENT.SET_ACTIF(FALSE); END;");

        return $this;
    }



    /**
     * @param       $sql
     * @param array $params
     *
     * @return \Doctrine\DBAL\Driver\Statement
     */
    protected function exec($sql, array $params = [])
    {
        return $this->getEntityManager()->getConnection()->executeQuery($sql, $params);
    }
}