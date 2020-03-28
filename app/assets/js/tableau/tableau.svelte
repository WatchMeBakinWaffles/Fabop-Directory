<script type="text/javascript">
export let data;
let entity = data;
var node = document.getElementById("example_wrapper");
if(node != null){
  node.remove();
}
let route = "none";
let list;
async function fetchAsync () {
  // await response of fetch call
  let response = await fetch('http://localhost/api/entity_'+entity);
  // only proceed once promise is resolved
  let res = await response.json();
  // only proceed once second promise is resolved
  return res;
}

// trigger async function
// log response or catch error of fetch promise
fetchAsync()
  .then(res => createTable(res["hydra:member"]
  ))
  .catch(reason => console.log(reason.message))

function createTable(res){
  let buttons = document.getElementById('buttons_space')
  let tableau = document.getElementById('example');
  let thead = document.getElementById('TableHeader');
  let tbody = document.getElementById('TableBody');
  let tfoot = document.getElementById('TableFooter');
  let str = "";

  // GESTION DES TABLEAUX SELON ENTITY //
  if(entity == "peoples"){
    str = "<tr>"+
            "<th><input type='checkbox' id='select-all'></th>"+
            "<th>Nom</th>"+
            "<th>Prénom</th>"+
            "<th>Email</th>"+
            "<th>Date de naissance</th>"+
            "<th>Abonnement à la newsletter</th>"+
            "<th>Code postal</th>"+
            "<th>Ville</th>"+
            "<th>Date d'ajout</th>"+
            "<th><i class='fas fa-cog'></i></th>"+
          "</tr>";
    thead.innerHTML = str;
    tfoot.innerHTML = str;
    route = "people";
    list = "Liste des personnes";
    buttons.innerHTML =     '<a class="btn btn-primary mr-2" href="/manager/'+route+'/new"><i class="fas fa-plus"></i> Ajouter</a>'+
                  '<span class="not-allowed"><a class="btn text-warning mr-2" href="manager/import_export"><i class="fas fa-file-import"></i> Import des données</a></span>'+
                  '<span class="not-allowed"><a class="btn text-warning mr-2" href="#" id="exportClick"><i class="fas fa-file-export"></i> Export des données</a></span>';

    res.forEach(elem =>
      tbody.innerHTML += "<tr role='row' class='odd'>"+
                  "<td id='"+elem.id+"'><input type='checkbox' name='selected["+elem.id+"]' class='checkImport'></td> <!--VALUE !-->"+
                  "<td>"+elem.name+"</td>"+
                  "<td>"+elem.firstname+"</td>"+
                  "<td>"+elem.adresseMailing+"</td>"+
                  "<td>"+new Date(elem.birthdate).toLocaleDateString('en-GB')+"</td>"+
                  "<td>"+(elem.newsletter ? "Oui" : "Non")+"</td>"+
                  "<td>"+elem.postalCode+"</td>"+
                  "<td>"+elem.city+"</td>"+
                  "<td>"+new Date(elem.addDate).toLocaleDateString('en-GB')+"</td>"+
                  "<td>"+
                      "<a href='/manager/"+route+"/"+elem.id+"'><i class='fas fa-eye'></i></a>"+
                      "<a href='/manager/"+route+"/"+elem.id+"/edit'><i class='far fa-edit'></i></a>"+
                  "</td>"+
                "</tr>"
    );
  }
  else if(entity == "institutions"){
    str = "<tr>"+
            "<th><input type='checkbox' id='select-all'></th>"+
            "<th>Nom</th>"+
            "<th>Rôle</th>"+
            "<th><i class='fas fa-cog'></i></th>"+
          "</tr>";
    thead.innerHTML = str;
    tfoot.innerHTML = str;
    route = "institutions";
    list = "Liste des institutions";
    buttons.innerHTML =     '<a class="btn btn-primary mr-2" href="/manager/'+route+'/new"><i class="fas fa-plus"></i> Ajouter</a>'
    res.forEach(elem =>
      tbody.innerHTML += "<tr role='row' class='odd'>"+
                "<td id=''><input type='checkbox' name='selected[]' class='checkImport'></td> <!--VALUE !-->"+
                "<td>"+elem.name+"</td>"+
                "<td>"+elem.role+"</td>"+
                "<td>"+
                    "<a href='/manager/"+route+"/"+elem.id+"'><i class='fas fa-eye'></i></a>"+
                    "<a href='/manager/"+route+"/"+elem.id+"/edit'><i class='far fa-edit'></i></a>"+
                "</td>"+
              "</tr>"
    );
  }
  else if(entity == "shows"){
    str = "<tr>"+
            "<th><input type='checkbox' id='select-all'></th>"+
            "<th>Nom</th>"+
            "<th>Année</th>"+
            "<th><i class='fas fa-cog'></i></th>"+
          "</tr>";
    thead.innerHTML = str;
    tfoot.innerHTML = str;
    route = "shows";
    list = "Liste des spectacles";
    buttons.innerHTML =     '<a class="btn btn-primary mr-2" href="/manager/'+route+'/new"><i class="fas fa-plus"></i> Ajouter</a>'
    res.forEach(elem =>
      tbody.innerHTML += "<tr role='row' class='odd'>"+
                "<td id=''><input type='checkbox' name='selected[]' class='checkImport'></td> <!--VALUE !-->"+
                "<td>"+elem.name+"</td>"+
                "<td>"+elem.year+"</td>"+
                "<td>"+
                    "<a href='/manager/"+route+"/"+elem.id+"'><i class='fas fa-eye'></i></a>"+
                    "<a href='/manager/"+route+"/"+elem.id+"/edit'><i class='far fa-edit'></i></a>"+
                "</td>"+
              "</tr>"
    );
  }
  else if(entity == "tags"){
    str = "<tr>"+
            "<th><input type='checkbox' id='select-all'></th>"+
            "<th>Nom</th>"+
            "<th><i class='fas fa-cog'></i></th>"+
          "</tr>";
    thead.innerHTML = str;
    tfoot.innerHTML = str;
    route = "tags";
    list = "Liste des étiquettes";
    buttons.innerHTML =     '<a class="btn btn-primary mr-2" href="/manager/'+route+'/new"><i class="fas fa-plus"></i> Ajouter</a>'
    res.forEach(elem =>
      tbody.innerHTML += "<tr role='row' class='odd'>"+
                "<td id='1'><input type='checkbox' name='selected[1]' class='checkImport'></td> <!--VALUE !-->"+
                "<td>"+elem.name+"</td>"+
                "<td>"+
                    "<a href='/manager/"+route+"/"+elem.id+"'><i class='fas fa-eye'></i></a>"+
                    "<a href='/manager/"+route+"/"+elem.id+"/edit'><i class='far fa-edit'></i></a>"+
                "</td>"+
              "</tr>"
    );
  }
  else if(entity == "performances"){
    str = "<tr>"+
            "<th><input type='checkbox' id='select-all'></th>"+
            "<th>Id</th>"+
            "<th>Date</th>"+
            "<th>Spectacle</th>"+
            "<th><i class='fas fa-cog'></i></th>"+
          "</tr>";
    thead.innerHTML = str;
    tfoot.innerHTML = str;
    route = "performance";
    list = "Liste des performances";
    buttons.innerHTML =     '<a class="btn btn-primary mr-2" href="/manager/'+route+'/new"><i class="fas fa-plus"></i> Ajouter</a>'
    res.forEach(elem =>
      tbody.innerHTML  += "<tr role='row' class='odd'>"+
                "<td id='1'><input type='checkbox' name='selected[1]' class='checkImport'></td> <!--VALUE !-->"+
                "<td>"+elem.date+"</td>"+
                "<td>"+elem.shows+"</td>"+
                "<td>"+
                    "<a href='/manager/"+route+"/"+elem.id+"'><i class='fas fa-eye'></i></a>"+
                    "<a href='/manager/"+route+"/"+elem.id+"/edit'><i class='far fa-edit'></i></a>"+
                "</td>"+
              "</tr>"
    );
  }
  table_init('#example')
  // Cette ligne au dessus est importante pour que le tableau s'init bien avec bs4 datatable.
}
</script>

<slot></slot>
<div id="buttons_space" class="row m-3">
</div>
<table id="example" class="table table-striped" style="width:100%">
  <thead class="bg-secondary text-white" id="TableHeader"></thead>
  <tbody id="TableBody"></tbody>
  <tfoot class="bg-secondary text-white" id="TableFooter"></tfoot>
</table>
<hr>

<style>
hr{
	border: 1px solid #951b81;
}
</style>
