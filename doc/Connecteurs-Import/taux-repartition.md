# Problématique des taux de mixité
Dans OSE, au niveau de l’offre de formation, il est possible de spécifier, au niveau de chaque élément pédagogique, 
s'il est éligible :
- à la formation initiale (FI),
- à la formation en apprentissage (FA)
- ou bien à la formation continue (FC).

Dans OSE, FI, FA et FC sont appelés des types d’heures. Il y a aussi le référentiel, mais il est traité à part, 
car il n’est pas adossé à l’offre de formation.

Des témoins permettent d’avoir cette information, à travers les colonnes FI, FA et FC de la table 
[ELEMENT_PEDAGOGIQUE](Création-tables/ELEMENT_PEDAGOGIQUE.md) dont les valeurs, booléennes, peuvent être respectivement de 0 ou 1.

Et il est possible d'avoir un mix des trois, s’exprimant en pourcentage.
Par exemple, un élément peut être 50% FI, 25% FA et 25% FC. 
Les colonnes TAUX_FI, TAUX_FA et TAUX_FC de la table [ELEMENT_PEDAGOGIQUE](Création-tables/ELEMENT_PEDAGOGIQUE.md)
doivent contenir des valeurs de type flottantes comprises entre 0 et 1, 
avec en contrainte  TAUX_FI +  TAUX_FA +  TAUX_FC = 1. 50% FI se traduit par TAUX_FI = 0,5. Ce sont les taux de mixité.

L’autre contrainte est que si le témoin FI est à 0, alors TAUX_FI doit aussi être à 0. 
Idem pour les deux autres types d’heures.
En résumé, pour la table ELEMENT_PEDAGOGIQUE les contraintes sont les suivantes :
- FI = {0;1}
- FA = {0;1}
- FC = {0;1}
- 1 <= FI + FA + FC <= 3.
- TAUX_FI = \[0 .. 1,0\] et 0 si FI = 0
- TAUX_FA = \[0 .. 1,0\] et 0 si FA = 0
- TAUX_FC = \[0 .. 1,0\] et 0 si FC = 0
- (TAUX_FI + TAUX_FA + TAUC_FC) = 1

# Pour renseigner ces données : principe général

Si vous utilisez le [connecteur Apogée](Apogée/Connecteur.md), les témoins FI, FA et FC sont peuplés à partir du connecteur.
Les taux de mixité peuvent être saisis directement dans OSE, dans la partie Offre de formation, 
puis en cliquant sur une formation et enfin «Taux de mixité». 
Là, une page vous permettra de saisir ces taux pour tout ou partie des éléments de cette formation.

Compte tenu du nombre de données à saisir, il est plus simple de mettre en place un traitement automatique pour renseigner ces informations, 
la saisie manuelle pouvant être réservée aux cas particuliers.

A Caen, nous initialisons ces taux de mixité en nous basant sur les effectifs de l’année passée en début d'année universitaire,
puis à partir de décembre, ce sont les effectifs de l'année en cours qui sont utilisés.

Les taux sont initialisés dès que possible via le processus de synchronisation standard. 
En revanche, s'il y a une modification du fait d'un changement de la base d'effectifs, celle-ci ne sera faite que le 15 décembre. 

# Architecture

Les taux de mixité sont stockés dans la table [ELEMENT_TAUX_REGIMES](Création-tables/ELEMENT_TAUX_REGIMES.md).
Les informations stockées dans [ELEMENT_TAUX_REGIMES](Création-tables/ELEMENT_TAUX_REGIMES.md) sont ensuite transférées 
dans [ELEMENT_PEDAGOGIQUE](Création-tables/ELEMENT_PEDAGOGIQUE.md).
Ces sont les taux stockéss dans [ELEMENT_PEDAGOGIQUE](Création-tables/ELEMENT_PEDAGOGIQUE.md) qui sont utiles pour l’exploitation.

[ELEMENT_TAUX_REGIMES](Création-tables/ELEMENT_TAUX_REGIMES.md) ne sert que d’intermédiaire. 
Cette table est néanmoins nécessaire, car on peut avoir des taux de mixité saisis manuellement pour un élément pédagogique issu du connecteur Apogée. 
Si tout n’était stocké que dans [ELEMENT_PEDAGOGIQUE](Création-tables/ELEMENT_PEDAGOGIQUE.md), nous ne pourrions pas faire cela.

# En pratique

## Les vues

Les exemples ci-dessous sont tirés du [connecteur Apogée](Apogée/Connecteur.md).

Il va falloir peupler la table [ELEMENT_TAUX_REGIMES](Création-tables/ELEMENT_TAUX_REGIMES.md). 
La vue [SRC_ELEMENT_TAUX_REGIMES](Apogée/SRC_ELEMENT_TAUX_REGIMES.sql) va fournir les données nécessaires pour cela.

Ensuite, il faut spécifier, dans la vue [SRC_ELEMENT_PEDAGOGIQUE](Apogée/SRC_ELEMENT_PEDAGOGIQUE.sql), qu’il faut recourir à
[ELEMENT_TAUX_REGIMES](Création-tables/ELEMENT_TAUX_REGIMES.md) si la donnée existe. 
Si au contraire aucune répartition n’est spécifiée, alors un taux de mixité est calculé automatiquement à partie des témoins FI, FA et FC avec:
- l’un des trois types d’heures ==> 100% sur le taux correspondant;
- deux des trois témoins ==> 50% chacun;
- les trois témoins, 33,33% FA et FC, 33,34% pour la FI, histoire de faire strictement 100%.

## Le calcul des taux de mixité

Il existe trois méthodes qui permettent de calculer les taux de mixité en fonction de nombres et des témoins
- OSE_DIVERS.CALCUL_TAUX_FI( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC);
- OSE_DIVERS.CALCUL_TAUX_FC( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC);
- OSE_DIVERS.CALCUL_TAUX_FA( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC);

eff_fi, eff_fc et eff_fa sont des nombres flottants. Ils seront convertis en pourcentages. C'est-à-dire que vous pouvez saisir \[250, 0, 49\].
fi, fa et fc correspondent aux témoins. Vous devrez transmettre 0 ou 1. CALCUL_TAUX_FI retournera le taux de mixité correspondant pour la FI.
Idem pour CALCUL_TAUX_FC avec la FC et CALCUL_TAUX_FA pour la FA.

## Explications sur le contenu des vues

### [SRC_ELEMENT_TAUX_REGIMES](Apogée/SRC_ELEMENT_TAUX_REGIMES.sql)

Cette vue se base sur les données en provenance d'Apogée, en l'occurence sur la table ose_element_effectifs du connecteur.

Pour chaque élément pédagogique, on récupère la période de paiement courante, donc le mois en cours via `OSE_DIVERS.DATE_TO_PERIODE_CODE(sysdate,ep.annee_id)`.
Cette période va nous donner l'écart en nombre de mois `PERIODE.ECART_MOIS` par rapport au mois de septembre.

On récupère ensuite dans `aetr` les effectifs de l'année précédente si on est avant décembre et ceux de l'année en cours si on est en décembre ou après.
On récupère dans `aetraa` les effectifs de l'année précédente.

On liste des taux de régime déjà saisis dans OSE, dans `etr`.

Au niveau filtres :
- On ne s'interesse qu'aux éléments pédagogiques actifs.
- Il ne faut pas qu'un taux de régime ai déjà été saisi manuellement (sinon la synchronisation écraserait ce qui a été entré dans OSE).
- Il faut qu'il y ait des effectifs dans au moins une des deux années n-1 ou n, sinon inutile de faire un import.

Ensuite, au niveau des trois lignes 
```sql
  OSE_DIVERS.CALCUL_TAUX_FI( COALESCE(aetr.effectif_fi,aetraa.effectif_fi), COALESCE(aetr.effectif_fc,aetraa.effectif_fc), COALESCE(aetr.effectif_fa,aetraa.effectif_fa), ep.fi, ep.fc, ep.fa ) taux_fi,
  OSE_DIVERS.CALCUL_TAUX_FC( COALESCE(aetr.effectif_fi,aetraa.effectif_fi), COALESCE(aetr.effectif_fc,aetraa.effectif_fc), COALESCE(aetr.effectif_fa,aetraa.effectif_fa), ep.fi, ep.fc, ep.fa ) taux_fc,
  OSE_DIVERS.CALCUL_TAUX_FA( COALESCE(aetr.effectif_fi,aetraa.effectif_fi), COALESCE(aetr.effectif_fc,aetraa.effectif_fc), COALESCE(aetr.effectif_fa,aetraa.effectif_fa), ep.fi, ep.fc, ep.fa ) taux_fa,
```

la logique est la suivante :
- on prend les effectifs issus de aetr et à défaut ceux de l'année n-1, donc aetraa. Ceci permet, d'éviter de supprimer des taux de régime au cas ou
les effectifs existeraient en n-1 mais pas en année courante (pour un cours du S2 synchronisé en décembre par exemple).


### [SRC_ELEMENT_PEDAGOGIQUE](Apogée/SRC_ELEMENT_PEDAGOGIQUE.sql)

Ligne 9 : `CASE WHEN ep.fi+ep.fa+ep.fc=0 THEN 1 ELSE ep.fi END fi`.
Si aucun témoins n'est activé, alors on force l'élément en FI pour respecter la règle 1 <= FI+FA+FC <= 3.

Au niveau des jointures :
```sql
  LEFT JOIN element_pedagogique         ep ON ep.source_code             = aq.source_code
                                          AND ep.annee_id                = aq.annee_id
  LEFT JOIN element_taux_regimes       etr ON etr.element_pedagogique_id = ep.id
                                          AND etr.histo_destruction      IS NULL
```

On va chercher les taux de répartition s'ils existent.

Et au niveau du SELECT on a :

```sql
  CASE
    WHEN etr.id IS NOT NULL
    THEN ose_divers.calcul_taux_fi( etr.taux_fi, etr.taux_fc, etr.taux_fa, aq.fi, aq.fc, aq.fa )
    ELSE ose_divers.calcul_taux_fi( aq.fi, aq.fc, aq.fa, aq.fi, aq.fc, aq.fa )
  END taux_fi,
```
Idem pour FA et FC.

Cela signifie : si on a un taux de mixité, alors on s'appuie dessus, sinon on calcule des taux de mixité à partir des témoins (100%, 50/50 ou 33/33/33).

## Le filtre des taux de mixité

Lors des opérations de synchronisation régulières, il faut pouvoir filtrer les données afin de ne synchroniser que les nouveaux taux, tout en ignorant les
modifications à apporter aux taux existants.

Vous avez sur la [page de documentation générale des connecteurs](Connecteurs-IMPORT.md#mise-en-place-des-filtres) tous les filtres utiles.
Le filtre à mettre en place ici est celui de la table ELEMENT_TAUX_REGIMES.

## La mise à jour du 15 décembre

Le 15 décembre, la commande `./bin/ose maj-taux-mixite` lancée via le `cron` actualise sur la base des effectifs de l'année en cours tous les taux précalculés, sans toucher à ceux qui ont été saisis manuellement.
Vous trouverez dans la [procédure d'installation](../../INSTALL.md#mise-en-place-des-t%C3%A2ches-cron) les éléments nécessaire pour mettre en place cette tâche.


## Conclusion

A Caen, nous nous basons sur les effectifs de l'année passée en début d'année, puis de l'année en cours à partir de décembre pour initialiser nos taux de mixité.
Ces taux ne doivent idéalement pas trop bouger, car cela impacte les demandes de mise en paiement. Mieux vaut donc les figer le plus tôt possible.
La synchronisation régulière ne s'occupe donc que des nouveaux taux de mixité, sans jamais modifier les taux existants.

Les taux de mixité sont aussi personnalisés manuellement en passant par "Offre de formation", au besoin.

Le 15 décembre, on actualise les taux sur la base des effectifs de l'année en cours, sans toucher à ceux qui ont été saisis manuellement.

Pour cela il faut mettre en place :
- La [partie Apogée du connecteur ODF](Apogée/Apogee-OSE-lisezMoi.md) pour avoir `ose_element_effectifs`.
- La vue source [SRC_ELEMENT_TAUX_REGIMES](Apogée/SRC_ELEMENT_TAUX_REGIMES.sql)
- La vue source [SRC_ELEMENT_PEDAGOGIQUE](Apogée/SRC_ELEMENT_PEDAGOGIQUE.sql)
- Le [filtre en import](Connecteurs-IMPORT.md#mise-en-place-des-filtres) sur ELEMENT_TAUX_REGIMES
- Le job qui va lancer tous les 15 décembre la commande `./bin/ose maj-taux-mixite` (cf. [doc Install](../../INSTALL.md#mise-en-place-des-t%C3%A2ches-cron))

Si vous ne souhaitez pas fonctionner de la même manière, alors il vous faudra adapter les éléments présentés ci-dessus.
Attention toutefois à bien peupler les taux de mixité des éléments pédagogiques à partir des taux de régime.
Le mieux est de laisser telle qu'elle [SRC_ELEMENT_PEDAGOGIQUE](Apogée/SRC_ELEMENT_PEDAGOGIQUE.sql).