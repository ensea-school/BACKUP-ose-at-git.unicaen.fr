<?php

use UnicaenCode\Form\ElementMaker;
use UnicaenCode\Util;


/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $viewName   string
 * @var $sl         \Zend\ServiceManager\ServiceLocatorInterface
 */

/** @var \Doctrine\ORM\EntityManager $em */
$em = $sl->get(\Application\Constants::BDD);

/** @var \Application\Service\Structure $serviceStructure */
$serviceStructure = $sl->get('applicationStructure');

/** @var \Application\Service\Context $serviceContext */
$serviceContext = $sl->get('applicationContext');

/** @var \Application\Service\Source $serviceSource */
$serviceSource = $sl->get('applicationSource');

/** @var \Application\Service\Role $serviceRole */
$serviceRole = $sl->get('applicationRole');

$civilites  = $em->getRepository(\Application\Entity\Db\Civilite::class)->findAll();
$structures = $serviceStructure->getList($serviceStructure->finderByHistorique($serviceStructure->finderByNiveau(2)));
$roles      = $serviceRole->getList($serviceRole->finderByHistorique());

$form = new \Zend\Form\Form();
$form->add(ElementMaker::select(
    'civilite', 'Civilité', \UnicaenApp\Util::collectionAsOptions($civilites), null
));
$form->add(ElementMaker::text(
    'nom', 'Nom'
));
$form->add(ElementMaker::text(
    'prenom', 'Prénom'
));
$form->add(ElementMaker::text(
    'mail', 'Email'
));
$form->add(ElementMaker::select(
    'structure', 'Structure', \UnicaenApp\Util::collectionAsOptions($structures), null
));
$form->add(ElementMaker::text(
    'login', 'Login'
));
$form->add(ElementMaker::text(
    'motdepasse', 'Mot de passe'
));
$form->add(ElementMaker::select(
   'role', 'Rôle', \UnicaenApp\Util::collectionAsOptions($roles), null
));
$form->add(ElementMaker::submit('create', 'Créer le compte'));
$form->setData($controller->getRequest()->getPost());

Util::displayForm($form);

if ($controller->getRequest()->isPost() && $form->isValid()) {

    $civilite   = $form->get('civilite')->getValue();
    $nom        = $form->get('nom')->getValue();
    $prenom     = $form->get('prenom')->getValue();
    $mail       = $form->get('mail')->getValue();
    $structure  = $form->get('structure')->getValue();
    $login      = $form->get('login')->getValue();
    $motdepasse = $form->get('motdepasse')->getValue();
    $role       = $form->get('role')->getValue();

    $bcrypt     = new Zend\Crypt\Password\Bcrypt();
    $motdepasse = $bcrypt->create($motdepasse);

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

    ?>
    <div class="alert alert-success">Compte bien créé</div>
    <?php

}