var ctes = {
    IP_SERVEUR: "http://localhost:8080/",
    IP_INCSE: "http://192.168.43.195:8080/",
    SHUTTLE_SEAT: 5,
    STOP_POINTS: new Array(4),
    GPS_SYNCHRO_TIME: 3000,
    TOL_X: 0.1,
    TOL_Y: 0.1
};
//coordonnées GPS à déf ! 
ctes.STOP_POINTS[0] = {
    x: 0,
    y: 0
};
ctes.STOP_POINTS[1] = {
    x: 0,
    y: 0
};
ctes.STOP_POINTS[2] = {
    x: 0,
    y: 0
};
ctes.STOP_POINTS[3] = {
    x: 0,
    y: 0
};
//192.168.43.193
//same as module.export sauf qu'on ne peut pas call comme une fonction
exports.ctes = ctes;
