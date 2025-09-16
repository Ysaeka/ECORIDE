# ECORIDE
Étapes de déploiement en local

## Prérequis :

- Docker et Docker Compose installés
- Tout est inclus dans l’image docker
- Copier/coller le contenu du fichier docker-compose.yaml qui se trouve dans le read-me du projet dans votre dossier mes documents « %HOMEPATH%\Documents »
ou cloner le fichier depuis git à cette adresse : https://github.com/Ysaeka/ECORIDE.git (branche -> main)

### docker-compose.yaml
```
services:
  ecoride:
    image: ysaeka/ecoride_karine:latest
    container_name: ecoride
    ports:
      - 80:80
    depends_on:
      - mariadb
      - mongodb
    restart: always
    environment:
      DB_HOST: mariadb
      DB_NAME: ecoride
      DB_USER: root
      DB_PASSWORD: password
      MONGO_HOST: mongodb
      MONGO_DB: ecoride_nosql
      MONGO_USER: root
      MONGO_PASSWORD: example
      APP_ENV: http://localhost
      MAIL_SERVER: smtp.gmail.com
      #MAIL_USER: mettre le votre ici
      #MAIL_PASSWORD: mettre le votre ici
    volumes:
        - uploads:/var/www/html/uploads
  mariadb:
    image: mariadb:10.4.32
    container_name: mariadb
    environment:
      MYSQL_ROOT_PASSWORD: password
      MYSQL_DATABASE: demo
    volumes:
      - mariadata:/var/lib/mysql
    ports:
      - 3306:3306
    restart: always
  mongodb:
    image: mongo:latest
    container_name: mongodb
    restart: always
    environment:
      MONGO_INITDB_ROOT_USERNAME: root
      MONGO_INITDB_ROOT_PASSWORD: example
    volumes:
      - mongodata:/data/db
    ports:
      - 27017:27017

volumes:
  mariadata:
    name: "mariadb-data"
  mongodata:
    name: "mongodb-data"
  uploads:
    name: "ecoride-uploads"
```


Configuration avant lancement :

Vous pouvez adapter la configuration selon vos besoins ou laisser les valeurs par défaut  dans le fichier docker-compose.yaml.

Aucune variable n’est optionnelle.

Variables d’environnement à adapter si besoin  : 

|Variable d’environnement|Description|     
|:--|:--|     
|DB_HOST|	Adresse du serveur MariaDb|
|DB_NAME|	Nom de la base de donnée|
|DB_USER|	Nom d’utilisateur de la base de donnée|
|DB_PASSWORD|	Mot de passe associé à l’utilisateur|
|MONGO_HOST|	Adresse du serveur MongoDb|
|MONGO_DB|	Nom de la base de donnée|
|MONGO_USER|	Nom d’utilisateur de la base de donnée|
|MONGO_PASSWORD|	Mot de passe associé à l’utilisateur|
|APP_ENV|	Adresse du site ecoride deployé ex : http://localhost|
|MAIL_SERVER|	Adresse du serveur SMTP ex : smtp.gmail.com|
|MAIL_USER|	Adresse mail utilisé pour l’envoi et la réception de mail via phpMailer, vous pouvez mettre votre propre adresse mail pour tester les mails.|
|MAIL_PASSWORD|	Mot de passe (si gmail est utilisé en serveur SMTP, il faut définir un mot de passe d’application dans le compte google) Doc google pour définir le mot de passe d’application :  https://support.google.com/accounts/answer/185833?hl=fr|

## Lancer l’application

Ouvrir un terminal et placez-vous dans le dossier dans lequel vous avez créé le fichier docker-compose.yaml et exécuter :
```
docker compose up -d
```

Tout est déjà inclus dans l’image Docker Hub (https://hub.docker.com/repository/docker/ysaeka/ecoride_karine/general) :

Code PHP/Apache

Bases MySQL et MongoDB

Scripts d’initialisation (set_up.php)

Fichiers de données (BDD_ECORIDE.sql, avis_test.json)

## Initialiser les bases de données

Dans votre navigateur, ouvrir :

```http://localhost/```

Crée automatiquement la base MySQL et la collection MongoDB

Supprime les fichiers SQL/JSON après import

Redirige vers index.php une fois terminé

## Utiliser l’application

Après l’initialisation :

```http://localhost/index.php```

## Commandes utiles

Arrêter les services :

```docker compose down```

Voir les logs du conteneur Ecoride :

```docker logs ecoride```

