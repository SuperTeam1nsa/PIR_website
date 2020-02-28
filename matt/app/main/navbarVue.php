<?php

$navbar='<header>
        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
            <a class="navbar-brand" href="#"><img src=\'<?php echo BASE_PATH."favicon.ico" ?>'> Saveurs d'Antan</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" id="histoire" href=\'<?php echo BASE_PATH."main/show" ?>\'>Histoire </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="glace" href=\'<?php echo BASE_PATH."main/showIceCreams" ?>\'>Glaces</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="commandes" href=\'<?php echo BASE_PATH."main/showForm" ?>\'>Commandes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="Contact" href="#">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="https://www.facebook.com/Saveurs-dAntan-Collioure-957790424586304/" title="Suivez-nous sur facebook !"><img class="logo" src="\'<?php echo BASE_PATH."ressources/facebook.png" ?>'" alt='facebook ' /></a>

                    </li>

                </ul>

            </div>
        </nav>
    </header>'

?>