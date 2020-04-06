const stations_dist = [125, 125, 110, 350];

var vitesse = 15;

function estimatedTime(e) {

    var n_dest = document.getElementById("destination").value - 1;
    var n_dep = document.getElementById("departure").value - 1;
    if (n_dep != n_dest) {
        //var n_dep = stations_num[dep];
        //var n_dest = stations_num[dest];
        var dst = 0;

        while (n_dep != n_dest) {
            dst += stations_dist[n_dep];
            n_dep = (n_dep + 1) % 4;

        }
        var x = dst / (15 / 3.6);
        var min = Math.floor(x / 60);
        //arrondi les sec à la dizaine sup
        var sec = Math.round((x % 60) / 10) * 10;
        if (sec >= 60) {
            min++;
            sec = 0;
        }
        document.getElementById("estimated_time").textContent = min + "min " + sec + "s";
    } else {

        document.getElementById("estimated_time").textContent = " Vous êtes déjà arrivés :p ";

    }

}
setTimeout(estimatedTime(null), 1000);
window.onload = estimatedTime(null);
document.getElementById("departure").addEventListener("change", estimatedTime);
document.getElementById("destination").addEventListener("change", estimatedTime);
