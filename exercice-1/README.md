# Exercice 1 : Validation d'acquis

## Questions et réponses

### 1. Qu'est ce qu'un conteneur ?
Un conteneur est une unité logicielle standardisée qui encapsule une application et toutes ses dépendances (bibliothèques, fichiers de configuration, etc.) dans un package isolé et portable. Contrairement aux machines virtuelles, les conteneurs partagent le noyau du système d'exploitation hôte, ce qui les rend légers et rapides à démarrer. Ils garantissent que l'application s'exécutera de manière identique, quel que soit l'environnement d'exécution.

### 2. Est-ce que Docker a inventé les conteneurs Linux ? Qu'a apporté Docker à cette technologie ?
Non, Docker n'a pas inventé les conteneurs Linux. La technologie de conteneurisation existait déjà sous différentes formes comme LXC (Linux Containers), cgroups et namespaces dans le noyau Linux.

Docker a apporté :
- Une interface utilisateur simplifiée et des outils de ligne de commande accessibles
- Un format d'image standardisé et portable
- Un écosystème complet pour créer, distribuer et exécuter des conteneurs
- Un registre centralisé pour partager des images (Docker Hub)
- Une approche "infrastructure as code" avec les Dockerfiles

### 3. Pourquoi est-ce que Docker est particulièrement pensé et adapté pour la conteneurisation de processus sans états (fichiers, base de données, etc.) ?
Docker est particulièrement adapté pour les processus sans état car :
- Les conteneurs sont éphémères par conception et peuvent être détruits/recréés facilement
- Les données à l'intérieur d'un conteneur sont perdues lors du redémarrage, à moins d'utiliser des volumes
- L'architecture favorise le modèle "immutable infrastructure" où les conteneurs sont remplacés plutôt que modifiés
- Les applications sans état sont plus faciles à mettre à l'échelle horizontalement
- L'orchestration (comme avec Kubernetes) est simplifiée avec des processus sans état

Pour les applications avec état (bases de données, systèmes de fichiers), Docker nécessite des mécanismes spécifiques comme les volumes pour persister les données en dehors du cycle de vie du conteneur.

### 4. Quel artefact distribue-t-on et déploie-t-on dans le workflow de Docker ? Quelles propriétés désirables doit-il avoir ?
Dans le workflow Docker, on distribue et déploie des **images Docker**.

Propriétés désirables d'une image Docker :
- **Légèreté** : taille minimale pour des transferts et déploiements rapides
- **Sécurité** : absence de vulnérabilités et de composants non nécessaires
- **Reproductibilité** : comportement identique dans différents environnements
- **Immuabilité** : une fois créée, l'image ne change pas
- **Modularité** : construction en couches pour optimiser le stockage et les mises à jour
- **Documentation** : tags clairs, métadonnées et documentation pour les utilisateurs
- **Portabilité** : capacité à s'exécuter sur différentes plateformes

### 5. Quelle est la différence entre les commandes docker run et docker exec ?
- **docker run** : Crée et démarre un nouveau conteneur à partir d'une image. Elle configure un environnement d'exécution complet pour un conteneur qui n'existe pas encore.

- **docker exec** : Exécute une commande dans un conteneur déjà en cours d'exécution. Elle ne crée pas de nouveau conteneur mais permet d'interagir avec un conteneur existant.

### 6. Peut-on lancer des processus supplémentaires dans un même conteneur docker en cours d'execution ?
Oui, on peut lancer des processus supplémentaires dans un conteneur Docker en cours d'exécution en utilisant la commande `docker exec`. Cependant, cette pratique n'est généralement pas recommandée dans la philosophie Docker qui préconise un processus par conteneur pour une meilleure isolation, surveillance et gestion des ressources.

### 7. Pourquoi est il fortement recommandé d'utiliser un tag précis d'une image et de ne pas utiliser le tag latest dans un projet Docker ?
Utiliser un tag précis plutôt que `latest` est recommandé car :
- Le tag `latest` est dynamique et peut pointer vers différentes versions au fil du temps
- Cela peut causer des problèmes de reproductibilité et des comportements inattendus
- Les mises à jour automatiques peuvent introduire des incompatibilités ou des régressions
- Il est difficile de suivre quelle version est réellement utilisée en production
- Le versionnement explicite facilite les rollbacks en cas de problème
- Cela permet une traçabilité et une gestion plus précise des dépendances

### 8. Décrire le résultat de cette commande : docker run -d -p 9001:80 --name my-php -v "$PWD":/var/www/html php:8.2-apache.
Cette commande crée et démarre un conteneur Docker avec les caractéristiques suivantes :
- `-d` : Le conteneur s'exécute en arrière-plan (mode détaché)
- `-p 9001:80` : Le port 80 du conteneur est mappé au port 9001 de la machine hôte
- `--name my-php` : Le conteneur est nommé "my-php"
- `-v "$PWD":/var/www/html` : Le répertoire courant de l'hôte est monté dans le conteneur à l'emplacement /var/www/html (où Apache sert les fichiers web)
- `php:8.2-apache` : Utilise l'image officielle PHP 8.2 avec le serveur web Apache

Résultat : Un serveur web Apache avec PHP 8.2 est démarré, accessible via http://localhost:9001, servant les fichiers PHP du répertoire courant.

### 9. Avec quelle commande docker peut-on arrêter tous les conteneurs ?
```bash
docker stop $(docker ps -q)
```
Cette commande arrête tous les conteneurs en cours d'exécution. Elle utilise `docker ps -q` pour obtenir uniquement les IDs de tous les conteneurs actifs, puis les passe à la commande `docker stop`.

### 10. Quelles précautions doit-on prendre pour construire une image afin de la garder de petite taille et faciliter sa reconstruction ?
Pour maintenir une image Docker légère et optimiser sa reconstruction :

1. **Utiliser une image de base minimaliste** (Alpine Linux ou images "slim")
2. **Combiner les instructions RUN** pour réduire le nombre de couches
3. **Nettoyer les caches et fichiers temporaires** dans la même instruction RUN
4. **Utiliser .dockerignore** pour exclure les fichiers non nécessaires
5. **Multi-stage builds** pour séparer la compilation des artefacts d'exécution
6. **Installer uniquement les dépendances nécessaires** pour l'exécution
7. **Éviter d'installer des outils de développement** dans l'image finale
8. **Supprimer les fichiers de package et caches** après installation
9. **Minimiser le nombre de couches** en groupant les opérations similaires
10. **Prioriser les dépendances qui changent rarement** dans les premières couches

### 11. Lorsqu'un conteneur s'arrête, tout ce qu'il a pu écrire sur le disque ou en mémoire est perdu. Vrai ou faux ? Pourquoi ?
**Partiellement vrai**. 

Les données écrites dans le système de fichiers du conteneur (couche d'écriture) sont perdues lorsque le conteneur est supprimé (avec `docker rm`), mais pas nécessairement quand il est simplement arrêté avec `docker stop`. Un conteneur arrêté conserve ses données jusqu'à ce qu'il soit supprimé.

Pour persister les données au-delà du cycle de vie du conteneur, il faut utiliser :
- Des volumes Docker (`docker volume`)
- Des bind mounts (montage de répertoires de l'hôte)
- Des volumes nommés

Les données en mémoire sont toujours perdues lorsqu'un conteneur s'arrête, conformément au comportement normal des processus système.

### 12. Lorsqu'une image est crée, elle ne peut plus être modifiée. Vrai ou faux ?
**Vrai**. 

Une fois créée, une image Docker est immuable et ne peut pas être modifiée. Si des changements sont nécessaires, une nouvelle image doit être construite.

Cette immuabilité est fondamentale dans l'architecture Docker et offre plusieurs avantages :
- Garantit la reproductibilité des déploiements
- Facilite le versionnement
- Permet l'utilisation de caches en couches
- Améliore la sécurité et la traçabilité

Pour "modifier" une image existante, on doit en réalité créer une nouvelle image, soit en la reconstruisant à partir du Dockerfile, soit en créant un nouveau conteneur basé sur l'image existante, en y apportant les modifications, puis en commitant ce conteneur comme nouvelle image.

### 13. Comment peut-on publier et obtenir facilement des images ?
Publication et obtention d'images Docker :

**Publication** :
1. Créer un compte sur Docker Hub ou un autre registre d'images
2. Tagger l'image locale avec le format `utilisateur/nom_image:tag`
   ```bash
   docker tag mon_image:latest utilisateur/mon_image:v1.0
   ```
3. Se connecter au registre
   ```bash
   docker login
   ```
4. Pousser l'image vers le registre
   ```bash
   docker push utilisateur/mon_image:v1.0
   ```

**Obtention** :
1. Récupérer une image depuis un registre
   ```bash
   docker pull nom_image:tag
   ```
2. Ou directement via la commande run (qui fera un pull automatiquement)
   ```bash
   docker run nom_image:tag
   ```

Les images peuvent être partagées via Docker Hub (registre public), des registres privés (comme Docker Registry, Harbor, Nexus), ou des registres cloud (AWS ECR, Google Artifact Registry, Azure Container Registry).

### 14. Comment s'appelle l'image de plus petite taille possible ? Que contient-elle ?
L'image Docker de plus petite taille possible s'appelle **scratch**. C'est une image vide qui ne contient absolument rien - pas de système de fichiers, pas de shell, pas d'utilitaires, pas même de libc.

L'image `scratch` est principalement utilisée :
- Comme point de départ pour construire des images minimalistes
- Pour les applications compilées statiquement (Go, Rust) qui n'ont pas besoin de dépendances externes
- Pour des binaires qui contiennent tout ce dont ils ont besoin pour s'exécuter

Pour l'utiliser dans un Dockerfile, on écrit simplement :
```dockerfile
FROM scratch
```

### 15. Par quel moyen le client docker communique avec le serveur dockerd ? Est-il possible de communiquer avec le serveur via le protocole HTTP ? Pourquoi ?
Le client Docker communique avec le démon Docker (dockerd) principalement via un **socket Unix** (`/var/run/docker.sock`) sur les systèmes Unix/Linux, ou via un **named pipe** (`//./pipe/docker_engine`) sur Windows.

Il est possible de configurer dockerd pour qu'il écoute sur un socket TCP et accepte des communications HTTP, mais **cela n'est pas recommandé pour des raisons de sécurité**. Par défaut, cette option est désactivée.

Raisons pour lesquelles la communication HTTP non sécurisée n'est pas recommandée :
- Pas d'authentification par défaut
- Pas de chiffrement des communications
- Exposition potentielle à des attaques réseau
- Accès root au système hôte via l'API Docker

Pour les communications à distance, il est préférable d'utiliser HTTPS avec TLS mutuel (mTLS) pour authentifier les clients et chiffrer les communications.

### 16. Un conteneur doit lancer un processus par défaut que l'on pourra override à l'execution. Quelle commande faut-il utiliser pour lancer ce processus : CMD ou ENTRYPOINT ?
Pour définir un processus par défaut qui peut être remplacé à l'exécution, il faut utiliser la commande **CMD** dans le Dockerfile.

- **CMD** définit des commandes et/ou paramètres par défaut qui peuvent être complètement remplacés lors de l'exécution en spécifiant une nouvelle commande après le nom de l'image dans `docker run`.

Exemple :
```dockerfile
# Dans le Dockerfile
CMD ["echo", "Hello World"]
```

```bash
# L'exécution par défaut affichera "Hello World"
docker run mon_image

# On peut remplacer la commande par défaut
docker run mon_image echo "Bonjour Monde"
```

À l'inverse, **ENTRYPOINT** définit l'exécutable principal qui sera toujours exécuté au démarrage du conteneur. Les arguments passés à `docker run` sont ajoutés comme paramètres à cet exécutable plutôt que de le remplacer.

