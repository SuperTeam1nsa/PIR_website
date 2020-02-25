<!DOCTYPE html>
<html lang='fr'>

<head>
    <meta charset='UTF-8'>
    <title>Dark Vador Snake! </title>
    <!-- <script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script> -->
    <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
    <link rel="stylesheet" href='<?php echo APP_PATH."game/style/style.css" ?>'>
</head>

<body>
    <!-- but : utiliser le cours de base de données +dataonly pour en faire un vrai jeu là c'est le squelette(+1 queue ^^) PS:peut-être pas refresh à chaque fois du coup :p>
    <!-- Rq avec un @media on pourrait redimensionner dynamiquement le canvas pour les petits écrans phone(fait dans l'exo des billes) +add une pause si SPACE ou clic sur un bouton pause -->

    <div id="cadre" style="display: grid;grid-gap: 0px;">
        <div id="boite0" style=" width: 200px;height: 200px;grid-column: 1;grid-row: 1;justify-self:start;">
            <footer>
                <!-- div id="txt_js"></div -->

                <!-- <input type="button" value="Reset !" onclick="javascript:window.location.reload();"> -->
                <br><br>
                <span id="speed_txt"> Choose your speed ! </span>
                <input type="range" id="speed" min="0" max="100" step="1" value="50">
                <div id="controles"> Contrôles : Any keys: start <br>P to pause<br> Enter: start again <br>
                    U/D/R/L arrow
                </div>
                <input type="button" value="Play !" id="launchgame">
                <input type="button" value="Pause" id="pause">
                <input type="button" value="Home" id="home">


            </footer>
        </div>
        <div id="boite1" style="border-style: solid;border-bottom-width:thick; grid-column: 2;grid-row: 1;justify-self:end;">


        </div>
        <br>
        <div id="boite2" style="width: 200px;height: 200px;grid-column: 3;grid-row: 1;justify-self:start;">

        </div>

    </div>
    <br>


    <script>
        var ismobile = 0;
        var vitesse_mobile = 1;
        //mobile
        if (screen.width < 1400) {
            ismobile = 1;
            document.getElementById("boite0").style = " marging-left:30px; 20width: 550px;height: 100px;grid-column: 1;grid-row: 2;justify-self:center;";
            document.getElementById("boite0").innerHTML = "<input type='button' value='➕' id='plus'><input type='button' value='➖' id='moins'><span><input type='button' value='&#8613;' id='Up'> <input type='button' value='Home' id='home'></span>";
            document.getElementById("boite0").innerHTML += "<div><span id='vitesse_mobile'>Speed: 1</span><input type='button' value='&#8612;' id='Left'><input type='button' value='&#8615;' id='Down'><input type='button' value='&#8614;' id='Right'><input type='range' id='speed' min='-1000' max='1000' step='1' value='-150' hidden='true'><div id='score_mobile'><hr>Score: 0</div></div>";
            document.getElementById("boite1").style = "border-style: solid;border-bottom-width:thick; grid-column: 1;grid-row: 1;justify-self:center;"; //end
            document.getElementById("boite1").innerHTML = '<canvas id="mc" width="416" height="544"></canvas>';
            //document.getElementById("speed").value = "-150";
            document.getElementById("boite2").style = "width: 0px;height: 0px;grid-column: 1;grid-row: 1;justify-self:start;";
            document.getElementById("boite2").style.visibility = "hidden";

        } else { //le canvas aime pas les redimensionnenement à posteriori il vit ça comme un zoom
            document.getElementById("boite1").innerHTML = '<canvas id="mc" width="800" height="640"></canvas>';
        }




        //lance le chargement
        var eat = new Image();
        var eat2 = new Image();
        var head = new Image();
        var head_R = new Image();
        var head_U = new Image();
        var head_D = new Image();
        var body = new Image();
        var tomb = new Image();
        eat.onload = function() {
            //alert("OK !");
            document.getElementById("boite2").innerHTML = "<br> Tu es prêt à partir !";
        }
        eat.onerror = function() {
            alert("erreur chargement image");
        }
        eat.onabort = function() {
            alert("arrêt chargment image");
        };

        window.onload = function() {
            //alert("charge !");
            const cote = 32;
            const width = document.getElementById("mc").width;
            const height = document.getElementById("mc").height;
            lastkey = "L";
            bg = document.getElementById("mc").getContext('2d');

            function part(img, x, y, w, h) {
                this.sprite = img;
                this.x = x;
                this.y = y;
                this.w = w;
                this.h = h;
            }

            part.prototype.deplace = function(x, y) {
                this.efface();
                this.setx(x);
                this.sety(y);
                this.dessine();
            };
            part.prototype.getx = function() {
                return this.x;
            };
            part.prototype.setx = function(a) {
                this.x = a;
            };
            part.prototype.gety = function() {
                return this.y;
            };
            part.prototype.sety = function(a) {
                this.y = a;
            };
            part.prototype.setSprite = function(img) {
                return this.sprite = img;
            };
            part.prototype.dessine = function() {
                bg.drawImage(this.sprite, this.x, this.y);
            };
            part.prototype.efface = function() {
                bg.clearRect(this.x, this.y, this.w, this.h);
            };
            Array.prototype.clear = function() {
                this.splice(0, this.length)
            };
            var inter = 0;;
            var points;
            var eaten = 0;
            var vitesse_jeu_lance;
            var fruits = {
                x: 0,
                y: 0,
                points: 0,
            };
            var play = false;
            var pause = false;
            var mort = false;
            var startOne = true;
            var serpent = new Array();


            function Init() {
                serpent.clear();
                points = 0;
                bg.clearRect(0, 0, width, height);
                document.getElementById("boite2").innerHTML = "En avant !";
                if (ismobile)
                    document.getElementById("score_mobile").innerHTML = "Score: 0";
                fruits.x = 0;
                fruits.y = 0;
                fruits.points = 0;
                play = false;
                pause = false;
                mort = false;
                startOne = true;
                eaten = 0;
                bg.drawImage(head, 6 * cote, 6 * cote);
                serpent.push(new part(head, 6 * cote, 6 * cote, cote, cote));
                //on uniformise à 30x30 toutes les parties du serpent
                for (var i = 1; i <= 5; i++) {
                    //bg.drawImage(head, 100, 200); possible ;)
                    serpent.push(new part(body, 6 * cote + i * cote, 6 * cote, cote, cote));
                    bg.drawImage(serpent[i].sprite, serpent[i].x, serpent[i].y);
                }
            }

            function home() {
                document.location.href = '<?php echo BASE_PATH ?>';
            }

            function Pause(e) {
                //alert("PAUSEEE");
                if (play) {
                    if (pause) {
                        pause = false;
                        eaten++;
                        if (eaten < 10)
                            document.getElementById("boite2").innerHTML += "<br>~~~ ╔͎═͓═͙╗<br>~~~ ╚̨̈́═̈́﴾ ̥̂˖̫˖̥  ̂ )";
                        else {
                            document.getElementById("boite2").innerHTML = "<br>~~~ ╔͎═͓═͙╗<br>~~~ ╚̨̈́═̈́﴾ ̥̂˖̫˖̥  ̂ )";
                            eaten = 0;
                        }
                        vitesse_jeu_lance = (150 - document.getElementById("speed").value);
                        inter = setInterval(move, vitesse_jeu_lance);

                    } else {
                        pause = true;
                        eaten++;
                        if (eaten < 10)
                            document.getElementById("boite2").innerHTML += "<br> Petit break ☕";
                        else {
                            document.getElementById("boite2").innerHTML = "<br> Petit break ☕";
                            eaten = 0;
                        }
                        clearInterval(inter);
                        inter = 0;
                    }
                }
            }

            function move() {

                var lastx = serpent[serpent.length - 1].getx();
                var lasty = serpent[serpent.length - 1].gety();

                for (var i = serpent.length - 1; i >= 1; i--) {
                    //algorithme de swap classique NON
                    //aux = serpent[i].getx();
                    serpent[i].setx(serpent[i - 1].getx());
                    //serpent[i].setx(aux);
                    //auy = serpent[i].gety();
                    serpent[i].sety(serpent[i - 1].gety());
                    //serpent[i].sety(auy);
                }
                //tweak on bouge la tête, on colle un corps à son ancienne position, on supprime la queue
                //et on change juste le tableau des positions => mouvement en limitant au strict minimum les changements graphiques
                //pour la perf et pour changer du code du snake déjà créé
                switch (lastkey) {
                    case "L":
                        serpent[0].setSprite(head);
                        serpent[0].deplace(serpent[0].x - cote, serpent[0].y);
                        break;
                    case "R":
                        serpent[0].setSprite(head_R);
                        serpent[0].deplace(serpent[0].x + cote, serpent[0].y);
                        break;
                    case "U":
                        serpent[0].setSprite(head_U);
                        serpent[0].deplace(serpent[0].x, serpent[0].y - cote);
                        break;
                    case "D":
                        serpent[0].setSprite(head_D);
                        serpent[0].deplace(serpent[0].x, serpent[0].y + cote);
                        break;
                    default:
                }
                serpent[1].dessine();
                bg.clearRect(lastx - 1, lasty - 1, cote + 3, cote + 3);
                //gestion de la queue
                //bg.clear serpent[serpent.length-1].x
                //serpent[serpent.length-1].setsprite selon x /y précédant #-2
                //serpent[serpent.length-1].dessine()
                var isinvisible = false;
                for (i = 1; i < serpent.length; i++) {
                    if (serpent[0].x == serpent[i].x && serpent[0].y == serpent[i].y)
                        Game_Over("Don't bite yourself !");
                    if (serpent[i].x == fruits.x && serpent[i].y == fruits.y) {
                        bg.drawImage(eat, fruits.x, fruits.y);
                        isinvisible = true;
                    }
                    //on redessine le fruits pour qu'il ne soit pas effacer#Zvalue non présente
                    //il y aurait eu moyen d'utiliser la façon odnt on gère les collisions entre canvas en créant un canvas pour le fruit et en mettant
                    // cf:ctx.globalCompositeOperation = "xor";
                    //ligther=> au-dessus =par defaut
                    //destination-over=l'inverse =pour le body
                }
                if (isinvisible) {
                    document.getElementById("boite2").innerHTML = "<br> Attention tu as enfoui le prochain fruit dans le sol ! D'après nos éclaireurs il est " + function() {
                        var txt = "dans le quart";
                        if (fruits.y > document.getElementById("mc").height / 2)
                            txt += " en bas ";
                        else
                            txt += " en haut";
                        if (fruits.x > document.getElementById("mc").width / 2)
                            txt += " à droite ";
                        else
                            txt += " à gauche ";


                        return txt;
                    }();
                }
                if (serpent[0].x < 0 || serpent[0].x > document.getElementById("mc").width || serpent[0].y < 0 || serpent[0].y > document.getElementById("mc").height)
                    Game_Over("Don't go so far so fast, you bump into yours limits !");
                else if (serpent[0].x == width || serpent[0].y == height) {
                    document.getElementById("boite2").innerHTML += "<br> passage secret ಠ_ರೃ";
                    eaten++;
                }

                if (serpent[0].x == fruits.x && serpent[0].y == fruits.y) {

                    points += fruits.points;
                    serpent.push(new part(body, serpent[serpent.length - 1].getx(), serpent[serpent.length - 1].gety() + cote));
                    call_of_food(); //on remet un fruit


                    if (ismobile) {
                        document.getElementById("score_mobile").innerHTML = "Score: " + Math.round((points + points * 300 / (150 + (-30 * vitesse_mobile) * 2 / 3)));
                    } else {
                        //messages de motivation :
                        eaten++;
                        if (eaten < 10)
                            document.getElementById("boite2").innerHTML += "<br> Yeah that's it !";
                        else if (eaten == 10)
                            document.getElementById("boite2").innerHTML = "<br> Yeah You're good!";
                        else if (eaten % 10 != 0)
                            document.getElementById("boite2").innerHTML += "<br>Your current score is: " + Math.round(points + points * 300 / vitesse_jeu_lance);
                        else
                            document.getElementById("boite2").innerHTML = "<br>Your current score is: " + Math.round(points + points * 300 / vitesse_jeu_lance);
                    }
                }

            }
            //https://www.cambiaresearch.com/articles/15/javascript-char-codes-key-codes
            function clavier(e) {
                if (startOne && e.keyCode != 32) { //ne pas commencer par une pause
                    Game(e);
                    startOne = false;
                }
                if (play) {
                    var keeper = lastkey;

                    if (e.keyCode == 39 || e.keyCode == 68) //flèche droite ou D
                        lastkey = "R";
                    else if (e.keyCode == 37 || e.keyCode == 81) //flèche g ou Q
                        lastkey = "L";
                    else if (e.keyCode == 38 || e.keyCode == 90) //flèche haut ou Z
                        lastkey = "U";
                    else if (e.keyCode == 40 || e.keyCode == 83) //flèche bas ou s
                        lastkey = "D";
                    if (keeper == "R" && lastkey == "L" || keeper == "L" && lastkey == "R" ||
                        keeper == "U" && lastkey == "D" || keeper == "D" && lastkey == "U")
                        Game_Over("Don't go back !");
                    if (e.keyCode == 80 || e.keyCode == 32) { // p ou space
                        //alert("P ?");
                        Pause(e);
                    }
                } else if (e.keyCode == 13) { //enter
                    Game(e);
                }


            }

            //rq: en bas et à droite une case de rab as a bonus #survivant
            function Game_Over(txt) {
                clearInterval(inter);
                inter = 0;
                total = Math.round((points + points * 300 / vitesse_jeu_lance));
                document.getElementById("boite2").innerHTML = "You are dead !" + txt +
                    "<br> Votre score est de : <br> fruits: " + points + "<br> Vitesse:" + Math.round(points * 300 / vitesse_jeu_lance) + "<br> Total: " + total;
                if (ismobile) {
                    total = Math.round((points + points * 300 / (150 + (-30 * vitesse_mobile) * 2 / 3)));
                    document.getElementById("score_mobile").innerHTML = "Score final :" + total;
                }
                if (!mort)
                    sendToPhp({
                        s: total
                    });
                play = false;
                mort = true;
                bg.clearRect(0, 0, width, height);
                // bg.commit();
                bg.drawImage(tomb, serpent[5].x, serpent[5].y);
                // bg.commit();



            }
            //http://hayageek.com/jquery-ajax-post/
            //sans jquery :https://ajax.developpez.com/cours/ (moins pratique)
            function sendToPhp(donnees) {
                $.ajax({
                    url: '<?php echo BASE_PATH.'score/enregistrer'?>',
                    type: "POST",
                    data: donnees,
                    dataType: "json",
                    success: function(response) {
                        if (!ismobile)
                            $('#boite2').html($('#boite2').html() + "<br>" + response.message);
                        else
                            document.getElementById("score_mobile").innerHTML += "<br>" + response.message;
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        if (!ismobile)
                            $('#boite2').html($('#boite2').html() + "<br>" + errorThrown);
                        else
                            document.getElementById("score_mobile").innerHTML += "<br>" + errorThrown;
                    }
                });
            }

            function speedset(e) {
                var valeur = document.getElementById("speed").value;
                document.getElementById("speed_txt").innerHTML = (150 - valeur) + " ms";
            }

            function souris(e) {
                if (play) {
                    var x = e.pageX - document.getElementById("mc").offsetLeft;
                    var y = e.pageY - document.getElementById("mc").offsetTop;
                    var keeper = lastkey;
                    //on ne se tue pas à la souris: siiiiii
                    var keeper = lastkey;
                    if (lastkey == "R" || lastkey == "L") {
                        if (y > serpent[0].y)
                            lastkey = "D";
                        else
                            lastkey = "U";
                    } else if (lastkey == "U" || lastkey == "D") {
                        if (x > serpent[0].x)
                            lastkey = "R";
                        else
                            lastkey = "L";
                    }
                    if (keeper == "R" && lastkey == "L" || keeper == "L" && lastkey == "R" ||
                        keeper == "U" && lastkey == "D" || keeper == "D" && lastkey == "U")
                        Game_Over("Don't go back !");
                    // alert("Avant:" + keeper + "\n" + x + " " + y + "\n" + serpent[0].x + " " + serpent[0].y + "lastkey selon clic: " + lastkey);
                } else {
                    Game(e);
                }

            }

            function up(e) {
                if (!play)
                    Game(e);
                if (lastkey == "D")
                    Game_Over("Don't go back !");
                lastkey = "U";
            }

            function down(e) {
                if (!play)
                    Game(e);
                if (lastkey == "U")
                    Game_Over("Don't go back !");
                lastkey = "D";
            }

            function right(e) {
                if (!play)
                    Game(e);
                if (lastkey == "L")
                    Game_Over("Don't go back !");
                lastkey = "R";
            }

            function left(e) {
                if (!play)
                    Game(e);
                if (lastkey == "R")
                    Game_Over("Don't go back !");
                lastkey = "L";
            }

            function plus(e) {
                if (play) {
                    vitesse_mobile++;
                    if (vitesse_mobile > 5) {
                        vitesse_mobile = 5;
                        document.getElementById("vitesse_mobile").innerHTML = "Maximum ";
                    } else {
                        document.getElementById("speed").value = -150 + vitesse_mobile * 30;
                        document.getElementById("vitesse_mobile").innerHTML = "Speed: " + vitesse_mobile;
                        if (inter != 0)
                            clearInterval(inter);
                        if (!mort)
                            inter = setInterval(move, 150 - (-150 + vitesse_mobile * 30));
                    }
                }
            }

            function moins(e) {
                if (play) {
                    vitesse_mobile--;
                    if (vitesse_mobile < 1) {
                        vitesse_mobile = 1;
                        document.getElementById("vitesse_mobile").innerHTML = "Minimum ";
                    } else {
                        document.getElementById("speed").value = -150 + vitesse_mobile * 30;
                        document.getElementById("vitesse_mobile").innerHTML = "Speed: " + vitesse_mobile;
                        if (inter != 0)
                            clearInterval(inter);
                        if (!mort)
                            inter = setInterval(move, 150 - (-150 + vitesse_mobile * 30));
                    }
                }
            }

            function call_of_food() {
                //(rand.nextInt(660-30)/30)+0.5))*30+10;
                var x = Math.round(Math.random() * (document.getElementById("mc").width - cote) / cote) * cote; //#pas envie d'avoir 640 :p
                var y = Math.round(Math.random() * (document.getElementById("mc").height - cote) / cote) * cote;
                //system de cases
                if (Math.round(Math.random()) % 2) {
                    fruits.x = x;
                    fruits.y = y;
                    fruits.points = 20;
                    bg.drawImage(eat, fruits.x, fruits.y);
                } else {
                    fruits.x = x;
                    fruits.y = y;
                    fruits.points = 40;
                    bg.drawImage(eat2, fruits.x, fruits.y);
                }
                //s'autoeffacera quadn le serpent passera dessus #yeah ! #effacement du serpent
                //system de points dans l'idée de faire varier les bonus, ici lightweight snake on va pas trop pousser, see version client pour more ;)

            }
            //custom on pourrait lancer le jeu à l'appui sur une touche: la fonction clavier appelle game
            function Game(e) {
                if (play == false && mort == false) {
                    startOne = false;
                    play = true;
                    lastkey = "L";
                    points = 0;
                    eaten = 0;
                    vitesse_jeu_lance = (150 - document.getElementById("speed").value);
                    if (!inter)
                        inter = setInterval(move, vitesse_jeu_lance);
                    else {
                        clearInterval(inter);
                        inter = setInterval(move, vitesse_jeu_lance);
                    }
                    call_of_food();
                } else {
                    Init();
                    // window.location.reload();
                    //parce qu'on réinitialisera le jeu que par reset qui recherge la page
                    //#paye moi un troisième bras et une autre vie /période de vacances si tu veux autre chose :p
                }
            }
            Init();
            document.getElementById("mc").addEventListener("click", souris, true);
            document.getElementById("home").addEventListener("click", home, true);
            if (!ismobile) {
                document.getElementById("launchgame").addEventListener("click", Game, true);
                document.addEventListener("keydown", clavier);
                document.getElementById("speed").addEventListener("change", speedset);
                document.getElementById("pause").addEventListener("click", Pause, true);
            } else {
                document.getElementById("Up").addEventListener("click", up, true);
                document.getElementById("Down").addEventListener("click", down, true);
                document.getElementById("Right").addEventListener("click", right, true);
                document.getElementById("Left").addEventListener("click", left, true);
                document.getElementById("plus").addEventListener("click", plus, true);
                document.getElementById("moins").addEventListener("click", moins, true);
            }
            //comparer les coordonnées du clic avec la tête du serpent(sachant que si on clique on veut changer de direction) et donner une direction/ ou 4 boutons

        };
        eat2.src = '<?php echo APP_PATH."game/images/raspberry.png" ?>';
        eat.src = '<?php echo APP_PATH."game/images/cherries.png" ?>';
        tomb.src = '<?php echo APP_PATH."game/images/tomb.png"?>';
        head.src = '<?php echo APP_PATH."game/images/head_uni.png"?>';
        head_R.src = '<?php echo APP_PATH."game/images/head_R_uni.png"?>';
        head_U.src = '<?php echo APP_PATH."game/images/head_U_uni.png"?>';
        head_D.src = '<?php echo APP_PATH."game/images/head_D_uni.png"?>';
        body.src = '<?php echo APP_PATH."game/images/body.png"?>';

    </script>

</body>

</html>
<!--
//code de test pour le chargement des images:

/* tab[1] = new Image();
tab[1].src = <?php echo APP_PATH."game/images/body.png" ?>;
tab[1].onload = function() {
bg.drawImage(tab[1], 150, 200);
}
/* tab[2] = new Image();
tab[2].src = "images/body.png";
tab[2].onload = function() {
bg.drawImage(tab[2], 200, 200);
}*/
///!\ onload=function => perd les variables dedans
//var i = 1;


//for (i = 0; i < tab.length; i++) /* tab[0].onload=function(i) { bg.drawImage(tab[0].cloneNode(true), i * 50 + 20, 200); }*/ /* preloadimages(['/images/body.png', '/images/head.png' ], function(images) { var bg=document.getElementById("-1").getContext('2d'); //for (var i=0; i < 2; i++) { bg.drawImage(images[1], 50, 200); //} });*/ /* var image=new Image(); image.src='images/head.png' ; image.onload=function() { bg.drawImage(image, 200, 200); }*/ /*var image2=new Image(); image2.src='images/head.png' ; image2.onload=function() { bg.drawImage(image2, 290, 200); }*/ //var max; /* function MultiBody(x, y) { this.Sprite=new Image(); this.X=x; this.Y=y; this.Sprite.src="" ; } MultiBody.prototype.setSrc=function(img) { this.Sprite.src=img; } function GameLoop() { // grafx.clearRect(0, 0, gameCanvas.width, gameCanvas.height); for (var a=0; a < 5; a++) { tab.push(new MultiBody(200 + a * 20, 200)); tab[a].setSrc("images/head.png"); tab[a].onload=function() { //bg.drawImage(tab[a].Sprite, tab[a].X, tab[a].Y); alert("loaded!"); } } }*/ //rq : console.log(document.getElementById("titre").parentElement.id); // <script src="preloadimages.min.js">


    //=> trouver une bibliothèque js qui gère mieux les images ^^
    // var tab = [];

    // tab[0] = new Image();
    // tab[0].src = "images/head.png";
    // tab[0].onload = function() {
    /*im = new Image();
    im.src = "d.jpg";
    im.onload = function() {
    document.getElementById("mc").getContext('2d').drawImage(im, 200, 400);
    }*/


    /*var image = document.createElement( 'image' );
    image.src = '//user.oc-static.com/files/257001_258000/257108.png';
    document.getElementById( 'div1' ).appendChild( image );
    document.getElementById( 'div2' ).appendChild( image.cloneNode( true ) );
    // il y a 2 images, une par div
    */

    // tab[0] = document.getElementById("boite1");

    //for (var i = 0; i < 5; i++) { //var i=1; //tab[0].innerHTML +="<canvas id='1' width='45' height='45'></canvas>" ; //tab[1]=document.getElementById("-1").getContext("2d"); /* body.onload=function() { bg.drawImage(body, 240, 200); }*/ /*else tab[i].drawImage(body, 40 * i, 40); // } */
