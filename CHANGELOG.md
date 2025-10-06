# Version stable

OSE [24.11](#ose-2411-06102025)


# OSE 25 (à venir)

## Nouveautés

* Nouveau workflow plus souple et compréhensible
    * Possibilité de personnaliser l'ordre des étapes, dans une certaine mesure
    * Menus intervenant remaniés et plus cohérents

* Données personnelles
    * Possibilité de collecter les données personnelles en deux étapes : en cours de recrutement et finalisation après recrutement

* Pièces justificatives
    * Possibilité de collecter les pièces justificatives en deux étapes : en cours de recrutement et finalisation après recrutement

* Possibilité d'exploiter 2 états de sortie différents via le menu "Services"

## Corrections

* Le formulaire de saisie de mission ne propose plus de mission par défaut, le selecteur est vide pour éviter les conflits de type de mission et de taux (#56779)

## Notes de mise à jour

Passage à PHP 8.4 : Une fois passé en version 25, Vous devrez monter en version 8.4 de PHP.
Pensez à installer l'extention php calendar si ce n'est pas déjà le cas.



# OSE 24.11 (06/10/2025)

## Nouveautés

* Nouvel état de sortie pour l'offre de formation permettant d'affiner par rapport au besoin de chaque établissement l'export de l'offre de formation

## Corrections

* Bonne prise en compte de l'ordonnancement des statuts au niveau des données personnelles (#63380)
* Correction sur les pieces jointes demandées par rapport à un seuil d'HETD (#62910)
* Sur le formulaire de saisie d'enseignement, la recherche d'enseignement se fait bien sur le code et non le source_code
* Correction sur le formulaire d'édition d'un groupe type de formation
* Afficher les demandes de mise en paiement d'une sous structure lorsqu'on possède un rôle de structure parente à celles-ci (#63508)
* Correction pour masquer les candidatures des autres étudiants sur le détail d'une offre d'emploi
* Demandes de mises en paiement : lien rétabli entre les composantes actrices et les centres de coûts (#63491)

# OSE 24.10 (15/09/2025)

## Corrections

* Correction des vues pour les indicateurs 241 et 231 (#63193)
* Correction des problèmes de warnings introduits en 24.9 (#62443)
* Amélioration des performances pour le calcul des formules du Havre & Lille
* Test formules / export CSV : les colonnes non payable & service statutaire sont maintenant dans le bon ordre (#62553)
* Les plafonds de périmètres élément pédagogique sont maintenant fonctionnels (#60536)
* Remontée de la composante d'affectation si celle du contrat est null dans les indicateurs 461, 470, 471, 480, 481


# OSE 24.9 (09/09/2025)

## Nouveautés

* Ajout d'indicateurs (231 et 241) pour les permanents et les vacataires pour gérer les pièces justificatives facultatives (#62574)
* Possibilité de filtrer les demandes de pièces jointes par rapport à de la FA (#63083)
* Nouvelle formule pour La Rochelle (#61573)
* Nouvelle formule pour Lyon 1 (#56775)
* Nouvelle formule pour Picardie (#60997)
* Nouvelle formule pour Paris 8 (#48203)

## Améliorations

* Passage de 25 à 50 caractères autorisés pour les libellés courts des structures

## Corrections

* Correction demande de mise en paiement avec paiement par la composante d'affectation (#62759)
* L'affichage de la saisie de services fonctionne à nouveau avec des enseignements pris sur l'extérieur (pb arrivé en 24.8)
* Correction d'un problème sur le refus d'une candidature dans le cadre des missions (#63060)
* Correction d'un problème de création automatique de la mission lors de l'acceptation d'une candidature (#63060)
* Correction sur la saisie d'enseignement hors établissement (#63120)
* Remontée de la composante d'affectation si celle du contrat est null dans les indicateurs 450 et 460
* Correction de la formule du Havre (sur les anciennes années) (#62443)
* Correction de la requête d'alimentation de la formule de Lyon 3 (#38136)


# OSE 24.8 (23/07/2025)

## Nouveautés

* Saisie de service d'enseignement
    * Possibilité de renseigner précisément l'étape d'enseignement en cas d'élément pédagogique mutualisé (#53620)

* Nouvelle infrastructure de déploiement Docker pour le dév et la prod (pas d'impact sur les procédures actuelles)

## Corrections

* Le report de paramétrages sur les années suivantes fonctionne de nouveau pour les statuts & les taux
* Les taux personnalisés par mission sont maintenant correctement exploités dans les contrats de travail
* Dans la page de demande de mise en paiement, tenir compte de l'historisation de la table centre_cout_structure, pour filtrer les centres de coûts proposés (#62665)
* Les heures de formation des missions remontent correctement dans v_contrat_main pour être affichées dans le contrat.


# OSE 24.7 (08/07/2025)

## Améliorations

* Nouvelle formule de calcul pour Savoie Mont Blanc
* Nouvelle version de la formule de calcul du Havre
* Nouvelle version de la formule de Dauphine
* Nouvelle version de la formule de Paris 8
* Nouvelle version de la formule de La Rochelle
* Nouvelle version de la formule de Brest

## Corrections

* Lors de l'ajout d'une première heure d'enseignement depuis l'application, les formules se recalculent de nouveau correctement : le problème empêchait les feuilles de route de se mettre à jour.
* [Problème sur 27 tableurs de formules de calcul : la détection de structure université ne fonctionnait pas. Impact limité au téléversement de feuilles de calcul sur l'IHM de test.](https://git.unicaen.fr/open-source/OSE/-/commit/2c458ff613f295e2399ea053b83f11b4c3820ccc)
* Correction privilege visualisation des candidatures d'une offre d'emploi (#59099)
* Pouvoir refuser une candidature même si l'étudiant n'a pas renseigné à 100% ses données persos et ses pièces justificatives (#62327)
* En mode "utiliser les centres de coûts de la composante d'affectation" pour les paiements, dans le cas des vacataires, la composante d'enseignement reste celle utilisée (#62447)

## Notes de mise à jour

Note à destination des administrateurs fonctionnels.
En ce qui concerne le souci de report de paramétrages sur les années suivantes pour les statuts & les taux,
ce denier ne concerne que les établissements ayant installé OSE durant les deux dernières années (à partir d'une base de données vide, donc).
Pour vous, il se peut que les paramétrages faits pour l'année 2024/2025 n'aient pas été répertutés sur les années suivantes.
Nous vous invitons :
- à migrer en version 24.7 si vous êtes en version 23 ou 24 ;
- à vous positionner sur l'année 2025/2026 ;
- à bien vérifier que vos paramétrages sont corrects, au niveau des statuts & des taux ;
- à modifier vos paramétrages s'ils ne sont pas bons : la résolution du problème fait que vos actions seront bien répercutées sur les années à venir.


# OSE 24.6 (17/06/2025)

## Améliorations

* Nouvelle formule pour La Rochelle
* En cas de saisie calendaire d'une heure débutant à 00:00, seule la date est affichée, l'heure étant considérée comme non saisie
* En mode calendaire, la saisie des dates de début et de fin sont obligatoires

## Corrections

* La version 24 ne comptabilisait pas tous les volumes horaires de référentiel pour les paiements dans certaines situations
* Les fenêtres à bulles ("popvers") s'affichent maintenant tojours à l'intérieur de l'écran





# OSE 24.5 (SORTIE ANNULEE)

## Amélioration

* Nouvelle formule pour La Rochelle

## Correction

* La version 24 ne comptabilisait pas tous les volumes horaires de référentiel pour les paiements dans certaines situations

**Attention** La version 24.5 a été annulée, le correctif entraine des régressions pouvant conduire à faire trop de demandes de mise en paiement


# OSE 24.4 (04/06/2025)

## Améliorations

* Possibilité de lancer la commande [calcul-tableaux-bord](doc/ligne-de-commande.md) en précisant un tableau de bord en particulier à actualiser et une année ou un intervenant précis

## Corrections

* Traducteur de formules de calcul : résolution de problème au niveau des SI embarqués dans des paramètres de fonctions (#50652)
* Correction des demandes de mise en paiement pour pouvoir gérer la notion de budget sur des types de ressources autres que de la "paie état" ou des "ressources propres" (#61953)
* Correction sur les demandes de mise en paiement pour prendre en compte le paiement d'heures faites dans un établissement extérieur.
* Formules de calcul : correction d'un problème important prenant en compte des heures non payables dans des calculs intermédiaires (#61947).
* Formules de calcul : correction d'un problème qui empêchait de prendre en compte les paramètres personnalisés (#60719)


# OSE 24.3 (22/05/2025)

## Améliorations

* Possibilité d'utiliser la signature électronique (esup signature) avec un wildcard token de paramétré
* Traducteur de formules de calcul : gestion des SI embarqués dans des paramètres de fonctions
* Nouvelle version de la formule de calcul de Paris Dauphine (#50652)
* Formule de calcul "par défaut" disponible pour tous les établissements qui le souhaitent ou bien en attente de leur formule personnalisée
* Les mises à jour OSE ne pourront plus être bloquées par des modifications locales : elles seront écrasées
* Nouvelle formule de calcul pour Lyon 3 (#38136)

## Corrections

* Correction d'un bug lorsqu'un vacataire change d'année universitaire via le menu année en haut à gauche
* Prise en compte du paramétrage d'un centre de coût par défaut sur un élement pédagogique au niveau de la page de demande de mise en paiement
* Ajout du bouton "Nouvel employeur" qui avait disparu dans Administration > Nomenclatures > Employeurs
* Correction pour aller chercher la bonne valeur du taux lors d'une prise en charge ou d'un renouvellement dans SIHAM (#61928)
* Correction d'un problème qui empêchait la création d'un nouveau taux de rémunération
* La formule de calcul de Lyon 2 est de nouveau opérationnelle (#61925)

## Notes de mise à jour

* Veillez à retester le workflow au nouveau des règles d'accès aux contrats
* Bien lire celles de la 24.1
* Si vous voulez monter en version depuis la version 23, lire celles de la 24.0




# OSE 24.2 (15/05/2025)

## Corrections

* Correction de plusieurs soucis bloquant la génération des contrats

## Notes de mise à jour

* Veillez à retester le workflow au nouveau des règles d'accès aux contrats
* Bien lire celles de la 24.1
* Si vous voulez monter en version depuis la version 23, lire celles de la 24.0


# OSE 24.1 (15/05/2025)

## Améliorations

* La clôture des services d'enseignement est désormais possible pour les intervenants avec fiche de services en lecture seule (#61700)
* Les agréments par lots sont maintenant triables, filtrables et les disciplines sont affichées (#61456)
* Prise en compte de tous les types d'intervention lors l'export des formations au format CSV(#61727)

## Corrections

* L'état de sortie des paiements est de nouveau opérationnel avec des primes (#61568)
* Meilleure présentation des résultats au niveau de l'arrondisseur de calcul HETD : les sommes sont toutes recalculées
* Arrondisseur de règle de calcul HETD corrigé pour être le plus compatible possible aux résultats de l'ancienne infrastructure "formules"
* Pour les demandes de mise en paiement, toujours proposer les centres de coûts de la composante d'enseignement pour les vacataires et les étudiants (#61780)
* L'intégration de nouvelles formules de calcul plantait en affichant le code généré
* Correction apportée à la formule de calcul du Havre (#54003)
* Les contrats s'affichent de nouveau avec Oracle23 (#61799)
* Le commande ./bin/ose clear-cache ne supprime plus le répertoire cache, elle se contente de le vider (#61810)
* Correction d'un bug d'affichage lors d'un changement d'onglet sur l'édition d'une structure (#61814)
* Correction d'une régression sur la prise en compte du paramétrage de l'état de sortie pour les indémnités de fin de contrat des missions
* Pour les demandes de mise en paiement, alimenter la liste des centres de coût avec ceux de la composante d'enseignement dans le cas d'un vacataire (#61780)
## Notes de mise à jour

* Vous devez être en version 23.14 minimum afin de pouvoir migrer en 24.1
* Veuillez vérifier vos requêtes liées au plafonds (cf. notes de mise à jour v24.0) : elle doivent être adaptées
* Attention : Si vous étiez en 24.0, il vous faudra relancer le calcul des formules, puis des tableaux de bord avec les commandes suivantes :
```
./bin/ose formule-calcul
./bin/ose calcul-tableaux-bord
```


# OSE 24 (29/04/2025)

## Nouveautés

* Nouvelle infrastructure de gestion des formules de calcul
    * Calcul plus rapide de l'ensemble des fiches, avec ajout de jauges pour le suivi d'exécution en ligne de commande
    * Les tableurs sont tous centralisés dans l'application et accessibles via le menu d'administration (#51993)
    * Possibilité de téléverser par vous-mêmes vos fichiers tableurs (#51994)
    * Les requêtes d'alimentation en données ainsi que les règles de délégation sont maintenant paramétrables directement dans le fichier tableur (#51553)
    * Prise en compte des heures non payables (#23420)
    * Nouvelle page de détail des calculs plus complète (#23421)
    * Meilleure lisibilité du résumé des heures HETD (#23421)
    * Nouveau dispositif de tests des formules, avec possibilité d'exporter en CSV les données, en plus du format JSON existant (#55389)
* Nouvelle page de demandes de mise en paiement (#53922)
* [Signature électronique (support d'Esup Signature pour le moment)](https://git.unicaen.fr/open-source/OSE/-/blob/master/doc/Signature-Electronique/configuration.md?ref_type=heads) (#26825)
* Paramétrage affiné des codes situation pour l'export siham
* Nouvelles possibilités de paramétrage des contrats (#51241)
    * Possibilité de contractualiser du référentiel sans heure d'enseignement (#38876)
    * Possibilité de créer un contrat de mission sur seule prolongation de fin de date de contrat
    * possibilité de créer des contrats multi-missions
    * Possibilité de créer un contrat sans aucune heure
* Les projets sont transformé en avenant dans l'interface et plus seulement après validation
* Remise au propre des vues v_contrat_main et v_contrat_services:
    * Uniformisation des différents noms de variables
* [Possibilité de créer vos propres scripts PHP exploitant OSE](/doc/scripts.md) (#60691)

## Améliorations

* Injection de la situation matrimoniale "Célibataire" par défaut pour l'export SIHAM si celle-ci n'est pas renseignée par l'intervenant (#60066)
* Diverses optimisations rendant l'application plus réactive
* Optimisation de la base de données : reconstruction des indexs systématique lors de chaque mise à jour
* Renforcement de la sécurité avec la mise à jour de plusieurs dépendances du projet
* Nouveau mode en ligne de commande : ./bin/ose vous donne maintenant la liste de toutes commandes possibles

## Corrections

* Correction d'un bug lors de la suppression de référentiel fonction (#59691)
* Correction d'un bug sur la gestion des fonctions référentiels parents (#59063)
* Bug sur l'onglet service avec un utilisateur ayant un rôle avec un périmètre composante (#60291)
* Renforcement pour limiter la validation ou le refus d'une candidature à sa propre composante uniquement (#60566)
* Le différentiel s'affiche correctement dans la page d'administration de l'import (#59394)
* Correction pb aff mois sur OSE Missions (#61452)

## Notes de mise à jour

* **ATTENTION : la version 24 ne peut être installée qu'à partir des versions 23.13 ou supérieures. Pour les versions antérieures, il vous faut préalablement monter en 23.13 minimum.**

* L'opération de migration peut durer assez longtemps : prévoyez jusqu'à 2h de durée d'exécution pour le script de mise à jour.

* Dans cette nouvelle version la commande **bin/ose** a évolué et est maintenant en bash et non en php. Pour son utilisation ponctuelle rien ne change, par contre si vous avez planifié des tâches via crontab, il faudra ajuster celui-ci pour executer **bin/ose** comme une commande bash et non comme un script php :

    * #avant /usr/bin/php /chemin_absolu_vers/bin/ose notifier-indicateurs
      ` *`#après /chemin_absolu_vers/bin/ose notifier-indicateurs`

* Le calcul des heures complémentaires ayant complètement changé, il se peut que sur certaines fiches complexes avec des paiements déjà effectués vous ayez un différentiel qui apparaisse avec quelques centimes à mettre en paiement ou au contraire quelques centimes en trop payé

* Attention à bien vérifier que les requêtes de vos plafonds fonctionnent toujours.
  Exemples de modifications pouvant les impacter :
    * La table formule_resultat a été renommée en formule_resultat_intervenant
    * Les tables formule_resultat_service et formule_resultat_service_ref ont été supprimées
    * Les tables formule_resultat_vh et formule_resultat_vh_ref ont été fusionnées dans formule_resultat_volume_horaire
    * Les colonnes heures_compl_fc_majorees ont été renommées en heures_primes
    * Les colonnes service_referentiel ont été renommées en heures_service_referentiel

* L'état de sortie export des services devra être adapté dans certains cas pour ne plus faire référence à HEURES_COMPL_FC_MAJOREES dans le traitement php de la partie export pdf, mais faire maintenant référence à HEURES_PRIMES.
  Un script de migration est chargé de faire ce travail, mais il ne pourra pas le faire dans tous les cas de figure.

* **ATTENTION à bien adapter vos contrats de travail** aux changements intervenus en particulier sur v_contrat_main. La liste des changements est la suivante :
    * FORMULE_RESULTAT_ID colonne supprimée
    * totalDiviseParDix   colonne supprimée
    * tauxId              colonne supprimée
    * tauxMajoreId        colonne supprimée

    * heuresPeriodeEssai    nouvelle colonne : nombre d'heures concernées par une période d'essai (missions)
    * heuresPrimePrecarite  nouvelle colonne : nombre d'heures relatives à la prime de précarité (missions)
    * missions              nouvelle colonne : liste des libellés des missions, remplace libelleMission
    * typesMission          nouvelle colonne : liste des libelles des types de missions, remplace missionNom
    * missionsTypesMissions nouvelle colonne : liste des missions avec mention des types de missions
    * date_creation         renommée en dateCreation
    * date_contrat_lie      renommée en dateContratLie
    * pays_nationalite      renommée en paysNationalite




# OSE 23.15 (24/04/2025)

## Corrections

* Correction import des numéros de prise en charge pour permettre de rechercher un intervenant sur les 13 premiers caractères de son numéro insee (#61528)
* Modification connecteur pegase pour ignorer certains elements


# OSE 23.14 (05/03/2025)

## Corrections

* **Faille de sécurité importante corrigée** : un intervenant connecté ayant accès aux contrats téléversés pouvait télécharger les contrats d'autres intervenants


# OSE 23.13 (17/02/2025)

## Corrections

* Correction pour injecter le bon taux horaire d'une mission dans le cadre de l'export RH Siham

# OSE 23.12 (26/11/2024)

## Nouveautés

* Possibilité de ne pas inclure le contrat en pièce jointe lors de l'envoi du mail contrat à l'intervenant (#58818)
* Nouvel indicateur 471 permettant de lister les intervenants pour qui l'envoi du contrat par mail n'a pas encore éte effectué
* Possibilité d'ajouter dynamiquement l'url de la page contrat de l'intervenant directement dans le corps du mail d'envoi de contrat (paramètres généraux)
* Possibilité de renseigner l'adresse mail perso d'un intervenant dans le formulaire de création (#58904)


## Améliorations

* Au niveau de la fiche intervenant, de l'onglet notes, distinction dans l'historique des demandes de mise en paiement et des mises en paiement (#58342)
* L'indicateur 210 ne nécessite plus d'avoir obligatoirement un service prévisionnel de renseigné pour remonter les intervenants ayant des pièces justificatives manquantes (#58301)
* Meilleure prise en compte du nombre d'heures contractualisées à transmettre lors d'une prise en charge ou renouvellement ose vers siham
* Persistance de la puce de notification (rouge) de présence de notes sur la fiche d'un intervenant (#59170)

## Corrections de bugs

* Au niveau des missions, le contrat est marqué comme fait s'il a été réellement finalisé avec date de retour signé le cas échéant
* Au niveau de la saisie du suivi des missions, gestion correcte du cas où un même intervenant a plusieurs missions, mais qu'un des contrats n'a pas été finalisé
* Éradication de l'utilisation de certains caractères spéciaux dans le cache de OSE qui faisait planter l'application
* Correction sur la visualisation des candidatures d'une offre d'emploi quand on a le privilège 'Visualiser les candidatures d'une offre' (#59099)


# OSE 23.11 (17/10/2024)

## Corrections de bugs

* Dysfonctionnenent des demandes de mises en paiement par lot suite au filtrage par structure (#58788)

## Nouveautés

* Nouvelle formule pour Bretagne Occidentale (Brest)



# OSE 23.10 (03/10/2024)

## Nouveautés

* Nouveau paramétrage général pour sélectionner l'état de sortie à utiliser pour l'extraction des indémnités de fin de mission.


## Corrections de bugs

* Correction d'un problème intervenu en 23.9 : impossibilité de valider du suivi de mission.
* Correction sur le calcul du montant des indémnités des missions
* L'utilisation de la fonctionnalité de demande de mise en paiement en lot mettait toutes les heures de l'intervenant sélectionné en demandes de mise en paiement sans tenir compte de la composante sélectionnée. (#58607)
* Ajout d'une variable de contrat nommé "numeroAvenant" pour numéroter les éditions d'avenant (#58658)



# OSE 23.9 (23/09/2024)

## Corrections de bugs

* Prise en compte du bon nombre d'heures de la mission lors de l'export RH dans SIHAM
* Bloquer la possiblité de saisir une date antérieure à la date de début dans le cadre d'une mission
* Correction du connecteur Pégase pour pouvoir utiliser les formations comme étapes
* Le filtrage par élément pédagogique de la page des services refonctionne (#58031)

## Améliorations

* Pour l'export RH vers SIHAM, possiblité de renseigner via le fichier de configuration le code categorie de situation et le code motif de situation pour la clôture du dossier dans SIHAM lors d'une prise en charge ou un renouvellement (#58351)
* Nouveau privilège spécifique pour refuser une candidature étudiante, se cumule avec le privilège valider une candidature étudiante.
* Saisie de suivi de missions : la saisie d'horaires se chevauchant est désormais interdite (#57926)
* Modification de la formule de calcul de Lyon 2 (#57423)

## Note de mise à jour

Pour les utilisateurs des missions sous ose et notamment des candidatures, le privilèges "Accepter une candidature" a été scindé en deux avec un nouveau privilège "Refuser une candidature", pensez donc à ajouter ce nouveau privilège à vos utilisateurs pour qu'ils continuent de refuser des candidatures.

# OSE 23.8 (06/09/2024)

## Améliorations

* Modification de la règle de saisie pour les dates de début et de fin de mission afin de laisser un peu de marge pour les cas des missions à cheval sur deux années universitaires : la mission devra être saisie sur l'année universitaire où elle doit être majoritairement réalisée.
* Optimisation de calcul des états des volumes horaires (impact sur les formules, les extractions des services, etc)
* Ajout d'un message sur la page candidature de la feuille de route pour les missions étudiantes, afin d'inciter ceux ci à compléter leurs données personnelles dans le cadre de l'étude de leur candiature (#57927)
* L'indicateur 120 (saisi des données personnelles qui diffèrent de celles importées) prend maintenant en compte le changement ou modification du numéro INSEE (#57995)
* La date d'effet de la situation matrimoniale des données personnelles devient non obligatoire dans le cas d'un célibataire.

## Corrections de bugs

* Plus de message d'erreur lorsqu'on affiche la page de validation des référentiels avec aucun service validé (#57826)
* Seules les structures porteuses d'enseignements sont affichées dans l'onglet offre de formation (#57896)
* Correction du report du service référentiel impacté par l'annualisation des fonctions (#57947)
* Modification du connecteur pégase : Utilisation de "structure_porteuse" si elle existe au lieu de "code_structure" provenant de pégase pour la structure d'un élément
* Correction de l'état de problèmes de calcul sur l'export CSV des missions



# OSE 23.7 (11/07/2024)

## Nouveautés

* Gestion de la situation matrimoniale dans les données personnelles, avec export vers Siham de la donnée collectée. (#56868)

## Améliorations

* Modification des indicateurs relatifs aux missions, pour ne plus filtrer uniquement sur le type intervenant étudiant, mais prendre aussi en compte les missions de vacataires (#57424)
* Refactoring pour plus de cohérence sur le choix de la date d'effet et de fin d'un renouvellement ou d'une prise en charge Siham dans le cadre notamment des missions.
* Dans les demandes de mise en paiement par lot, on filtre maintenant les intervenants trop payés pour une composante donnée (#56770)
* Demande de mise en paiement par lot, classement des intervenants par ordre alphabétique par nom (#56558)
* Bloquer la saisie des dates de début et de fin d'une mission aux bornes de l'année universitaire du contexte de saisie de la mission.

## Corrections de bugs

* Correction d'un problème de suppression d'une mission avec plusieurs volumes horaires prévisionnels



# OSE 23.6 (13/06/2024)

## Nouveautés

* Préversion du connecteur en import Pégase [documentation temporaire ici](https://git.unicaen.fr/open-source/OSE/-/blob/b23/admin/pegase/doc_temporaire/Documentation.txt?ref_type=heads)

## Corrections de bugs

* Les taux majorés personnalisés par mission sont bien affichés dans les contrats de travail
* Pouvoir sélectionner l'ensemble des structures dans le filtre structure de la page offre de formation (#56680)
* Modification de la formule de calcul de Paris 1
* L'assertion des clôtures ne fonctionnait pas correctement : la saisie était interdite systématiquement dans certains cas
* Modification de la formule de calcul de Paris Dauphine (#50652)
* Pour la fonctionnalité "Demande de mise en paiement en lot" les HETD référentiel ne sont plus prises en compte dans le total HETD des heures à payer faute de pouvoir pour le moment paramètrer un centre de coût par défaut pour les fonctions référentiels (#56717)
* Affichage du type de mission et du libellé de la mission dans la page de demandes de mise en paiement (#56869)
* Correction indicateur 570 pour permettre de voir les intervenants avec des validations référentiels en attente en dehors de leur composante d'affectation (#56951)
* Correction indicateur 280 pour enlever les étudiants dont les candidatures ont été refusé
* Correction de la formule de calcul de Paris8 (#48203)
* En cas d'erreur d'enregistrement, s'il y a contrôle de plafond, les messages d'erreur seront de nouveau explicites (#57207)

## Améliorations

* Classement des intervenants par ordre alphabétique dans la page de demande de mise en paiement par lot (#56558)
* Les plafonds des intervenants sont mis à jour automatiquement suite à la saisie de modifications de service dû (#56421)
* En cas de saisie manuelle de nouveau volume horaire d'enseignement (nombre d'heures * nombre de groupes par élément pédagogique), la saisie de service devient possible sans attendre
* Pour les états de paiements, il est de nouveau possible de faire des extractions tous types d'intervenants confondus (#54966)
* Optimisation de la recherche dans l'offre de formation depuis le formulaire de saisie de service (#56847)
* Tri des périodes de paiement et des offres d'emploi du plus récent au plus ancien
* Au niveau de la fonction "Demande de mise en paiement par lot" Ajout d'un raccourci (bouton) permettant d'aller directement aux mises en paiement (#56718)
* Meilleur affichage html du descriptif des offres d'emploi (Ose mission)
* Fiabilisation de la saisie d'un siret/siren lors l'ajout d'un employeur via l'administration (#55462)





# OSE 23.5 (19/04/2024)

## Corrections de bugs

* Procédure d'installation à nouveau fonctionnelle
* Correctif apporté à la formule d'ASSAS (#55357)
* Renforcement du typage des données en entrée pour le chargement en masse des numéros de prise en charge (#56241)
* Contrôle de la présence obligatoire d'un domaine fonctionnel pour la mise en paiement par lot pour les heures de missions et de services référentiel
* Missions : Le gestionnaire peut maintenant sélectionner une sous-structure (#55566)
* Rouen : corrections dans la formule de calcul des HC (#55241)
* La Réunion : corrections dans la formule de calcul des HC (#55792)

## Améliorations

* Pour l'export des imputations budgétaires Siham, l'export proratise maintenant par code indémnité puis centre de coût
* Possibilité de désactiver par statut d'intervenant les étapes "Indémnités de fin de contrat" (Mission étudiante) et "Pièces justificatives (#51245 et #56269)



# OSE 23.4 (26/03/2024)

## Corrections de bugs

* Les modifications de service dû ne sont enregistrées que s'il y a un changement effectif de donnée (#55446)
* Correction sur le privilege d'administration des tags (#55439)
* Empêcher de postuler à une offre d'emploi tant qu'elle n'est pas validée
* Le calcul des paiements s'effectue correctement sur les cas complexes avec des heures négatives
* Impossible de choisir un centre de coût par défaut pour une structure
* Problème de mise en paiement en masse avec les enveloppes budgétaires en ressources propres à 0 (#55672)
* Correction des mises en paiement lors du passage de 'Distinction Fi,Fa,Fc' en 'Tous enseignements confondus', le regroupement n'était pas visuellement correcte (#54144)
* Corrections des formules de calcul de Rouen (#55241)
* Correction d'un problème d'erreur php lors de la migration des fonctions référentielles
* Prise en compte de l'auto-validation des heures prévisionnelles pour le contrat
* Afficher le bon libellé de la composante d'affectation fonctionnelle dans l'onglet Export RH (Siham)

## Améliorations

* Dans la partie administration/structure, La liste des centres de coût par défaut possible pour une structure est maintenant filtrée pour choisir uniquement des centres de coût rattachés à cette structure.
* Adaptation de l'export paie Siham préliquidation pour les missions étudiantes (congés payés)
* Prise en charge de l'extension du numéro de voie (Bis, Ter etc...) dans l'export RH lors d'une PEC ou REN
* Possiblité de paramètrer par défaut la catégorie de contrat via l'export RH Siham



# OSE 23.3 (23/02/2024)

## Corrections de bugs

* Correction sur l'export des imputations budgétaires siham

# OSE 23.2 (23/02/2024)

## Nouveautés

* Formule de calcul de Paris Deauphine (#50652)
* Possibilité de paramétrer un domaine fonctionnel par défaut pour une strucutre (#54962)

## Corrections de bugs

* Modification de la formule de calcul de l'UPEC (#54445)
* La prise en compte du caractère éligible selon le type d'heures est rétablie sur les extractions de paye
* Modification workflow pour permettre la saisie de mission par plusieurs composantes sur un même étudiant (#54487)
* Dysfonctionnement recherche LDAP quand le code utilisateur de l'intervenant fait moins de 8 caractères (#54717)
* La modification de formule de ROUEN demandée pour la 23.1 s'applique désormais uniquement à partir de 2023/2024 (#55241)
* Dysfonctionnement sur la création d'une nouvelle fonction référentiel via l'administration (#55404)



# OSE 23.1 (02/02/2024)

## Nouveautés

* Formule de calcul de Panthéon ASSAS (#51544)

## Corrections de bugs

* Correction sur la bonne prise en compte de la durée de validité d'un agrément par rapport à l'année d'obtention de l'agrément (#54499)
* La version 23.0 introduisait un problème au niveau de la gestion des tags du dépôt GIT qui bloquait toute mise à jour
* Correction d'un script de migration érroné introduit en version 23.0
* Les heures mises en paiement puis dévalidées ou supprimées peuvent à nouveau être visualisées correctement (#54340)
* Arrondi des calculs d'heures de modifications de service dû (#50570)
* Formule de Rouen : résolution de problème de division par zéro (#53987)

## Améliorations

* Modification d'affichage de la date de fin de validité d'un agrément (#54400)
* Ajout de la date d'expiration d'un agrément dans l'export CSV des agréments (#54400)
* La formule de Côte d'Azur gère le service dû avec un taux à 0 pour les types d'intervention (#54508)




# OSE 23 (26/01/2024)

## Nouveautés

* Gestion arbosrescente des structures dans toute l'application (#3268)
* Possibilité d'importer et/ou gérer les numéros de prise en charge des intervenants pour la paie (#15131)
* Possibilité de faire des demandes de mise en paiement par lot (#12584)
* Possibilité de paramétrer l'export OSE/SIHAM pour créer le contrat directement dans SIHAM
* Annualisation des fonctions référentielles

## Améliorations

* Une colonne "Structure" a été ajoutée à l'export de l'offre de formation
* Activation de la saisie de service hors établissement, en fonction de l'option de statut "L'intervenant pourra assurer des services dans d'autres établissements" et non par rapport au type d'intervenant (#54004)
* Adaptation des exports de paie (winpaie et siham) pour gérer le paiement des congés payés dans le cadre des missions étudiantes
* Export Ose/Siham : Auto validation de la clôture d'un dossier d'un agent
* Possibilité d'ajouter un centre de coût par défaut au niveau de la structure

## Corrections

* Ajout d'un contrôle au niveau des données personnelles,  pour empêcher de mettre un statut d'une année différente de l'intervenant (#53668)
* Prise en compte des dates bornées d'une année universitaire dans le contrôle de saisie de service en mode calendaire (#53947)
* Modification de la formule de calcul de Paris 8
* Modification de la formule de calcul de Picardie
* Correction de la formule de calcul de Rouen
* Correction sur les incohérences du nombre d'heures sur l'export des imputations budgétaires SIHAM (#53098)
* Affichage inversé FI FA dans l'administration des types d'activité des centres de coûts (#54059)
* Correction des dates de saisies d'heures lors d'un changement de mois #54005

## Notes de mise à jour

À partir la V23 uniquement, PHP 8.2 est maintenant obligatoire.

Pour les établissements qui utilisent le module Export SIHAM : la configuration spécifique du module d'export Siham anciennement mise dans `config/autoload/unicaen-siham.local.php` doit maintenant être mise directement à la racine de OSE dans le fichier de configuration globale config.local.php. (Voir un exemple dans [config.local.php.default](config.local.php.default)). A noter, qu'il est maintenant possible de paramétrer la création du contrat automatiquement dans SIHAM.

Veillez bien à retester tous vos états de sortie si vous les avez personnalisés au niveau des requêtes SQL.
Ceux qui sont filtrables par structure, comme les états de paiements, nécessitent une nouvelle colonne STRUCTURE_IDS qui remonte l'information présente dans la colonne `STRUCTURE.IDS`.

Attention : la version 23.0 introduit deux régressions corrigées en 23.1. Il est donc déconseillé d'installer cette version et de privilégier la 23.1 (ou ultérieure) pour votre montée en version.



# OSE 22.4 (01/12/2023)

## Corrections de bugs

* Depuis la 22.3, les demandes de mise en paiement pouvaient être faites plusieurs fois pour les mêmes heures si on ne distinguait pas FI/FA/FC (#53913)


# OSE 22.3 (30/11/2023)

## Corrections de bugs

* Impossible de saisir du service hors établissement suite à une régression (#53694)
* Report de service dans l'IHM de tests de formules de calcul rétabli (#53684)
* Correction sur le rafraîchissement de l'intervenant lors d'un changement de statut au niveau des données personnelles (#53778)
* Les intervenants ayant un avenant créé sans date de retour signé ne remontent plus dans l'indicateur 430 (#53709)
* En sélectionnant "Non" au paramètre général "Distinction FI/FA/FC des heures à payer", les demandes de mise en paiement pouvaient disparaitre de l'écran des demandes (#53752)
* Les jauges de plafond référentiel s'affichent de nouveau correctement (#53371)
* Les couleurs distinctes sont de nouveau présentes sur les jauges des plafonds (#53371)
* La formule de calcul D'Avignon prend maintenant en compte correctement les heures de référentiel (#36193)
* Administration : Le différentiel au niveau de l'import des données s'affiche de nouveau sans erreur si un filtre est présent  (#53300)
* Correction de problème d'arrondi au niveau des jauges des plafonds
* L'état de sortie listant les privilèges de chaque rôle renvoie les bonnes valeurs pour les administrateurs (#53549)
* Problème de valeurs NULL retourné par la formule de calcul de Rennes 2 résolu (#51135)
* Les messages d'alerte des plafonds à la saisie tiennent maintenant compte des dérogations saisies (#51729)
* Le formulaire de saisie d'enseignement fonctionne à nouveau depuis le menu "Services" (#51903)
* Formule de calcul de l'UBO de nouveau opérationnelle (#53533)
* Les jauges budgétaires des demandes de mise en paiement s'affichent ànouveau correctement (#51066)
* Résolution des soucis de sous-service dans la formule de Paris 8 (#51659)
* Charges d'enseignement : l'affichage de la page des seuils s'affiche de nouveau en ckiquant sur "afficher" (#53580)
* Modifications de service dû : le total tient compte du coefficient multiplicateur (#50570)

## Améliorations

* Ajout d'un contrôle de date sur l'année universitaire sur la saisie en mode calendaire (#53364)
* Nouvelle commande ./bin/ose calcul-feuille-de-route <in intervenant>
* Indicateurs relatifs aux plafonds liés aux charges : ajout du code des enseignements (#47340)
* Modification de la formule de calcul de Rennes 2 (#47753)
* Plus de lien "Mot de passe oublié" affiché sur la fenêtre de connexion non CAS (#51885)
* Mise à jour de la formule de Paris 1 (#48148)

# OSE 22.2 (10/11/2023)

## Corrections de bugs

* La saisie des valeurs de plafonds par composante et par fonction référentielle est de nouveau opérationnelle
* Résolution d'un problème de calcul des mises en paiement pour des services étalés sur plusieurs semestres

## Améliorations

* Meilleur affichage du service référentiel dans les notes de l'intervenant (#53479)
* Les indicateurs 420 et 430 (contrat) remontent maintenant l'ensemble des intervenants (vacataires ou permanents)
* Ajout de l'indicateur 392 pour suivre les indémnités de fin de contrat non validées par les étudiants



# OSE 22.1 (30/10/2023)

## Nouveautés

* Formule de calcul d'Avignon

## Améliorations

* Modification de la formule de calcul de Picardie
* Modification de la formule de calcul de Paris 8 (#48203)
* Ajout des tags dans l'export CSV de services (#51614)

## Corrections de bugs

* Erreur sur la page d'administration des pièces jointes par statut (#53289)
* En mode calendaire, si pas de période d'enseignement définie sur l'élément pédagogique alors le choix du semestre est libre pour la saisie d'heures (#53422)
* La visualisation des heures mises en paiement est de nouveau opérationnelle (#53386)
* Correction de l'indicateur 530 renvoie maintenant correctement vers les fiches des intervenants



# OSE 22 (12/10/2023)

## Nouveautés

* Nouveau mode de calcul des heures à payer
    * Paiements : Gestion fine des changements de valeurs de taux horaires en cours d'année
    * Paiements : Répartition des heures AA/AC tenant compte du semestre des heures réalisées (#45564)
    * Possibilité de personnaliser le ratio AA/AC pour le référentiel (#47972)
    * Mises en paiement possibles pour les missions (emplois étudiants) (#51156)
* Gestion des indemnités de fin de contrat pour les missions étudiantes (#47519)
* Extraction du fichier de paie des indemnités de fin de contrat pour les missions étudiantes
* Nouveau mode de calcul des tableaux de bord de calcul intermédiaires. commande ose build-tableaux-bord supprimée et non remplacée (#51555)
* Saisie de date de commission de recrutement pour accepter les candidatures des missions
* Case à cocher par les étudiants avec un texte règlementaire personnalisable préalablement à la candidature à une offre d'emploi

## Améliorations

* Ajout des dates de dévalidation des données personnelles dans les notes/historique par intervenant
* Ajout du suivi du référentiel dans les notes de l'intervenant (#52478)
* En mode de saisie de service calendaire, la période ainsi que les types d'intervention sont maintenant filtrés par rapport à l'élement pédagogique (#51141)
* Possibilité d'utiliser les indicateurs 550, 560 et 570 même sans activation de la clôture pour les permanents (#50952)

## Corrections de bugs

* Prise en compte de la bonne fin d'année universitaire pour la clôture du dossier d'un vacataire (PEC) dans l'export Ose vers SIHAM (#52484)
* Suppression des notes d'un intervenant avant sa suppression définitive de OSE (#52719)
* Masquer 'Données personnelles' sur le menu de gauche lorsque celles-ci sont désactivées pour l'intervenant (#52479)
* Le bandeau d'heures réalisé sur l'année précédente est bien affiché dans le menu données personelles (#48022)
* Le report du service réalisé validé fonctionne de nouveau pour le référentiel (#53144 et #53144 et #53159)
* L'export ne remonte plus de taux à 1 (#53198)
* Recherche des intervenants avec apostrophe améliorée (#50815)
* Problème de privilèges sur la saisie de l'employeur sur le dossier de l'intervenant (#53126)

## Notes de mise à jour

* Oracle est maintenant requis en version 19 au MINIMUM
* Attention : la table TBL_PAIEMENT a évolué, si vous avez des vues ou des extractions basées sur cette table, vous devrez donc les faire évoluer en conséquence. Doc disponible [ici](doc/export-pilotage.md) :



# OSE 21.3 (15/09/2023)

## Améliorations

* Au niveau des données personnelles, le champ employeur peut être activé et mis en facultatif (#51889)


## Corrections de bugs

* Le filtre par élément pédagogique refonctionne normalement pour l'affichage du résumé des services (#51144)
* Possibilité d'éditer ou ajouter un employeur via l'administration (#52301 et #52261)
* Filtre formation refonctionne normalement au niveau de l'offre de formation
* Les calculs n'ignorent plus certains plafonds dont le montant est fixé par requête (#51991)
* Les libellés des enveloppes budgétaires s'affichent mieux sur la page des demandes de mises en paiement (#51066)
* Système de gestion des heures négatives de la formule de calcul du Havre corrigé (#48972)



# OSE 21.2 (31/08/2023)

## Nouveautés

* Permettre d'activer le champ employeur des données personnelles et le rendre optionnel dans la complétude du dossier (#51889)

## Corrections de bugs

* Prise en compte du filtre formation lors de la recherche d'un enseignement en saisie de service (#51823)
* Correction de V_CONTRATS_SERVICES (#51795)
* Nouveaux problèmes corrigés au niveau du calcul des plafonds



# OSE 21.1 (13/07/2023)

## Corrections de bugs

* Correction de la formule de l'UPEC
* Correction de la formule de Paris 1
* Les structures peuvent de nouveau être ajoutées depuis l'IHM (#51692)
* Plusieurs problèmes ont été corrigés au niveau des plafonds
* Réparation du connecteur Actul => OSE (#51613)

# OSE 21 (07/07/2023)

## Nouveautés

* Nouvelle notion de mission, permettant de [gérer les contrats étudiants](https://redmine.unicaen.fr/Etablissement/dmsf/files/71233/view)
    * Référentiel de missions avec par défaut 8 types de mission proposés et personnalisables via une interface d'administration
    * Gestion des offres d'emploi & des candidatures
    * Nouvelle interface de gestion des missions
    * Nouvelle interface de saisie des suivis de missions
    * Adaptation de la partie paiement pour gérer les heures nocturnes/dimanches/jours fériés
    * Plafonds applicables aux missions avec un nouveau périmètre par type de mission

* Gestion renforcée des taux de paiement
    * Possibilité de gérer de nouveaux taux différents du taux HETD de 42,86€
    * Prise en compte du nouveau taux HETD de 43,50€ pour 2023/2024
    * Nouvelle interface d'administration des taux de paiement
    * Les taux peuvent être indexés sur d'autres taux (le SMIC par exemple)
    * Les taux peuvent être appliqués globalement, par mission, par statut, par élément pédagogique, selon le contexte

* Pièces justificatives
    * Nouveau filtre permettant de ne demander des pièces que pour les étrangers

* Contrats de travail
    * Possibilité de contractualiser des heures de référentiel
    * Possibilité de contractualiser des heures de mission
    * Possibilité d'avoir des états de sortie distincts pour les contrats et pour les avenants, par statut

* Tag
    * Possibilité de mettre des dates de début et de fin d'utilisation pour les tags

* Extraction paie
    * Nouveaux paramétrages par statut permettant de spécifier par statut le code indémnité attendu, le mode de calcul et le type de carte

  **IMPORTANT** : Si aucun de ces paramètres n'est spécifié au niveau des statuts, ce sont les valeurs par défaut habituelles qui seront fournies dans
  l'extraction Winpaie et la préliquidation SIHAM. N'hésitez pas à tester vos extractions de paie.

## Améliorations

* Modification de libellé dans l'affichage de l'offre de formation (#49763)
* Possibilité de modifier manuellement l'email expéditeur pour l'envoi d'email via les indicateurs (#50725)
* Synchronisation du code source de l'intervenant avec l'export SIHAM lors d'une PEC ou REN (#50845)
* Les indicateurs 550, 560 et 570 sont maintenant utilisables même si la clôture n'est pas utilisée par les établissements (#50952)
* Blocage de la saisie de motif de non paiement sur du service référentiel déjà validé (#51180)

## Corrections de bugs

* Il est possible de rentrer une date de retour sur un contrat après avoir téléversé le contrat sans avoir besoin de recharger la page
* Impossibilité de saisir des heures hors établissement (#51483)
* Paris 1 : modification de la formule de calcul (#48148)
* Rennes 2 : modification de la formule de calcul (#51135)
* Filtrage des types d'intervention pour la saisie de service hors établissement (#51512)
* L'enregistrement d'un statut ne possédant pas de contrat se fait correctement lorsqu'il n'y a pas d'état de sortie de saisie (#51400)
* Le bouton de saisie du référentiel apparait maintenant même si la composante de l'intervenant diffère de celle du gestionnaire (#50799)
* Remise en forme de l'écran engagement et liquidation (#50738)



# OSE 20.5 (07/06/2023)

## Correction de bug

* Correction d'une régression introduite en 20.4 empêchant de saisir ou modifier du référentiel

# OSE 20.4 (05/06/2023)

## Corrections de bugs

* Erreur d'affichage du contrat unique avec des heures multi-composantes (#50889 et #50980)
* Problème de saisie de services hors établissement (#50990)
* Formulaire d'édition des états de sortie réparé au niveau de l'affichage des sous-requêtes
* Correction de la formule de Picardie
* Correction de la formule de calcul de Lyon 2
* Correction de la formule de Paris 1

# OSE 20.3 (23/05/2023)

## Nouveautés

* Possibilité de spécifier le mode de saisie des heures (calendaire ou semestriel) par statut d'intervenant
* La Réunion : nouvelle formule appliquée à partir de 2023/2024 uniquement
* Paris 1 Panthéon Sorbonne : nouvelle formule de calcul
* Rennes 2 : nouvelle formule de calcul

## Corrections de bugs

* Meilleure gestion dans l'expérience utilisateur de la saisie des dates de début et de fin dans la saisie de service en mode calendaire (#50508)
* Masquer de la liste de choix d'une étape, les formations historisées lors de l'ajout d'un élément pédagogique à une formation (#48878)
* Possibilité de choisir quel type d'affectation (HIE ou FUN) est remontée/testée lors d'une PEC ou REN (#49954)
* Dans la partie notes intervenant, différenciation entre les demandes de mise en paiement et les mises en paiement (#50081)
* Affichage de la fiche intervenant lorsque la civilité n'est pas renseignée (#50813)
* La saisie de nouvelles heures sur des services existants en passant par le bouton "ajout" fonctionne de nouveau (#50814)
* La structure est obligatoire dans le formulaire de saisie du service référentiel et ne fait plus planter l'application lorsqu'elle n'est pas saisie
* Mauvais affichage des HETD dans l'export des services pour une ligne comportant un Tag (#50091)

## Notes de migration

Si vous créez des intervenants locaux sans leur remplir de données personnelles, de services ou de PJ, OSE les historise. Afin d'éviter cela, il vous faut
modifier le filtre de synchronisation des intervenants.

Vous trouverez plus d'indications ainsi que le filtre en question ici :
https://git.unicaen.fr/open-source/OSE/-/blob/master/doc/Connecteurs-Import/Connecteurs-IMPORT.md#utilisation-pour-contr%C3%B4ler-la-synchronisation-des-intervenants



# OSE 20.2 (28/04/2023)

## Nouveautés

* Nouvelle formule de calcul de Rouen

## Améliorations

* Possibilité d'entrer des dérogations aux plafonds avant d'avoir des heures à plafonner (#46387)

## Corrections de bugs

* Les motifs de modification de service dû supprimés ne peuvent plus être sélectionnés (#50328)
* Messages d'erreur corrigés lors de l'exécution du script de migration de la V20.
* Le bouton prévu=>réalisé s'affiche de nouveau lorsqu'il n'y a pas de contrat (#45643)
* Formule de Poitiers : rétablissement du test pour appliquer l'ancienne formule avant 2022.
* Formule de Picardie : prise en compte des heures négatives (#50471)
* L'interface d'administration des types de formation est de nouveau opérationnelle (#50360)

# OSE 20.1 (04/04/2023)

## Nouveautés

* Ajout de la date de clôture dans la page historique de l'intervenant
* Possibilité de saisir une modification de service dû avec 0 heure (#49764)

## Améliorations

* Passage à 200 caractères max. pour les libellés longs des structures

## Corrections de bugs

* Correction d'une régression de la V20 sur le module Export Siham
* Suppression d'un message d'erreur sur l'envoi de mail via les indicateurs (#49873)
* Formule Paris 8 : correction d'un problème de code de composante
* Formule Poitiers : (#46805)
* Formule La Réunion : (#24229)
* Formule de Picardie:  (#47224)

## Notes de mise à jour

Si vous rencontrez les deux messages d'erreurs suivants, merci de ne pas en tenir compte, ces erreurs n'occasionneront pas de dysfonctionnezmenet de l'
application.

1. Suppression de l'index TYPE_INTERVENTION_CODE_UN
   ORA-02429: cannot drop index used for enforcement of unique/primary key (offset 11
   DROP INDEX TYPE_INTERVENTION_CODE_UN

2. Transformation des modèles de contrats en états de sortie ... Convertion des contrats de travail en états de sortie
   Erreur : ORA-00001: unique constraint (OSE.ETAT_SORTIE_CODE_UN) violated

# OSE 20 (28/02/2023)

## Nouveautés

* Les modèles de contrats de travail sont maintenant gérés comme n'importe quel autre état de sortie
* Reconduction de l'offre de formation pour les éléments de OSE portés par un élément synchronisé
* Possibilité de rentrer un taux de charge par statut d'intervenant
* Ajout d'un choix par statut pour "contrat de travail et avenants" pour laisser la possibilité à l'intervenant de télécharger son contrat en pdf
* Migration technnique vers le framework Bootstrap 5 et modernisation de l'identité visuelle
* Réorganisation du menu "Administration" pour plus de lisibilité
* Filtrage des caractères interdits lors de l'export RH SIHAM (#47267)
* Associer des tags aux services afin de flécher certains financements (#42451)
* Envoyer un email via un indicateur à la fois sur l'email perso et l'email pro de l'intervenant (#48687)
* Nouveaux indicateurs 500 et 505 listant les permanents sans service ni référentiel

## Corrections de bugs

* Sur la page "Services", la sélection d'un élément après selection d'une composante et d'une formation est désormais fonctionnel
* Correction des indicateurs 910 et 920 qui étaient non fonctionnels dans le cas d'une autovalidation ou d'une absence de contrat
* Correction de la suppression d'un role dans la page d'administration des roles.
* Correction mineure sur les notes des intervenants au niveau de l'historique (#46303)
* La durée de vie attendue des pièces justificatives est maintenant celle de l'année en cours et plus celle de l'année de dépôt de la pièce
* Les annulations de mises en paiement sont désormais bien prises en compte dès la première annulation
* Il est désormais possible de clôturer le service réalisé même si aucune heure n'est saisie
* Les indicateurs 530 et 540 ne renvoient plus de vacataires
* Dans les diagrammes du module Charges, la boite de dialogue s'affiche correctement, même avec beaucoup de types d'intervention
* Le plafond relatif aux charges / services saisis est maintenant opérationnel
* Lors de la saisie d'enseignement, les elements pedagogique sur lesquels il est impossible de saisir des heures seront surlignés en rouge
* Il est de nouveau possible de saisir des taux de charge TTC et des taux de charge patronale a virgule
* Modification du filtre des status séléctionnables dans les données personnelles (#48151)
* Lors de la demande de mise en paiement, pouvoir choisir un EOTP même si son centre de coût parent n'est pas de l'activité attendue (pilotage / enseignement) (
  #48286)
* Utilisation prioritaire de l'email personnel des données personnelles pour l'envoi d'email via les indicateurs (#48393)
* Meilleure gestion de la casse lors de la recherche d'un employeur (#48543)
* Ajout d'une contrainte d'unicité sur la colonne code de la table type_intervention (#48727)
* Correction formule d'UVSQ (#47149)
* Et beaucoup d'autres ...

## Notes de mise à jour

* Supprimer la ligne faisant référence à TBL_NOEUD dans Administration/Synchronisation/Tables, table NOEUD, champ "Traitements postérieurs : à exécuter après la
  synchro".
* La génération des contrats de travail ayant été remaniée, veuillez vérifier que vous pouvez générer correctement de nouveaux contrats de travail

Avec l'ajout de la notion de tag sur les services d'enseignement et référentiel, les champs 'TAG' et 'TAG_ID' ont été ajouté dans la V_EXPORT_SERVICE, si vous
avez créé votre propre V_EXPORT_SERVICE, il vous faudra la modifier vous même en vous appuyant sur la V_EXPORT_SERVICE par défaut de
OSE (https://git.unicaen.fr/open-source/OSE/-/blob/master/data/ddl/view/V_EXPORT_SERVICE.sql)

Ensuite si vous souhaitez faire apparaître les tags dans l'export des services, il vous faudra modifier vous même l'état de sortie 'Export des services', dans
l'onglet 'Export CSV' :

A la **ligne 56** ajouter TAG_ID à la variable $sid :

    $sid .= '_' . $d['TAG_ID'];

A la **ligne 102** ajouter la colonne TAG dans le tableau $ds:

    tag' => $d['TAG'],

A la **ligne 206** Ajouter le titre de colonne TAG dans le tableau $head :

    'tag' => 'Tags',

Le système de mise à jour peut - dans certaines circonstances - vous afficher quelques erreurs qui sont sans impact
sur le bon fonctionnement de l'application.
Je vais modifier les scripts de mise à jour pour éviter qu'elles ne se produisent.
Mais en attendant, si vous y êtes confrontés, vous en trouverez les explications sur le ticket suivant :
https://redmine.unicaen.fr/Etablissement/issues/49445?issue_count=42&issue_position=1&next_issue_id=48972

# OSE 19.7 (16/12/2022)

## Corrections de bugs

* Correction régression 19.5 : le workflow fonctionne à nouveau (#47982)
* Correction formule de calcul du Havre (#48024)

# OSE 19.6 (14/12/2022)

### Attention : il est déconseillé d'utiliser les 19.5 et 19.6, des régressions ont été constatées à plusieurs niveaux.

## Corrections de bugs

* Correction régression 19.5 : les indicateurs 910 et 920 fonctionnent de nouveau
* Correction régression 19.5 : les formules de calcul fonctionnent de nouveau
* Correction régression 19.5 : la page d'administration des statuts fonctionne de nouveau (#47976)
* Correction sur l'état de sortie préliquidation SIHAM (#47678)
* Prise dans compte des modulateurs pour la formule de Rennes 2 (#47753)

# OSE 19.5 (12/12/2022)

### Attention : il est déconseillé d'utiliser les 19.5 et 19.6, des régressions ont été constatées à plusieurs niveaux.

## Corrections de bugs

* Dans le module Charges, la saisie de seuils par défaut refonctionne normalement (#47451)
* Les plafonds de périmètre "volume horaire" sont de nouveau activables (#47340,#45225)
* Filtre des pays avec dates de validité périmées dans les listing des données personnelles (#47492)
* Correction sur le script de mise à jour des employeurs
* Correction sur les notes de l'intervenant au niveau de l'historique (#46303)
* Vue V_IMPORT_DEPUIS_DOSSIERS permettant de réinjecter les données personnelles dans les fiches corrigée (pb de filtre année) (#46769)
* Formule de calcul du Havre mise à jour
* Correction de bug dans la formule de Picardie
* Correction de bug de l'envoi du contrat par email lorsque la civilité est absente
* Correction du bouton "Prévu->Réalisé" absent pour le service réalisé
* Lors de la saisie de référentiel, le tri se fait correctement sur les fonction référentielles et sur les types de fonction
* Les caractères spéciaux sont bien pris en compte dans les types d'intervention (exemple : CM/TD)
* Le tableau des services d'enseignement n'affiche plus les colonnes inutiles
* Les étapes d'ODF complémentaire peuvent de nouveau être modifiées (#46922)
* Ajout du libelle du statut (champ STATUT_LIBELLE) pour affichage dans les états de paiement si nécessaire (#47762)
* Correction pour prise en compte des départements de naissance dans les DOM TOM dans la PEC Siham.
* Le workflow se calcule correctement lorsqu'il n'y a qu'un seul contrat par intervenant
* Correction sur la gestion des pièces jointes demandées uniquement dans le cadre de la formation continue
* Formule de calcul de ROUEN corrigée (#47876)

# OSE 19.4 (21/10/2022)

## Corrections de bugs/petites évolutions

* Les types d'intervention personnalisés par statut peuvent de nouveau être saisis (#46930)
* Modification de la formule de calcul de Poitiers
* Modification de la formule de calcul de Rennes 2
* Modification de la formule de calcul de Lyon 2
* Modification de la formule de calcul de Nice Cote d'Azur

# OSE 19.3 (08/09/2022)

## Corrections de bugs

* Pb lié à la 19.2 : la vue V_ETAT_PAIEMENT n'était pas mise à jour correctement.

# OSE 19.2 (06/09/2022)

## Nouveautés

* Prise en compte du nouveau point d'indice valable à partir du 1er juillet 2022

# OSE 19.1 (21/07/2022)

## nouveautés

* Formule de calcul de Picardie
* Nouvel état de sortie pour les écarts des heures complémentaires, maintenant personnalisable (#45807)
* Possibilité de faire une PEC ou REN (SIHAM) l'année universitaire N-1
* Nouvel état de sortie pour télécharger une synthèse des privilèges par rôle (#45629)
* Nouveau paramètre du module export RH (SIHAM) permettant de synchroniser le code intervenant avec le matricule SIHAM lors d'une PEC ou d'un renouvellement

## Corrections de bugs

* La synchronisation via la ligne de commande ne fonctionnait plus. C'est rétabli

# OSE 19.0 (12/07/2022)

## Nouveautés

* Ajout d'un paramètre général qui permet de choisir si un contrat peut avoir une date de retour signé ou non s'il n'y a
  pas de fichier
* Ajout d'un paramètre général qui permet de choisir pour l'intervenant sur l'année universitaire entre : avoir autant
  d'avenants que nécessaire, avoir un
  contrat/avenant par structure, avoir un contrat unique toutes composantes confondues
* Ajout d'un paramètre général pour permettre de créer les contrats sans passer par un projet de contrat
* Ajout de date de dernière modification des données dans les indicateurs notifiant d'une validation en attente
* Nouvel état de sortie pour l'extraction des paiements dans le cadre de la pré-liquidation SIHAM
* Ajout d'un bouton de refus de pièce justificative avec envoi d'email à l'intervenant
* Changement du bouton de cloture de service pour un libellé plus parlant et un style de bouton plus prononcé
* Formules nouvelles ou mises à jour : Rennes 2, Paris Saclay, Guyane, Côte d'Azur, La Réunion, Poitiers, Brest, Rouen
* Possibilité de créer un nouveau test de formule en téléversant une feuille de calcul au format tableur
* Ajout d'un champ cci pour l'envoi de mail aux intervenants et le refus de pièces jointes (#45083)

## Corrections de bugs

* Données personnelles : pouvoir pré-remplir le champ statut avec un statut non sélectionnable dans la liste. (#45216)
* Budget/Liquidation : afficher le nombre de HETD uniquement des HCO et non les HETD des HCO + Heures de service
* Notes : Afficher le bon utilisateur pour la validation de service (#45413).
* Forcer l'activiation de l'étape pièces justificatives même si il n'y a pas de service prévisionnel de renseigné.
* Choix du bon modèle de contrat dans le cas de plusieurs modèles de contrat (par structure et/ou par statut) (#45520)
* Bouton Prévu->réalisé Apparait correctement pour le service réalisé.
* Correction sur la reconduction des centres de coût et modulateurs (#45746)

## Notes de mise à jour

* Si vous êtes en version 17.x, se référer à toutes les notes de migration de la version [18.0](#ose-18-23052022)
  Une fois la migration réalisée et quelques tests effectués, vous devrez supprimer manuellement les tables de sauvegarde listées ci-dessous.
  Si vous ne le faites pas, le risque est que les scripts de migration de la version 17 à la version 18 soient rejoués sans qu'il n'en soit nécessaire, avec en
  sus un *risque de perte de données* pour des intervenants ayant changé de statut entre temps.


* Si vous êtes déjà en version 18.x et si ce n'est déjà fait, il vous faudra supprimer les tables de sauvegardes liées à la migration 17 --> 18 et
  la table STATUT_INTERVENANT
  **avant** de migrer en 19.0.

#### Liste des tables de sauvegardes de migration 17=>18 concernées :

```sql
DROP TABLE save_v18_dossier_autre_statut;
DROP TABLE save_v18_plafond;
DROP TABLE save_v18_plafond_app;
DROP TABLE save_v18_referentiel;
DROP TABLE save_v18_statut;
DROP TABLE save_v18_statut_privilege;
DROP TABLE save_v18_structure;
DROP TABLE save_v18_ta_statut;
DROP TABLE save_v18_tis;
DROP TABLE save_v18_tpjs;
DROP TABLE save_v18_dossier;
DROP TABLE save_v18_intervenant;
DROP TABLE save_v18_privilege;
DROP TABLE save_v18_role_privilege;
DROP TABLE statut_intervenant;

```

# OSE 18.2 (15/06/2022)

## Corrections de bugs

* Utilisation du mail expéditeur des paramétres par défaut pour l'envoi de mail via les notes et les refus de pièces
  jointes et correction dans le cas où l'
  intervenant n'a pas encore de dossier (#45083)
* Correction du bouton reporter les données de cet intervenant dans l'interface de test de formule (#45140)
* Les demandes de mise en paiement faites pour des services historisés s'affichent en rouge plutôt que de provoquer une
  erreur
* Les modifications sur les types d'intervention ne recalculent plus automatiquement toutes les formules, ce qui
  bloquait l'application
* Les modifications sur les types d'intervention ne retournent plus d'erreur de type sur le "Taux Hetd Complémentaire"
* L'ajout d'une structure est de nouveau possible depuis la page d'administration des structures.
* Les statuts sont de nouveau filtrés correctement dans l'interface d'administration des types d'intervention (#45141)
* Détection du type "LONG" dans la base de données pour permettre les mises à jour (#45174)
* Un nouveau paramètre de configuration : cas.exclusif a été ajouté. Il permet de n'offrir que le CAS comme possibilité
  de connexion (#44824)
* Correction du lien vers la fiche intervenant des indicateurs de dépassement de charges

## Notes de mise à jour

Si vous êtes en version 17, se référer à toutes les notes de migration de la version 18.0

Si vous êtes déjà en version 18.x et si ce n'est déjà fait, il vous faudra supprimer les tables de sauvegardes liées à la migration 17 --> 18 et
la table STATUT_INTERVENANT
**avant** de migrer en 18.1.

```sql
DROP TABLE save_v18_dossier_autre_statut;
DROP TABLE save_v18_plafond;
DROP TABLE save_v18_plafond_app;
DROP TABLE save_v18_referentiel;
DROP TABLE save_v18_statut;
DROP TABLE save_v18_statut_privilege;
DROP TABLE save_v18_structure;
DROP TABLE save_v18_ta_statut;
DROP TABLE save_v18_tis;
DROP TABLE save_v18_tpjs;
DROP TABLE save_v18_dossier;
DROP TABLE save_v18_intervenant;
DROP TABLE save_v18_privilege;
DROP TABLE save_v18_role_privilege;
DROP TABLE statut_intervenant;

```

# OSE 18.1 (31/05/2022)

## Corrections de bugs

* On peut maintenant se connecteur en CAS avec le login LDAP désativé (#44824)
* **IMPORTANT** Dans la 18.0, les données personnelles ne pouvaient pas s'enregistrer
* Pour certains intervenants multi-statuts, les agréments ne sont plus affichés en double
* Les intervenants multi-statuts peuvent maintenant agir sur toutes leurs fiches sans avoir d'erreur de saisie

## Notes de mise à jour

Si vous êtes en version 17, se référer à toutes les notes de migration de la version 18.0

Si vous êtes déjà en version 18.0, il vous faudra supprimer les tables de sauvegardes liées à la migration 17 --> 18 et
la table STATUT_INTERVENANT
**avant** de migrer en 18.1.

```sql
DROP TABLE save_v18_dossier_autre_statut;
DROP TABLE save_v18_plafond;
DROP TABLE save_v18_plafond_app;
DROP TABLE save_v18_referentiel;
DROP TABLE save_v18_statut;
DROP TABLE save_v18_statut_privilege;
DROP TABLE save_v18_structure;
DROP TABLE save_v18_ta_statut;
DROP TABLE save_v18_tis;
DROP TABLE save_v18_tpjs;
DROP TABLE save_v18_dossier;
DROP TABLE save_v18_intervenant;
DROP TABLE save_v18_privilege;
DROP TABLE save_v18_role_privilege;
DROP TABLE statut_intervenant;

```

# OSE 18 (23/05/2022)

Objectif : Plafonds personnalisables & refonte gestion des statuts

## Nouveautés

* Nouvelle infrastructure de gestion des plafonds
    * Les plafonds sont maintenant personnalisables : vous pouvez les modifier en retirer ou en créer
    * [Une nouvelle documentation pour les plafonds](doc/Plafonds/Plafonds.md)
    * Les plafonds pourront être personnalisés le cas échéant :
        * par composante
        * par statut d'intervenant
        * par fonction référentielle
        * par élément pédagogique
        * par volume horaire (par élément pédagogique et par type d'intervention, exemple: nombre de CM en Maths)
    * Les paramétrages liés aux plafonds sont annualisés
    * Les plafonds pourront être utilisés comme de simples indicateurs
    * Des jauges relatives aux plafonds s'affichent dans la page de saisie de service
    * Des dérogations aux plafonds sont possibles par intervenant via un nouvel onglet dédié

* Indicateurs
    * Optimisation du chargement de la page des indicateurs
    * Gestion des dossiers irrecevables (#18307)
    * Extraction CSV des indicateurs (#19405)
    * Certains statuts pourront être affichés de manière prioritaire pour être traités en premier (#20808)
    * Possibilité d'envoyer en cci l'email des indicateurs (#40999)
    * Pour plus de cohérence, réorganisation et **changemenent de numéro des indicateurs**

* Saisie de service & référentiel
    * Par statut, vous pouvez maintenant choisir d'activer le prévisionnel et le réalisé de manière indépendante
    * Vous avez maintenant des privilèges distincts pour la saisie du service : un pour le prévisionnel et un pour le
      réalisé
    * Idem pour le référentiel
    * Idem pour les validations des services
    * Idem pour les validations du référentiel
    * Vous pouvez maintenant désactiver la possibilité de reporter le prévisionnel n-1 vers l'année en cours ou du
      prévisionnel vers le réalisé

* Contrats
    * Un nouveau modèle de contrat sera possible avec la ventilation des heures de services par types d'intervention (
      CM/TD/TP)
    * Paramétrage du mail expéditeur du contrat (Tâche #41014)
    * Vérification de la présence d'au moins un fichier avant de permettre l'enregistrement d'une date de retour signé

* Fiche Intervenant
    * Le grade devient modifiable dans la fiche pour les anciens intervenants #40369
    * Ajout d'un privilège 'Edition avancée' au niveau de l'intervenant pour donner le droit de modifier manuellement le
      code intervenant et la source de l'intervenant
    * Le code de l'intervenant peut devenir cliquable pour vous rediriger vers une page de gestion des comptes d'accès
      au SI ou autre (cf. notes de mise à jour, paramètre ldap>systemeInformationUrl)

* Export des intervenants vers Siham
    * Possiblité de récupérer plusieurs typeUO pour alimenter la liste des structures pour la PEC et la REN (#41454)
    * Nouveau paramètre dans administration > paramètres généraux permettant de choisir l'étape de la feuille de route à
      franchir pour pouvoir exporter un intervenant vers le SIRH
    * Meilleure gestion du pays de naissance lors de la PEC ou REN

* Ajout d'un module de gestion des Notes sur l'intervenant
    * Possibilité de rajouter une note écrite (informations, message important etc...) sur une fiche intervenant (Tâche
      #25565)
    * Possibilité d'envoyer un email à intervenant avec historisation de l'email directement depuis la fiche
      intervenant (Tâche #26546)
    * Historique des emails envoyés à l'intervenant (contrat, indicateur etc...)

* Interfaces d'administration
    * Les types de formations et les groupes les contenant pourront être ajoutés, supprimés ou modifiés depuis
      l'administration des types de formations.
    * Des périodes pourront être ajoutés, supprimés ou modifiés depuis l'administration des périodes.
    * Des établissements pourront être ajoutés, supprimés ou modifiés depuis l'administration des établissements.
    * Des pays pourront être ajoutés, supprimés ou modifiés depuis l'administration des pays.
    * Des départements pourront être ajoutés, supprimés ou modifiés depuis l'administration des départements.
    * Des corps pourront être ajoutés, supprimés ou modifiés depuis l'administration des corps présent dans le bloc d'
      administration des nomenclatures RH.
    * Améliorations ergonomiques de la "matrice" des privilèges
    * La page d'administration des statuts a été réécrite pour plus de clarté et de souplesse
        * Il n'est plus nécessaire de paramétrer les privilèges par statut, tout se passe désormais dans l'IHM d'
          administration des statuts
        * Les paramétrages de statuts sont maintenant annualisés

* En bref
    * Il est maintenant possible de choisir si on veut être connecté avec le CAS ou avec un compte LDAP ou local au
      moment du login (options désactivables)
    * Vous pouvez vous connecter avec l'identité d'un autre utilisateur à des fins de tests, si vous vous en donnez le
      droit (cf. [config.local.php](config.local.php.default), rubrique "ldap").
    * Nouvel état de sortie sur l'export des agréments, rendant celui-ci maintenant paramétrable par les
      établissements (#42944)
    * Les paramétrages de pièces justificatives par statut sont maintenant annualisés: il n'y a plus de notion d'année
      de début/année de fin
    * Les paramétrages des types d'intervention par statut sont également annualisés
    * [Technique] Migration vers Laminas et Composer 2
    * [Technique] Migration vers PHP 8.0

## Corrections de bugs (liste non exhaustive)

* Le service dû s'affiche de nouveau normalement dans la page "Calcul HETD" de l'intervenant
* Adaptation de la commande update-employeur pour assurer la compatibilité avec les différentes versions d'oracle
* Correction sur un problème de route dans l'écran Engagements & Liquidation (#38763)
* Export CSV des agréments : inversion de colonnes (#41513)
* Correction sur la suppression de service lorsque la clôture de service a été historisé (#42046)
* Le calcul des choix minimum/maximum est de nouveau fiable (#42080)
* Liens inactifs lors du changement d'année universitaire (#40992)
* Dans certains cas avec des motifs de non paiements, le détail des services n'affichait pas toutes les heures

## Notes de mise à jour

* Les indicateurs portant sur les anciens plafonds ayant été supprimés et remplacés par de tous nouveaux indicateurs,
  les notifications par mail et abonnements correspondants seront résiliés
* En raison de l'ampleur de la mise à jour, l'opération de maintenance va prendre du temps. Prévoyez une journée
  d'interruption de service.

## Procédure de mise à jour spécifique à la version 18

1. Mettez l'aplication en maintenance
2. Si votre version de OSE est antérieure à la version 17, mettez **d'abord** à jour en version **17.3**
3. Installez **PHP8.0** sur votre serveur ainsi que [toutes ses dépendances nécessaires](install.md)
4. Dans le répertoire de OSE, lancez `php composer.phar self-update --2`
5. Mettez ensuite OSE à jour en version 18 `./bin/ose update` (attention, ce traitement est long, il pourra prendre
   plusieurs heures)
6. Recalculez toutes les forules de calcul : `./bin/ose formule-calcul` (attention, ce traitement dure plusieurs heures)
7. Pour votre instance de production, la nouvelle commande `./bin/ose maj-exports` doit être lancée régulièrement (
   cf. [procédure d'installation](install.md)). Ceci met à jour toutes les vues matérialisées dédiées à l'export
   MV_EXT_*.
8. Mettez à jour votre vue source [SRC_INTERVENANT](doc/Connecteurs-Import/Générique/SRC_INTERVENANT.sql)
9. Réactivez la synchronisation en import pour la table INTERVENANT, que la mise à jour a volontairement désactivée (en
   production).
10. Mettez à jour votre [modèle de contrat de travail](data/modele_contrat_ventile.odt) si vous voulez bénéficier de la
    ventilation par CM/TD/TP/Autres des heures.
11. Si vous utilisez l'export RH Siham, renseignez un nouveau paramètre dans Administration > paramètres généraux >
    Gestion export RH, en sélectionnant l'étape de la feuille de route franchie à partir de laquelle l'intervenant peut
    être exporté dans SIHAM.
12. Au niveau du fichier de configuration [config.local.php](config.local.php.default), vous pouvez remplir les
    paramètres (facultatifs) ldap>systemeInformationUrl, les paramètres ldap>local et ldap>usurpationIdentite.
13. Sortez du mode maintenance

# OSE 17.3 (17/03/2022)

## Corrections de bugs

* Dans la page Offre de formation, le total des éléments par formations tient maintenant compte des éléments
  mutualisés (#42043)
* Lors de la saisie d'un nouveau service, le filtre par formation prend maintenant en compte les formations ne contenant
  que des éléments mutualisés (#40208)
* Formule de l'université de Université Paris-Est Créteil (UPEC) corrigée (#37737)

## Notes de mise à jour

* La mise à jour risque de bloquer à cause d'un fichier "composer.lock" situé dans le répertoire racine de
  l'application. Veuillez le supprimer manuellement pour que la mise à jour puisse se dérouler.

# OSE 17.2 (05/01/2022)

## Nouveautés

* Ajout d'un nouveau paramètre 'code-type-structure-affectation' dans le fichier de config unicaen-siham.local.php pour
  le module exportRH afin de rendre paramétrable le code type structure pour la remontée des structures d'affectation de
  SIHAM.

## Notes de mise à jour

* Pour les utilisateurs du module ExportRH Siham, il faut rajouter un nouveau paramètre dans le fichier
  unicaen-siham.local.php, qui correspond au code de type de structure SIHAM que vous souhaitez remonter pour la liste
  des structures d'affectation via les Webservices, exemple :

`'code-type-structure-affectation' => 'COP',`

# OSE 17.1 (07/12/2021)

## Nouveautés

* Ajout du numéro de SIRET au niveau de la table employeur et suppression de la contrainte d'unicité sur le SIREN (Tâche
  #40810)

## Corrections de bugs

* Correction sur l'horodatage du service prévisionnel qui se mettait à jour lors de la création ou suppresion d'un
  contrat (#40925)
* Correction de bug empêchant le calcul des charges d'enseignement (#40991)
* Prise en compte des accents dans les recherches (#40917)
* Correction sur la complétude des données personnelles lorsque la case 'INSEE provisoire' est cochée (#41141).
* Les modifications de motifs de non paiement fonctionnement de nouveau en mode calendaire (#40037)
* L'autovalidation fonctionne maintenant pour le référentiel (#41149)
* La constatation d'heures réalisées à partir du prévisionnel tient maintenant compte des horaires et des motifs de non
  paiements (#39202)
* Dans la fiche de service, les enseignements mutualisés sont précisés #40402

## Notes de mise à jour

* Si vous utilisez la commande ./bin/ose update-employeur pour charger la liste des employeurs INSEE, en passant en 17.1
  vous pourrez récupérer la notion de SIRET dans la table employeur, ainsi la contrainte d'unicité sur le SIREN sera
  également levée.
* Attention : lors de la mise à jour, vous verrez apparaître des messages d'erreur liés aux contraintes d'unicité
  modifiées avec des indexes non conformes. Il n'est pas nécessaire d'en tenir compte, puisque la mise à jour des
  indexes se fait juste après, ce qui rétablit la situation.

# OSE 17 (18/11/2021)

Objectif : Connecteur Export OSE => Logiciel RH

## Corrections de bugs

* Au niveau du connecteur Actul+, les formations fermées étaient ignorées, elles sont également synchronisées
* La suppression d'un statut intervenant n'était plus possible (#39548)
* Il était impossible de modifier les règles de validation des services via le formulaire de l'administration (#39194)
* Amélioration ergonomique du champ de saisie sur recherche (#40618)

## Nouveautés

* Module export intervenant de OSE vers SIHAM
* Nouvelle vue V_IMPORT_DEPUIS_DOSSIERS pouvant servir pour peupler les données des intervenants à partir des données
  personnelles
* Possibilité pour un gestionnire de saisir des heures d'enseignement ou de référentiel qui n'auront pas besoin d'être
  validées par la suite (nouveau privilège d'autovalidation associé)
* Ajout d'une colonne dans l'export CSV des charges précisant si un élément est mutualisé ou non
* Ajout de la composante hiérarchique de l'intervenant dans l'export csv des agréments (#40053)
* Ajout de la structure d'affectation d'un intervenant vacataire dans l'extraction de mise en paiement (#40180)
* Ajout d'un nouveau paramètre pour pouvoir spécifier un email expéditeur générique dans le cadre d'envoi d'email via
  les indicateurs (#40106)
* Affichage de l'horodatage du dossier de l'intervenant dans les données personnelles (#39014)
* Affichage du grade et de la discipline au niveau de la fiche intervenant (#39603)
* Synchronisation de l'email pro de la fiche intervenant avec le dossier de l'intervenant en cas de mise à jour (#39346)
* Paiements : Pour du référentiel, la répartition AA/AC tient maintenant compte du ratio configuré dans les paramètres
  généraux (#39695).
* Nouvelles optimisations du modules CHARGES, au niveau des seuils et des calculs d'effectifs
* Formule de calcul de l'Université Paris-Est Créteil
* Formule de calcul de l'Université de Guyane
* Nouvelle formule pour l'université d'Artois qui remplace la précédente (#40425)
* La saisie calendaire ne bloque plus lorsqu'un nouveau volume horaire a des horaires de début et de fin identiques (
  #40037)
* Périodes : le paiement tardif peut être supprimé (en BDD) et les périodes peuvent être d'avantage personnalisées (en
  BDD aussi) (#31050)

## Notes de mise à jour

* Si vous souhaitez mettre en place l'export RH vers Siham, il vous faudra le configurer. Vous avez pour cela la
  documentation :
    * [côté utilisateur](doc/Export-Rh/fonctionnalite.md)
    * [pour la configuration du connecteur](doc/Export-Rh/configuration.md)

# Anciennes versions de OSE

[C'est ici!](doc/anciennes-versions.md)
