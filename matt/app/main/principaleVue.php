<?php 
require_once(dirname(__FILE__).'/CommandeVue.php');
global $corps;
$corps .='<br>
<form>
   <div class="form-row">
            <div class="form-group col-md-3">
              <label for="prenom">Prénom</label>
              <input type="text" class="form-control" id="prenom" placeholder="Jean">
            </div>
      <div class="form-group col-md-3">
      <label for="nom">Nom</label>
      <input type="text" class="form-control" id="nom" placeholder="Dupont">
      </div>
          <div class="form-group col-md-6">
            <label for="mail">Email</label>
            <input type="email" class="form-control" id="mail" placeholder="nom@exemple.fr" required >
            <small id="emailHelp" class="form-text text-muted">Email seulement utilisé pour la confirmation de la commande. </small>
          </div>
      </div>
      
 <div class="form-group">
     <table class="table table-bordered table-striped">
     <thead>
            <tr>
             <th class="col-sm-4">Quantité</th>
             <th class="col-sm-4">Glace</th>
             <th class="col-sm-2"></th>
            </tr>
          </thead>
          <tbody>
     <tr v-for="(item, index) in panier">
              <td>{{item.quantite}}</td>
              <td>{{item.glace}} </td>
        <td><button type="button" class="btn btn-xs btn-danger btn-block" @click="supprimer(index)">Supprimer</button></td>
      </tr>
      </tbody>
    </table>
    </div>
<div class="form-row">
<div class="form-group col-md-4">
        <label for="glace">Glace</label>
        <select class="form-control" v-model="selected"  required>
          <option v-for="product in products" v-bind:value="{ name: product.name, value: product.v }">{{ product.name }}
    </option>
        </select>
         <h1>Nom:
   {{selected.name}}
   </h1>
   <h1>value:
   {{selected.value}}
   </h1>
    </div>
    <div class="form-group col-md-1">
      <label for="qte">Quantité</label>
      <select class="form-control" id="qte"  v-model="input.quantite" required>
       <option v-for="option in numbers" :value="option">
      {{ option }}
      </select>
    </div>
     <div class="form-group col-md-1">
     <label for="prix">Prix</label> <br>
      {{selected.value*input.quantite}} €
      </div>
    <div class="form-group col-md-2">
      <label for="mod"><br></label><br><!-- ou @click="" -->
      <button type="button" class="btn btn-xs btn-success btn-block " v-on:click="ajouter()">Ajouter</button>
    </div>
  </div>
  <div class="form-group">
    <label for="com">Commentaire</label>
    <textarea class="form-control" id="com" rows="3"></textarea>
  </div>
  <center><button type="submit" class="btn btn-primary mb-2">Validation</button></center>
</form>';


//cf :last example https://maurice-chavelli.developpez.com/tutoriels/javascript/les-bases-vue-js-2/?page=on-se-repete
//panier: [{qte: "", glace: "",identifiant:""}]
//rq: changer g
$script = '
<script>
        var vm = new Vue({
            el: \'#main\',
            data: {
                 selected: {value:0,name:""},
                numbers: [1,2,3,4,5,6,7,8,9,10],
                products: [
                { name: "Pistache 1L", v: 7.5 },
                { name: "Vanille 750ml", v: 5}
                ],
                panier: [
                        { quantite: 1, glace:"vanille", prix: 5.30 },
                        { quantite: 2, glace:"fraise", prix: 5.30},
                        { quantite: 3, glace:"caramel", prix: 5.30 }
                ],
                input: { quantite:0 , glace: \'\', prix: 0 }
            },
        methods: {
          ajouter: function() {
          //si qte>0
                this.input.prix=this.selected.value;
                this.input.glace=this.selected.name;
                this.panier.push(this.input);
                this.input={ quantite:0 , glace: \'\', prix: 0 };

            },
          supprimer: function(index) {
            this.panier.splice(index, 1);
          }
        }
      });

      var ordonner = function (a, b) { return (a.qte > b.qte) };

    </script>
    ';

/*           this.panier.sort(ordonner);
            ,
          toutPoubelle: function() {
            this.poubelle = this.poubelle.concat(this.personnes);
            this.poubelle.sort(ordonner);
            this.personnes = [];
          },
          toutRetablir: function() {
            this.personnes = this.personnes.concat(this.poubelle);
            this.personnes.sort(ordonner);
            this.poubelle = [];
          },
          toutEliminer: function() {
            this.poubelle = [];
          }
          */
//gestion du highlight de la navbar (active)
$Endscript = <<<anl
<script>
$(document).ready(function(){
   $(".nav-item.active").removeClass("active");
   $("#commandes").addClass("active");
});
</script>
anl;

/* custom invalid message from bootsrtap example
$corps.<<<ERTE
<script>
// Example starter JavaScript for disabling form submissions if there are invalid fields
(function() {
  'use strict';
  window.addEventListener('load', function() {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();

</script>
ERTE;
*/

require_once(dirname(__FILE__).'/../_config/gabarit.php');

?>
