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
  let tableau = document.getElementById('logs');
  let thead = document.getElementById('TableHeader');
  let tbody = document.getElementById('TableBody');
  let tfoot = document.getElementById('TableFooter');
  let str = "";
  str = "<tr>"+
          "<th>ID</th>"+
          "<th>Date</th>"+
          "<th>Element</th>"+
          "<th>Type d'action</th>"+
          "<th>Commentaire</th>"+
          "<th>ID de l'utilisateur</th>"+
          "<th>ID de l'institution</th>"+
        "</tr>";
  thead.innerHTML = str;
  tfoot.innerHTML = str;
  str="";
  res.forEach(function(elem){
    str += "<tr role='row' class='odd'>"+
              "<td>"+elem.id+"</td>"+
              "<td>"+new Date(elem.date).toLocaleDateString('en-GB')+"</td>"+
              "<td>"+elem.element+"</td>"+
              "<td>"+elem.typeAction+"</td>"+
              "<td>"+elem.comment+"</td>"
    if (elem.idUser){
      str += "<td><a href='/admin/users/"+elem.idUser+"'>"+elem.idUser+"<i class='fas fa-users'></i></a></td>"
    }
    else{
      str += "<td>New user</td>"
    }
    if (elem.institution){
      str += "<td><a href='/manager/institutions/"+elem.institution+"'>"+elem.institution+"<i class='fas fa-university'></i></a></td></tr>"
    }
    else{
      str += "<td>New Institution</td></tr>"
    }
  }
  );
  tbody.innerHTML = str;
  str = "";
  table_init('#logs')
}
</script>

<h2><i class="fas fa-clipboard-list"></i> Logs</h2>
<table id="logs" class="table table-striped" style="width:100%">
  <thead class="bg-secondary text-white" id="TableHeader"></thead>
  <tbody id="TableBody"></tbody>
  <tfoot class="bg-secondary text-white" id="TableFooter"></tfoot>
</table>
