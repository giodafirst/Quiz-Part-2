<?php
/*Remplacer le mock par la base de données
Réécrire les scripts de façon à accéder aux données dans la base de données
Affichage de la liste des quiz
Recherche de quiz
Affichage d'un quiz sélectionné*/

require('config.php');

session_start();

if(isset($_FILES['photo'])){
    if($_FILES['photo']['error']==0){
        if($_FILES['photo']['size']<300000 && $_FILES['photo']['type']=='image/png'){
            $source = $_FILES['photo']['tmp_name'];
            $destination = getcwd().'/images/quiz-logo.png';
            var_dump('$destination');
                if(move_uploaded_file($source,$destination)){
                    echo "<p>Le fichier est valide et a été téléchargé avec succès</p>";
                }else{
                    echo "<p>Erreur lors du téléchargement</p>";
                }
        }else{
            echo "<p>Une erreur est survenue !</p>";    
        }
    }
}

//Déclaration des variables
$message = "";


if(!empty($_GET['keyword'])){
    $keyword = $_GET['keyword'];
}

//Connexion au serveur
$mysqli = @mysqli_connect ('localhost','root',''); //var_dump($mysqli);

if($mysqli){
    //Connexion à la Base de Données
    if(mysqli_select_db($mysqli,'DBquiz')){
        if(empty($keyword)){
            //Requêtes
            $query = "SELECT * FROM quiz";
        } else {
            $query = "SELECT * FROM quiz WHERE titre LIKE '%$keyword%'";
        }
        //Extraction des résultats
        $result = mysqli_query($mysqli,$query); //var_dump($result);
        if($result){
            $quiz = mysqli_fetch_all($result); //var_dump($quiz);
            //Libération de la mémoire des résultats
            mysqli_free_result($result);
        } else {
            $message = "Erreur de requête !";
        }
    } else {
        $message = "Base de données inconnue !";
    } 
    //Fermeture de la connexion
    mysqli_close($mysqli);           
} else {
    $message = "Erreur de connexion !";
} //var_dump($message);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Projet Quiz</title>
</head>
<body id="body">
    <header>
        <div id="logo">
            <a href="#">
                <img src="images/quiz-logo.png" alt="logo quiz">
            </a>
            
        </div>
        <h1 id="titre">Projet Quiz</h1>
    </header>
    <section id="section">
        <form action="<?= $_SERVER['PHP_SELF']?>" method="get" name="frm">
            <label for="keyword">Recherche du quiz :</label>
            <input type="search" id="keyword" name="keyword" for="keyword">
            <button>Chercher</button>
        </form><br><br>
        <div>
            <?php foreach($quiz as $value){?>
                <ul>
                    <li id="liste"><?= $value[2] ?></li>
                        <ul>
                            <img src="<?= $value[9] ?>" alt="image">
                            <li id="info"><?= $value[3]?></li><br><br>
                        </ul>
                </ul>
            <?php }?>
        </div><br><br>
            <h2>Changer de logo</h2>
                <form enctype="multipart/form-data" action="<?= $_SERVER['PHP_SELF'];?>" method="post">
                <input type="hidden" name="MAX_FILE_SIZE" value="300000">
                <label>Photo</label><br>
                <input name="photo" type="file"><br>
            <br>
                <input type="submit" value="Modifier le logo">
        </form>
    </section>
    <footer>
        <h3 class="footer">Copyright 2022.</h3>
            <a href="">
                <h3 class="footer">Conditions d'utilisation</h3>
            </a>   
    </footer>       
</body>
</html>