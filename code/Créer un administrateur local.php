<?php

use Application\Service\ContextService;
use Application\Service\SourceService;

function creerAdmin($sl, array $admin)
{

    /** @var \Doctrine\ORM\EntityManager $em */
    $em = $sl->get(\Application\Constants::BDD);

    /** @var \Application\Service\ContextService $serviceContext */
    $serviceContext = $sl->get(ContextService::class);

    /** @var \Application\Service\SourceService $serviceSource */
    $serviceSource = $sl->get(SourceService::class);


    $nom        = $admin['nom'];
    $prenom     = $admin['prenom'];
    $mail       = isset($admin['mail']) ? $admin['mail'] : 'ne-pas-repondre@unicaen.fr';
    $login      = $admin['login'];
    $bcrypt     = new Zend\Crypt\Password\Bcrypt();
    $motdepasse = $bcrypt->create($admin['motdepasse']);
    $role       = isset($admin['role']) ? $admin['role'] : 'administrateur';
    $structure  = isset($admin['structure']) ? $admin['structure'] : 'C68';

    $structure = $em->getRepository(\Application\Entity\Db\Structure::class)->findOneBy(['sourceCode' => $structure])->getId();
    $role      = $em->getRepository(\Application\Entity\Db\Role::class)->findOneBy(['code' => $role])->getId();
    $civilite  = $em->getRepository(\Application\Entity\Db\Civilite::class)->findOneBy(['libelleLong' => $admin['civilite']])->getId();

    $source      = $serviceSource->getOse()->getId();
    $utilisateur = $serviceContext->getUtilisateur()->getId();

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

$admins = [
];

foreach ($admins as $admin) {
    creerAdmin($sl, $admin);
}
