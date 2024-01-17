https://codeshare.io/vwyldm
https://github.com/public-apis/public-apis

1. Choix API :
https://apidocs.cheapshark.com

2. Consommer l'API avec Postman :
- Avec l'API choisi, utiliser Postman afin de lister différentes ressources selon l'URL
- Utiliser les ressources d'une réponse API dans une autre

3. Consommer l'API avec PHP :
	- Avec l'API choisi, utiliser PHP pour reproduire le même fonctionnement
	-> Le paramètre URL " page " permet de récupérer la page souhaitée ("deals", "games")
  -> Le ?paramètre URL " id " permet de récupérer un seul élément
  -> Pour la page "game", ajouter le paramètre URL "title" en obligatoire
  
  Exemple : /index.php?page=games&title=batman --> Liste tous les jeux ayant comme titre "batman"
  			/index.php?page=deals --> Liste tous les deals
            /index.php?page=games&id=124 --> Liste le jeu ayant l'id "124"
            /index.php?page=deals&id=29w9gv08PYo8%2Bo%2FOHfN1fZVlm8K5EsaN5sTlSXK6eMg%3D --> Liste le deal 
  
4. Sauvegarde des données API avec PHP :
- En reprenant le point 3, sauvegarder dans une base de données (SQLite par exemple)
	les données qui vous intéressent
  
  
5. Utilisation des données BDD avec PHP :
- Ajouter une condition de vérification de présence des données demandée (id) dans la BDD
	-> Si n'existe pas, créer automatiquement selon les données de l'API
- Faire un retour tableau JSON des données récupérer depuis la BDD.

6. Sécurité par Token API :
- Créer une table "users" avec comme colonnes :
	-> id (C'est logique !)
  -> name
  -> apitoken (varchar 64)
- Générer un token aleatoire (https://generate-random.org/api-key-generator?count=1&length=64&type=mixed-numbers&prefix=)
- Avec le paramètre d'URL "apikey", restreindre l'accès à votre API par token existant
- Vérifier que l'apitoken fasse bien 64 caractères et que c'est un alphanumeric
	-> Des fonctions existes pour les 2 vérifications

7. Utiliser Postman sur votre API

8. Toujours sur Postman, passer l'API en "API KEY" header (dans "authorization") au lieu de l'utiliser en paramètre
	(info: Il faut utiliser le header dans le code php)
  --> Ceux ayant utiliser "Bearer Token" ont un bonus

9. Exporter le fichier Postman

10. Utilisation du htaccess pour le Rewrite URL : https://www.generateit.net/mod-rewrite/

A rendre avant le 23/01/24