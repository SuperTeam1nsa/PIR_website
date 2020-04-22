# Configuration :

Edit the file `nodejs\myapp\config.js` with IP/URL of:
* server node js (your IP or localhost if you want to test on your PC and not on an online server) 
* server IN-CSE (om2m)  

## Running :
From nodejs prompt or a command prompt with node paths configured :  
` cd nodejs\myapp`
  1. Start in debug mode
    ` set DEBUG=myapp:* & npm start  `
  2. Start in normal mode:
    `node ./bin/www  `
## Open your browser on IP/URL of server node js (the one configured in config.js)
By default : 
``` 
http://localhost:8080/  
yourIP:8080  
 ```
