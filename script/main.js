//to uncomment in prod 
//synchronize();
var myVar = setInterval(main, 1000);
var px = 0;
var py = 0;

function main() {
    get_gps();
    maj_posi(px, py);
}

function get_gps() {
    //call car via http request
    px += 10;
    py += 10;
}

function maj_posi(posix, posiy) {
    document.getElementById("position_car").style = "z-index: 2; position:absolute; top:" + posiy +
        "px; left:" + posix +
        "px;";
}
