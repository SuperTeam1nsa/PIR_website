//to uncomment in prod 
//synchronize();
var script = document.createElement('script');
script.src = 'http://code.jquery.com/jquery-1.11.0.min.js';
script.type = 'text/javascript';
document.getElementsByTagName('head')[0].appendChild(script);

//rq: could check details before submitting, here all taff for server
$('#mySuperForm').submit(function () { // catch the form's submit event
    $.ajax({ // create an AJAX call...
        data: $(this).serialize(), // get the form data
        type: $(this).attr('method'), // GET or POST
        url: $(this).attr('action'), // the file to call
        success: function (response) { // on success..
            $('#result_form_submit').html(response); // update the DIV
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $('#result_form_submit').html(jqXHR.responseText); //jqXHR.status = code d'erreur
        }
    });
    return false; // cancel original event to prevent form submitting
});

setTimeout(get_seats, 100);
var myVar = setInterval(main, 2000);
var px = 0;
var py = 0;

function main() {
    get_gps();
    maj_posi(px, py);
    get_seats();
}

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

function get_gps() {
    //call car via http request
    $.ajax({
        url: 'getGPS',
        dataType: "json",
        success: function (response) {
            var x = response.x;
            var y = response.y;
            //alert(" ok manger ");
            $('#GPS').html("GPS: x : " + x + " y: " + y);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            //alert(" NOK ");
            $('#GPS').html(errorThrown);
        }
    });
    //pour test en vrai mettre x et y got
    px += 10;
    py += 10;
}

function get_seats() {
    //call car via http request
    $.ajax({
        url: 'get_seats',
        dataType: "html",
        success: function (response) {
            //alert(" ok manger ");
            $('#available_seats').html(response);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            //alert(" NOK ");
            $('#available_seats').html(errorThrown);
        }
    });
}

function maj_posi(posix, posiy) {
    document.getElementById("position_car").style = "z-index: 2; position:absolute; top:" + posiy +
        "px; left:" + posix +
        "px;";
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
