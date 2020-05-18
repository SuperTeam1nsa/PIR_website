var express = require('express');
var router = express.Router();
var om2m = require('../om2m/request');
var conf = require('../config');
var qs = require('querystring');
var current_seat_taken = 0;
var current_inside_nav = 0;
var lastStation = 3;
var txt = "Driving happily...";
var coord = om2m.getGPS();
var get_in_people = new Array(conf.ctes.STOP_POINTS.length + 1);
var get_out_people = new Array(conf.ctes.STOP_POINTS.length + 1).fill(0, 0, conf.ctes.STOP_POINTS.length + 1);
//struct: tab[stations_in][reservation n°]{nb, out} //cf reservation part
for (let k = 0; k < get_in_people.length; k++) {
    get_in_people[k] = new Array();
}


//get gps et est-ce qu'on doit s'arrêter pour get people ?
//+defini à quel fréquence on obtient les infos gps
var interval = setInterval(function () {
    coord = om2m.getGPS();
    var cpt = 0;
    var station = -1;
    //console.log('[Check Station position vs :' + JSON.stringify(coord) + ']');
    for (let i = conf.ctes.STOP_POINTS[cpt]; cpt < conf.ctes.STOP_POINTS.length; i = conf.ctes.STOP_POINTS[cpt]) {
        //console.log("x <= " + ((1 + conf.ctes.TOL_X) * i.x) + " x >= " + ((1 - conf.ctes.TOL_X) * i.x) +
        //    " y <= " + ((1 + conf.ctes.TOL_Y) * i.y) + " y>= " + ((1 - conf.ctes.TOL_Y) * i.y));
        //si on est à un stop point à TOL% près (à def dans config)
        //% injuste => offset / rayon better
        var entier = parseInt(cpt + 1, 10);
        if (coord.x <= (conf.ctes.TOL_X + i.x) && coord.x >= (i.x - conf.ctes.TOL_X) &&
            coord.y <= (conf.ctes.TOL_Y + i.y) && coord.y >= (i.y - conf.ctes.TOL_Y)) {
            console.log('\n\n\n [Arrive at Station :' + entier + " in/out: " + ((get_in_people[entier].length > 0) || (get_out_people[entier] > 0)) + ' ] \n\n\n');
            //3 ifs la procedure in/out pouvant varier
            if ((get_in_people[entier].length > 0) && (get_out_people[entier] > 0)) {
                let nb_in = 0
                for (let j = 0; j < get_in_people[entier].length; j++) {
                    nb_in += get_in_people[entier][j].nb;
                }
                current_inside_nav = (current_inside_nav - get_out_people[entier]) + nb_in;
                current_seat_taken -= get_out_people[entier];
                txt = " We get in " + nb_in + " people get out " + get_out_people[entier] + "at last station! :D ";
                //procédure de chargement/dechargement
                om2m.stop_get_in_out(false); //on s'arrête
                setTimeout(om2m.stop_get_in_out.bind(null, true), conf.ctes.delayArret); //repart dans conf.ctes.delayArret
                get_out_people[entier] = 0;
                for (let j = 0; j < get_in_people[entier].length; j++) {
                    get_out_people[get_in_people[entier][j].out] += get_in_people[entier][j].nb;
                }
                get_in_people[entier] = 0;
            } else if (get_in_people[entier].length > 0) {
                let nb_in = 0
                //procédure de chargement/dechargement
                om2m.stop_get_in_out(false); //on s'arrête
                setTimeout(om2m.stop_get_in_out.bind(null, true), conf.ctes.delayArret); //repart dans conf.ctes.delayArret
                for (let j = 0; j < get_in_people[entier].length; j++) {
                    console.log(" get out station num" + get_in_people[entier][j].out + " 1st value: " +
                        get_out_people[get_in_people[entier][j].out] + " add " +
                        get_in_people[entier][j].nb);
                    get_out_people[get_in_people[entier][j].out] += get_in_people[entier][j].nb;
                    nb_in += get_in_people[entier][j].nb;
                }

                current_inside_nav += nb_in;
                txt = " We get in " + nb_in + " people at last station!: D ";
                get_in_people[entier] = new Array(0);
            } else if (get_out_people[entier] > 0) {
                om2m.stop_get_in_out(false); //on s'arrête
                setTimeout(om2m.stop_get_in_out.bind(null, true), conf.ctes.delayArret); //repart dans conf.ctes.delayArret
                txt = " We get out " + get_out_people[entier] + " people at last station! #Done ! ";
                current_inside_nav -= get_out_people[entier];
                current_seat_taken -= get_out_people[entier];
                get_out_people[entier] = 0;
            } else {
                txt = " There was no one to have a ride with us at last station... :'( ";
            }
            station = entier;
            //on est à un seul endroit à la fois
            break;
        }
        cpt++;
    }
    if (station != -1)
        lastStation = station;

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
//rq: pour un from plus complexe, il est préférable de action="check_form" et check_form() fait de l'ajax avec le 
//serveur pour update la page en local
router.post('/reservation/:secret', function (req, res) {
    console.log("Reservation en cours de la station " + req.body.selecterIN + " à la station " + req.body.selecterOUT);
    if (req.params.secret == 42) {
        var body = req.body;
        // Too much POST data, kill the connection!
        // 1e6 === 1 * Math.pow(10, 6) === 1 * 1000000 ~~~ 1MB
        if (body.length > 1e6) {
            console.log("destroyed");
            req.connection.destroy();
        }
        // use post['blah'], etc.
        // console.log("INSIDE ..." + req.body.selecterIN + " " + req.body.selecterOUT + " " + req.body.seats);
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
                var in_station = parseInt(req.body.selecterIN, 10);
                var out_station = parseInt(req.body.selecterOUT, 10);
                //on connait le nombre de personnes à récupérer et à déposer + le lieu(pour stats/opti trajet futurs)
                //rq: check tha get in BEFORE get out
                //struct: tab[stations_in][reservation n°]{nb, out}
                get_in_people[in_station].push({
                    nb: asked,
                    out: out_station
                });
                res.write("<p>OK, reservation prise en compte de " + asked + " il y en avait : " + current_seat_taken +
                    " de deja pris et " + conf.ctes.SHUTTLE_SEAT + " en tout </p>");
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
router.get('/getInfo', function (req, res) {
    res.writeHead(200, {
        "Content-Type": "application/json"
    });
    var stats = "People inside :" + parseInt(current_inside_nav, 10) + " and " + parseInt(current_seat_taken - current_inside_nav, 10) + " bookings";
    let seats = (parseInt(conf.ctes.SHUTTLE_SEAT, 10) - parseInt(current_seat_taken, 10));
    //on add les infos aux coord obtenus via la syncho gps
    let coordExtended = Object.assign({
        lastStation: lastStation,
        seats: seats,
        details: txt,
        stats: stats
    }, coord);
    res.write(JSON.stringify(coordExtended));
    res.end();

    //om2m.getGPS(res);
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
