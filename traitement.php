<?php

session_start();

if(isset($_GET["action"])) {
    switch($_GET["action"]) {
        case "register":
            $pdo = new PDO("mysql:host=localhost;dbname=php_hash;charset=utf8", "root", "");
            
            //filtrer les champs du form pour lutter contre la faille XSS
            $pseudo = filter_input(INPUT_POST, "pseudo", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_VALIDATE_EMAIL);
            $pass1 = filter_input(INPUT_POST, "pass1", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $pass2 = filter_input(INPUT_POST, "pass2", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            if($pseudo && $email && $pass1 && $pass2) {
          
                //on vérifie que l'mail n'existe aps déja
                $requete =$pdo->prepare("SELECT *
                 FROM user
                 WHERE email = :email");
                 $requete->execute(["email" => $email]);
                 $user = $requete->fetch();
                 //si l'utilisateur existe
                 if($user) {
                    header ("Location: register.php"); exit;
                 } else {
                         //var_dump("Utilisateur inexistant");die;
                         //insertion de l'utilisateur en BDD

                         if($pass1==$pass2 && strlen($pass1)>= 5) {
                            $insertUser = $pdo->prepare
                            //dans le forum utiliser la méthode add au lieu de insert into
                            ("INSERT INTO user
                            (pseudo, email, password) VALUES (:pseudo, :email, :password)");
                            $insertUser->execute([
                                "pseudo"=>$pseudo,
                                "email"=> $email,
                                "password"=> password_hash($pass1, PASSWORD_DEFAULT)
                            ]);
                            header ("Location: login.php"); exit;
                            } else {
                                //"Les messages ne sont pas identiques ou mot de passe trop court"

                            }
                        }
                    } else {
                        //probleme de saisie dans les champs de formulaire
                    }
                    header("Location: register.php"); exit;
            break;

            case "login":
                if($_POST["submit"]) {
                    $pdo = new PDO("mysql:host=localhost;dbname=php_hash;charset=utf8", "root", "");

                    //filtrer les champs
                    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_VALIDATE_EMAIL);
                    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                    if($email && $password) {
                        //permet de lutter contre la faille d'injection SQL
                        $requete = $pdo->prepare("SELECT *
                        FROM user
                        WHERE email = :email");
                        $requete->execute(["email" => $email]);
                        $user = $requete->fetch();
                        //var_dump($user);die;
                        //est ce que user existe
                        if($user) {
                            //password de la base de donnée
                            $hash = $user["password"];
                            if(password_verify($password, $hash)) {
                                $_SESSION["user"] = $user;
                                header("Location: home.php");exit;
                            } else {
                                header("Location:login.php");exit;
                                //msg utilisateur inconnu ou mdp inccorect
                            }
                        } else {
                            //msg user inconnu ou mdp incorrect
                            header("Location:login.php");exit;
                        }
                    }
                }
            
       
                //connexion à l'application
                header ("Location: login.php"); exit;
            break;

            case"profil":
                header("Location: profil.php");exit;
            break;

            case "logout":
                unset($_SESSION["user"]);
                header("Location: home.php");exit;
                break;

            }
        }