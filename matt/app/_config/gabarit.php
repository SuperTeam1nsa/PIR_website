<!doctype html>
<html lang="fr">
<!-- todo: redimensionnenement out des logos : évite de charger + de reseau (meme si à la marge) -->


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="RF">
    <link rel="shortcut icon" type="image/x-icon" href='<?php echo BASE_PATH."favicon.ico" ?>'>
    <title>PIR: voiture autonome  </title>
    <link rel="stylesheet" href="<?php echo BASE_PATH."css/style.css"; ?>" type="text/css">

    <!--<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
-->
    <!-- cf :https://www.bootstrapcdn.com/bootswatch/ 
    
--sketchy 
        <link href="https://stackpath.bootstrapcdn.com/bootswatch/4.3.1/sketchy/bootstrap.min.css" rel="stylesheet" integrity="sha384-N8DsABZCqc1XWbg/bAlIDk7AS/yNzT5fcKzg/TwfmTuUqZhGquVmpb5VvfmLcMzp" crossorigin="anonymous">
    <link href="https://stackpath.bootstrapcdn.com/bootswatch/4.3.1/united/bootstrap.min.css" rel="stylesheet" integrity="sha384-WTtvlZJeRyCiKUtbQ88X1x9uHmKi0eHCbQ8irbzqSLkE0DpAZuixT5yFvgX0CjIu" crossorigin="anonymous">
-->
    <link href="https://stackpath.bootstrapcdn.com/bootswatch/4.3.1/cerulean/bootstrap.min.css" rel="stylesheet" integrity="sha384-C++cugH8+Uf86JbNOnQoBweHHAe/wVKN/mb0lTybu/NZ9sEYbd+BbbYtNpWYAsNP" crossorigin="anonymous">



</head>

<body>
    <header>
        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
            <a class="navbar-brand" href="#"><img src='<?php echo BASE_PATH."favicon.ico" ?>'> Saveurs d'Antan</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" id="histoire" href='<?php echo BASE_PATH."main/show" ?>'>Histoire </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="glace" href='<?php echo BASE_PATH."main/showIceCreams" ?>'>Glaces</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="commandes" href='<?php echo BASE_PATH."main/showForm" ?>'>Commandes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="Contact" href="#">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="https://www.facebook.com/Saveurs-dAntan-Collioure-957790424586304/" title="Suivez-nous sur facebook !"><img class="logo" src='<?php echo BASE_PATH."ressources/facebook.png" ?>' alt='facebook ' /></a>

                    </li>

                </ul>

            </div>
        </nav>
    </header>

    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <!-- prod 
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.0"></script>
-->
    <main role="main" id="main">


        <?php echo $corps ?>

    </main>

    <?php if(isset($script)) echo $script ?>

    <!-- FOOTER -->
    <footer class="container">
        <p class="float-right"><a href="#">Début ⇑</a></p>
        <p>&copy; 2019 Saveurs d'Antan </p>
        <!--&middot; -->
    </footer>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <?php if(isset($Endscript)) echo $Endscript ?>

</body>

</html>
