var http = require('http');
var url = require("url");
var querystring = require('querystring');
var om2m = require('./request');

var server = http.createServer(function (req, res) {
    var page = url.parse(req.url).pathname;
    var params = querystring.parse(url.parse(req.url).query);
    res.writeHead(200, {
        "Content-Type": "json"
    });
    if (page == '/') {
        res.write('{"general":""Serveur online !"}');
    } else if (page == '/getGPS') {
        //call request.js good function
        var d = om2m.getGPS();
        res.write('{"x":"' + d[0] + '", "y":"' + d[1] + '"}');
    } else if (page == '/etage/1/chambre') {
        res.write('Hé ho, c\'est privé ici !');
    }
    if ('prenom' in params && 'nom' in params) {
        res.write('Vous vous appelez ' + params['prenom'] + ' ' + params['nom']);
    }
    res.end();

});

server.listen(8080);
