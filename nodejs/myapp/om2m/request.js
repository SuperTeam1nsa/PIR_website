var conf = require("../config")
//URL du in-cse
var base;
var url_cmd;
var url_sensors;
var urlOnOff;
if (!conf.ctes.isSimu) {
    base = conf.ctes.IP_INCSE + '~/in-cse/in-name/MOBILITY_LAB/';
    url_cmd = base + 'NAV_CMD/';
    url_sensors = base + 'NAV_SENSORS/'; // not use currently #node om2m à créer
    urlOnOff = null;
} else { //simu
    base = conf.ctes.IP_INCSE + '~/in-cse/in-name/';
    url_cmd = base + 'NavCommands/DATA';
    url_sensors = base + 'NavSensors/DATA';
    urlOnOff = base + 'NavStartStop/DATA';
	urlLink = base + 'NavLink/DATA';
}
console.log(" base: " + base + "\n url_cmd: " + url_cmd + "\n url_sensors: " + url_sensors);
/*var url_cmd = 'http://192.168.43.195:8080/~/in-cse/in-name/MOBILITY_LAB/NAV_CMD/';
var url_sensors = 'http://192.168.43.195:8080/~/in-cse/in-name/MOBILITY_LAB/NAV_SENSORS/';
var base = 'http://192.168.43.195:8080/~/in-cse/in-name/MOBILITY_LAB/'
*/

var cin = 'la';

/* Rq :
     XMLHttpRequest is a built-in object in web browsers.
It is not distributed with Node; you have to install it separately, 
    Install it with npm,
    npm install xmlhttprequest
    Now you can require it in your code.
    var XMLHttpRequest = require("xmlhttprequest").XMLHttpRequest;
    var xhr = new XMLHttpRequest();
That said, Node comes with the http module which is the normal tool for choice for making HTTP requests from Node. 
*/
//rq: return always possible, but not good with asynchronous
// simulation
var tab_coord = conf.ctes.simulation_posi;
var cpt = 0;
var coord = 0; //au lancement on n'est pas synchro 
var XMLHttpRequest = require("xmlhttprequest").XMLHttpRequest;
module.exports = {
    getGPS: function (res) {
        ////TODO:CHECK_DENIS # 
        if (!(conf.ctes.isSimu == 2)) {
            var xhr = new XMLHttpRequest();
            //envoyer des requetes de façon synchrone si false asynchrone autrement (best)
            xhr.open('GET', url_sensors+"/la", true);
            xhr.setRequestHeader('Access-Control-Allow-Headers', '*');
            xhr.setRequestHeader('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE');
            xhr.setRequestHeader('Access-Control-Allow-Credentials', 'true');
            xhr.setRequestHeader('X-M2M-Origin', 'admin:admin');
            xhr.setRequestHeader('Accept', 'application/xml');

            xhr.onerror = function () {
                console.log("Ctes getting fail !");
            };
            //xhr.timeout = 500; // time in milliseconds
            xhr.onload = function () {
                if (xhr.status != 200) { // analyze HTTP status of the response
                    console.log(`Error $ {
                              `+ xhr.status +`
                          }
                          : + $ {
                              xhr.statusText
                          }`); //  e.g. 404: Not Found
                } else {
                    rep = xhr.responseText;
					//console.log(rep) ;
                    //alert(rep);
                    //rep = rep.replace(regex, '<');
                    rep = rep.split('<con>');
					rep2 = rep[1].split('</con>');
					rep3 = rep2[0].replace(/&quot;/g, '"');
                    console.log("rep3 : " + rep3);
                    var dataShuttle = JSON.parse(rep3);
                    coord = {
                        x: dataShuttle.x,
                        y: dataShuttle.y
                    };
                };
            };

            setTimeout(function () {
                // vs. a.timeout 
                if (xhr.readyState < 4) {
                    xhr.params = "has timeout :p";
                    xhr.abort();
                }
            }, 5000); //PROD : timeout: 1000 et pas 5
            xhr.send();

            //fais le tour tout seul
        } else {
            coord = tab_coord[cpt];
            cpt = (cpt + 1) % tab_coord.length;
        }
        if (typeof res !== 'undefined') {
            res.write(JSON.stringify(coord));
            res.end();
        }

        return coord;
    }, //Go false = stop, true = marche
    stop_get_in_out: function (Go) {
        //console.log("\n OK I need to take someone ! Othewise you didn't tell me how ! Oh how awful you are ! ");
        // 1. Create a new XMLHttpRequest object
        let xhr = new XMLHttpRequest();

        //TODO:CHECK_DENIS @IN/NavStartStop/Data --> on+off, info stockées au format json
        //"con": "true", "lbl": "start_stop" 
        var body = "<m2m:cin xmlns:m2m='http://www.onem2m.org/xml/protocols'><con>" + Go + "</con><lbl>'start_stop'</lbl></m2m:cin>";
        // This will be called after the response is received
        xhr.onload = function () {
            if (xhr.status != 200 && xhr.status != 201) { // analyze HTTP status of the response
                console.log(`Error ${xhr.status}: ${xhr.statusText}`); // e.g. 404: Not Found
            } else {
                console.log("Success vehicule is waiting: " + !Go);
            }
            res.end();
        };

        xhr.onerror = function () {
            console.log("Request failed :" + `Error ${xhr.status}: ${xhr.statusText} ${xhr.params}`);
        };
        xhr.open('POST', url_cmd, true);

        setTimeout(function () {
            /* vs. a.timeout */
            if (xhr.readyState < 4) {
                xhr.params = "has timeout :p";
                xhr.abort();
            }
        }, 2000);
        xhr.setRequestHeader('Access-Control-Allow-Headers', '*');
        xhr.setRequestHeader('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE');
        xhr.setRequestHeader('Access-Control-Allow-Credentials', 'true');
        xhr.setRequestHeader('X-M2M-Origin', 'admin:admin');
        xhr.setRequestHeader('Content-Type', 'application/xml;ty=4');

        xhr.send(body);
    },
    generate_table: function (res) {
        var cntListe;
        if (!conf.ctes.isSimu) {
            cntListe = ['localization_time', 'localization_valid', 'localization_accurate', 'path_time', 'path_lateral_error', 'path_orientation_error', 'remote_start', 'remote_manual', 'remote_buzzer', 'remote_door', 'supervisor_enabled', 'supervisor_run', 'supervisor_resume', 'carselector_auto', 'anticollision_stop', 'anticollision_warning', 'door_opened', 'door_error', 'safetyok', 'supervisor_emergency_io_steer_drive_aok', 'supervisor_emergency_car_time', 'supervisor_emergency_car_command', 'supervisor_emergency_car_speed', 'supervisor_emergency_car_steer', 'supervisor_emergency_steer_encoder_time', 'supervisor_emergency_left_encoder_time', 'supervisor_emergency_right_encoder_time', 'supervisor_pause_laser_time', 'supervisor_pause_door_closed', 'supervisor_enable_power', 'door_closed', 'pad_A', 'pad_B', 'parkingBrake_Released', 'remote_estop'];
        } else {
            cntListe = ['all']; //#all in one json request (x,y,angle)
        }
        cntListe = cntListe.sort();
        //cntListe.unshift('***'); // addd *** en tant que capteur => ???? url d'inititalisation ??
        let rep = null;
        var regex = /<\//gi; //pour l'extraction du contenu



        // get the reference for the tblBody
        /*var body = document.getElementsByID("INFO")[0];

        // creates a <table> element and a <tbody> element
        var tbl = document.createElement("table");
        var tblBody = document.createElement("tbody");
        
        */
        var tableau = "<table style='width:100%', border='1'><tr><th>Sensors</th><th>Data</th></tr><tbody>";

        for (var i = 0; i < cntListe.length; i++) {

            var xhr = new XMLHttpRequest();
            //envoyer des requetes de façon synchrone si false asynchrone autrement (best)
            //TODO:CHECK_DENIS
            if (!conf.ctes.isSimu) {
                tableau += "<tr><td>" + cntListe[i] + "</td>";
                xhr.open('GET', base + cntListe[i] + '/la/', true);
            } else {
                xhr.open('GET', url_sensors, true);
            }
            xhr.setRequestHeader('Access-Control-Allow-Headers', '*');
            xhr.setRequestHeader('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE');
            xhr.setRequestHeader('Access-Control-Allow-Credentials', 'true');
            xhr.setRequestHeader('X-M2M-Origin', 'admin:admin');
            xhr.setRequestHeader('Accept', 'application/xml');

            xhr.onerror = function () {
                console.log("Ctes getting fail !");
                tableau += "<td>unknow </td></tr>";
            };
            //xhr.timeout = 500; // time in milliseconds
            xhr.onload = function () {
                if (xhr.status != 200) { // analyze HTTP status of the response
                    console.log(`Error $ {
                              xhr.status
                          }
                          : + $ {
                              xhr.statusText
                          }`); //  e.g. 404: Not Found
                } else {
                    rep = xhr.response;
                    //alert(rep);
                    rep = rep.replace(regex, '<');
                    rep = rep.split('<con>');
                    console.log(rep[1]);

                    // Create a <td> element and a text node, make the text
                    // node the contents of the <td>, and put the <td> at
                    // the end of the table row
                    //  var cell = document.createElement("td");
                    //    var cellText = document.createTextNode(rep[1]);
                    if (!conf.ctes.isSimu) {
                        tableau += "<td>" + rep[1] + "</td></tr>";
                    } else {
                        var dataShuttle = JSON.parse(rep[1]);
                        tableau += "<tr><td>" + 'x' + "</td>";
                        tableau += "<td>" + dataShuttle.x + "</td></tr>";
                        tableau += "<tr><td>" + 'y' + "</td>";
                        tableau += "<td>" + dataShuttle.y + "</td></tr>";
                        tableau += "<tr><td>" + 'angle' + "</td>";
                        tableau += "<td>" + dataShuttle.angle + "</td></tr>";
                    }
                    //cell.appendChild(cellText); // add the row to the end of the table body
                    //tblBody.appendChild(row);
                    //row.appendChild(cell);
                }
            };

            //TO DELETE in prod
            //tableau += "</th>";

            // add the row to the end of the table body
            // tblBody.appendChild(row);


            setTimeout(function () {
                /* vs. a.timeout */
                if (xhr.readyState < 4) {
                    xhr.params = "has timeout :p";
                    xhr.abort();
                }
            }, 5000); //PROD : timeout: 1000 et pas 5
            xhr.send();

        } //for
        tableau += "</tbody></table>";
        console.log("\n sending back data sensor !");
        res.write(tableau);
        res.end();
        // put the <tbody> in the <table>
        //tbl.appendChild(tblBody);
        // appends <table> into <body>
        //body.appendChild(tbl);
        // sets the border attribute of tbl to 2;
    },
    send_req: function (cnf, content, res) {
        // 1. Create a new XMLHttpRequest object
        let xhr = new XMLHttpRequest();

        //TODO:WORK_DENIS how to modify steer and angle from command of website :@IN/NavCommands/Data --> speed + steer, info stockées au format json
        var body;
        if (!conf.ctes.isSimu) {
			body = "<m2m:cin xmlns:m2m='http://www.onem2m.org/xml/protocols'><cnf>" + cnf + "</cnf><con>" + content + "</con></m2m:cin>";
            url_final = url_cmd ;
        } else {
			body = "<m2m:cin xmlns:m2m='http://www.onem2m.org/xml/protocols'><cnf>" + cnf + "</cnf><con>" + content + "</con><lbl>pilot</lbl></m2m:cin>";
            url_final = urlLink ;
        }
        //fail
        /* xhr.ontimeout = function () {
             console.error("The request has timed out :" + `Error ${xhr.status}: ${xhr.statusText}`);
             res.write('{"data":"false"}');
             res.end();
         };*/
        // This will be called after the response is received
        xhr.onload = function () {
            if (xhr.status != 200 && xhr.status != 201) { // analyze HTTP status of the response
                console.log(`Error ${xhr.status}: ${xhr.statusText}`); // e.g. 404: Not Found
                res.write('{"data":"false"}');
            } else {
                res.write('{"data":"true"}');
            }
            res.end();
            //else { // show the result
            //alert(`Done, got ${xhr.response}`); // responseText is the server
            //}
        };

        // xhr.onprogress = function (event) {
        //if (event.lengthComputable) {
        //  alert(`Received ${event.loaded} of ${event.total} bytes`);
        //} else {
        //  alert(`Received ${event.loaded} bytes`); // no Content-Length
        //}

        // };

        xhr.onerror = function () {
            console.log("Request failed :" + `Error ${xhr.status}: ${xhr.statusText} ${xhr.params}`);
            res.write('{"data":"false"}');
            res.end();
        };
        xhr.open('POST', url_final, true);

        //xhr.timeout = 500; //fail
        //timeout au bout de 2s
        setTimeout(function () {
            /* vs. a.timeout */
            if (xhr.readyState < 4) {
                xhr.params = "has timeout :p";
                xhr.abort();
            }
        }, 2000);
        xhr.setRequestHeader('Access-Control-Allow-Headers', '*');
        xhr.setRequestHeader('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE');
        xhr.setRequestHeader('Access-Control-Allow-Credentials', 'true');
        xhr.setRequestHeader('X-M2M-Origin', 'admin:admin');
        xhr.setRequestHeader('Content-Type', 'application/xml;ty=4');

        xhr.send(body);

    }
};
/*
function del() { //alert("I'm in");
    // 1. Create a new XMLHttpRequest object
    let xhr = new XMLHttpRequest();
    //var body = "<m2m:cin xmlns:m2m='http://www.onem2m.org/xml/protocols' rn='cin_nav'><cnf>FORWARD</cnf><con>i</con></m2m:cin>";
    // 2. Configure it: GET-request for the URL /article/.../load
    xhr.open('DELETE', url_cmd + cin);
    xhr.setRequestHeader('Access-Control-Allow-Headers', '*');
    xhr.setRequestHeader('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE');
    xhr.setRequestHeader('Access-Control-Allow-Credentials', 'true');
    xhr.setRequestHeader('X-M2M-Origin', 'admin:admin');
    xhr.setRequestHeader('Content-Type', 'application/xml;ty=4');

    // 3. Send the request over the network
    xhr.send();

    // 4. This will be called after the response is received
    xhr.onload = function () {
        if (xhr.status != 200) { // analyze HTTP status of the response
            alert(`Error ${xhr.status}: ${xhr.statusText}`); // e.g. 404: Not Found
        }
        //else { // show the result
	    //alert(`Done, got ${xhr.response}`); // responseText is the server
	 // }

    };

    xhr.onprogress = function (event) {
        if (event.lengthComputable) {
            alert(`Received ${event.loaded} of ${event.total} bytes`);
        }
        //else {
	  //  alert(`Received ${event.loaded} bytes`); // no Content-Length
	  //}

    };

    xhr.onerror = function () {
        alert("Request failed");
    };

}






//for test1.html
*/


/*
//these functions allow the page to be refreshed each time we press on the button, otherwhise each time we press on the update button a new table will be added to the previous one

window.onload = function () {
    var reloading = sessionStorage.getItem("reloading");
    if (reloading) {
        sessionStorage.removeItem("reloading");
        generate_table();
    }
}

function reloadP() {
    sessionStorage.setItem("reloading", "true");
    document.location.reload();
}



function synchronize() {

    let xhr = new XMLHttpRequest();
    var body = "<m2m:cin xmlns:m2m='http://www.onem2m.org/xml/protocols'><cnf>synchronization</cnf><con>ok</con></m2m:cin>";

    xhr.open('POST', url_sensors);
    xhr.setRequestHeader('Access-Control-Allow-Headers', '*');
    xhr.setRequestHeader('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE');
    xhr.setRequestHeader('Access-Control-Allow-Credentials', 'true');
    xhr.setRequestHeader('X-M2M-Origin', 'admin:admin');
    xhr.setRequestHeader('Content-Type', 'application/xml;ty=4');

    xhr.send(body);

    //This will be called after the response is received
    xhr.onload = function () {
        if (xhr.status != 200 && xhr.status != 201) { // analyze HTTP status of the response
            alert(`Error ${xhr.status}: ${xhr.statusText}`); // e.g. 404: Not Found
        } else { // show the result
            //alert(`Done, got ${xhr.response}`);
            move();
        }
    };

    xhr.onerror = function () {
        alert("Request failed");
    };
}

function move() {
    var elem = document.getElementById("myBar");
    var x = document.getElementById("myProgress");
    x.style.display = "block";
    var width = 10;
    var id = setInterval(frame, 300);

    function frame() {
        if (width >= 100) {
            clearInterval(id);
            x.style.display = "none";
        } else {
            width++;
            elem.style.width = width + '%';
        }
    }
}

function test() {
    alert("TEST");
    move();
}
*/
