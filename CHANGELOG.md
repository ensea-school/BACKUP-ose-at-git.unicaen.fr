---
title: "Changements intervenus sur OSE"
author: Laurent Lécluse - DSI - Unicaen
---

# OSE 9.0.2-zf2 et 9.0.2-zf3

## Correction de bugs

* Un bug modifiant les paramètres globaux qui avaient été configurés a été résolu
* La duplication de statuts refonctionne
* Lors de la création d'un nouveau statut, l'octroi de privilèges ne plante plus


# OSE 9.0.1-zf2 et 9.0.1-zf3

## Correction de bugs

* Un bug empêchant d'avoir accès aux indicateurs si on est connecté en tant que composante a été résolu

# OSE 9.0-zf2 et 9.0-zf3

## Correction de bugs

* On peut maintenant s'abonner à un indicateur même avec un rôle de périmètre établissement réduit à une composante.
* Les services réalisés peuvent maintenant être initialisés à partir de volumers horaires auto-validés en plus e ceux qui ont été validés manuellement.
* Le référentiel peut être saisi même pour un intervenant d'une autre composante si l'enseignement est dans la composante du gestionnaire
* L'utilisateur OSE était mentionné partout comme modificateur au lieu de l'utilisateur courant. C'est rétabli.
* La création d'un nouveau projet de contrat ne calculait pas les heures HETD avec la formule. C'est maintenant automatique. 
* Depuis quelques temps, les plafonds bloquants fonctionnaient comme des plafonds informatifs. C'est corrigé.
* La séquence FORMULE_RESULTAT_SERVIC_ID_SEQ se met maintenant correctement à jour (avant, cela entrainait de nombreux bugs, car la formule des HC ne se calculait plus après une mise à jour)
* L'indicateur 120 renvoyait à tort le même résultat que le 110.
* Lors de la modification d'un privilège, le cache se met à jour automatiquement désormais

## Nouveautés

* Deux éditions de OSE sont disponibles :
    * 9.0-zf2 basée sur le Zend Framework 2 et qui requiert PHP7.0 (édition "historique")
    * 9.0-zf3 basée sur le Zend Framework 3 et qui requiert PHP7.3
* Ajout d'un nouveau contrôle lors des demandes de mise en paiement : il n'est plus possible de payer plus d'heures que d'HETD même si des HETD ont déjà été payées à tort
* La vue matérialisée MV_EXT_SERVVICE a été créée pour être exploitée pour alimenter des outils de pilotage (BO, etc).

## Notes de mise à jour

Si vous mettez à jour à partir des versions :

* 8.2.* : pas de soucis, un ./bin/ose update suffit.
* 8.1.* : lancez ./bin/ose update normalement. Vous rencontrerez un message d'erreur dû à un bug lié à cette version (PHP Fatal error:  Uncaught Error: Call to undefined method OseAdmin::getOseAppliId()).
Pour pallier à cela, il vous faudra exécuter ensuite la commande ./bin/ose update-bdd pour que tout rentre dans l'ordre.
* < 8.1 : mettez d'abord à jour en version 8.1.4, puis mettez à jour en 9.0.

# OSE 8.2.2

## Correction de bugs

* Les futurs vacataires ne pouvaient plus se connecter à l'application (le choix de l'année en cours ne leur était pas proposé si leur fiche n'existait pas)

# OSE 8.2.1

## Correction de bugs

* Depuis la 8.2, les RIB SEPA n'étaient jamais considérés comme validés.
* Toujours depuis la 8.2, les ID des types de volumes horaires et des états de volumes horaires pouvaient être modifiés à tort.
* Warning PHP (sans conséquence) se prosuisant lors de la mise à jour corrigé. 

## Notes de mise à jour

Si vous mettez à jour depuis la v8.2, un Warning apparaitra au début. Il est sans conséquence. Merci de ne pas en tenir compte.

# OSE 8.2

## Correction de bugs

* Lorsqu'on supprime une ligne de service avec des volumes horaires validés en mode calendaire, 
les nouveaux volumes horaires négatifs générés portent maintenant les mêmes dates de début et de fin que les originaux.
* Des volumes horaires référentiels auto-validés pouvaient être modifiés via l'IHM dans certaines circonstances : c'est corrigé.
* La modification d'heures de service référentiel fonctionne bien même avec des heures auto-validées et calendarisées. 
* Le total HETD HC affiche réellmenet les HC et non le total des heures dans services/résumé
* Lorsqu'un pays a été importé plusieurs fois, les items historisés remontaient parfois dans des recherches par libelle. Seuls les pays non historisés remontent maintenant.

## Nouveautés

* Le logo Unicaen n'est plus fourni par défaut dans l'interface de OSE en pied de page
* Le lien "Informatique et libertés" est remplacé par un nouveau lien "Vie privée"
* Interface de gestion des motifs de non paiement
* Possibilité de créer un nouvel utilisateur dans OSE déconnecté du LDAP, ainsi qu'une fiche intervenant.
* [Documentation de la ligne de commande OSE](doc/ligne-de-commande.md)
* Pour une fiche intervenant, les champs suivants n'ont plus besoin d'être systématiquement fournis :
    * Civilité
    * Composante d'affectation
    * Pays de naissance
    * Nom patronymique

* Les coordonnées bancaires peuvent être saisies même si elles sont hors zone SEPA (une case à cocher limite le contrôle)
* L'export PDF des services est maintenant personnalisable.
* Le pays "France" est détecté automatiquement. Il n'a donc plus besoin d'être identifié via administration/paramètres généraux.
* Amélioration de performances pour l'affichage des données personnelles
* Nouveau plafond à définir par statut portant sur les HETD complémentaires en FI hors EAD (Enseignement à distance)
* Indicateurs 580 et 590 liés à ce nouveau plafond (580=prévisionnel, 590=réalisé)

## Notes de mise à jour

Si vous faites la mise à jour depuis une version ANTÉRIEURE à la 8.1 :

* Il est obligatoire de migrer OSE d'abord en version 8.1 (bien 8.1, pas 8.0.1 ou autres), puis ensuite de faire la migration 8.1 => 8.2.

Pour tout le monde :

* CSS personnalisée : Si vous avez remplacé le logo Unicaen par votre propre logo en pied de page, 
la classe CSS pour cela est maintenant "lien-univ" au lien de "ucbn".

* Dans le fichier de configuration local.config.php, remplacer l'item "informatiqueEtLibertes" par "viePrivee"


# OSE 8.1.4

## Correction de bug

* Bug de la version 8.1.3 corrigé dans l'infrastructure de gestion des formules de calcul : 
certains volumes horaires étaient comptés deux fois,entrainant plus d'heures HETD que prévu.

# OSE 8.1.3

## Corrections de bugs

* Les informations complémentaires de l'utilisateur (qui relevaient d'une liste spécifique à l'Université de Caen et qui pouvait amener un plantage de l'application) 
ne sont plus affichées lorsqu'on clique sur l'utilisateur en haut à droite.
* Dans la fiche de service, les codes des éléments et des étapes s'affichent de nouveau (ils n'étaient plus visibles)
* Lorsqu'on crée un nouveau rôle et qu'on lui ajoute des privilèges, l'application ne plante plus (problème de cache de données réglé).
* Dans l'export Winpaye, le numéro INSEE est maintenant correctement formatté (il supprime les espaces en trop, et ajoute des zéros devant les clés si nécessaire)
* Dans le dossier, les Numéros INSEE avec pour département 75 sont comptés valides par rapport au département de naissance si l'intervenant est né en actuelle région parisienne avant 1968.

## Nouveautés
* Nouveau script de test d'accès à la BDD depuis le script de mise à jour (./bin/ose test-bdd)
* Formules de calcul : nouveau système de récupération des paramètres spécifiques directement implanté dans les formules. 
Les vues V_FORMULE_LOCAL_I_PARAMS et V_FORMULE_LOCAL_VH_PARAMS ne sont plus nécessaires.
* Renforcement du script de mise à jour (pour les futures mises à jour) : détection de l'accès à la BDD OK ou non avant de démarrer la procédure et avertissement sans blocage si le cache ne peut pas se nettoyer.

# OSE 8.1.2

## Corrections de bugs

* Depuis l'offre de formation :
  * lorsqu'un élément a plusieurs centres de coûts associés, les historiques ne sont plus pris en compte
  * les niveaux ne sont plus affichés si aucune étape ne leur correspond 
* L'email professionnelle, qui ne pouvait pas être saisie, n'empêchera plus la validation des données personnelles si elle est vide.
* Formules de calcul :
  * Correction de problèmes de performance. OSE_FORMULE.CALCULER_TOUT doit avoir une vitesse de calcul de 90 intervenants/seconde environ.
  * Modifications sur la formule de Montpellier (réalisées par Montpellier)
  * Correction d'un bug entrainant des erreurs de calcul si on utilise OSE_FORMULE.CALCULER_TOUT.
* Lors de la saisie de service, "null" n'apparait plus si le semestre n'était pas renseigné (c'était le cas dans certaines situations)

## Nouveautés

* Mise en place d'un nouveau système de mises à jour. Ce dispositif permet maintenant de mettre à jour automatiquement la base de données.
Il n'y a donc plus de scripts SQL à exécuter en plus.

Attention : le système calcule tout seul le différentiel entre l'état actuel de votre base de données et l'état attendu
par la nouvelle version.
Il se focalise sur les structures de données fournies "en standard" dans l'application, qui doivent être les mêmes pour tout le monde. 
Il ignore donc les objets qui ont été créés par vos soins. 
Ces derniers ne seront donc pas modifiés ou supprimés. 
De même, les connecteurs ne seront pas impactés. 

* Lien LDAP : possibilité de définir dans le fichier de configuration config.local.php le paramètre loginObjectClass qui permet de rechercher des utilisateurs de classe autre que posixAccount.
cf. Fichier config.local.php.default.


# OSE 8.1.1

## Corrections de bugs

* La prise en compte des paramètres supplémentaires pour la formule de calcul fonctionne désormais pleinement.
* Dans l'onglet Suppression de la fiche intervenant, l'arborescence s'affiche correctement. La suppression partielle ou complète d'un intervenant fonctionne donc de nouveau.
* Un bug se produisait dans l'arborescence de suppression de fiche intervenant : s'il n'y avait qu'un seul service ou qu'un seul référentiel alors rien n'était affiché. C'est corrigé. 

## Nouveautés

* Possibilité de récupérer des attributs multivalués pour faire la correspondance d'identifiant entre le LDAP et le code utilisateur indiqué dans la fiche Intervenant
* Possibilité de modifier les données liées aux charges d'enseignement sur des éléments pédagogiques qui ne sont plus synchronisés avec Apogée.

## Notes de mise à jour

* Attention : Le répertoire public/modeles de OSE était propre à l'Université de Caen et il a été supprimé.
Il servait à fournir des modèles de pièces justificatives à remplir par les intervenants.
Si vous voulez fournir vos propres modèles de pièces justificatives, vous devrez les placer sur un serveur Web (qui peut être celui de OSE, mais ailleurs que dans le code source) 
pour avoir une URL que vous collerez dans l'interface de gestion des pièces justificatives en éditant un type de pièce jointe, champ "modèle".


# OSE 8.1

## Corrections de bugs

* Les avenants comportaient par défaut un champ "modifieComplete" qui n'était pas fourni par la vue.
* Lors de la saisie d'un nouveau service d'enseignement, si la formation a déjà été sélectionnée :
    * Les éléments sont triés correctement
    * Les éléments sont affichés avec le semestre

* Avenants au contrat travail corrigé : le projet et l'avenant validé ne présentaient pas les mêmes nombres d'heures 
dans le détail des services.
* Contrats : le problème de double espace situé entre "titre" et "qualité" a été corrigé. 
* Procédures d'installation et de mise à jour intégrées directement au projet dans Gitlab.
* Certains dossiers intervenants pouvaient être validés plusieurs fois. 
Un mécanisme contrôle désormais que le dossier n'est pas déjà validé avant de valider à nouveau.
* Le contrôle de cohérence des données personnelles prend maintenant en compte le cas des français nés dans un ex-département français d'Algérie.  
* Les mails de notification aux intervenants peuvent être envoyés même si certains d'entre eux n'ont pas de mail. Ces derniers seront listés.
* Les indexes de clés étrangères n'étaient pas créés par les précédents scripts d'installation. C'est désormais le cas et le script de MAJ 
inclue les indexes manquants pour création.

## Nouveautés

* Il est désormais possible de customiser et/ou de traduire de petites parties de l'application. 
Attention toutefois : le travail de mise en place du dispositif n'en est qu'au tout début.
* Possibilité de choisir sa formule dans les paramétrages généraux via une liste déroulante
* Formule de calcul de l'université de Montpellier
* Formule de calcul de l'université du Havre
* Formule de calcul de l'université de Nanterre
* Formule de calcul de l'université de Bretagne Occidentale
* Formule de calcul de l'Ensicaen
* Interface de test de la formule de calcul directmement intégrée dans OSE
* Interface d'administration des motifs de modification de service dû
* Interface d'administration des domaines fonctionnels
* Installation possible via Docker d'une version de développement ou de test
* Changements d'organisation des fichiers du projet
    * Les fichiers liés à la base de données sont maintenant placés dans /bdd. Un sous-répertoire update recense tous les
    fichiers de mises à jour de base de données liés aux nouvelles versions
    * Le fichier [bdd/install.sql](bdd/install.sql) est à injecter dans un schéma de base de données vide pour toute nouvelle installation de OSE.
    * Le dossier data/cache s'appelle maintenant directement /cache
    * Les connecteurs sont maintenant placés dans un dossier /connecteurs
* Les procédures d'installation et de mise à jour sont disposibles également dans le Gitlab 
(Cf. [Procédure d'installation](INSTALL.md) et [Procédure de mise à jour](UPDATE.md))
* Nouveau privilège permettant de modifier des services après côture, même en cas de mise en paiement (à réserver à des gestionnaires avertis des conséquences sur les paiements)
* Dans les paramètres généraux, il n'est plus nécessaire de renseigner qui est le DRH de l'établissement
* La recherche d'intervenants saisis directement dans OSE fonctionne maintenant même sans avoir de vue source Intervenant
* Les fonctions référentielles peuvent être regroupées par types, avec des plafonds et indicateurs associés
* Un plafond par composante a été ajouté pour le référentiel.
* Pour suivre l'évolution de la règlementation des heures supplémentaires, l'export Winpaye a été modifié : 
le code retenue est passé à 2251 pour les vacataires. Les permanents restent à 0204.

## Notes de mise à jour

* Modifiez la structure de votre base de données en exécutant dans SQL developer le script de mise à jour suivant :
[bdd/update/08.1.sql](bdd/update/08.1.sql)
Les mises à jour [`bdd/update/08.0.1.sql`](bdd/update/08.0.1.sql) et [`bdd/update/08.0.3.sql`](bdd/update/08.0.3.sql) sont inclues dans le précédent fichier.
Inutile, donc, de les exécuter si vous mettez à jour depuis la 8.0.
* Attention : le dossier de cache est déplacé de /data/cache vers /cache. Attention à bien donner à l'utilisateur Apache les droits d'écriture dans le dossier de cache. 
Vous pourrez supprimer manuellement l'ancien dossier /data/cache qui n'a plus d'utilité.
* Attention : au niveau de votre configuration Apache, APPLICATION_ENV peut prendre désormais trois valeurs possibles :
dev,test ou prod. Les anciennes valeurs development et production doivent donc être respectivement remplacées par dev et prod.

* Si vous avez déjà installé une des versions 8.1beta, je vous invite à exécuter le script requête par requête. Les packages, les vues et les triggers pourront être
mis à jour sans aucun soucis. Par contre, veillez bien à ne pas insérer deux fois les mêmes données, ou bien à ne pas tenter de créer deux fois les mêmes colonnes ou les mêmes clés étrangères.
Le mieux est de dupliquer votre instance de production en test si vous en avez une, puis ensuite d'appliquer la mise à jour avec la dernière version disponible.

* La vue export Winpaye a légèrement évolué : le code retenue n'est désormais plus le même pour les vacataires et les permanents (cf. nouveautés ci-dessus). 
Cela devrait n'avoir aucune incidence sur vos paramétrages d'états de sortie Winpaye.

* Pour les universités de Bretagne Occidentale et de Nanterre : votre formule de calcul nécessite des vues spécifiques (V_FORMULE_LOCAL_*) 
qui vous ont été fournies, à implanter dans votre base de données. Veillez à bien implanter ces vues également sur votre serveur de production.

# OSE 8.0.3

## Corrections de bugs

* L'export CSV global des paiements refonctionne
* Le tri des demandes de mises en paiements et mises en paiement se fait de nouveau par intervenant
* Dans Gestion/Paiement/Mises en paiement, les mises en paiement fonctionnent à nouveau
* La date et l'heure situées en bas de page de l'état de paiement sont maintenant bien à jour

# OSE 8.0.2

## Corrections de bugs

* Dans l'onglet Services, le filtre par intervenant fonctionne à nouveau
* Dans la page des mises en paiement (menu gestion), si on a un rôle de périmètre composante, le bug n'affichant plus la structure est résolu


# OSE 8.0.1

## Corrections de bugs

* La mise en paiement est de nouveau accessible
* L'export CSV des services est de nouveau accessible
* L'état de sortie Winpaie comportait une colonne utilisée pour des tests qui a été corrigée

## Notes de mise à jour

* Modifiez la structure de votre base de données en exécutant dans SQL developer le script de mise à jour suivant :
`data/Mises à jour/08.0.1.sql`


# OSE 8.0

## Corrections de bugs

* Lors de la saisie de service, si on sélectionne une étape, tous les éléments associés remontent dans le formolaire et non les 100 premiers comme avant.
* La validation des services tentait de valider des heures déjà validées dans certains cas.
* Les données personnelles ne pouvaient pas être enregistrées si les intervenants avaient plusieurs adresses.

## Nouveautés

* Possibilité de récupérer les libellés de labos dans les affectations de recherche. 
Attention : pour en bénéficier, il faudra mettre à jour votre connecteur affectation_recherche
en vous inspirant de l'exemple fourni dans le code source de OSE (data/Déploiement/Connecteurs/Connecteurs OSE.sql). 

* Interface de paramétrage des centres de coûts dans le menu Administration.

* Paramétrages généraux : la composante représentant l'université (de niveau 1 donc) peut maintenant se paramétrer directement 
dans l'interface d'administration.

* Refonte de l'infrastructure de gestion de la formule de calcul. Les performances ont été très sensiblement améliorées (x100).

* L'installation de OSE via Gitlab passe désormais par HTTPS au lieu de SSH. La clé de déploiement n'est plus nécessaire.

* Nouveau système d'états de sorties personnalisables. Les documents concernés sont :
  * L'export Winpaie
  * Les états de paiement et états de demandes de mise en paiement

* Amélioration de l'interface d'administration des types d'intervention : possibilité de définir des taux spécifiques par statut d'intervenant (pour le TP hors service par exemple)

## Notes de mise à jour

* Modifiez la structure de votre base de données en exécutant dans SQL developer le script de mise à jour suivant :
`data/Mises à jour/08.0.sql`

* Mettez à jour vos paramètres généraux de configuration (Menu Administration/Paramétrages/Paramètres généraux).

# OSE 7.0.6

## Corrections de bugs

* Faille de sécurité qui permettait à un intervenant de visualiser les données d'autres intervenants en changeant le code dans l'URL corrigée.

# OSE 7.0.5

## Corrections de bugs

* Correction au niveau du plafond "Heures max. de référentiel par intervenant selon son statut" : les heures de FC majorées ne sont plus prises en compte.
* La génération de contrat n'éditait plus qu'un seul exemplaire (depuis la v7.0.4). C'est réparé.
* De nombreux libellés "StructureService" ont été remplacés par "Structure", "Service" ayant été ajouté par erreur.
* Le mode de saisie de service Calendaire ne fonctionnait pas. Il a été réparé.
* Dans la fiche intervenant, d'anciennes adresses s'affichaient parfois à la place des actuelles.
* La saisie d'intervenants depuis le menu "Services" fonctionne de nouveau pleinement, y compris avec des intervenants dont les codes comportent des lettres.
* Lors de la saisie de service référentiel, le système de sélection des composantes fonctionne de nouveau.
* Sur la page  de validation du service réalisé d'un intervenant, le rappel des volumes horaires prévus et validés s'affiche correctement

# OSE 7.0.4

## Nouveautés

* Possibilité d'éditer 1, 2 ou 3 exemplaires d'un contrat à partir d'un modèle
* Unoconv est maintenant utilisé sans besoin de faire appel à SUDO.

## Corrections de bugs

* Les intervenants peuvent maintenant avoir un code composé de lettres, plus seulement de chiffres
* Le report des heures de l'année précédente vers l'année en cours actualise maintenant automatiquement la feuille de route de l'intervenant
* Les plafonds bloquants ne peuvent plus être dépassés lors du report  du service prévisionnel de l'année dernière vers l'année actuelle
* Les plafonds bloquants ne peuvent plus être dépassés lors de l'initialisation du réalisé à partir du prévisionnel
* Lors de l'initialisation du service prévisionnel à partir de celui de l'année dernière, les heures portant sur des services précédemment historisés s'importent aussi dorénavant.
* Lors de la génération de contrat, le bug "Uncaught TypeError: Return value of getVariables must be of the type array, null returned"
 qui se produisait si aucune variable n'était positionnée dans les en-têtes ou pieds de page a été résolu.
* L'ergonomie du formulaire d'édition des modèles de contrats a été revue afin de mieux vous guider
* Faille de sécurité corrigée : le sudo n'est plus obligatoire pour utiliser unoconv

# OSE 7.0.3

## Corrections de bugs

* Le modèle de contrat est désormais téléchargeable depuis l'interface d'administration.
* Un bug affectant l'affichage de la page "Calcul HETD" dans certains cas est résolu.

# OSE 7.0.2

## Corrections de bugs

* En mode calendaire, le formulaire de saisie de service réalisé ne s'affichait plus.

# OSE 7.0.1

## Corrections de bugs

* La génération des contrats ne fonctionnait pas en mode production.
* Idem pour télécharger des modèles de contrats.

## Notes de mise à jour

Pas de BDD à mettre à jour, juste le code de l'application en lancant la commande /bin/ose update.

# OSE 7.0

## Corrections de bugs

* La page "Contact" est maintenant personnalisable.

* Le bug empêchant de saisir de nouveaux services depuis la page "Services" est résolu.

* Les filtres portant sur l'export des services (CSV et PDF) refonctionnent.

* L'affichage des détails d'une composante (lorsqu'on clique sur son nom) a été réparé.

* Administration des fonctions référentielles :
Les composantes supprimées n'apparaissent maintenant plus dans la liste. Apparaissent en plus les composantes sans enseignements (pour lesquelles il peut y avoir du référentiel).

* Page d'accès aux contrats qui affichait une erreur si des services étaient validés dans une composante supplémentaire sans que l'agrément du conseil restreint ne soit saisi.

* Dans certains cas, le passage des heures du prévisionnel au réalisé ne fonctionnait pas.

* Les utilisateurs LDAP qui n'ont pas de SupannEmpId peuvent maintenant se connecter à OSE sans soucis

* Lorsqu'un clique sur une composante dans le service référentiel, l'affichage du détail ne plante plus.

## Nouveautés

* Synchronisation possible de services et des volumes horaires associés (en vue d'import depuis ADE)

* Possibilité de définir des volumes horaires enseignement et référentiel comme auto-validés.

* Possibilité de déterminer si les heures de service pour un type de formation donné peuvent être comptées dans le service statutaire ou non. 

* Possibilité de déterminer si les heures pour une fonction référentielle donnée peuvent être comptées dans le service statutaire ou non.

* Possibilité de basculer OSE en mode calendaire ou en mode semestriel (par défaut) selon qu'on soit en prévisionnel ou en réalisé.

* Mise en place d'une interface de saisie d'heures en mode calendaire

* Possibilité de personnaliser le modèle de contrat de travail et d'avenant.
    * Un modèle générique est à votre disposition dans le dossier data de l'application (format OpenDocument Texte à adapter avec l'application LibreOffice).
    * Les modèles peuvent être spécifiques à une composante donnée ou bien à un statut donné (ou les deux)
    * Un système de variables permet de réaliser un publipostage à partir des données de contrat pour les injecter dans le document
    * Vous pouvez personnaliser les requêtes afin d'injecter dans le modèle les données de votre choix
    * Vous pouvez créer de nouvelles variables comme bon vous semble
    * Une interface d'administration vous permettra de configurer vos modèles

* il est désormais possible, via la gestion des privilèges, d'interdire à des statuts ou des rôles de générer de nouveaux contrats et/ou avenants.
    
* Le pays "France" peut maintenant être sélectionné dans la configuration globale de l'application

* Formulaire de saisie de services amélioré :
    * Le choix de l'intervenant n'apparait plus lorsque  l'on se trouve déjà dans la fiche de l'intervenant
    * La saisie des volumes horaires n'est affichée que lorsque c'est nécessaire (plus de liste de tous les types d'intervention affichés par défaut).
    * Il est désormais possible de limiter les types d'intervention disponibles pour saisir des services à l'extérieur
     (paramétrage possible depuis Administration/Types d'intervention)

* Dans le module Charges, il est désormais possible de modifier/créer des scénarios de niveau établissement

* Meilleures possibilités de personnalisation :
    * page contacts, 
    * adresse mail d'envoi, 
    * icône de l'application, 
    * URL des documentations permanents et vacataires dans les paramètres généraux.
    * possibilité de préciser des informations lorsqu'un recherche une personne pour affectation à OSE

## Notes de mise à jour

* Modifiez la structure de votre base de données en exécutant dans SQL developer le script de mise à jour suivant :
`data/Mises à jour/07.0.sql`

* Dans les paramètres de configuration (Menu Administration/Paramètres généraux), 
veuillez sélectionner "FRANCE" dans le bloc "Divers" (en bas à gauche de la page). 
**Attention** : si ce n'est pas fait, la page "données personnelles" des intervenants ne pourra pas s'afficher.

* De nouveaux paramètres de configuration ont été ajoutés dans le fichier de configuration global de l'application
`/config.local.php`. A vous de les ajouter manuellement à votre fichier de confguration existant.
Au besoin, le modèle est le fichier `/config.local.php.default`.

Les paramètres à ajouter puis personnaliser sont :

| Rubrique        | Paramètre       | Description                         |
| --------------- | --------------- | ----------------------------------- |
| etablissement | contact | Personnalisation du contenu de votre page "contact" (code HTML) |
| etablissement | icones | Personnaliser l'icône de l'application |
| mail | from | préciser l'adresse d'expéditeur des mails de OSE |
| ldap | utilisateurExtraMasque | Masque de données à afficher lorsqu'on recherche une personne en vue de lui créer une affectation | 
| ldap | utilisateurExtraAttributes | Attributs LDAP à fournir pour le masque ci-dessus |

* Pour la génération des contrats de travail, veillez bien à installer Unoconv sur votre serveur.
Pour plus d'informations, merci de vous rendre à la page "Procédure d'installation" où tout y est expliqué.

* De nouveaux privilèges liés aux contrats de travail ont été créés. Il vous revient de les attribuer aux rôles et statuts de votre souhait.
Sans cela, les fonctionnalités associées ne seront pas accessibles.


# OSE 6.3.2

## Corrections de bugs

* Mise à jour des tableaux de bord lancée depuis le CRON plus fiable : si un tableau de bord plante, 
les autres sont tout de même mis à jour.

* La personnalisation des liens informatique et libertés et mentions légales refonctionne

* Correction définitive du bug de saisie du service référentiel

* Accès données personnelles : correction d'un bug portant sur certains vacataires affichant une page d'erreur.

* Accès données personnelles : le dossier était accessible même si l'intervenant concerné ne devait pas avoir de dossier.

* Données personnelles : les statuts supprimés étaient toujours disponibles au choix.

* Clôture des services réalisés : les tableaux de bord se mettent à jour automatiquement, ce qui n'était pas le cas avant.

* Lors de la déconnexion, lorsqu'on est cassifié, l'application met complètement fin à la session de 
l'utilisateur, ce qui n'était pas le cas avant où on pouvais se reconnecter sans avoir à se ré-authentifier.

* Correction de bug faisant planter la saisie d'un nouveau service depuis la page "Enseignements".

## Nouveautés

* Pièces justificatives : vous avez la possibilité de personnaliser les documents types à télécharger, y compris au moyen d'URLs.

## Notes de mise à jour

* PHP 7.0.x est obligatoire. Les versions supérieures de PHP ne sont pas supportées pour le moment.
* Une DeployKey générique est intégrée dans le script d'installation de OSE. Il n'est maintenant plus nécessaire de 
déployer une clé nominative sur un serveur.

# OSE 6.3.1

## Corrections de bugs

* Suppression du bug empêchant de saisir des services référentiels si la structure "UNIV" n'existe pas.

* Correction de bug empêchant la modification d'heures de service déjà validés dans certains cas.

* Affichage d'un message d'erreur explicite si l'établissement n'est pas renseigné dans les paramètres.

* La notification gênante est supprimée lors du dépôt de pièces justificatives

## Notes de mise à jour

* Si vous mettez à jour à partir de la version 6.3, pas de changement en base de données.
Toutefois, le dépôt de OSE a changé. 
Il faut donc mettre à jour votre configuration via ces trois étapes :
    1. Dans le dossier de OSE, exécutez en ligne de commande :
  `git remote set-url origin git@git.unicaen.fr:open-source/OSE.git`
    2. Puis `git checkout tags/6.3.1`
    3. Suivez ensuite la procédure de [mise à jour](https://ose.unicaen.fr/deploiement/mise-a-jour.html) normale.

* Si vous n'avez pas installé la version 6.3, alors merci d'installer la 6.3.1 avec la procédure de mise à jour 
indiquée pour la 6.3 (ci-dessous).
N'oubliez pas d'exécuter les requêtes SQL de mise à jour (`data/Mises à jour/06.3.sql`)!


# OSE 6.3 

## Corrections de bugs

* Suppression de la vue `V_TYPE_INTERVENTION_REGLE_EP` qui ne compilait pas.

* Ajout de la dépendance à BCMath (extension PHP à installer).

* Ajout de la dépendance à GD (extension PHP à installer).

* La validation du numéro INSEE dans les données personnelles était incorrecte dans certains cas.

* Recherche d'intervenants inopérante (se produisant uniquement avec PHP 7.1).

* Connecteurs : les disciplines remontent aussi pour les intervenants ayant une fin d'affectation en cours d'année.

* L'affichage de l'interface d'administration des années ne fonctionnait plus.

* à l'installation : toutes les tables étaient considérées par défaut comme synchronisées, ce qui n'était pas le cas.

* Dans l'export CSV des services, le libellé de formation n'apparaissait plus.

## Nouveautés

* Personnalisation : vous pouvez maintenant adapter entièrement l'interface de OSE 
à votre établissement (finies les références explicites à l'Université de Caen dans l'interface)
L'apparence de l'application peut également être personnalisée en fournissant une URL qui
pointe vers une feuille de style CSS propre à votre établissement.

* Nouvel outil en ligne de comande permettant de piloter OSE ( `<dossier OSE>/bin/ose help` pour la liste des possibilités)

* Nouvelle procédure d'installation (https://ose.unicaen.fr/deploiement)
Le déploiement de OSE se fait désormais directement à partir de sa plateforme GitLab.

* Nouvelle procédure de mise à jour à partir de GitLab.

* Suppression des anciens Jobs Oracle et remplacement par des tâches CRON

* Possibilité de changer le mot de passe d'un utilisateur local depuis la ligne de commande

## Notes de mise à jour

* Sauvegardez votre fichier de configuration local (`config/application.local.php`)
dans un autre répertoire que celui de OSE.

* Du fait de la mise en place d'une nouvelle procédure de mise à jour, l'application doit être
réinstallée (uniquement les fichiers, pas la base de données). 
Supprimez complètement le répertoire OSE.

* Procédez à une nouvelle installation (procédure ici : https://ose.unicaen.fr/deploiement/install).
Si vous déployez au même endroit qu'avant, la configuration d'Apache ne devra pas être modifiée.
La base de données n'a pas non plus besoin d'être réinstallée.

* Réintégrez votre fichier de configuration locale.
Attention : **ce dernier a changé d'emplacement**. De `config/application.local.php` il est devenu `config.local.php`.
Le fichier n'est donc plus placé dans le répertoire `config`, mais à la **racine** du répertoire OSE!!

* Le fichier de configuration locale (`config.local.php`) doit être modifié.
  La rubrique `'liens'` (Liens divers) n'a plus d'utilité et doit être supprimée.

  A sa place, copiez-collez dans votre fichier la rubrique `'etablissement'` en provenance 
  du fichier `config/application.local.dist`, puis personnalisez-la.

* Modifiez la structure de votre base de données en exécutant dans SQL developer le script de mise à jour suivant :
`data/Mises à jour/06.3.sql`

* Modifiez votre configuration Apache pour supprimer la ligne suivante, qui n'est plus nécessaire :
`Alias /ose/vendor/unicaen/app	/var/www/ose/vendor/unicaen/app/public`
`/ose/` et `/var/www/ose/` sont à adapter selon votre configuration.
Pour plus d'informations, merci de vous reporter à la [procédure d'installation](https://ose.unicaen.fr/deploiement/install.html).

* Créez les tâches CRON suivantes si elles n'existent pas déjà :
    * notifier-indicateurs
    * synchronisation
    * chargens-calcul-effectifs
    * calcul-tableaux-bord
    * formule-calcul

Pour cela, se référer à la procédure d'installation de OSE.