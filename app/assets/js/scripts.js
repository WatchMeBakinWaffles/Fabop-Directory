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
        
// Ajax function to append the content of the form to create new tags
$('#btn-add-tag').click(function() {
    $.ajax({
        url: "http://localhost/manager/tags/modal/new",
        method: "get"
    }).done(function(content){
        appendContentToModal(content);
    })
});

// Ajax function to append the content of the form to create new performance
$('#btn-add-performance').click(function() {
    $.ajax({
        url: "http://localhost/manager/performances/new",
        method: "get"
    }).done(function(content){
        appendContentToModal(content);
    })
});

function appendContentToModal(content){
    $('.modal-body').empty();
    $('.modal-body').append(content);
}

/* If the current path of the page is matching the regex, ajax queries are send */

const path = window.location.pathname;

if (path.match(/\/manager\/people\/[0-9]/g) != null) {
    // Ajax function to append the list of existing tags
    $.ajax({
        url: "http://localhost/manager/tags/list",
        method: "get"
    }).done(function(content){
        $('#tags_affect_tag').append(content);
    })
    
    // Ajax function to append the list of existing performances
    $.ajax({
        url: "http://localhost/manager/performances/list",
        method: "get"
    }).done(function(content){
        $('#tags_affect_performance').append(content);
    })
}


/** When the submit button of id "form_entity_tags_new" is clicked, a tag is added, the modal is no longer visible
    and the options for the select tags are updated */
$(document).on("submit", "#form_entity_tags_new", function(e){
    e.preventDefault();
    $.post('http://localhost/manager/tags/new', 
        $('#form_entity_tags_new').serialize(), 
        function(data, status, xhr){
            if(status == "success") {
                alert("Le tag a bien été ajouté");
                $('#exampleModalCenter').modal("hide");
                $.ajax({
                    url: "http://localhost/manager/tags/list",
                    method: "get"
                }).done(function(content){
                    $('#tags_affect_tag').empty();
                    $('#tags_affect_tag').append(content);
                })
            }
            else alert("Le tag n'a pas été ajouté");
    });
});

/** When the submit button of id "form_entity_performances_new" is clicked, a performance is added, the modal is no longer visible
    and the options for the select performances are updated */
$(document).on("submit", "#form_entity_performances_new", function(e){
    e.preventDefault();
    $.post('http://localhost/manager/performances/new', 
    $('#form_entity_performances_new').serialize(), 
        function(data, status, xhr){
            if(status == "success") {
                alert("La représentation a bien été ajoutée");
                $('#exampleModalCenter').modal("hide");
                $.ajax({
                    url: "http://localhost/manager/performances/list",
                    method: "get"
                }).done(function(content){
                    $('#tags_affect_performance').empty();
                    $('#tags_affect_performance').append(content);
                })
            }
            else alert("La représentation n'a pas été ajouté");
    });
});

/** When the submit button of id "form_tags_affect_new" is clicked, a tag_affect is added
    and the table of tags_affect is updated */
$(document).on("submit", "#form_tags_affect_new", function(e){
    e.preventDefault();
    $.post('http://localhost/manager/tags-affect/new', 
    $('#form_tags_affect_new').serialize(), 
        function(data, status, xhr){
            if(status == "success") {
                alert("L'affectation de tag a bien été ajoutée");
                $.ajax({
                    url: "http://localhost/manager/tags-affect/",
                    method: "get"
                }).done(function(content){
                    // On peut commenter car on reload la page
                    // $('#table_tags_affect').empty();
                    // $('#table_tags_affect').append(content);
                    /** A changer, trouver une autre solution (on reload sinon la liste des tags_affect
                        s'affiche entièrement au lieu d'une personne particulière) */
                    window.location.reload();
                })
            }
            else alert("L'affectation de tag n'a pas été ajoutée");
    });
});

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

$('#import-file').change(function(){
    var v = $(this).val().split('\\');
    var m = v[v.length-1]
    $('#labelChoose').text(m);
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
