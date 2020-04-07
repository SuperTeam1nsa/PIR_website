"use strict";
//192.168.43.193
var ctes = {
    IP_SERVEUR: "http://192.168.43.193:8080/",
    IP_INCSE: "http://192.168.43.195:8080/",
    SHUTTLE_SEAT: 5,
    STOP_POINTS: new Array(4),
    GPS_SYNCHRO_TIME: 1000,
    TOL_X: 10,
    TOL_Y: 4,
    simulation_posi: new Array(50)
};
//coordonnées GPS des stations
ctes.STOP_POINTS[0] = {
    x: 307,
    y: 202
};
ctes.STOP_POINTS[1] = {
    x: 220,
    y: 202
};
ctes.STOP_POINTS[2] = {
    x: 87,
    y: 202
};
ctes.STOP_POINTS[3] = {
    x: 160,
    y: 182
};

//simulation du trajet navette
let y_ini = 182;
let x_ini = 75;
let x_max = 245; //to add at ini
let y_max = 20; // ""
let step_x = 20; //nb de steps
let step_y = 5;
//de haut à gauche à haut à droite
for (let i = 0; i <= step_x; i++) {
    ctes.simulation_posi[i] = {
        x: x_ini + (x_max / step_x) * i,
        y: y_ini
    };
}
//de haut à droite à en bas à droite 
for (let i = 0; i <= step_y; i++) {
    ctes.simulation_posi[step_x + i] = {
        x: x_max + x_ini,
        y: y_ini + (y_max / step_y) * i
    };
}
//de bas à droite à bas à gauche
for (let i = 0; i <= step_x; i++) {
    ctes.simulation_posi[step_x + step_y + i] = {
        x: (x_max + x_ini) - (x_max / step_x) * i,
        y: y_max + y_ini
    };
}
//de bas à gauche à haut à gauche
for (let i = 0; i < step_y; i++) {
    ctes.simulation_posi[step_x * 2 + step_y + i] = {
        x: x_ini,
        y: (y_max + y_ini) - (y_max / step_y) * i
    };
}



//same as module.export sauf qu'on ne peut pas call comme une fonction
exports.ctes = ctes;
