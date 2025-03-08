# Exercice 2 : Projet web avec compose

## Service database

### 2. Quelle commande faut-il utiliser pour ouvrir un processus bash interactif sur le conteneur de la base de données MySQL ? Donner ensuite les commandes shell à utiliser pour vérifier que la base par défaut est bien présente ainsi que son contenu initial.

Pour ouvrir un processus bash interactif sur le conteneur MySQL :
```bash
docker compose exec database bash
```

Pour vérifier la base par défaut et son contenu initial :
```bash
mysql -u db_client -ppassword
```

Puis dans le client MySQL :
```sql
USE docker_doc_dev;
SHOW TABLES;
SELECT * FROM article;
```

### 3. Réaliser un dump de la base de données sur votre machine hôte avec mysqldump en executant une commande supplémentaire sur le conteneur mysql avec docker compose exec sans utiliser le mode interactif (sans ouvrir de session bash au préalable). Stocker le dump dans un fichier dump.sql. Donner la commande pour réaliser cette tâche.

```bash
docker compose exec database mysqldump -u db_client -ppassword docker_doc_dev > dump.sql
```

## Service client

### 5. Au lancement du projet, le service client doit interroger la base de données docker_doc_dev et afficher le contenu de la table article de la base de données docker_doc_dev sur une page web (document HTML).

L'URL locale pour accéder à cette page web sera :
```
http://localhost:8080/
```

### 6. Configurer le fichier Compose pour permettre de développer le script client PHP (le modifier, l'éditer) sans avoir à reconstruire l'image à chaque changement. Indiquer la commande pour relancer le projet permettant de recharger les sources dès qu'elles changent.

Commande pour relancer le projet et recharger les sources :
```bash
docker compose up -d
```

## Configuration d'environnement(s)

### 7. En vous basant sur une stratégie de votre choix, créer des fichiers d'environnement et modifier le fichier Compose pour configurer les deux environnement différents au lancement du projet (docker compose up). Fournissez les deux commandes suivantes.

Commande pour lancer le projet dans l'environnement dev :
```bash
docker compose -f compose.yaml -f compose.dev.yaml up -d
```

Commande pour lancer le projet dans l'environnement prod :
```bash
docker compose -f compose.yaml -f compose.prod.yaml up -d
```

### 8. Est-ce une bonne pratique de placer des données sensibles (password, clés secrètes, etc.) dans des variables d'environnement comme on le fait ici ? Pourquoi ? Quelle autre option mise à disposition par Docker faut-il privilégier pour le faire et pourquoi ?

Non, ce n'est pas une bonne pratique de placer des données sensibles dans des variables d'environnement car :
- Elles peuvent être exposées via des commandes comme `docker inspect`
- Elles sont visibles dans les logs et les historiques de commandes
- Elles sont stockées en clair dans les fichiers de configuration
- Elles peuvent être accidentellement exposées lors du partage de configurations

Docker propose une meilleure option : les **secrets**. Les secrets Docker sont à privilégier car :
- Ils sont chiffrés au repos
- Ils sont montés comme des fichiers temporaires dans les conteneurs (/run/secrets/)
- Ils ont une granularité d'accès plus fine (par service)
- Ils sont conçus spécifiquement pour gérer les données sensibles
- Ils ne sont pas exposés via les commandes standard comme `docker inspect`

