# Documentation de la ligne de commande

## Lancer la ligne de commande OSE

* Lancer un terminal
* Se positionner dans le répertoire de OSE
* Lancer ```./bin/ose <action> <paramètres éventuels>```

Pour information, ./bin/ose help vous affichera la liste des actions disponibles.

## Liste des actions possibles

### ```update``` : Mise à jour de l'application

Plus d'infos sur la procédure de mise à jour ici :
[Procédure de mise à jour](../UPDATE.md).

### ```notifier-indicateurs``` : Envoi des mails relatifs aux indicateurs

Pas de paramètre pour cette commande.

### ```synchronisation``` : ```job``` : Effectue la synchronisation des données pour le ```job``` transmis

Pas de paramètre pour cette commande.

### ```chargens-calcul-effectifs``` : Calcul des effectifs du module Charges

Pas de paramètre pour cette commande.

### ```calcul-tableaux-bord``` : Recalcule tous les tableaux de bord de calculs intermédiaires

Pas de paramètre pour cette commande.
Attention : le temps de traitement peut être long!

Paramètres supplémentaires :

Vous pouvez lancer la commande pour un tableau de bord en particuler :

```
./bin/ose calcul-tableaux-bord workflow
```

Et également, pour un tableau de bord donné, recalculer pour un intervenant donné (ID à fournir) :

```
./bin/ose calcul-tableaux-bord workflow --intervenant=517
```

Ou même pour une année donnée :

```
./bin/ose calcul-tableaux-bord workflow --annee=2024
```

### ```calcul-feuille-de-route <id>``` : Recalcule la feuille de route de l'intervenant dont l'ID est <id>

<id> doit être un nombre entier correspondant à l'ID d'un intervenant.
Le calcul prend environ deux secondes.


### ```formule-calcul``` : Calcul de toutes les heures complémentaires à l'aide de la formule

Pas de paramètre pour cette commande.
Attention : le temps de traitement peut être long!

### ```creer-utilisateur``` : Création d'un nouvel utilisateur de OSE (hors LDAP).

Utile pour créer un utilisateur (personnel ou intervenant) non présent dans le LDAP
Vous pourrez également créer une fiche intervenant dans la foulée pour ce nouvel utilisateur.
Une fois l'utilisateur créé, vous pourrez aller dans OSE, Administration, Affectations pour lui donner une nouvelle affectation.
Attention : l'**utilisateur** ne pourra se **connecter** directement à OSE **que si** l'application n'est **pas cassifiée**.

Paramètres possibles :
* ```nom``` : Nom de l\'utilisateur
* ```prenom``` : Prénom
* ```date-naissance``` : Date de naissance (format jj/mm/aaaa)
* ```login``` : Login
* ```mot-de-passe``` : Mot de passe (6 caractères minimum)
* ```creer-intervenant``` : Voulez-vous créer un intervenant pour cet utilisateur ?
* ```code``` : Code éventuel de l'intervenant
* ```annee``` : Année universitaire pour laquelle l'intervenant sera créé (ex : 2020 pour 2020/2021)
* ```statut``` : Code du statut de l'intervenant


Exemple de commande lancée avec des paramètres :
```bash
./bin/ose creer-utilisateur \
    --nom="Lécluse" \
    --prenom="Laurent" \
    --date-naissance="01/01/1990" \
    --login="lecluse" \
    --mot-de-passe="mon-mdp-caché" \
    --creer-intervenant=n
```

### ```changement-mot-de-passe``` : Changement de mot de passe (pour un utilisateur local uniquement)

Pas de paramètre pour cette commande.
Deux informations vous seront demandées :
* le login de l'utilisateur
* le nouveau mot de passe à saisir, ainsi que sa confirmation

Valable uniquement pour des utilisateurs non connectés au LDAP!!

### ```clear-cache``` : Vidage du cache de l'application

Pas de paramètre pour cette commande.

### ```update-employeur``` : Charge la liste d'employeurs en base de données 

Cette commande charge (update/insert/soft-delete) la table employeur de la base de données à partir d'un fichier source INSEE fourni.

Important : l'archive employeur.tar.gz fourni par CAEN doit être déposée dans /data/employeur/import

Note : Le premier chargement à vide peut prendre quelques dizaines de minutes, les suivants seront plus rapides car uniquement le delta sera modifié en base.

Pas de paramètre pour cette commande.

### ```test-bdd``` : Test d'accès à la base de données

Affiche si la base de données est accessible ou non.

Pas de paramètre pour cette commande.
