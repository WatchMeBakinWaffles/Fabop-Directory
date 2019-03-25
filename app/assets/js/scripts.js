// Side menu dropdown
$('.dropdown').on('show.bs.dropdown', function(e){
    $(this).find('.dropdown-menu').first().stop(true, true).slideDown(300);
});

$('.dropdown').on('hide.bs.dropdown', function(e){
    $(this).find('.dropdown-menu').first().stop(true, true).slideUp(200);
});

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

$(document).ready(function() {
    $('#table-view').DataTable({
        buttons: [
            'csv', 'excel', 'pdf'
        ],
        order:[],
        'columnDefs': [{
            'orderable': false,
            'targets': [0]
        },
        {
            'orderable': false,
            'targets': [8]
        }],
        language: {
            url:'//cdn.datatables.net/plug-ins/1.10.15/i18n/French.json'
        }
        
    });
});

/*
function supp_accents( data ) {
    return ! data ?
        '' :
        typeof data === 'string' ?
            data
                .replace( /έ/g, 'ε' )
                .replace( /[ύϋΰ]/g, 'υ' )
                .replace( /ό/g, 'ο' )
                .replace( /ώ/g, 'ω' )
                .replace( /ά/g, 'α' )
                .replace( /[ίϊΐ]/g, 'ι' )
                .replace( /ή/g, 'η' )
                .replace( /\n/g, ' ' )
                .replace( /á/g, 'a' )
                .replace( /é/g, 'e' )
                .replace( /í/g, 'i' )
                .replace( /ó/g, 'o' )
                .replace( /ú/g, 'u' )
                .replace( /ê/g, 'e' )
                .replace( /î/g, 'i' )
                .replace( /ô/g, 'o' )
                .replace( /è/g, 'e' )
                .replace( /ï/g, 'i' )
                .replace( /ü/g, 'u' )
                .replace( /ã/g, 'a' )
                .replace( /õ/g, 'o' )
                .replace( /ç/g, 'c' )
                .replace( /ì/g, 'i' ):
            data;
};

jQuery.extend( jQuery.fn.dataTableExt.oSort,
{
    "french-string-asc"  : function (s1, s2) { return s1.localCompare(s2); },
    "french-string-desc" : function (s1, s2) { return s2.localCompare(s1); }
});

jQuery.fn.DataTable.ext.type.search['string'] = function (data) {
    return supp_accents(data);
}

$('#table-view_filter input[type=search]').keyup( function() {
    var table = $('#table-view').DataTable();
    table.search(
        jQuery.fn.DataTable.ext.type.search.string(this.value)
        ).draw();
});
*/


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
