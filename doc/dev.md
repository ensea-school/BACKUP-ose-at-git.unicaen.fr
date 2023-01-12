# Installation de l'environnement de développement de OSE

OSE a besoin de npm, node, vue-cli, sass

NPM doit être installé sur la machine au moyen du gestionnaire de packages

Pour utiliser la version 19 de NPM sur Ubuntu, utiliser la commande :

```bash
curl -fsSL https://deb.nodesource.com/setup_19.x | sudo -E bash -
```

Attention : l'utilisation de npm doit se faire toujours depuis la machine hôte et pas à l'intérieur du conteneurDocker.

## Installation des dépendances

Dans le dossier du projet :

```bash
npx npm install
```


## Mise à jour des dépendances

```bash
npx npm update
```


## Utilisation de Vite

Vite est un outil de développement web rapide qui permet de créer facilement
des projets web avec Vue.js ou autre. 
Il offre un serveur de développement local, le rechargement en direct et la compilation en temps réel
et peut être configuré avec Babel et TypeScript.

En mode dév, il faut lancer le servcie Vite au moyen de 

```bash
npx vite dev
```

Pour la production, il est nécessaire de compiler tous vos composants Vue au moyen de

```bash
npx vite build
```
Les fichiers ainsi générés seront placés dans le répertoire public/dist.
Enfin, commitez le tout et testez avant de déployer!!!