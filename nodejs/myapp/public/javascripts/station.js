const stations_num = {

    riquet: 2,
    stpi: 1,
    fourier: 0,
    exam: 3

};

const stations_dist = [125, 125, 110, 350]

const vitesse = 15;

function estimatedTime(e) {

    let dest = document.getElementById("destination").value;
    let dep = document.getElementById("departure").value;
    if (dep != dest) {
        let n_dep = stations_num[dep];
        let n_dest = stations_num[dest];
	let dst = 0;
	
	while(n_dep != n_dest) {
		dst += stations_dist[n_dep];
		n_dep = (n_dep + 1) % 4;
		
	}
        let x = dst / (15 / 3.6) ;
        let min = Math.floor(x / 60);
        let sec = Math.floor(x % 60);
        document.getElementById("estimated_time").textContent = min + "min " + sec + "s";
    } else {

        document.getElementById("estimated_time").textContent = "";

    }   

}

window.onload = estimatedTime(null);
document.getElementById("departure").addEventListener("change", estimatedTime);
document.getElementById("destination").addEventListener("change", estimatedTime);
