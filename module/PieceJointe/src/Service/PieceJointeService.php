<?php

namespace PieceJointe\Service;

use Application\Entity\Db\Fichier;
use Application\Entity\Db\TypeValidation;
use Application\Service\AbstractEntityService;
use Application\Service\Traits\FichierServiceAwareTrait;
use Application\Service\Traits\TypeValidationServiceAwareTrait;
use Application\Service\Traits\ValidationServiceAwareTrait;
use BjyAuthorize\Exception\UnAuthorizedException;
use Intervenant\Entity\Db\Intervenant;
use PieceJointe\Entity\Db\PieceJointe;
use PieceJointe\Entity\Db\TblPieceJointe;
use PieceJointe\Entity\Db\TblPieceJointeDemande;
use PieceJointe\Entity\Db\TblPieceJointeFournie;
use PieceJointe\Entity\Db\TypePieceJointe;

/**
 * Description of PieceJointe
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class PieceJointeService extends AbstractEntityService
{
    use TypeValidationServiceAwareTrait;
    use ValidationServiceAwareTrait;
    use FichierServiceAwareTrait;

    /**
     * @var float[]
     */
    protected $hps = [];



    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return PieceJointe::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'pj';
    }



    /**
     * @param Intervenant     $intervenant
     * @param TypePieceJointe $type
     *
     * @return PieceJointe
     */
    public function getByType(Intervenant $intervenant, TypePieceJointe $type)
    {
        $qb = $this->finderByType($type);
        $this->finderByHistorique($qb);
        $this->finderByIntervenant($intervenant, $qb);

        return $qb->getQuery()->getOneOrNullResult();
    }



    /**
     * Retourne la liste des types de pièces jointes demandées
     *
     * @param Intervenant $intervenant
     *
     * @return TblPieceJointeDemande[]
     */
    public function getTypesPiecesDemandees(Intervenant $intervenant)
    {
        $dql  = "
        SELECT
          pjd, tpj
        FROM
          PieceJointe\Entity\Db\TblPieceJointeDemande pjd
          JOIN pjd.typePieceJointe tpj
        WHERE
          pjd.intervenant = :intervenant
        ORDER BY tpj.ordre ASC  
        ";
        $lpjd = $this->getEntityManager()->createQuery($dql)->setParameters([
            'intervenant' => $intervenant,
        ])->getResult();
        /* @var $lpjd TblPieceJointeDemande[] */
        $hps    = 0;
        $result = [];
        foreach ($lpjd as $pjd) {
            if ($pjd->getHeuresPourSeuil() > 0) {
                $hps = $pjd->getHeuresPourSeuil();
            } // les heures sont toutes les mêmes pour l'intervenant

            $typePieceJointe                   = $pjd->getTypePieceJointe();
            $result[$typePieceJointe->getId()] = $pjd;
        }
        $this->hps[$intervenant->getId()] = $hps;

        return $result;
    }



    /**
     * @param Intervenant $intervenant
     *
     * @return float
     */
    public function getHeuresPourSeuil(Intervenant $intervenant)
    {
        if (!isset($this->hps[$intervenant->getId()])) {
            $this->getTypesPiecesDemandees($intervenant);
        }

        return $this->hps[$intervenant->getId()];
    }



    /**
     * @param Intervenant $intervenant
     *
     * @return TblPieceJointe[] $result
     */
    public function getPiecesSynthese(Intervenant $intervenant): array
    {
        $dql = "
            SELECT 
              pj
            FROM 
              PieceJointe\Entity\Db\TblPieceJointe pj
            WHERE
              pj.intervenant = :intervenant
        ";

        $listTblPieceJointe = $this->getEntityManager()->createQuery($dql)->setParameters([
            'intervenant' => $intervenant->getId(),
        ])->getResult();

        $result = [];
        foreach ($listTblPieceJointe as $TblPieceJointe) {
            $result[$TblPieceJointe->getTypePieceJointe()->getId()] = $TblPieceJointe;
        }

        return $result;
    }



    /**
     * @param Intervenant $intervenant
     *
     * @return TblPieceJointeFournie[]
     */
    public function getPiecesFournies(Intervenant $intervenant)
    {
        $dql = "
        SELECT
          pjf, pj, tpj, v, f        
        FROM
          PieceJointe\Entity\Db\TblPieceJointeFournie pjf
          JOIN pjf.pieceJointe pj
          JOIN pjf.typePieceJointe tpj
          LEFT JOIN pjf.validation v
          LEFT JOIN pjf.fichier f
        WHERE
          pjf.codeIntervenant = :intervenant
        AND
          pjf.dateValidite > :annee 
        AND 
            pjf.annee <= :annee
        AND 
            (pjf.dateArchive IS NULL OR pjf.dateArchive > :annee)  
        ORDER BY pjf.annee DESC";

        $lpjf = $this->getEntityManager()->createQuery($dql)->setParameters([
            'intervenant' => $intervenant->getCode(),
            'annee'       => $intervenant->getAnnee()->getId(),
        ])->getResult();


        /* @var $lpjf \PieceJointe\Entity\Db\TblPieceJointeFournie[] */

        $result = [];
        foreach ($lpjf as $pjf) {
            $pj        = $pjf->getPieceJointe();
            //Gérer les cas où plusieurs PJ sont éligible mais sans date d'archive, on prend la première uniquement.
            if (!array_key_exists($pj->getType()->getId(), $result)) {
                $result[$pj->getType()->getId()] = $pj;
            }
        }

        return $result;
    }



    /**
     * Validation d'une PJ.
     *
     * @param \PieceJointe\Entity\Db\PieceJointe $pj
     *
     * @return \Application\Entity\Db\Validation
     * @throws UnAuthorizedException
     */
    public function valider(PieceJointe $pj)
    {
        $role      = $this->getServiceContext()->getSelectedIdentityRole();
        $structure = $role->getStructure() ? $role->getStructure() : $pj->getIntervenant()->getStructure();
        $intervenant = $pj->getIntervenant();

        $typeValidation = $this->getServiceTypeValidation()->getByCode(TypeValidation::CODE_PIECE_JOINTE);
        //On valide également tous les fichiers
        foreach ($pj->getFichier() as $fichier) {
            $this->getServiceFichier()->valider($fichier, $intervenant);
        }

        $validation = $this->getServiceValidation()->newEntity($typeValidation);
        $validation->setIntervenant($pj->getIntervenant());
        $validation->setStructure($structure);

        $pj->setValidation($validation);

        $this->getEntityManager()->persist($validation);
        $this->getEntityManager()->persist($pj);
        $this->getEntityManager()->flush();

        return $validation;
    }



    public function archiver(PieceJointe $pj)
    {
        $annee = $this->getServiceContext()->getAnnee();
        $pj->setDateArchive($annee);
        $this->getEntityManager()->persist($pj);
        $this->getEntityManager()->flush();

        return $pj;
    }



    /**
     * Dévalidation d'une PJ.
     *
     * @param \PieceJointe\Entity\Db\PieceJointe $pj
     *
     * @return \Application\Entity\Db\Validation Validation historisée
     * @throws UnAuthorizedException
     */
    public function devalider(PieceJointe $pj)
    {
        $validation = $pj->getValidation();

        $this->getServiceValidation()->delete($validation, true);

        $pj->setValidation(null);

        $this->getEntityManager()->flush($pj);
        
        //On devalide tous les fichiers liées à cette pièce jointe
        foreach ($pj->getFichier() as $fichier) {
            $this->getServiceFichier()->devalider($fichier);
        }
        

        return $validation;
    }



    /**
     * Création si besoin de la PieceJointe et ajout des Fichiers associés.
     *
     * @param array   $files       Ex: ['tmp_name' => '/tmp/k65sd4d', 'name' => 'Image.png', 'type' => 'image/png', 'size' =>
     *                             321215]
     * @param boolean $deleteFiles Supprimer les fichiers après création de la PJ$
     *
     * @return array
     */
    public function ajouterFichiers($files, Intervenant $intervenant, TypePieceJointe $type, $deleteFiles = true): array
    {
        if (!$files) {
            throw new \LogicException("Aucune donnée sur les fichiers spécifiée.");
        }

        // création si besoin d'une PieceJointe
        $pj = $this->getByType($intervenant, $type);
        /* @var $pj PieceJointe */
        if (!$pj) {
            $pj = $this->newEntity()
                ->setType($type)
                ->setIntervenant($intervenant)
                ->setValidation(null);

            $this->getEntityManager()->persist($pj);
        }

        $errors = [];
        foreach ($files as $file) {
            $path          = $file['tmp_name'];
            $nomFichier    = str_replace([',', ';', ':'], '', $file['name']);
            $typeFichier   = $file['type'];
            $tailleFichier = $file['size'];

            $fichier = (new Fichier())
                ->setTypeMime($typeFichier)
                ->setNom($nomFichier)
                ->setTaille($tailleFichier)
                ->setContenu(file_get_contents($path))
                ->setValidation(null);


            if ($this->getServiceFichier()->isValide($fichier)) {
                $pj->addFichier($fichier);
                $this->getServiceFichier()->save($fichier);
            } else {
                $errors[] = 'Fichier ' . $fichier->getNom() . ' invalide : format "' . $fichier->getTypeMime() . '" non reconnu ou interdit.';
            }

            if ($deleteFiles) {
                unlink($path);
            }
        }

        $this->getEntityManager()->flush();

        return $errors;
    }



    /**
     * Suppression d'un Fichier déposé lié à une PJ.
     *
     * @param \Application\Entity\Db\Fichier     $fichier
     * @param \PieceJointe\Entity\Db\PieceJointe $pj
     *
     * @return void
     * @throws UnAuthorizedException
     */
    public function supprimerFichier(Fichier $fichier, PieceJointe $pj)
    {
        $pj->removeFichier($fichier);
        $this->getServiceFichier()->delete($fichier);

        if (!count($pj->getFichier())) {
            $this->delete($pj);
        }

        $this->getEntityManager()->flush();
    }
}
