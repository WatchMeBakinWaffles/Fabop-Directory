<script type="text/javascript">
export let data;
let entity = data;
var node = document.getElementById("example_wrapper");
if(node != null){
  node.remove();
}
let api = "none";
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
  var tableau = document.getElementById('example');
  var thead = document.getElementById('TableHeader');
  var tbody = document.getElementById('TableBody');
  var tfoot = document.getElementById('TableFooter');
  var str = "";
  if(entity == "peoples"){
    str = "<tr>"+
            "<th><input type='checkbox' id='select-all'></th>"+
            "<th>Nom</th>"+
            "<th>Prénom</th>"+
            "<th>Date de naissance</th>"+
            "<th>Abonnement à la newsletter</th>"+
            "<th>Code postal</th>"+
            "<th>Ville</th>"+
            "<th>Date d'ajout</th>"+
            "<th><i class='fas fa-cog'></i></th>"+
          "</tr>";
    thead.innerHTML += str;
    str = "<tr>"+
            "<th><input type='checkbox' id='select-all'></th>"+
            "<th>Nom</th>"+
            "<th>Prénom</th>"+
            "<th>Date de naissance</th>"+
            "<th>Abonnement à la newsletter</th>"+
            "<th>Code postal</th>"+
            "<th>Ville</th>"+
            "<th>Date d'ajout</th>"+
            "<th><i class='fas fa-cog'></i></th>"+
        "</tr>";
    tfoot.innerHTML += str;
    str = "";
    api = "people";
    list = "Liste des personnes";
  }else if(entity == "institutions"){
    str = "<tr>"+
            "<th><input type='checkbox' id='select-all'></th>"+
            "<th>Nom</th>"+
            "<th>Rôle</th>"+
            "<th><i class='fas fa-cog'></i></th>"+
          "</tr>";
    thead.innerHTML += str;
    str = "<tr>"+
            "<th><input type='checkbox' id='select-all'></th>"+
            "<th>Nom</th>"+
            "<th>Rôle</th>"+
            "<th><i class='fas fa-cog'></i></th>"+
          "</tr>";
    tfoot.innerHTML += str;
    str = "";
    api = "institution";
    list = "Liste des institutions";
  }else if(entity == "shows"){
    str = "<tr>"+
            "<th><input type='checkbox' id='select-all'></th>"+
            "<th>Nom</th>"+
            "<th>Année</th>"+
            "<th><i class='fas fa-cog'></i></th>"+
          "</tr>";
    thead.innerHTML += str;
    str = "<tr>"+
            "<th><input type='checkbox' id='select-all'></th>"+
            "<th>Nom</th>"+
            "<th>Année</th>"+
            "<th><i class='fas fa-cog'></i></th>"+
          "</tr>";
    tfoot.innerHTML += str;
    str = "";
    api = "show";
    list = "Liste des spectacles";
  }else if(entity == "tags"){
    str = "<tr>"+
            "<th><input type='checkbox' id='select-all'></th>"+
            "<th>Nom</th>"+
            "<th><i class='fas fa-cog'></i></th>"+
          "</tr>";
    thead.innerHTML += str;
    str = "<tr>"+
            "<th><input type='checkbox' id='select-all'></th>"+
            "<th>Nom</th>"+
            "<th><i class='fas fa-cog'></i></th>"+
          "</tr>";
    tfoot.innerHTML += str;
    str = "";
    api = "tag";
    list = "Liste des étiquettes";
  }else if(entity == "performances"){
    str = "<tr>"+
            "<th><input type='checkbox' id='select-all'></th>"+
            "<th>Id</th>"+
            "<th>Date</th>"+
            "<th>Spectacle</th>"+
            "<th><i class='fas fa-cog'></i></th>"+
          "</tr>";
    thead.innerHTML += str;
    str = "<tr>"+
            "<th><input type='checkbox' id='select-all'></th>"+
            "<th>Id</th>"+
            "<th>Date</th>"+
            "<th>Spectacle</th>"+
            "<th><i class='fas fa-cog'></i></th>"+
          "</tr>";
    tfoot.innerHTML += str;
    str = "";
    api = "performance";
    list = "Liste des performances";
  }
  if(api =='people'){
    res.forEach(d =>
      str += "<tr role='row' class='odd'>"+
                  "<td id='1'><input type='checkbox' name='selected[1]' class='checkImport'></td> <!--VALUE !-->"+
                  "<td>"+d.name+"</td>"+
                  "<td>"+d.firstname+"</td>"+
                  "<td>"+d.birthdate+"</td>"+
                  "<td>"+d.newsletter+"</td>"+
                  "<td>"+d.postalCode+"</td>"+
                  "<td>"+d.city+"</td>"+
                  "<td>"+d.addDate+"</td>"+
                  "<td>"+
                      "<a href='/manager/"+api+"/"+d.id+"'><i class='fas fa-eye'></i></a>"+
                      "<a href='/manager/"+api+"/"+d.id+"/edit'><i class='far fa-edit'></i></a>"+
                  "</td>"+
                "</tr>"
    );
    tbody.innerHTML += str;
    str = "";
  }else if(api =='institution'){
    res.forEach(d =>
      str += "<tr role='row' class='odd'>"+
                "<td id='1'><input type='checkbox' name='selected[1]' class='checkImport'></td> <!--VALUE !-->"+
                "<td>"+d.name+"</td>"+
                "<td>"+d.role+"</td>"+
                "<td>"+
                    "<a href='/manager/"+api+"/"+d.id+"'><i class='fas fa-eye'></i></a>"+
                    "<a href='/manager/"+api+"/"+d.id+"/edit'><i class='far fa-edit'></i></a>"+
                "</td>"+
              "</tr>"
    );
    tbody.innerHTML += str;
    str = "";
  }else if(api =='show'){
    res.forEach(d =>
      str += "<tr role='row' class='odd'>"+
                "<td id='1'><input type='checkbox' name='selected[1]' class='checkImport'></td> <!--VALUE !-->"+
                "<td>"+d.name+"</td>"+
                "<td>"+d.year+"</td>"+
                "<td>"+
                    "<a href='/manager/"+api+"/"+d.id+"'><i class='fas fa-eye'></i></a>"+
                    "<a href='/manager/"+api+"/"+d.id+"/edit'><i class='far fa-edit'></i></a>"+
                "</td>"+
              "</tr>"
    );
    tbody.innerHTML += str;
    str = "";
  }else if(api =='tag'){
    res.forEach(d =>
      str += "<tr role='row' class='odd'>"+
                "<td id='1'><input type='checkbox' name='selected[1]' class='checkImport'></td> <!--VALUE !-->"+
                "<td>"+d.name+"</td>"+
                "<td>"+
                    "<a href='/manager/"+api+"/"+d.id+"'><i class='fas fa-eye'></i></a>"+
                    "<a href='/manager/"+api+"/"+d.id+"/edit'><i class='far fa-edit'></i></a>"+
                "</td>"+
              "</tr>"
    );
    tbody.innerHTML += str;
    str = "";
  }else if(api =='performance'){
    res.forEach(d =>
      str += "<tr role='row' class='odd'>"+
                "<td id='1'><input type='checkbox' name='selected[1]' class='checkImport'></td> <!--VALUE !-->"+
                "<td>"+d.date+"</td>"+
                "<td>"+d.shows+"</td>"+
                "<td>"+
                    "<a href='/manager/"+api+"/"+d.id+"'><i class='fas fa-eye'></i></a>"+
                    "<a href='/manager/"+api+"/"+d.id+"/edit'><i class='far fa-edit'></i></a>"+
                "</td>"+
              "</tr>"
    );
    tbody.innerHTML += str;
    str = "";
  }
  table_init()
}
</script>

<slot></slot>
<div class="row m-3">
    <a class="btn btn-primary mr-2" href="/manager/people/new"><i class="fas fa-plus"></i> Ajouter</a>
    <span class="not-allowed"><a class="btn text-warning mr-2" href="manager/imp_exp"><i class="fas fa-file-import"></i> Import des données</a></span>
    <span class="not-allowed"><a class="btn text-warning mr-2" href="manager/import_export" id="exportClick"><i class="fas fa-file-export"></i> Export des données</a></span>
</div>
<table id="example" class="table table-striped" style="width:100%">
  <thead class="bg-secondary text-white" id="TableHeader"></thead>
  <tbody id="TableBody"></tbody>
  <tfoot class="bg-secondary text-white" id="TableFooter"></tfoot>
</table>

