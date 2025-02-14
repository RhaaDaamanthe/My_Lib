<?php
// On recupere la session courante
session_start();

// On inclue le fichier de configuration et de connexion à la base de données
include('includes/config.php');

// Si l'utilisateur n'est pas logue, on le redirige vers la page de login (index.php)
if(strlen($_SESSION['login'])==0) {
    header('location:index.php');
} else {
    // si le formulaire a ete envoye : $_POST['change'] existe
    if(isset($_POST['change'])) {
        // On recupere le mot de passe et on le crypte (fonction php password_hash)
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        
        // On recupere l'email de l'utilisateur dans le tabeau $_SESSION
        $email = $_SESSION['login'];
        
        // On cherche en base l'utilisateur avec ce mot de passe et cet email
        $sql = "SELECT * FROM tblreaders WHERE EmailId=:email";
        $query = $dbh->prepare($sql);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);
        
        // Si le resultat de recherche n'est pas vide
        if($query->rowCount() > 0) {
            // On met a jour en base le nouveau mot de passe (tblreader) pour ce lecteur
            $sql = "UPDATE tblreaders SET Password=:password WHERE EmailId=:email";
            $query = $dbh->prepare($sql);
            $query->bindParam(':password', $password, PDO::PARAM_STR);
            $query->bindParam(':email', $email, PDO::PARAM_STR);
            $query->execute();
            
            // On stocke le message d'operation reussie
            echo "<script>alert('Votre mot de passe a été modifié avec succès');</script>";
        } else {
            // sinon (resultat de recherche vide)
            // On stocke le message "mot de passe invalide"
            echo "<script>alert('Mot de passe invalide');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Gestion de bibliotheque en ligne | changement de mot de passe</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />

    <!-- Penser au code CSS de mise en forme des message de succes ou d'erreur -->
    <style>
        .error-message {
            color: #dc3545;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #dc3545;
            border-radius: 4px;
            background-color: #f8d7da;
        }
        .success-message {
            color: #28a745;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #28a745;
            border-radius: 4px;
            background-color: #d4edda;
        }
    </style>
</head>

<script type="text/javascript">
    /* On cree une fonction JS valid() qui verifie si les deux mots de passe saisis sont identiques 
    Cette fonction retourne un booleen*/
    function valid() {
        var password = document.getElementById("password").value;
        var confirmPassword = document.getElementById("confirm-password").value;
        
        if(password != confirmPassword) {
            alert("Les mots de passe ne correspondent pas!");
            return false;
        }
        return true;
    }
</script>

<body>
    <?php include('includes/header.php');?>
    
    <!--On affiche le titre de la page : CHANGER MON MOT DE PASSE-->
    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="header-line">CHANGER MON MOT DE PASSE</h4>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12 offset-md-3">
                    <div class="panel panel-info">
                        <div class="panel-body">
                            <!--On affiche le formulaire-->
                            <!-- la fonction de validation de mot de passe est appelee dans la balise form : onSubmit="return valid();"-->
                            <form role="form" method="post" onSubmit="return valid();">
                                <div class="form-group">
                                    <label>Nouveau mot de passe</label>
                                    <input class="form-control" type="password" name="password" id="password" required />
                                </div>

                                <div class="form-group">
                                    <label>Confirmer le mot de passe</label>
                                    <input class="form-control" type="password" name="confirm-password" id="confirm-password" required />
                                </div>

                                <button type="submit" name="change" class="btn btn-info">Changer</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('includes/footer.php');?>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>