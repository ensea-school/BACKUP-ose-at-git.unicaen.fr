# Module Workflow

## Resume simple

Le module Workflow sert a construire la feuille de route d'un intervenant. Il ne realise pas directement les actions metier : il observe l'etat des autres modules, calcule l'avancement de chaque etape, puis indique quelles etapes sont atteignables, franchies ou bloquees.

Le principe general est le suivant :

1. Les etapes possibles sont definies dans `WORKFLOW_ETAPE` et dans `data/workflow_etapes.php`.
2. Les modules metier alimentent des tableaux de bord ou des vues SQL.
3. Le process `WorkflowProcess` lit les vues `V_TBL_WORKFLOW_*`.
4. Il applique les dependances configurees entre etapes.
5. Il enregistre le resultat dans `TBL_WORKFLOW`.
6. L'interface lit `TBL_WORKFLOW` pour afficher la feuille de route, les pourcentages et les raisons de blocage.

Le workflow est donc un moteur de lecture et d'orchestration. Les donnees de progression viennent principalement des modules metier, pas du module Workflow lui-meme.

## Grands principes

### Etape

Une etape represente un jalon de la feuille de route : saisie des donnees personnelles, pieces justificatives, enseignements prevus, validation, contrat, paiement, etc.

Elle est portee par l'entite `Workflow\Entity\Db\WorkflowEtape` et par la table `WORKFLOW_ETAPE`.

Une etape contient notamment :

- un `code`, utilise partout comme identifiant fonctionnel ;
- un `ordre`, qui determine l'ordre d'affichage ;
- un `perimetre`, generalement etablissement ou composante ;
- une `route`, utilisee pour naviguer vers l'ecran concerne ;
- des libelles distincts pour l'intervenant et les autres utilisateurs ;
- une description affichee quand l'etape precedente n'est pas franchie.

Les codes d'etapes sont declares comme constantes dans `WorkflowEtape`, par exemple :

- `donnees_perso_saisie`
- `pj_saisie`
- `enseignement_saisie`
- `enseignement_validation`
- `contrat`
- `demande_mep`
- `saisie_mep`

### Dependances

Une dependance dit qu'une etape ne peut etre atteinte que si une autre etape a suffisamment avance.

Elle est portee par `Workflow\Entity\Db\WorkflowEtapeDependance` et par la table `WORKFLOW_ETAPE_DEPENDANCE`.

Une dependance contient :

- l'etape suivante ;
- l'etape precedente attendue ;
- un indicateur actif/inactif ;
- un perimetre de comparaison ;
- eventuellement un type d'intervenant ;
- un niveau d'avancement requis.

Les trois niveaux d'avancement sont :

- `Debute` : au moins une progression existe ;
- `Partiel` : au moins un element est termine ou marque partiellement termine ;
- `Integral` : tous les elements attendus sont termines.

Exemple : l'etape "validation des pieces justificatives" peut dependre de l'etape "saisie des pieces justificatives". Selon le parametrage, on peut exiger que la saisie soit seulement commencee, partiellement terminee ou integralement terminee.

### Contraintes de conception

Le fichier `data/workflow_etapes.php` contient aussi des contraintes fixes. Ces contraintes ne sont pas de simples dependances administrables : elles expriment des enchainements imposes par la conception.

Elles sont utilisees notamment lors du tri des etapes pour empecher un ordonnancement incoherent. Par exemple, une etape de validation ne peut pas etre placee avant l'etape de saisie correspondante si cette contrainte est declaree.

### Perimetre

Le workflow gere deux logiques principales de perimetre :

- etablissement : l'etape est globale pour l'intervenant ;
- composante : l'etape peut etre suivie par structure.

Lors du calcul des dependances, le perimetre decide quelles lignes de progression de l'etape precedente sont comparees avec l'etape courante. En perimetre composante, une structure est comparee a elle-meme, avec des cas globaux representes par la structure `0`.

## Modele de donnees

### `WORKFLOW_ETAPE`

Table de parametrage des etapes.

Champs importants :

- `CODE` : code fonctionnel de l'etape ;
- `PERIMETRE_ID` : perimetre de l'etape ;
- `ROUTE` et `ROUTE_INTERVENANT` : routes de navigation ;
- `LIBELLE_INTERVENANT` et `LIBELLE_AUTRES` : libelles affiches ;
- `DESC_NON_FRANCHIE` : message de blocage ;
- `ORDRE` : ordre de la feuille de route ;
- `ANNEE_ID` : annee concernee.

### `WORKFLOW_ETAPE_DEPENDANCE`

Table de parametrage des dependances.

Champs importants :

- `ETAPE_SUIVANTE_ID` : etape controlee ;
- `ETAPE_PRECEDANTE_ID` : etape attendue ;
- `ACTIVE` : dependance active ou non ;
- `TYPE_INTERVENANT_ID` : restriction optionnelle ;
- `PERIMETRE_ID` : perimetre de comparaison ;
- `AVANCEMENT` : niveau requis.

### `TBL_WORKFLOW`

Table de resultat. Elle est alimentee par le tableau de bord `workflow`.

Une ligne correspond a l'avancement d'une etape pour un intervenant, eventuellement pour une structure.

Champs importants :

- `ANNEE_ID`
- `INTERVENANT_ID`
- `ETAPE_ID` et `ETAPE_CODE`
- `STRUCTURE_ID`
- `ATTEIGNABLE`
- `OBJECTIF`
- `PARTIEL`
- `REALISATION`
- `WHY_NON_ATTEIGNABLE`

`OBJECTIF` et `REALISATION` permettent de calculer le pourcentage d'avancement. Une etape est consideree franchie quand `REALISATION >= OBJECTIF`.

`ATTEIGNABLE` indique si l'etape est accessible au regard des dependances. `WHY_NON_ATTEIGNABLE` stocke les raisons de blocage sous forme JSON.

## Calcul du workflow

### Source des donnees

Le calcul repose sur les vues SQL `V_TBL_WORKFLOW_*`, par exemple :

- `V_TBL_WORKFLOW_DOSSIER`
- `V_TBL_WORKFLOW_PJ`
- `V_TBL_WORKFLOW_ENSEIGNEMENT_PREVU`
- `V_TBL_WORKFLOW_CONTRAT`
- `V_TBL_WORKFLOW_PAIEMENT`

Ces vues renvoient toutes une structure commune :

- `etape_code`
- `intervenant_id`
- `structure_id`
- `objectif`
- `partiel`
- `realisation`

Chaque vue traduit une realite metier en progression workflow. Par exemple, la vue dossier transforme la completude ou la validation du dossier en objectif et realisation. La vue contrat transforme l'etat des contrats en progression de l'etape `contrat`.

### Process central

Le calcul est effectue par `Workflow\Tbl\Process\WorkflowProcess`.

Son deroulement principal :

1. `run()` lance le calcul, par annee ou selon les filtres fournis.
2. `load()` construit en memoire les etapes d'un ou plusieurs intervenants.
3. `sqlAlimentation()` concatene les vues `V_TBL_WORKFLOW_*` en `UNION ALL`.
4. `sqlActivationEtapes()` active ou non certaines etapes selon le statut de l'intervenant.
5. `traitementExportRh()` retire l'etape export RH si l'export RH est desactive.
6. `calculDependances()` applique les dependances configurees.
7. `save()` fusionne le resultat dans `TBL_WORKFLOW`.

### Activation des etapes

Toutes les etapes ne sont pas utiles pour tous les intervenants. `sqlActivationEtapes()` decide si une etape doit exister selon le statut de l'intervenant.

Exemples :

- les etapes de candidature dependent de `offre_emploi_postuler` ;
- les etapes mission dependent de `mission` ;
- les etapes de service prevu dependent de `service_prevu` ;
- les etapes de paiement dependent de `paiement`.

### Application des dependances

Pour chaque etape, `calculDependances()` parcourt ses dependances actives.

Une dependance est ignoree si :

- elle est inactive ;
- elle vise un autre type d'intervenant ;
- l'etape precedente n'existe pas pour l'intervenant.

Sinon, le process compare la progression de l'etape precedente avec le niveau requis :

- `Debute` : progression ou partiel superieur a `0` ;
- `Partiel` : au moins une ligne partielle ou terminee ;
- `Integral` : toutes les lignes atteignent leur objectif.

Si la condition n'est pas satisfaite, l'etape devient non atteignable et une raison de blocage est ajoutee.

## Feuille de route

La feuille de route affichable est construite par `Workflow\Model\FeuilleDeRoute`.

Elle lit `TBL_WORKFLOW` pour un intervenant, puis construit une liste de `FeuilleDeRouteEtape`.

Une etape de feuille de route expose notamment :

- son numero ;
- son code ;
- son libelle ;
- son URL ;
- son pourcentage de realisation ;
- son etat atteignable ou non ;
- ses raisons de blocage ;
- son detail par structure quand il existe.

La classe `FeuilleDeRouteEtape` considere qu'une etape est :

- franchie si `realisation >= objectif` ;
- autorisee si elle est atteignable ou si une realisation existe deja ;
- navigable si elle est autorisee et si l'utilisateur a le droit d'acceder a la route.

L'etape courante est la premiere etape atteignable non franchie.

## Interfaces et routes

### Feuille de route utilisateur

Les routes principales sont declarees dans `module/Workflow/config/module.config.php`.

- `workflow/feuille-de-route-data/:intervenant` : retourne les donnees de la feuille de route ;
- `workflow/feuille-de-route-refresh/:intervenant` : recalcule les tableaux de bord puis retourne les donnees ;
- `workflow/feuille-de-route-nav/:intervenant` : retourne l'etape suivante apres une etape donnee.

Le front Vue est dans :

- `front/Workflow/FeuilleDeRoute.vue`
- `front/Workflow/Nav.vue`

### Administration

Les routes d'administration sont declarees dans `module/Workflow/config/administration.config.php`.

L'ecran d'administration permet :

- de consulter les etapes ;
- de modifier les libelles d'une etape ;
- d'ajouter, modifier ou supprimer des dependances ;
- de reordonner les etapes, sous reserve des contraintes.

Le controleur principal est `Workflow\Controller\AdministrationController`.

Le front Vue est dans `front/Workflow/Administration.vue`.

### Commande de reinitialisation

La commande CLI `workflow-reset` reinitialise la configuration du workflow depuis les donnees de reference.

Elle est implementee par `Workflow\Command\WorkflowResetCommand`.

Attention : cette commande remet le parametrage a l'etat initial apres confirmation interactive.

## Recalcul et dependances entre tableaux de bord

Le workflow depend fortement des autres tableaux de bord. `WorkflowService::calculerTableauxBord()` connait les dependances de recalcul.

Exemples :

- le dossier, les pieces jointes, les services, les missions et les paiements declenchent ensuite un recalcul workflow ;
- les validations d'enseignement et de referentiel dependent de la formule puis du workflow ;
- le contrat depend des validations d'enseignement et de referentiel.

Le tableau de bord `workflow` est configure dans `config/autoload/unicaen-tbl.global.php` avec le process `WorkflowProcess`.

## Modifier le workflow

### Modifier l'ordre des etapes

Passer par l'administration Workflow. Le service `WorkflowService::trier()` verifie que le nouvel ordre ne viole pas :

- les contraintes de conception declarees dans `data/workflow_etapes.php` ;
- les dependances deja configurees.

### Modifier les libelles

Passer par l'administration Workflow. Les libelles sont sauvegardes dans `WORKFLOW_ETAPE`.

### Modifier une dependance

Passer par l'administration Workflow. Une dependance peut etre activee/desactivee, restreinte a un type d'intervenant, et parametree avec un niveau d'avancement requis.

Apres modification, le cache des etapes est vide. Il faut ensuite recalculer le workflow pour que `TBL_WORKFLOW` reflete les nouvelles regles.

### Ajouter une nouvelle etape

Une nouvelle etape demande plus qu'un simple ajout en base. Il faut generalement :

1. ajouter une constante dans `WorkflowEtape` ;
2. declarer l'etape dans `data/workflow_etapes.php` ;
3. fournir une vue `V_TBL_WORKFLOW_*` qui calcule `objectif`, `partiel` et `realisation` ;
4. s'assurer que l'etape est activee dans `sqlActivationEtapes()` si elle depend du statut ;
5. ajouter la route cible si necessaire ;
6. tester le recalcul du tableau de bord workflow.

## Points d'attention

- `TBL_WORKFLOW` est une table de resultat, pas une table source. Si les donnees metier changent, il faut recalculer.
- Les vues `V_TBL_WORKFLOW_*` doivent rester homogenes : meme structure de colonnes, memes conventions sur `objectif`, `partiel`, `realisation`.
- Une etape peut etre globalement atteignable mais avoir un detail par structure.
- Les dependances sont appliquees apres le chargement des progressions metier.
- Une etape non atteignable peut rester autorisee si elle a deja une realisation, afin de ne pas rendre inaccessible un travail deja commence.
- L'ordre d'affichage ne suffit pas a bloquer une etape : le blocage vient des dependances.
- Les contraintes de `data/workflow_etapes.php` servent surtout a proteger la coherence du parametrage.
- Le cache des etapes est vide lors des sauvegardes de parametrage, mais les resultats de `TBL_WORKFLOW` necessitent un recalcul.

## Fichiers de reference

- `module/Workflow/src/Service/WorkflowService.php` : service principal, cache des etapes, feuille de route, recalcul.
- `module/Workflow/src/Tbl/Process/WorkflowProcess.php` : moteur de calcul du workflow.
- `module/Workflow/src/Model/FeuilleDeRoute.php` : construction de la feuille de route affichable.
- `module/Workflow/src/Model/FeuilleDeRouteEtape.php` : logique d'une etape affichee.
- `module/Workflow/src/Entity/Db/WorkflowEtape.php` : entite de parametrage d'une etape.
- `module/Workflow/src/Entity/Db/WorkflowEtapeDependance.php` : entite de parametrage d'une dependance.
- `data/workflow_etapes.php` : referentiel fonctionnel des etapes, contraintes et dependances par defaut.
- `data/ddl/view/V_TBL_WORKFLOW_*.sql` : vues SQL de progression.
- `data/ddl/table/TBL_WORKFLOW.php` : definition de la table resultat.
- `front/Workflow/*.vue` : composants Vue de consultation et d'administration.

## Lecture rapide pour un developpeur

Si une etape apparait bloquee :

1. verifier la ligne correspondante dans `TBL_WORKFLOW` ;
2. lire `WHY_NON_ATTEIGNABLE` ;
3. verifier les dependances dans `WORKFLOW_ETAPE_DEPENDANCE` ;
4. controler la vue `V_TBL_WORKFLOW_*` de l'etape precedente ;
5. recalculer les tableaux de bord de l'intervenant.

Si une progression semble fausse :

1. verifier la vue SQL source ;
2. verifier les tableaux de bord metier dont elle depend ;
3. recalculer le tableau de bord metier concerne ;
4. recalculer le workflow.

Si une etape n'apparait pas :

1. verifier `WORKFLOW_ETAPE` pour l'annee ;
2. verifier `sqlActivationEtapes()` et le statut de l'intervenant ;
3. verifier que la vue `V_TBL_WORKFLOW_*` renvoie au moins une ligne pertinente ;
4. recalculer le workflow.
