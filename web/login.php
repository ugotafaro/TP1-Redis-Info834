<?php

// Connexion à la base de données
    
$bdd = new PDO('mysql:host=localhost;dbname=tp1-redis-info834', 'root', '');

// Récupération des infos de l'utilisateur
if(isset($_POST['email']) && isset($_POST['password'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = $bdd->prepare('SELECT * FROM user WHERE email = :email');
    $query->execute(array(':email' => $email));
    $user = $query->fetch();

    # L'utilisateur n'existe pas : on le crée
    if ($user === false) {

        # creation du nouvel utilisateur dans la BDD
        $query = $bdd->prepare('INSERT INTO user (email, password) VALUES (:email, :password)');
        $query->execute(array(':email' => $email, ':password' => $password));   
        
        # creation du nouvel utilisateur dans le serveur Redis
        $script = "../script_redis_save_user.py";
        $data = array('email' => $email, 'password' => $password);
        $json_data = json_encode($data);
        $json_file = '../user.json';
        file_put_contents($json_file, $json_data);
        $command = "python $script $json_file";
        $output = shell_exec($command);

        # Accès à la page d'accueil
        header("Location: accueil.php");
    
    } else {
        # Utilisateur existe mais mauvais mot de passe
        if ($password != $user['password']){
            echo 'Mot de passe incorrect, réessayez';

        # Connexion réussie à un utilisateur existant 
        } else {  
            # appel du script python qui gère les connexions
            $script = "../script_redis_connect.py";
            $command = "python $script $email";
            $output = shell_exec($command); 
            $answer = substr($output, -2);

            if ($answer == 1) {
                header('Location: accueil.html');
                exit;
            } else {
                echo "Vous vous êtes connecté 10 fois en moins de 10 min, merci d'attendre pour accéder aux services";
            }
            exit;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div>
        <form method="post"> 
            <label for="email"> Email: </label> 
            <input type="text" 
                   id="email"
                   name="email" 
                   placeholder="Enter your Username" required> 
  
            <label for="password"> Password: </label> 
            <input type="password"
                   id="password" 
                   name="password" 
                   placeholder="Enter your Password" required> 
  
            <div class="wrap"> 
                <button type="submit"> Submit </button> 
            </div> 
        </form> 
    </div>
</body>
</html>