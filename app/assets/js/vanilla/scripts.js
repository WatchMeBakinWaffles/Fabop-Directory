const URL = "http://localhost:80";

// Side menu dropdown
$('.dropdown').on('show.bs.dropdown', function(e){
    $(this).find('.dropdown-menu').first().stop(true, true).slideDown(300);
});

$('.dropdown').on('hide.bs.dropdown', function(e){
    $(this).find('.dropdown-menu').first().stop(true, true).slideUp(200);
});

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

/* If the current path of the page is matching the regex, ajax queries are send */

const path = window.location.pathname;

if (path.match(/\/manager\/people\/[0-9]/g) != null) {
    // Ajax function to append the list of existing tags
    $.ajax({
        url: URL+"/manager/tags/list",
        method: "get"
    }).done(function(content){
        $('#tags_affect_tag').append(content);
    })

    // Ajax function to append the list of existing performances
    $.ajax({
        url: URL+"/manager/performances/list",
        method: "get"
    }).done(function(content){
        $('#tags_affect_performance').append(content);
    })
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

function startTime() {
    var today = new Date();
    var h = today.getHours();
    var m = today.getMinutes();
    var s = today.getSeconds();
    m = checkTime(m);
    s = checkTime(s);
    document.getElementById('date_time').innerHTML =
    h + ":" + m + ":" + s;
    var t = setTimeout(startTime, 500);
}
function checkTime(i) {
    if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
    return i;
}
