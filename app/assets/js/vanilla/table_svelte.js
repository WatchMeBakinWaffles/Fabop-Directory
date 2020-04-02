function table_init(id_table){
    if ($("table").length){
        $(document).ready(function() {
            const tableElementCount = $('table thead tr').get()[0].childElementCount;
            $(id_table).DataTable({
                initComplete: function () {
                    this.api().columns().every( function ( index ) {
                        var column = this;

                        if(index != 0 && index != tableElementCount-1) {
                            var select = $('<select class="form-control form-control-sm"><option value=""></option></select>')
                                .appendTo( $(column.footer()).empty() )
                                .on( 'change', function () {
                                    var val = $.fn.dataTable.util.escapeRegex(
                                        $(this).val()
                                    );

                                    column
                                        .search( val ? '^'+val+'$' : '', true, false )
                                        .draw();
                                } );

                            column.data().unique().sort().each( function ( d, j ) {
                                select.append( '<option value="'+d+'">'+d+'</option>' )
                            } );
                        } else {
                            $(column.footer()).appendTo( $(column.footer()).empty() )
                        }
                    } );
                },
                order:[],
                'columnDefs': [{
                    'orderable': false,
                    'targets': [0]
                },
                    {
                        'orderable': false,
                        'targets': [tableElementCount-1]
                    }],
                language: {
                    url:'//cdn.datatables.net/plug-ins/1.10.15/i18n/French.json'
                }
            })
        });
    }
    // Select all function in index tables
    $('#select-all').click(function(event) {
        if(this.checked) {
            // Iterate each checkbox
            $(':checkbox').each(function() {
                this.checked = true;
            });
        } else {
            $(':checkbox').each(function() {
                this.checked = false;
            });
        }
    });
    if (document.getElementById("exportClick")){
      document.getElementById("exportClick").addEventListener("click", function () {
          let checkboxs = document.getElementsByClassName("checkImport");
          let liste_id = [];
          for (const checkbox of checkboxs) {
              if (checkbox.checked === true) {
                  liste_id.push(checkbox.parentNode.id);
              }
          }
          if (liste_id.length > 0) {
              $.ajax({
                  url: '/manager/imp-exp/export_selectif',
                  method: "POST",
                  data: {
                      ids: liste_id
                  }
              }).done(function () {
                  window.location = "/export_selectif.xlsx";
              }).fail(function () {
                  alert("Le serveur a rencontré des difficultés avec votre demande.");
              });
          } else {
              alert("Vous n'avez rien séléctionné");
          }
      });
    }
}
