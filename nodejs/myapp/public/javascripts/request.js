var url = 'http://192.168.43.6:8080/~/in-cse/in-name/MOBILITY_LAB/NAV_CMD/';
var urls = 'http://192.168.43.6:8080/~/in-cse/in-name/MOBILITY_LAB/NAV_SENSORS/';
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
var XMLHttpRequest = require("xmlhttprequest").XMLHttpRequest;
module.exports = {
    getGPS: function () {
        //TODO
        return [42, 58];
    },
    generate_table: function () {

        var cntListe = ['localization_time', 'localization_valid', 'localization_accurate', 'path_time', 'path_lateral_error', 'path_orientation_error', 'remote_start', 'remote_manual', 'remote_buzzer', 'remote_door', 'supervisor_enabled', 'supervisor_run', 'supervisor_resume', 'carselector_auto', 'anticollision_stop', 'anticollision_warning', 'door_opened', 'door_error', 'safetyok', 'supervisor_emergency_io_steer_drive_aok', 'supervisor_emergency_car_time', 'supervisor_emergency_car_command', 'supervisor_emergency_car_speed', 'supervisor_emergency_car_steer', 'supervisor_emergency_steer_encoder_time', 'supervisor_emergency_left_encoder_time', 'supervisor_emergency_right_encoder_time', 'supervisor_pause_laser_time', 'supervisor_pause_door_closed', 'supervisor_enable_power', 'door_closed', 'pad_A', 'pad_B', 'parkingBrake_Released', 'remote_estop'];
        cntListe = cntListe.sort();
        cntListe.unshift('***');
        let rep = null;
        var regex = /<\//gi; //pour l'extraction du contenu

        let xhr = new XMLHttpRequest();

        // get the reference for the tblBody
        /*var body = document.getElementsByID("INFO")[0];

        // creates a <table> element and a <tbody> element
        var tbl = document.createElement("table");
        var tblBody = document.createElement("tbody");
        
        */
        var tableau = "<table><tbody>";

        for (var i = 0; i < cntListe.length; i++) {

            tableau += "<tr><td>" + cntListe[i] + "</td>";

            //envoyer des requetes de façon synchrone to UNCOMMENT
            /*  xhr.open('GET', 'http://192.168.43.6:8080/~/in-cse/in-name/MOBILITY_LAB/' + cntListe[i] + '/la/', false);
              xhr.setRequestHeader('Access-Control-Allow-Headers', '*');
              xhr.setRequestHeader('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE');
              xhr.setRequestHeader('Access-Control-Allow-Credentials', 'true');
              xhr.setRequestHeader('X-M2M-Origin', 'admin:admin');
              xhr.setRequestHeader('Accept', 'application/xml');
              xhr.send();
              xhr.timeout = 500; // time in milliseconds
              xhr.onload = function () {
                  if (xhr.status != 200) { // analyze HTTP status of the response
                      alert(`Error $ {
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
                      tableau += "<td>" + rep[1] + "</td></th>";
                      //cell.appendChild(cellText); // add the row to the end of the table body
                      //tblBody.appendChild(row);
                      //row.appendChild(cell);
                  }
              };
              */
            //TO DELETE in prod
            tableau += "</th>";

            // add the row to the end of the table body
            // tblBody.appendChild(row);
        }
        tableau += "</tbody></table>";
        return tableau;
        // put the <tbody> in the <table>
        //tbl.appendChild(tblBody);
        // appends <table> into <body>
        //body.appendChild(tbl);
        // sets the border attribute of tbl to 2;
    }
};
/*
function del() { //alert("I'm in");
    // 1. Create a new XMLHttpRequest object
    let xhr = new XMLHttpRequest();
    //var body = "<m2m:cin xmlns:m2m='http://www.onem2m.org/xml/protocols' rn='cin_nav'><cnf>FORWARD</cnf><con>i</con></m2m:cin>";
    // 2. Configure it: GET-request for the URL /article/.../load
    xhr.open('DELETE', url + cin);
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



function send_req(cnf, content) {

    // 1. Create a new XMLHttpRequest object
    let xhr = new XMLHttpRequest();
    var body = "<m2m:cin xmlns:m2m='http://www.onem2m.org/xml/protocols'><cnf>" + cnf + "</cnf><con>" + content + "</con></m2m:cin>";

    // 2. Configure it: GET-request for the URL /article/.../load
    xhr.open('POST', url);
    xhr.setRequestHeader('Access-Control-Allow-Headers', '*');
    xhr.setRequestHeader('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE');
    xhr.setRequestHeader('Access-Control-Allow-Credentials', 'true');
    xhr.setRequestHeader('X-M2M-Origin', 'admin:admin');
    xhr.setRequestHeader('Content-Type', 'application/xml;ty=4');

    // 3. Send the request over the network
    xhr.send(body);

    // 4. This will be called after the response is received
    xhr.onload = function () {
        if (xhr.status != 200 && xhr.status != 201) { // analyze HTTP status of the response
            alert(`Error ${xhr.status}: ${xhr.statusText}`); // e.g. 404: Not Found
        }
        //else { // show the result
	    //alert(`Done, got ${xhr.response}`); // responseText is the server
	  //}
    };

    xhr.onprogress = function (event) {
        //if (event.lengthComputable) {
        //  alert(`Received ${event.loaded} of ${event.total} bytes`);
        //} else {
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

    xhr.open('POST', urls);
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
