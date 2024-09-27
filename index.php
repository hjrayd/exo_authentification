<?php
session_start();

$password = "monMotDePasse1234";
$password2 = "monMotDePasse1234";


//algo de hachage FAIBLE
$md5 = hash('md5', $password);
$md5_2 = hash('md5', $password2);

//echo $md5."<br>";
//echo $md5_2."<br>";

$sha256 = hash('sha256', $password);
$sha256_2 = hash('sha256', $password);

//echo $sha256."<br>";
//echo $sha256."<br>";

//algo de hachage FORT
//bcript

$hash = password_hash($password, PASSWORD_DEFAULT );
$hash2 = password_hash($password2, PASSWORD_DEFAULT );

//echo $hash."<br>";
//echo $hash2."<br>";

//argon2i
$hash3 = password_hash($password, PASSWORD_ARGON2I );

//echo $hash3."<br>";

$hash4 = password_hash($password, PASSWORD_ARGON2ID );

//echo $hash4."<br>";


//saisie dans le formulaire de login
$saisie = "monMotDePasse1234";
$user = "Mickael";

$check = password_verify($saisie, $hash);
if(password_verify($saisie, $hash)) {
    //echo "Les mdp correspondent";
    $_SESSION["user"] = $user;
    echo $user." est connecté";
} else {
    echo "les mdp sont différents";
}

//salt = chaine de caractere aleatoire qui va etre rajouter devant notre mdp hacher 
//pour renforcer notre mdp itiial donc plus difficile de le craquer
//en base de données prévoir un varchar de 255 pour le mdp
//dans le formulaire il faut mettre input type password et pas text
//filter_input permet de se protéger contre la faille XSS
