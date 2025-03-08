# Exercice 1 : Validation d'acquis

## Questions et réponses

### 1. Qu'est ce qu'un conteneur ?
Un conteneur est comme une mini-machine virtuelle très légère. En fait, c'est un environnement isolé qui contient tout ce dont une application a besoin pour fonctionner correctement : le code, les bibliothèques, les fichiers de configuration, etc. 

L'avantage principal est que peu importe où je lance mon conteneur (sur mon ordinateur portable, sur un serveur de production ou dans le cloud), l'application fonctionnera exactement de la même façon. C'est cette portabilité qui rend les conteneurs si pratiques pour le développement et le déploiement d'applications.

### 2. Est-ce que Docker a inventé les conteneurs Linux ? Qu'a apporté Docker à cette technologie ?
Non, Docker n'a pas inventé les conteneurs Linux. Cette technologie existait déjà depuis plusieurs années sous différentes formes comme LXC (Linux Containers).

Ce que Docker a apporté, c'est une façon beaucoup plus simple et accessible d'utiliser cette technologie. Avant Docker, il fallait être un expert Linux pour utiliser des conteneurs efficacement. Docker a créé:
- Une interface utilisateur intuitive avec des commandes simples
- Un système standardisé pour créer et partager des images
- Un écosystème complet avec Docker Hub pour télécharger des images prêtes à l'emploi
- Des fichiers Dockerfile qui permettent de décrire l'environnement d'une application comme du code

Docker a démocratisé l'utilisation des conteneurs et les a rendus accessibles à tous les développeurs.

### 3. Pourquoi est-ce que Docker est particulièrement pensé et adapté pour la conteneurisation de processus sans états (fichiers, base de données, etc.) ?
D'après mon expérience, Docker est idéal pour les applications sans état car les conteneurs sont conçus pour être éphémères par nature. 

Quand je lance un conteneur, tout ce qui est écrit à l'intérieur disparaîtra lorsque le conteneur sera détruit, sauf si j'utilise des volumes pour sauvegarder ces données. Cette approche "jetable" est parfaite pour des applications comme les serveurs web ou les API, où chaque requête est indépendante.

Pour les bases de données ou tout service qui a besoin de stocker des informations de façon permanente, il faut ajouter une couche supplémentaire avec des volumes Docker. C'est faisable, mais demande plus de configuration et ne correspond pas au modèle idéal pour lequel Docker a été conçu initialement.

J'ai appris qu'en microservices, on sépare généralement les services sans état (stateless) des services avec état (stateful) pour cette raison même.

### 4. Quel artefact distribue-t-on et déploie-t-on dans le workflow de Docker ? Quelles propriétés désirables doit-il avoir ?
Dans Docker, l'artefact principal qu'on distribue et déploie est l'**image Docker**. 

J'ai compris qu'une bonne image Docker devrait avoir ces caractéristiques:
- **Être légère**: Plus l'image est petite, plus elle est rapide à télécharger et à démarrer
- **Être sécurisée**: Sans vulnérabilités connues et avec le minimum de composants nécessaires
- **Être déterministe**: La même image devrait donner le même résultat à chaque fois
- **Être bien documentée**: Avec des métadonnées claires et des instructions d'utilisation
- **Être construite en couches**: Pour optimiser le cache et accélérer les builds futurs

Pour mes projets, j'essaie toujours de créer des images aussi petites que possible, en partant d'images de base minimalistes comme Alpine Linux et en n'incluant que ce qui est absolument nécessaire.

### 5. Quelle est la différence entre les commandes docker run et docker exec ?
Après avoir utilisé Docker pendant un moment, j'ai bien saisi la différence entre ces deux commandes:

- `docker run` crée et démarre un tout nouveau conteneur à partir d'une image. C'est comme allumer un nouvel ordinateur virtuel avec un système d'exploitation fraîchement installé.

- `docker exec` me permet d'exécuter une commande supplémentaire dans un conteneur qui tourne déjà. C'est comme si j'ouvrais un nouveau terminal sur un ordinateur déjà allumé.

Par exemple, quand je développe, j'utilise souvent `docker run` pour démarrer mon application, puis `docker exec` pour me connecter en bash à ce conteneur et inspecter ce qui se passe à l'intérieur.

### 6. Peut-on lancer des processus supplémentaires dans un même conteneur docker en cours d'execution ?
Oui, c'est tout à fait possible avec la commande `docker exec`. 

Cependant, j'ai appris que ce n'est pas vraiment la philosophie de Docker. Le principe "un processus par conteneur" est généralement conseillé car il permet:
- Une meilleure isolation des services
- Une gestion plus simple des ressources
- Plus de facilité pour surveiller et relancer les processus en cas de problème

Dans mes projets, je préfère séparer les différents services dans des conteneurs distincts et les faire communiquer via le réseau, plutôt que d'avoir plusieurs processus dans un seul conteneur.

### 7. Pourquoi est il fortement recommandé d'utiliser un tag précis d'une image et de ne pas utiliser le tag latest dans un projet Docker ?
J'ai appris cette leçon à mes dépens! Utiliser le tag `latest` peut sembler pratique au début, mais ça peut vite devenir un cauchemar:

- Une image taguée `latest` aujourd'hui peut être complètement différente de la même image taguée `latest` demain
- Si votre application casse après un déploiement, il est difficile de savoir quelle version exacte causait le problème
- Impossible de revenir facilement à une version précédente qui fonctionnait

Maintenant, j'utilise toujours des tags spécifiques comme `python:3.9.7-slim` ou `node:16-alpine` dans mes projets. Ça garantit que mon environnement reste stable et que je peux reproduire exactement le même environnement à chaque déploiement.

### 8. Décrire le résultat de cette commande : docker run -d -p 9001:80 --name my-php -v "$PWD":/var/www/html php:8.2-apache.
Cette commande lance un serveur web PHP avec Apache. Voici ce qu'elle fait précisément:

- `-d` fait tourner le conteneur en arrière-plan
- `-p 9001:80` connecte le port 80 du conteneur (le port standard d'un serveur web) au port 9001 de ma machine locale
- `--name my-php` donne un nom facile à retenir au conteneur
- `-v "$PWD":/var/www/html` connecte mon dossier actuel au dossier `/var/www/html` dans le conteneur, qui est l'endroit où Apache cherche les fichiers à servir
- `php:8.2-apache` utilise l'image officielle PHP 8.2 avec Apache préinstallé

Après avoir exécuté cette commande, je peux ouvrir mon navigateur et aller sur http://localhost:9001 pour voir mon application PHP. L'avantage est que je peux modifier mes fichiers PHP localement et voir les changements instantanément sans redémarrer le conteneur.

### 9. Avec quelle commande docker peut-on arrêter tous les conteneurs ?
La commande que j'utilise pour arrêter tous mes conteneurs en même temps est:

```bash
docker stop $(docker ps -q)
```

Cette commande fonctionne en deux parties:
- `docker ps -q` liste uniquement les IDs de tous les conteneurs en cours d'exécution
- `docker stop` prend ces IDs et arrête proprement chaque conteneur

Cette commande m'a sauvé plusieurs fois quand je développais avec de nombreux conteneurs et que je voulais tout arrêter rapidement.

### 10. Quelles précautions doit-on prendre pour construire une image afin de la garder de petite taille et faciliter sa reconstruction ?
À force de travailler avec Docker, j'ai développé quelques bonnes pratiques:

1. Je commence toujours avec une image de base légère comme Alpine Linux
2. J'essaie de combiner plusieurs commandes RUN en une seule avec `&&` pour réduire le nombre de couches
3. Je nettoie toujours les caches et fichiers temporaires après l'installation des paquets:
   ```dockerfile
   RUN apt-get update && apt-get install -y package && \
       apt-get clean && rm -rf /var/lib/apt/lists/*
   ```
4. J'utilise les builds multi-étapes pour séparer la compilation et l'exécution
5. Je crée un fichier `.dockerignore` pour exclure les fichiers inutiles (comme node_modules)
6. J'ordonne mes instructions Dockerfile du moins changeant au plus changeant pour optimiser le cache

Ces techniques m'ont permis de réduire considérablement la taille de mes images et d'accélérer le processus de build.

### 11. Lorsqu'un conteneur s'arrête, tout ce qu'il a pu écrire sur le disque ou en mémoire est perdu. Vrai ou faux ? Pourquoi ?
C'est partiellement vrai, et j'ai dû bien comprendre cette nuance:

- Les données en **mémoire** sont toujours perdues quand un conteneur s'arrête
- Les données écrites sur le **disque dans le conteneur** ne sont pas perdues immédiatement quand on arrête le conteneur avec `docker stop`
- Ces données sur disque sont seulement perdues quand on supprime le conteneur avec `docker rm`

Pour conserver des données de façon permanente, j'utilise toujours:
- Des volumes Docker: `docker volume create mon_volume` puis `-v mon_volume:/data`
- Ou des montages depuis mon système hôte: `-v /chemin/local:/chemin/conteneur`

J'ai perdu des données importantes une fois en oubliant cette règle, maintenant j'y fais très attention!

### 12. Lorsqu'une image est crée, elle ne peut plus être modifiée. Vrai ou faux ?
C'est vrai! Une fois créée, une image Docker est immuable.

J'ai trouvé ça déroutant au début, mais maintenant je comprends pourquoi c'est si important: cette immuabilité garantit que l'image que j'ai testée en développement est exactement la même que celle qui tournera en production.

Pour "modifier" une image, je dois en réalité:
- Soit reconstruire une nouvelle image à partir du Dockerfile
- Soit créer un conteneur basé sur l'image existante, y faire des modifications, puis créer une nouvelle image avec `docker commit`

Ce principe d'immuabilité est fondamental pour la reproductibilité des environnements.

### 13. Comment peut-on publier et obtenir facilement des images ?
Voici comment je partage mes images Docker:

Pour publier:
1. Je crée un compte sur Docker Hub
2. Je tague mon image locale: `docker tag mon_app:1.0 monusername/mon_app:1.0`
3. Je me connecte: `docker login`
4. Je publie l'image: `docker push monusername/mon_app:1.0`

Pour obtenir:
- Je télécharge simplement avec `docker pull nom_image:tag`
- Ou je lance directement avec `docker run nom_image:tag` (Docker téléchargera l'image s'il ne l'a pas déjà)

J'utilise beaucoup les images officielles du Docker Hub pour gagner du temps, mais pour les projets d'entreprise, on utilise généralement un registre privé pour des raisons de sécurité.

### 14. Comment s'appelle l'image de plus petite taille possible ? Que contient-elle ?
L'image la plus petite s'appelle **scratch** et ce que j'aime avec cette image, c'est qu'elle ne contient... absolument rien!

C'est littéralement une image vide, sans système d'exploitation, sans shell, sans utilitaires, rien du tout. Sa taille est de 0 octet.

Je l'utilise parfois avec des applications Go compilées statiquement. Comme Go peut produire des binaires autonomes qui n'ont pas besoin de bibliothèques externes, je peux les placer directement dans une image scratch:

```dockerfile
FROM scratch
COPY myapp /
CMD ["/myapp"]
```

Le résultat est une image minuscule qui ne contient que mon application, ce qui réduit considérablement la surface d'attaque potentielle.

### 15. Par quel moyen le client docker communique avec le serveur dockerd ? Est-il possible de communiquer avec le serveur via le protocole HTTP ? Pourquoi ?
Le client Docker communique avec le démon Docker (dockerd) principalement via un socket Unix sur Linux (fichier `/var/run/docker.sock`).

Techniquement, on peut configurer dockerd pour accepter des connexions HTTP, mais je ne recommanderais jamais de faire ça en production, car:
- Il n'y a pas d'authentification par défaut
- Les communications ne sont pas chiffrées
- N'importe qui pourrait prendre le contrôle de votre système (car Docker a besoin d'accès root)

Si j'ai besoin d'accéder à Docker à distance, j'utilise toujours HTTPS avec TLS et des certificats clients pour l'authentification. C'est plus complexe à mettre en place, mais beaucoup plus sécurisé.

### 16. Un conteneur doit lancer un processus par défaut que l'on pourra override à l'execution. Quelle commande faut-il utiliser pour lancer ce processus : CMD ou ENTRYPOINT ?
Pour ce cas précis, j'utilise **CMD**.

La différence est subtile mais importante:
- **CMD** définit une commande par défaut qui peut être complètement remplacée lors de l'exécution
- **ENTRYPOINT** définit le programme principal qui sera toujours exécuté (les arguments après `docker run` sont ajoutés comme paramètres)

Par exemple, avec ce Dockerfile:
```dockerfile
FROM alpine
CMD ["echo", "Bonjour!"]
```

Je peux:
- Exécuter la commande par défaut: `docker run mon_image` → Affiche "Bonjour!"
- La remplacer: `docker run mon_image echo "Au revoir!"` → Affiche "Au revoir!"

C'est exactement ce qui est demandé: un comportement par défaut qu'on peut facilement modifier à l'exécution.
