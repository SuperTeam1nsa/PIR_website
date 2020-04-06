var express = require('express');
var router = express.Router();
var om2m = require('../om2m/request');
var conf = require('../config');
var qs = require('querystring');
var current_seat_taken = 0;
var get_in_people = new Array(conf.ctes.STOP_POINTS.length).fill(0, 0, conf.ctes.STOP_POINTS.length);
var get_out_people = new Array(conf.ctes.STOP_POINTS.length).fill(0, 0, conf.ctes.STOP_POINTS.length);

//get gps et est-ce qu'on doit s'arrêter pour get people ?

var interval = setInterval(function () {
    var coord = om2m.getGPS();
    var cpt = 0;
    console.log('[Check Station position vs :' + JSON.stringify(coord) + ']');
    for (var i = conf.ctes.STOP_POINTS[cpt]; i < conf.ctes.STOP_POINTS.length; i = conf.ctes.STOP_POINTS[cpt]) {
        //si on est à un stop point à TOL% près (à def dans config)
        if (coord.x <= ((1 + conf.ctes.TOL_X) * i.x) && coord.x >= ((1 - conf.ctes.TOL_X) * i.x) &&
            coord.y <= ((1 + conf.ctes.TOL_Y) * i.y) && coord.y >= ((1 - conf.ctes.TOL_Y) * i.y)) {
            if (get_in_people[cpt] > 0) {
                get_in_people[cpt] = 0;
                //procédure de chargement/dechargement
                om2m.stop_get_in_out();
            } else if (get_out_people[cpt] > 0) {
                get_out_people[cpt] = 0;
                om2m.stop_get_in_out();
            }
            //on est à un seul endroit à la fois
            break;
        }
        cpt++;
    }

}, parseInt(conf.ctes.GPS_SYNCHRO_TIME, 10));

//cf app.js
/*var app = express();
app.use(express.static('public'));*/

/* GET home page. */
//https://html-to-pug.com/
router.get('/', function (req, res) {

    res.render('parcours', {
        title: "Voiture autonomme de l'INSA Toulouse",
        URL: conf.ctes.IP_SERVEUR,
    });
    //om2m.generate_table();
});

/*
var page = url.parse(req.url).pathname;
        var params = querystring.parse(url.parse(req.url).query);
         if ('prenom' in params && 'nom' in params) {
            res.write('Vous vous appelez ' + params['prenom'] + ' ' + params['nom']);
            res.end();
        }
        */
router.get('/get_seats', function (req, res) {
    res.writeHead(200, {
        "Content-Type": "text/html"
    });
    res.write("<strong>" + (parseInt(conf.ctes.SHUTTLE_SEAT, 10) - parseInt(current_seat_taken, 10)) + "</strong");
    res.end();
});

//rq: pour un from plus complexe, il est préférable de action="check_form" et check_form() fait de l'ajax avec le 
//serveur pour update la page en local
router.post('/reservation/:secret', function (req, res) {
    console.log("Reservation en cours ...");
    if (req.params.secret == 42) {
        console.log("Post");
        console.log("data");
        var body = req.body;
        // Too much POST data, kill the connection!
        // 1e6 === 1 * Math.pow(10, 6) === 1 * 1000000 ~~~ 1MB
        if (body.length > 1e6) {
            console.log("destroyed");
            req.connection.destroy();
        }
        // use post['blah'], etc.
        console.log("INSIDE ..." + req.body.selecterIN + " " + req.body.selecterOUT + " " + req.body.seats);
        var asked = parseInt(req.body.seats, 10); //base 10
        if (asked <= 0) {
            res.writeHead(451);
            res.end("[ERROR] Don't be foolish it's forbidden :p ");
            return;
        } else {
            if ((current_seat_taken + asked) <= conf.ctes.SHUTTLE_SEAT) {
                res.writeHead(200, {
                    "Content-Type": "text/html"
                });
                var inp = parseInt(req.body.selecterIN, 10);
                var outp = parseInt(req.body.selecterOUT, 10);
                //on connait le nombre de personnes à récupérer et à déposer + le lieu(pour stats/opti trajet futurs)
                get_in_people[inp] += asked;
                get_out_people[outp] += asked;
                res.write("<p>OK, reservation prise en compte de " + asked + " il y en avait : " + current_seat_taken +
                    " de deja pris et " + conf.ctes.SHUTTLE_SEAT + " en tout </p");
                res.end();
                current_seat_taken += asked;
            } else {
                res.writeHead(418, {
                    "Content-Type": "text/html"
                });
                res.write("<p>NOK, plus assez de place pour ajouter " + asked + " car il y a deja " + current_seat_taken + " passagers et le maximum est : " + conf.ctes.SHUTTLE_SEAT + "<a href='https://www.google.com/teapot'>Do you want a cup of tea while waiting ? </a>");
                res.end();
            }
        }
    }

});
router.get('/getGPS', function (req, res) {
    res.writeHead(200, {
        "Content-Type": "application/json"
    });
    om2m.getGPS(res);
    //call request.js good function
    // res.write(); //JSON.stringify(
    // res.end(om2m.getGPS());
});

router.get('/ctes', function (req, res) { //, next
    res.writeHead(200, {
        "Content-Type": "text/html"
    });
    //call request.js good function
    om2m.generate_table(res);

});


// send command arrow to car
router.get('/command/:cnf/:content/:secret', function (req, res) {
    if (req.params.secret == 42) {
        //reçu
        res.writeHead(200, {
            "Content-Type": "application/json"
        });
        //on enverra le corps de la requête à la voiture +false/true back plus tard
        //asynchronous way
        /*The Connection general header controls whether or not the network connection stays open after the current transaction finishes. If the value sent is keep-alive, the connection is persistent and not closed, allowing for subsequent requests to the same server to be done.*/
        //until res.end() indeed
        om2m.send_req(req.params.cnf, req.params.content, res);
        //res.redirect('/');
    } else {
        res.writeHead(403);
        res.end(" Don't be foolish it's forbidden :p ");
    }
});

//router.post('/todo/ajouter/', function(req, res) { possible aussi ;) 
/* On redirige vers la page principale si la page demandée n'est pas
trouvée */
router.use(function (req, res) {
    console.log("Redirection en cours ...");
    res.redirect('/');
});
module.exports = router;
