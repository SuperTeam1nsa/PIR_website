var express = require('express');
var router = express.Router();
var om2m = require('../public/javascripts/request');
//cf app.js
/*var app = express();
app.use(express.static('public'));*/

/* GET home page. */
//https://html-to-pug.com/
router.get('/', function (req, res, next) {
    res.render('parcours', {
        title: "Voiture autonomme de l&apos;INSA Toulouse",
        INFO: om2m.generate_table()
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
        "Content-Type": "json"
    });
    //call request.js good function
    var d = om2m.getGPS();
    res.write('{"x":"' + d[0] + '", "y":"' + d[1] + '"}');
    res.end();
});

module.exports = router;
