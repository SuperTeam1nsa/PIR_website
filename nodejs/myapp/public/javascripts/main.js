//to uncomment in prod 
//synchronize();
var script = document.createElement('script');
script.src = 'http://code.jquery.com/jquery-1.11.0.min.js';
script.type = 'text/javascript';
document.getElementsByTagName('head')[0].appendChild(script);

var myVar = setInterval(main, 1000);
var px = 0;
var py = 0;

function main() {
    get_gps();
    maj_posi(px, py);
}

function get_gps() {
    //call car via http request
    $.ajax({
        url: 'http://localhost:8080/getGPS',
        dataType: "json",
        success: function (response) {
            var x = response.x;
            var y = response.y;
            //alert(" ok manger ");
            $('#GPS').html("GPS: x : " + x + " y: " + y);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert(" NOK ");
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
