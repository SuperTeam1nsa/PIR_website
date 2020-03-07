var http = require('http');
var url = require("url");
var querystring = require('querystring');
var om2m = require('./request');
var fs = require('fs');
var path = require('path');
var m = require('./principale_page')
var dir = path.join(__dirname, 'public');


fs.readFile(dir + '/parcours.html', function (err, html) {
    if (err) {
        throw err;
    }
    var server = http.createServer(function (req, res) {
        var page = url.parse(req.url).pathname;
        var params = querystring.parse(url.parse(req.url).query);
        //res.write(page);
        if (page == '/') {
            res.writeHead(200, {
                "Content-Type": "text/html"
            });
            res.write(html);
            //res.write(m.getHTML());
            //res.write('{"general":""Serveur online !"}');
            res.end();
        } else if (page == '/getGPS') {
            res.writeHead(200, {
                "Content-Type": "json"
            });
            //call request.js good function
            var d = om2m.getGPS();
            res.write('{"x":"' + d[0] + '", "y":"' + d[1] + '"}');
            res.end();
        } else if (page == '/ressources/bus.png') {
            fs.readFile(dir + '/ressources/bus.png', function (err, content1) {
                if (err) {
                    res.writeHead(400, {
                        'Content-type': 'text/html'
                    })
                    res.end("No such image" + err);
                } else {
                    //specify the content type in the response will be an image
                    res.writeHead(200, {
                        'Content-type': 'image/png'
                    });
                    res.write(content1);
                    res.end();
                }
            });
        } else if (page == '/ressources/plan_campus_insa.png') {
            fs.readFile(dir + '/ressources/plan_campus_insa.png', function (err, content) {
                if (err) {
                    res.writeHead(400, {
                        'Content-type': 'text/html'
                    })
                    res.end("No such image" + err);
                } else {
                    //specify the content type in the response will be an image
                    res.writeHead(200, {
                        'Content-type': 'image/png'
                    });
                    res.write(content);
                    res.end();
                }
            });
        }

        if ('prenom' in params && 'nom' in params) {
            res.write('Vous vous appelez ' + params['prenom'] + ' ' + params['nom']);
            res.end();
        }

    });

    server.listen(8080);
});
