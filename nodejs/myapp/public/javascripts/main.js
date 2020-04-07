//to uncomment in prod 
//synchronize();
/*var script = document.createElement('script');
script.src = 'http://code.jquery.com/jquery-1.11.0.min.js';
script.type = 'text/javascript';
document.getElementsByTagName('head')[0].appendChild(script);*/

//Params
var stations_dist = [125, 125, 110, 350];
var vitesse = 75;
var lastStation = 3;
var waiting = 0;
var px = 0;
var py = 0;

setInterval(get_info, 1000);

//rq: could check details before submitting, here all taff for server
$('#mySuperForm').submit(function () { // catch the form's submit event
    $.ajax({ // create an AJAX call...
        data: $(this).serialize(), // get the form data
        type: $(this).attr('method'), // GET or POST
        url: $(this).attr('action'), // the file to call
        success: function (response) { // on success..
            $('#result_form_submit').html(response); // update the DIV
            /* var cd5 = new Countdown({
                 cont: document.getElementById("countdown"),
                 date: Date.now() + waiting * 1000,
                 outputTranslation: {
                     minute: 'Minutes',
                     second: 'Seconds',
                 },
                 endCallback: function () {
                     document.getElementById("countdown").insertAdjacentHTML(
                         'beforeend',
                         '<div style="display: flex;height: 50px;align-items: center;justify-content: center;background: red;font-weight: bold;">Countdown ended. The shuttle should be here :D </div>')
                 },
                 outputFormat: 'minute|second',
             });
             cd5.start();*/
            //too much !
            //launch calc & notifs android
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $('#result_form_submit').html(jqXHR.responseText); //jqXHR.status = code d'erreur
        }
    });
    return false; // cancel original event to prevent form submitting
});

//relative to our web site domaine (#not begin with /) http://localhost:8080/
function send_req(cnf, content) {
    $('#RESULT_POST').html("Waiting for Command result...");
    $.ajax({
        url: 'command/' + cnf + '/' + content + '/42',
        dataType: "json",
        success: function (response) {
            $('#RESULT_POST').html("Command result: " + response.data);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $('#RESULT_POST').html("Error in sending command !");
        }
    });

}

function getCtes() {
    $('#CTES').html("Waiting for shuttle to get sensors info...");
    $.ajax({
        url: 'ctes/',
        dataType: "HTML",
        success: function (response) {
            $('#CTES').html(response);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $('#CTES').html("Error in getting ctes !");
        }
    });
}

function get_info() {
    //call car via http request
    $.ajax({
        url: 'getInfo',
        dataType: "json",
        success: function (response) {
            px = response.x;
            py = response.y;
            //mise à l'échelle selon l'écran
            var ratio = document.getElementById("planINSA").clientWidth * 10 / 404; //404 =ini
            px = px * ratio / 10;
            py = py * ratio / 10;
            lastStation = response.lastStation;
            //alert(" ok manger ");
            $('#GPS').html("<hr>GPS: x : " + Math.floor(px) + " y: " + Math.floor(py) + "<br>Last station visited :" + response.lastStation + " <br>" + response.details + "<br>" + response.stats + "<hr>");
            document.getElementById("position_car").style = "z-index: 2; position:absolute;visibility: visible; top:" + py + "px; left:" + px + "px;";
            $('#available_seats').html("<strong>" + response.seats + "</strong>");
            trajetTime();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            //alert(" NOK ");
            $('#GPS').html(errorThrown);
        }
    });
}

function keyCode(event) {
    var x = event.keyCode;
    if (x == 73) { // i key
        send_req('FORWARD', 'i');
    } else if (x == 85) { // u key
        send_req('LEFT', 'u');
    } else if (x == 79) { // o key
        send_req('RIGHT', 'o');
    } else if (x == 75) { // k key
        send_req('BACKWARD', 'k');
    } else if (x == 32) { //escape key
        send_req('STOP', ' ');
    } else if (x == 65) { //a key
        send_req('SPEED+', 'a');
    } else if (x == 90) { //z key
        send_req('SPEED-', 'z');
    } else if (x == 81) { //q key
        send_req('ANGULAR+', 'q');
    } else if (x == 83) { //s key
        send_req('ANGULAR-', 's');
    } else if (x == 87) { //w key
        send_req('LINEAR+', 'w');
    } else if (x == 88) { //x key
        send_req('LINEAR-', 'x');
    }
}

function heuristique_time(n_dep, n_dest, station_bonus) {
    if (n_dep != n_dest) {
        var dst = 0;
        while (n_dep != n_dest) {
            dst += stations_dist[n_dep];
            n_dep = (n_dep + 1) % stations_dist.length;
        }

        if (typeof station_bonus !== 'undefined')
            dst += stations_dist[station_bonus];
        var x = dst / (vitesse / 3.6);
        var min = Math.floor(x / 60);
        //arrondi les sec à la dizaine sup
        //var sec = Math.round((x % 60) / 10) * 10;
        var sec = Math.round(x % 60);
        if (sec >= 60) {
            min++;
            sec = 0;
        }
        return {
            min: min,
            s: sec
        };
    } else
        return {
            min: 0,
            s: 0
        };
}

function trajetTime() {

    var n_dep = document.getElementById("departure").value - 1;
    var n_dest = document.getElementById("destination").value - 1;
    var time = heuristique_time(n_dep, n_dest);
    if (time.min + time.s > 0) {
        document.getElementById("estimated_time").textContent = time.min + "min " + time.s + "s";
        //si la dernière station passée est la notre, il faudra faire un tour entier
        var wait = 0;
        if ((lastStation - 1) != n_dep)
            wait = heuristique_time(lastStation - 1, n_dep)
        else {
            wait = (heuristique_time(lastStation % stations_dist.length, n_dep, lastStation - 1));
        }
        waiting = parseInt(wait.min * 60 + wait.s, 10);
        document.getElementById("estimated_waiting_time").textContent = wait.min + "min " + wait.s + "s";
    } else {
        document.getElementById("estimated_time").textContent = " Vous êtes déjà arrivés :p ";
        document.getElementById("estimated_waiting_time").textContent = " Vous êtes déjà arrivés :p ";
    }

}

window.onload = function () {
    get_info();
    trajetTime();
};

document.getElementById("departure").addEventListener("change", trajetTime);
document.getElementById("destination").addEventListener("change", trajetTime);
