# Stockage des fichiers

## Présentation

Dans OSE, les fichiers, que ce soient les pièces justificatives ou les contrats ou tout autre fichier téléversé, 
sont par défaut stockés dans la table FICHIER et leur contenu dans la colonne CONTENU.

Au bout de plusieures années d'exploitation, cela devient problématique, car le tablespace Oracle est extensible jusqu'à 32Go, mais pas au-delà.

Il existe donc une alternative qui permet de stocker ces données directement dans le système de fichiers de votre serveur.


## Mise en œuvre du stockage dans le système de fichiers

Le stockage dans le système de fichiers est recommandé pour une instance de production uniquement.

Deux opérations sont nécessaires pour pouvoir stocker vos données dans votre système de fichiers :

### 1. Configuration

Dans votre fichier config.local.php, la rubrique "fichiers" doit être personnalisée.
Si vous avez un ancien fichier config.local.php qui ne comporte pas cette ubrique, veuillez copier/coller cette dernière depuis le fichier [config.local.php.default](../config.local.php.default).

Exemple de configuration :
```php
 /* Fichiers */
    'fichiers'      => [
        /* file => dans le système de fichiers par défaut, bdd => en base de données par défaut */
        'stockage' => 'file',

        /* Répertoire où seront stockés les fichiers (pièces justificatives, contrats déposés, etc.
         * A savoir : le répertoire par défaut data/fichiers est ignoré par GIT.
         * Il est nécessaire de prévoir une sauvegarde de ce répertoire.
         * IMPORTANT : ce répertoire doit être accéssible en lecture/écriture par l'utilisateur www-data d'Apache.
         */
        'dir'      => __DIR__ . '/data/fichiers',
    ],
```

Paramètre "stockage" : 
 - bdd => Stockage par défaut directement dans la base de données
 - file => Stockage dans le système de fichiers, dans un répertoire spécifique

Paramètre "dir" :
 - Ce paramètre vous permet de préciser dans quel répertoire stocker ces fichiers. Vous pouvez utiliser 
   comme ci-dessus la variable magique __DIR__ qui permet de partir du répertoire OSE, ou alors opter pour un chemin absolu en débutant par "/".

A Caen, nous avons opté pour un répertoire data de OSE lié symboliquement à un répertoire monté en réseau sur un espace de stockage distinct du serveur et sauvegardé régulièrement.

Au cas où le fichier ne pourrait pas être enregistré (espace disque insuffisant, problème réseau, droits mal configurés, etc.), alors le contenu sera stocké en base de données afin de ne pas être perdu.

### 2. Transfert des données en base vers le système de fichiers

Une fois votre configuration OK, un script vous permet de transférer tous les fichiers stockés dans votre base de données vers le système de fichiers, soulageant ainsi votre tablespace.

```bash
./bin/ose fichiers-vers-filesystem
```

### 3. Exploitation

Si OSE a besoin d'accéder au contenu d'un fichier, en mode "file", l'application ira chercher d'abord le contenu dans le système de fichier et s'il ne trouve rien il cherchera dans FICHIER.CONTENU.

