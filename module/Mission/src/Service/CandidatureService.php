<?php

namespace Mission\Service;

use Administration\Service\ParametresServiceAwareTrait;
use Application\Acl\Role;
use Application\Provider\Privilege\Privileges;
use Application\Service\AbstractEntityService;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\SourceServiceAwareTrait;
use Intervenant\Entity\Db\Intervenant;
use Mission\Entity\Db\Candidature;
use Mission\Entity\Db\OffreEmploi;
use UnicaenMail\Service\Mail\MailServiceAwareTrait;
use UnicaenVue\View\Model\AxiosModel;

/**
 * Description of CandidatureService
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 *
 * @method Candidature get($id)
 * @method Candidature[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 * @method Candidature newEntity()
 *
 */
class CandidatureService extends AbstractEntityService
{

    use SourceServiceAwareTrait;
    use ContextServiceAwareTrait;
    use ParametresServiceAwareTrait;
    use MailServiceAwareTrait;


    /**
     * Retourne la classe des entités
     *
     * @return string
     * @throws \RuntimeException
     */
    public function getEntityClass (): string
    {
        return Candidature::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias (): string
    {
        return 'ca';
    }



    public function postuler (Intervenant $intervenant, OffreEmploi $offre): Candidature
    {

        $candidature = $this->newEntity();
        $candidature->setIntervenant($intervenant);
        $candidature->setOffre($offre);

        return $this->save($candidature);
    }



    /**
     * @param Candidature $entity
     *
     * @return Candidature
     */
    public function save ($entity)
    {
        parent::save($entity);

        return $entity;
    }



    public function data (array $parameters, ?Role $role = null)
    {
        $dql = "
        SELECT 
         c, i, o, v, str
        FROM 
          " . Candidature::class . " c
          JOIN c.intervenant i
          JOIN c.offre o
          JOIN o.structure str
          LEFT JOIN c.validation v
        WHERE 
          c . histoDestruction IS null
          AND v.histoDestruction IS NULL
       ";


        $dql .= dqlAndWhere([
            'intervenant' => 'i',
        ], $parameters);


        $query  = $this->getEntityManager()->createQuery($dql)->setParameters($parameters);
        $result = $query->getResult();

        $triggers = [
            '/'                      => function (Candidature $original, array $extracted) {
                $extracted['canValider']    = $this->getAuthorize()->isAllowed($original, Privileges::MISSION_CANDIDATURE_VALIDER);
                $extracted['canRefuser'] = $this->getAuthorize()->isAllowed($original, Privileges::MISSION_CANDIDATURE_REFUSER);

                return $extracted;
            },
        ];

        $properties = [
            'id',
            'motif',
            'validation',
            'dateCommission',
            'canValider',
            'canRefuser',
            ['offre', ['id', 'typeMission', 'titre', ['structure', ['libelleCourt']]]],
            ['intervenant', ['id', 'nomUsuel', 'prenom', 'emailPro', 'code', ['structure', ['libelleLong', 'libelleCourt', 'code', 'id']], ['statut', ['libelle', 'code']]]],
        ];


        return new AxiosModel($query, $properties, $triggers);
    }



    public function envoyerMail (Candidature $candidature, string $modele, string $sujet): bool
    {
        //Récupération du modèle de mail
        $html = $this->getServiceParametres()->get($modele);
        //Ajout pour transformer les sauts de lignes en html <br/>
        $html = nl2br($html);
        //Personnalisation des variables
        $intervenant = $candidature->getIntervenant();
        if ($intervenant->getCivilite() != null) {
            $vIntervenant = $intervenant->getCivilite()->getLibelleCourt() . " " . $intervenant->getNomUsuel();
        } else {
            $vIntervenant = $intervenant->getNomUsuel();
        }
        $vUtilisateur = $this->getServiceContext()->getUtilisateur()->getDisplayName();
        $offre    = $candidature->getOffre();
        $vMission = $offre->getTitre() . ' (' . $offre->getTypeMission()->getLibelle() . ') prévue du ' . $offre->getDateDebut()->format('d-m-Y') . ' au ' . $offre->getDateFin()->format('d-m-Y');
        $html     = str_replace([':intervenant', ':utilisateur', ':mission'], [$vIntervenant, $vUtilisateur, $vMission], $html);
        $subject      = $this->getServiceParametres()->get($sujet);
        $subject      = str_replace(':intervenant', $vIntervenant, $subject);
        $to           = (!empty($intervenant->getEmailPerso())) ? $intervenant->getEmailPerso() : $intervenant->getEmailPro();
        if (!empty($to)) {
            $this->getMailService()->sendMail($to, $subject, $html);

            return true;
        }

        return false;
    }

}
