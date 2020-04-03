//to uncomment in prod 
//synchronize();
var script = document.createElement('script');
script.src = 'http://code.jquery.com/jquery-1.11.0.min.js';
script.type = 'text/javascript';
document.getElementsByTagName('head')[0].appendChild(script);

var myVar = setInterval(main, 2000);
var px = 0;
var py = 0;

function main() {
    get_gps();
    maj_posi(px, py);
}
//relative to our web site domaine (#not begin with /) http://localhost:8080/
function send_req(cnf, content) {
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

    px += 10;
    py += 10;
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
