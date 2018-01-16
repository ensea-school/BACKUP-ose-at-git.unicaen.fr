<?php

use Application\Service\ContextService;
use Application\Service\SourceService;
use Application\Service\WorkflowService;

function creerAdmin($sl, array $admin)
{

    /** @var \Doctrine\ORM\EntityManager $em */
    $em = $sl->get(\Application\Constants::BDD);

    /** @var \Application\Service\ContextService $serviceContext */
    $serviceContext = $sl->get(ContextService::class);

    /** @var \Application\Service\SourceService $serviceSource */
    $serviceSource = $sl->get(SourceService::class);

    /** @var \Application\Service\WorkflowService $serviceWorkflow */
    $serviceWorkflow = $sl->get(WorkflowService::class);


    $nom        = $admin['nom'];
    $prenom     = $admin['prenom'];
    $mail       = isset($admin['mail']) ? $admin['mail'] : 'ne-pas-repondre@unicaen.fr';
    $login      = $admin['login'];
    $bcrypt     = new Zend\Crypt\Password\Bcrypt();
    $motdepasse = $bcrypt->create($admin['motdepasse']);
    $role       = isset($admin['role']) ? $admin['role'] : 'administrateur';
    $statut     = isset($admin['statut']) ? $admin['statut'] : null;
    $structure  = isset($admin['structure']) ? $admin['structure'] : 'C68';

    $structure = $em->getRepository(\Application\Entity\Db\Structure::class)->findOneBy(['sourceCode' => $structure])->getId();
    if ($statut) {
        $statut = $em->getRepository(\Application\Entity\Db\StatutIntervenant::class)->findOneBy(['sourceCode' => $statut])->getId();
        $role   = null;
    } else {
        $role = $em->getRepository(\Application\Entity\Db\Role::class)->findOneBy(['code' => $role])->getId();
    }
    $civilite = $em->getRepository(\Application\Entity\Db\Civilite::class)->findOneBy(['libelleLong' => $admin['civilite']])->getId();

    $source      = $serviceSource->getOse()->getId();
    $utilisateur = $serviceContext->getUtilisateur()->getId();
    $pays = $em->getRepository(\Application\Entity\Db\Pays::class)->findOneBy(['libelleCourt' => 'FRANCE'])->getId();

    $uid = $em->getConnection()->fetchAssoc('SELECT utilisateur_id_seq.nextval UTILID FROM dual');
    $uid = (int)$uid['UTILID'];

    $em->getConnection()->insert('utilisateur', [
        'id'           => $uid,
        'username'     => $login,
        'password'     => $motdepasse,
        'display_name' => $nom . ' ' . $prenom,
        'email'        => $mail,
        'state'        => 1,
    ]);

    if ($role) {

        $pid = $em->getConnection()->fetchAssoc('SELECT personnel_id_seq.nextval PERSID FROM dual');
        $pid = (int)$pid['PERSID'];

        $em->getConnection()->insert('personnel', [
            'id'                    => $pid,
            'civilite_id'           => $civilite,
            'nom_usuel'             => $nom,
            'prenom'                => $prenom,
            'nom_patronymique'      => $nom,
            'email'                 => $mail,
            'structure_id'          => $structure,
            'source_id'             => $source,
            'source_code'           => 'utilisateur-id-' . $uid,
            'histo_creation'        => $em->getConnection()->convertToDatabaseValue(new \DateTime(), 'datetime'),
            'histo_createur_id'     => $utilisateur,
            'histo_modification'    => $em->getConnection()->convertToDatabaseValue(new \DateTime(), 'datetime'),
            'histo_modificateur_id' => $utilisateur,
            'code'                  => 'utilisateur-id-' . $uid,
        ]);

        $aid = $em->getConnection()->fetchAssoc('SELECT affectation_id_seq.nextval AFFID FROM dual');
        $aid = (int)$aid['AFFID'];
        $em->getConnection()->insert('affectation', [
            'id'                    => $aid,
            'personnel_id'          => $pid,
            'role_id'               => $role,
            'source_id'             => $source,
            'source_code'           => 'local-aff-' . $pid . '-' . $role,
            'histo_creation'        => $em->getConnection()->convertToDatabaseValue(new \DateTime(), 'datetime'),
            'histo_createur_id'     => $utilisateur,
            'histo_modification'    => $em->getConnection()->convertToDatabaseValue(new \DateTime(), 'datetime'),
            'histo_modificateur_id' => $utilisateur,
        ]);
    }

    if ($statut) {
        $iid = $em->getConnection()->fetchAssoc('SELECT intervenant_id_seq.nextval INTID FROM dual');
        $iid = (int)$iid['INTID'];

        $em->getConnection()->insert('intervenant', [
            'id'                         => $iid,
            'civilite_id'                => $civilite,
            'nom_usuel'                  => $nom,
            'prenom'                     => $prenom,
            'nom_patronymique'           => $nom,
            'date_naissance'             => $em->getConnection()->convertToDatabaseValue(\DateTime::createFromFormat('Y-m-d', '1980-09-27'), 'datetime'),
            'ville_naissance_code_insee' => 76540,
            'email'                      => $mail,
            'statut_id'                  => $statut,
            'structure_id'               => $structure,
            'source_id'                  => $source,
            'source_code'                => 'utilisateur-id-' . $uid,
            'code'                       => 'utilisateur-id-' . $uid,
            'supann_emp_id'              => 'utilisateur-id-' . $uid,
            'annee_id'                   => $serviceContext->getAnnee()->getId(),
            'pays_naissance_id'          => $pays, // France
            'histo_creation'             => $em->getConnection()->convertToDatabaseValue(new \DateTime(), 'datetime'),
            'histo_createur_id'          => $utilisateur,
            'histo_modification'         => $em->getConnection()->convertToDatabaseValue(new \DateTime(), 'datetime'),
            'histo_modificateur_id'      => $utilisateur,
        ]);

        $intervenant = $em->getRepository(\Application\Entity\Db\Intervenant::class)->find($iid);
        $serviceWorkflow->calculerTableauxBord(null,$intervenant);
    }
}

$admins = [




];

foreach ($admins as $admin) {
    creerAdmin($sl, $admin);
}
