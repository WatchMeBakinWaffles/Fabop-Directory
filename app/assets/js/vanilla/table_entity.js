if (path!='/dashboard'){
    if ($("table").length){
        $(document).ready(function() {
            const tableElementCount = $('table thead tr').get()[0].childElementCount;
            $('.table').DataTable({
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
}
