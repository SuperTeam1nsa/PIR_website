var createError = require('http-errors');
var express = require('express');
var path = require('path');
var cookieParser = require('cookie-parser');
var logger = require('morgan');
/*
PWA way :https://app-manifest.firebaseapp.com/
https://codelabs.developers.google.com/codelabs/your-first-pwapp/#3
https://www.eurateach.com/formation-developpement-lille/tutoriel-html-creer-progressive-web-app/
http://blog.occ.simplon.co/pwa/
https://blog.eleven-labs.com/fr/votre-premiere-pwa/
+F12 lighthouse chrome
//installation
https://fossbytes.com/windows-10-install-pwa-website-edge-chromium/
https://web.dev/codelab-make-installable/
//need https (so I let you add cache and https option support) on android:add to -> homescreen => webapp free

//Next time use a better generator :https://github.com/cdimascio/generator-express-no-stress
//all express +
//#Babel (auto js new gen retrocompatibility), ESLint true js debug, piano #good log
//dot env =config.js mais en .env #good practice
//swagger auto doc api
/* config :
npx express-generator
ou :
npm install -g express-generator
//express -h pour vérifier que ça a marché
puis express --view=pug myapp
Navigate into the app directory and use NPM to install dependencies
cd myapp
npm install
*/
//https://html-to-pug.com/
//depuis myapp et nodejs prompt 
//launch debug : set DEBUG=myapp:* & npm start
//default start :node ./bin/www
//http://localhost:8080/
//ou http://localhost:8080/PIR_website
var indexRouter = require('./routes/index');
//var usersRouter = require('./routes/users');

var app = express();


// view engine setup
app.set('views', path.join(__dirname, 'views'));
app.set('view engine', 'pug');


app.use(logger('dev'));
app.use(express.json());
app.use(express.urlencoded({
    extended: false
}));
app.use(cookieParser());
app.use(express.static(path.join(__dirname, 'public')));

/*app.post('/reservation/:secret', function (request, response) {
    console.log(" hum hum ..." + request.body.seats);
    response.writeHead(200, {
        "Content-Type": "text/html"
    });
    response.end();
    //console.log(request.body.user.email);
}); //app.use('/users', usersRouter);*/
app.use('/', indexRouter);

// catch 404 and forward to error handler
app.use(function (req, res, next) {
    next(createError(404));
});

// error handler
app.use(function (err, req, res, next) {
    // set locals, only providing error in development
    res.locals.message = err.message;
    res.locals.error = req.app.get('env') === 'development' ? err : {};

    // render the error page
    res.status(err.status || 500);
    res.render('error');
});

module.exports = app;
