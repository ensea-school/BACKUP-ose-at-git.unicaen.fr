<?php

use Application\Entity\Db\Utilisateur;
use Application\Service\Traits\AffectationServiceAwareTrait;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\IntervenantServiceAwareTrait;
use Application\Service\Traits\SourceServiceAwareTrait;
use Application\Service\Traits\UtilisateurServiceAwareTrait;
use Application\Service\Traits\WorkflowServiceAwareTrait;
use UnicaenApp\Service\EntityManagerAwareTrait;


class LocalUser
{
    /**
     * @var string
     */
    public $login;

    /**
     * @var string
     */
    public $civilite = 'Monsieur';

    /**
     * @var string
     */
    public $nom;

    /**
     * @var string
     */
    public $prenom;

    /**
     * @var string
     */
    public $mail = 'ne-pas-repondre@unicaen.fr';

    /**
     * @var string
     */
    public $motDePasse;

    /**
     * @var string
     */
    public $role = 'administrateur';

    /**
     * @var string
     */
    public $statut;

    /**
     * @var string
     */
    public $structure = 'C68'; // DSI Unicaen



    static public function createFromArray(array $array)
    {
        $lu = new self();
        if (isset($array['login'])) $lu->login = $array['login'];
        if (isset($array['civilite'])) $lu->civilite = $array['civilite'];
        if (isset($array['nom'])) $lu->nom = $array['nom'];
        if (isset($array['prenom'])) $lu->prenom = $array['prenom'];
        if (isset($array['mail'])) $lu->mail = $array['mail'];
        if (isset($array['motdepasse'])) $lu->motDePasse = $array['motdepasse'];
        if (isset($array['role'])) $lu->role = $array['role'];
        if (isset($array['statut'])) $lu->statut = $array['statut'];
        if (isset($array['structure'])) $lu->structure = $array['structure'];

        return $lu;
    }



    public function html()
    {
        $url = 'https://ose.unicaen.fr/demo';

        ?>
        <table class="table table-bordered table-condensed" style="border:1;">
            <tr>
                <th>Nom prénom</th>
                <td><?= $this->nom.' '.$this->prenom ?></td>
            </tr>
            <tr>
                <th>Login</th>
                <td><?= $this->login ?></td>
            </tr>
            <tr>
                <th>Mot de passe</th>
                <td><?= $this->motDePasse ?></td>
            </tr>
            <tr>
                <th>URL d'accès à OSE démo</th>
                <td><a href="<?= $url ?>"><?= $url ?></a></td>
            </tr>
        </table><br />
        <?php
    }
}





class LocalUserMaker
{
    use EntityManagerAwareTrait;
    use ContextServiceAwareTrait;
    use SourceServiceAwareTrait;
    use WorkflowServiceAwareTrait;
    use UtilisateurServiceAwareTrait;
    use AffectationServiceAwareTrait;
    use IntervenantServiceAwareTrait;



    public function creer(LocalUser $localUser)
    {
        $utilisateur = new Utilisateur();
        $utilisateur->setDisplayName($localUser->nom . ' ' . $localUser->prenom);
        $utilisateur->setEmail($localUser->mail);
        $utilisateur->setUsername($localUser->login);
        $utilisateur->setPassword($localUser->motDePasse, true);
        $utilisateur->setState(1);

        $this->getServiceUtilisateur()->save($utilisateur);

        if ($localUser->role) {
            $this->creerAffectation($localUser, $utilisateur);
        }

        if ($localUser->statut) {
            $utilisateur->setCode(999999 + $utilisateur->getId());
            $this->getServiceUtilisateur()->save($utilisateur);
            $this->creerIntervenant($localUser, $utilisateur);
        }
    }



    private function creerAffectation(LocalUser $localUser, Utilisateur $utilisateur)
    {
        $affectation = $this->getServiceAffectation()->newEntity();
        $affectation->setUtilisateur($utilisateur);
        $affectation->setSourceCode('local-aff-'.$utilisateur->getUsername());

        $role = $this->getEntity('Role','code',$localUser->role);
        $affectation->setRole($role);

        $this->getServiceAffectation()->save($affectation);
    }



    private function creerIntervenant(LocalUser $localUser, Utilisateur $utilisateur)
    {
        $intervenant = $this->getServiceIntervenant()->newEntity();

        $civilite = $this->getEntity('Civilite', 'libelleLong', $localUser->civilite);
        $intervenant->setCivilite($civilite);
        $intervenant->setNomUsuel($localUser->nom);
        $intervenant->setNomPatronymique($localUser->nom);
        $intervenant->setPrenom($localUser->prenom);

        $dateNaissance = \DateTime::createFromFormat('Y-m-d', '1980-09-27');
        $intervenant->setDateNaissance($dateNaissance);
        $intervenant->setVilleNaissanceCodeInsee(76540);
        $intervenant->setEmail($localUser->mail);

        $statut = $this->getEntity('StatutIntervenant', 'sourceCode', $localUser->statut);
        $intervenant->setStatut($statut);

        $structure = $this->getEntity('Structure', 'sourceCode', $localUser->structure);
        $intervenant->setStructure($structure);
        $intervenant->setSourceCode($utilisateur->getCode());
        $intervenant->setCode($utilisateur->getCode());
        $intervenant->setUtilisateurCode($utilisateur->getCode());
        $intervenant->setAnnee($this->getServiceContext()->getAnnee());

        $france = $this->getEntity('Pays', 'libelleCourt', 'FRANCE');
        $intervenant->setPaysNaissance($france);

        $this->getServiceIntervenant()->save($intervenant);

        $this->getServiceWorkflow()->calculerTableauxBord(null, $intervenant);
    }



    private function getEntity($class, $attribute, $value)
    {
        $entityClass = "Application\\Entity\\Db\\$class";
        $repo = $this->getEntityManager()->getRepository($entityClass);

        return $repo->findOneBy([$attribute => $value]);
    }
}

$lum = new LocalUserMaker();
$lum->setEntityManager($sl->get(\Application\Constants::BDD));

if (isset($_POST['lud'])){
    eval($_POST['lud']);
}else{
    $utilisateurs = [];
}

$default = "
\$utilisateurs = [
    [
        'civilite'   => 'Madame', // ou Monsieur
        'nom'        => '',
        'prenom'     => '',
        'login'      => '',
        'motdepasse' => '',
        //'mail'       => '',
        //'statut'     => '',
        'role'       => 'administrateur',
        //'structure'  => 'U10', // IAE = U10 pour l'exemple
    ],
];
";

if (!empty($utilisateurs)){
    echo '<h1>Utilisateurs créés</h1>';

    foreach ($utilisateurs as $utilisateur) {
        $localUser = LocalUser::createFromArray($utilisateur);

        $lum->creer($localUser);
        echo '<div class="row"><div class="col-md-8 col-md-offset-2">';
        $localUser->html();
        echo '</div></div>';
    }
}

?>
<h1>Création de comptes utilisateurs</h1>
<form method="post">
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <pre><textarea id="lud" name="lud" class="form-control" rows="30"><?= $default ?></textarea></pre>
        <button type="submit" class="btn btn-primary" id="luc">Créer les utilisateurs</button>
    </div>
</div>
</form>