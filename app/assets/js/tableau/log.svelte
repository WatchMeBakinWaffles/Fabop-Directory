<script type="text/javascript">
async function fetchAsync () {
  // await response of fetch call
  let response = await fetch('http://localhost/api/logs/');
  // only proceed once promise is resolved
  let res = await response.json();
  // only proceed once second promise is resolved
  return res;
}

// trigger async function
// log response or catch error of fetch promise
fetchAsync()
  .then(res => createTable(res["hydra:member"]))
  .catch(reason => console.log(reason.message))

function createTable(res){
  var tableau = document.getElementById('logs');
  var thead = document.getElementById('TableHeader');
  var tbody = document.getElementById('TableBody');
  var tfoot = document.getElementById('TableFooter');
  var str = "";
  str = "<tr>"+
          "<th>ID</th>"+
          "<th>Date</th>"+
          "<th>Element</th>"+
          "<th>Type d'action</th>"+
          "<th>Commentaire</th>"+
          "<th>ID de l'utilisateur</th>"+
          "<th>ID de l'institution</th>"+
        "</tr>";
  thead.innerHTML += str;
  str = "";
  str = "<tr>"+
          "<th>ID</th>"+
          "<th>Date</th>"+
          "<th>Element</th>"+
          "<th>Type d'action</th>"+
          "<th>Commentaire</th>"+
          "<th>ID de l'utilisateur</th>"+
          "<th>ID de l'institution</th>"+
        "</tr>";
  tfoot.innerHTML += str;
  str="";
  res.forEach(d =>
    str += "<tr role='row' class='odd'>"+
              "<td>"+d.id+"</td>"+
              "<td>"+d.date+"</td>"+
              "<td>"+d.element+"</td>"+
              "<td>"+d.typeAction+"</td>"+
              "<td>"+d.comment+"</td>"+
              "<td><a href='/manager/people/"+d.idUser+"'>"+d.idUser+"<i class='fas fa-users'></i></a></td>"+
              "<td><a href='/manager/institutions/"+d.institution+"'>"+d.institution+"<i class='fas fa-university'></i></a></td>"+
            "</tr>"
  );
  tbody.innerHTML += str;
  str = "";
  init_table();
}
</script>

<slot></slot>
<table id="logs" class="table table-striped" style="width:100%">
  <thead class="bg-secondary text-white" id="TableHeader"></thead>
  <tbody id="TableBody"></tbody>
  <tfoot class="bg-secondary text-white" id="TableFooter"></tfoot>
</table>


