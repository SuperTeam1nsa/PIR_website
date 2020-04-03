var express = require('express');
var router = express.Router();
var om2m = require('../om2m/request');
var conf = require('../config');
//cf app.js
/*var app = express();
app.use(express.static('public'));*/

/* GET home page. */
//https://html-to-pug.com/
router.get('/', function (req, res, next) {

    res.render('parcours', {
        title: "Voiture autonomme de l'INSA Toulouse",
        URL: conf.ctes.IP_SERVEUR
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
router.get('/getGPS', function (req, res, next) {
    res.writeHead(200, {
        "Content-Type": "application/json"
    });

    //call request.js good function
    om2m.getGPS(res);
});

router.get('/ctes', function (req, res, next) {
    res.writeHead(200, {
        "Content-Type": "text/html"
    });
    //call request.js good function
    om2m.generate_table(res)

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
})

//router.post('/todo/ajouter/', function(req, res) { possible aussi ;) 
/* On redirige vers la page principale si la page demandée n'est pas
trouvée */
router.use(function (req, res, next) {
    res.redirect('/');
})
module.exports = router;
