<?php

/*
// création code HTML
$donnees = $data['donnees'];
$erreurs = $data['erreurs'];
$valeur = isset($donnees['valeur']) ? $donnees['valeur'] : "";
$id = isset($donnees['id']) ? $donnees['id'] : "";
$action = isset($data['action']) ? $data['action'] : "";
$erreurValeur = isset($erreurs['valeur']) ? $erreurs['valeur'] : "";

$actionForm = BASE_PATH."score/".$action."/".$id;
$annuleForm = BASE_PATH."score/lister";
$corps = <<<EOT
<form id="creation-form" name="creation-form" method="post" action="$actionForm">
<label for="valeur">Score</label>
<input id="valeur" type="text" name="valeur" value="$valeur" required aria-required="true" />
<p class="erreur">$erreurValeur</p>
<br><br>
<input type="button" value="Annuler" onclick="location.href='$annuleForm'" />
<button name='submit' type='submit' id='submit'>Valider</button>
<input type='hidden' name='id' value='$id'/>
</form>
EOT;

date_default_timezone_set("Europe/Paris");
            $heure=date("G");
            $minute=date("i");
            $seconde=date("s");
//echo $heure." ".$minute."  ".$seconde;
$corps ="<h1>".$heure." ".$minute."  ".$seconde."</h1>";
*/
$corps = '
<div id="myCarousel" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                <li data-target="#myCarousel" data-slide-to="1"></li>
                <li data-target="#myCarousel" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <svg class="bd-placeholder-img" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img">
                        <img alt="glace chocolat" src="'.BASE_PATH."ressources/1.jpg".'" /></svg>
<div class="container">
    <div class="carousel-caption text-left">
        <h1 class="black">Nouvelle recrue 🍦</h1>
        <p class="catalan_yellow">
            Le caractère du chocolat, le craquant de l’amande, la douceur du miel ...</p>
        <p class="catalan_red">
            Venez découvrir la nouvelle crème glacée Chocolat miel amande 😁😍
        </p>

        <p><a class="btn btn-lg btn-primary" href="#" role="button">Commander</a></p>
    </div>
</div>
</div>
<div class="carousel-item">
    <svg class="bd-placeholder-img" width="100%" height="100%" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img">
        <img alt="glace à emporter" src="'.BASE_PATH."ressources/2.jpg".'" /></svg>
<div class="container">
    <div class="carousel-caption">
        <h1 class="black">A EMPORTER</h1>
        <p class="catalan_yellow">
            À partager entre amis autour d’un repas ou en famille le soir tranquillement dans le canap’ ... 🍦 </p>
        <p class="catalan_red">
            Les 3/4 de litre sont à commander et à venir récupérer en boutique 🎈
        </p>
        <p><a class="btn btn-lg btn-primary" href="#" role="button">Commander</a></p>
    </div>
</div>
</div>
<div class="carousel-item">
    <svg class="bd-placeholder-img" width="100%" height="100%" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img">
        <img alt="glace à emporter" src="'.BASE_PATH."ressources/3.jpg".'" /></svg>
<div class="container">
    <div class="carousel-caption text-right">
        <div class="black">
            <h1>BISCUIT</h1>
            <p>
                Celui qui met tout le monde d’accord 👉🏻 Boule Coco 😍</p>
            <p><a class="btn btn-lg btn-primary" href="#" role="button">Nous contacter</a></p>
        </div>
    </div>
</div>
</div>
</div>
<a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
</a>
<a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
</a>
</div>


<!-- Marketing messaging and featurettes
  ================================================== -->
<!-- Wrap the rest of the page in another container to center all the content. -->

<div class="container marketing">
    <!-- Three columns of text below the carousel -->
    <div class="row">
        <div class="col-lg-4">
            <img width="140" height="140" class="bd-placeholder-img rounded-circle" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="true" role="img" alt="mec à emporter" title="Mathieu Richard, l &#39 employé de l&#39année !" src='.BASE_PATH."ressources/f.jpg".' />
            <h2>Fils</h2>
            <p>Blabla le meilleur, blabla fête ses 21 ans!!!! Un mec en or, malheureusement pour les demoiselles déjà en couple :p puisse-t-il continuer à vieillir aussi bien ! </p>
            <p><a class="btn btn-secondary" href="https://www.facebook.com/mathieu.richard.3363" role="button">pour pouvoir le draguer c&#39est ici ! &raquo;</a></p>
        </div><!-- /.col-lg-4 -->
        <div class="col-lg-4">
            <svg class="bd-placeholder-img rounded-circle" width="140" height="140" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: 140x140">
                <title>Placeholder</title>
                <rect width="100%" height="100%" fill="#777" /><text x="50%" y="50%" fill="#777" dy=".3em">140x140</text>
            </svg>
            <h2>Père</h2>
            <p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh.</p>
            <p><a class="btn btn-secondary" href="#" role="button">En savoir plus &raquo;</a></p>
        </div><!-- /.col-lg-4 -->
        <div class="col-lg-4">
            <svg class="bd-placeholder-img rounded-circle" width="140" height="140" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: 140x140">
                <title>Placeholder</title>
                <rect width="100%" height="100%" fill="#777" /><text x="50%" y="50%" fill="#777" dy=".3em">140x140</text>
            </svg>
            <h2>Fille</h2>
            <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
            <p><a class="btn btn-secondary" href="#" role="button">Contacter Pauline &raquo;</a></p>
        </div><!-- /.col-lg-4 -->
    </div><!-- /.row -->


    <!-- START THE FEATURETTES -->

    <hr class="featurette-divider">

    <div class="row featurette">
        <div class="col-md-7">
            <h2 class="featurette-heading">Le début d&#39une entreprise. <span class="text-muted"> Toujours familiale</span></h2>
            <p class="lead">Un début dans la plus catalane de toutes les villes: Collioure. Ville aimée des peintres et des catalans qui aiment s&#39y reposer en contemplant la belle mer méditerranée.Blablbla. </p>
        </div>
        <div class="col-md-5">
            <br><br><br>
            <img src='.BASE_PATH."ressources/collioure.jpg".' class="bd-placeholder-img bd-placeholder-img-lg featurette-image img-fluid mx-auto" width="500" height="500" preserveAspectRatio="xMidYMid slice" focusable="true" role="img" aria-label="" title="Collioure la catalane !">
        </div>
    </div>

    <hr class="featurette-divider">

    <div class="row featurette">
        <div class="col-md-7 order-md-2">
            <h2 class="featurette-heading">Oh yeah, c&#39est joli ! <span class="text-muted">Regarde ici :O.</span></h2>
            <p class="lead">Produit sucré et aromatisé obtenu par glaçage d&#39un mélange pasteurisé, à base de lait, crème ou beurre et d&#39œufs (glace aux œufs), de sirop et de fruit (glace au sirop, sorbet).</p>
        </div>
        <div class="col-md-5 order-md-1">
            <svg class="bd-placeholder-img bd-placeholder-img-lg featurette-image img-fluid mx-auto" width="500" height="500" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: 500x500">
                <title>Placeholder</title>
                <rect width="100%" height="100%" fill="#eee" /><text x="50%" y="50%" fill="#aaa" dy=".3em">500x500</text>
            </svg>
        </div>
    </div>

    <hr class="featurette-divider">

    <div class="row featurette">
        <div class="col-md-7">
            <h2 class="featurette-heading">Et enfin un dernier. <span class="text-muted">Look.</span></h2>
            <p class="lead">Qui appartient à la Catalogne. La langue catalane, ou, substantivement, le catalan, langue parlée dans la Catalogne, qui est un des idiomes romans et qui a les plus grandes affinités avec l&#39ancien provençal.
            </p>
        </div>
        <div class="col-md-5">
            <svg class="bd-placeholder-img bd-placeholder-img-lg featurette-image img-fluid mx-auto" width="500" height="500" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: 500x500">
                <title>Placeholder</title>
                <rect width="100%" height="100%" fill="#eee" /><text x="50%" y="50%" fill="#aaa" dy=".3em">500x500</text>
            </svg>
        </div>
    </div>

    <hr class="featurette-divider">

    <!-- /END THE FEATURETTES -->

</div><!-- /.container -->
';

//gestion du highlight de la navbar (active)
$Endscript = <<<anl
<script>
$(document).ready(function(){
   $(".nav-item.active").removeClass("active");
   $("#histoire").addClass("active");
});
</script>
anl;


// insertion dans le gabarit
require_once (dirname(__FILE__)."/../_config/gabarit.php");

?>
