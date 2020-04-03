# Config :
> cd nodejs\myapp

Editer le fichier config.js avec les IP/URL(celles accessibles, locales ou distantes) des:
* serveur node js
* serveur IN-CSE (om2m)  

# Lancement :
Depuis nodejs prompt ou un invite de commande avc les paths de node :  
> cd nodejs\myapp  
## Lancer en debug:  
> set DEBUG=myapp:* & npm start  
## Lancer en normal:  
> node ./bin/www  
## Ouvrir avec le naviagteur:  
**Utilisez l'IP/URL du serveur node js renseignée dans config.js**  
Si vous ne savez pas quoi mettre, et voulez un indice :  
> http://localhost:8080/  
ou > votreIP:8080  
