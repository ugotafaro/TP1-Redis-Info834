# TP1-Redis-Info834

## Lien github 
https://github.com/lilcdx/TP1-Redis-Info834.git

## Objectif 
Empêcher aux clients d'un site web trop de connexions en trop peu de temps (maximum 10 toutes les 10 min), en passant par un serveur Redis.

## Lancer le programme

1. Utiliser Wampserver pour lancer une base de données phpmyadmin et les pages web reliées
2. Créer la base de données suivante dans phpmyadmin :
CREATE TABLE user (
    id SERIAL AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(50),
    password VARCHAR(50)
);

3. Lancer un serveur Redis en lançcant dans votre répertoire Redis la commande redis-server. Vérifiez bien que le serveur se lance sur le port 6379 (par défaut normalement)

4. Pour accéder au site web, http://localhost/TP1-Redis-Info834/web/login.php (en ayant lancé Wampserver)

## Problemes
Malheureusement, je n'ai pas réussi la liaison python et php. Ce n'est donc pas possible d'accéder au vrai fonctionnement depuis le site web. Après plusieurs tentatives de résolutions, j'ai été bloquée car la commande exécutée sur le terminal fonctionne et correspond exactement à celle du php. Pour tester les scripts Python :

1. Créer un utilisateur dans le serveur Redis avec la commande : python ../script_redis_save_user.py ../user.json
Le json est mis à jour quand un nouvel utilisateur (pas présent dans la BDD) se connecte
Cet utilisateur sera enregistré dans le serveur Redis avec cette commande

2. Tester la possibilité de la connexion avec : python ../script_redis_connect.py email 
Avec email l'email d'un utilisateur existant dans le serveur Redis.
Si vous tentez de vous connectez plus de 10 fois en moins de 10min, vous observerez en effet que le serveur demande d'attendre.