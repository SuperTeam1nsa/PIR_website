const stations = {

    riquet: 0,
    stpi: 125,
    fourier: 250,
    exam: 40

};

const vitesse = 15;

function estimatedTime(e) {

    let dest = document.getElementById("destination").value;
    let dep = document.getElementById("departure").value;
    if (dep != dest) {
        let coord_dep = stations[dep];
        let coord_dest = stations[dest];
        let x = Math.abs(coord_dest - coord_dep) / (15 / 3.6) + 60;
        let min = Math.floor(x / 60);
        let sec = Math.round(x % 60);
        document.getElementById("estimated_time").textContent = min + "min " + sec + "s";
    } else {

        document.getElementById("estimated_time").textContent = "";

    }   

}

document.getElementById("departure").addEventListener("change", estimatedTime);
document.getElementById("destination").addEventListener("change", estimatedTime);
