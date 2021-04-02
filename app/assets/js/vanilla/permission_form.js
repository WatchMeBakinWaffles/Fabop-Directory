if(path==="/admin/permission/create" || path.startsWith('/admin/permission') && path.endsWith('/edit')) {
    indice_tableau = 0

    if ( $( ".add_custom_data" ).length ) {
        $(".toHide").css('display', 'none');
    }
    if ( $( ".count_perm" ).length ) {
        indice_tableau = $( ".count_perm" ).val()
    }
    $( ".new_perm").parents('div.form-group').before( "<hr/>")
    setTimeout(function() {$(".flash-message").delay(5000).hide();}, 5000);

    $(".add_custom_data").click(function () {
        if(indice_tableau === 0) {
            indice_tableau ++
        }
        let separator = $("<p class='text-muted' style='margin-bottom: -20px'>Nouveau filtre</p><hr/>")
        let label = $("<div class='form-group'><label></div>").text('Champ a filtrer');
        let input = $('<select name="custom_data['+indice_tableau+'][champ_a_filtrer]" class="form-control" id="champ_a_filtrer['+indice_tableau+']"/>')
        let label2 = $("<div class='form-group'><label>").text('Valeur du filtre');
        let input2 = $('<input name="custom_data['+indice_tableau+'][valeur_du_filtre]" class="form-control" required/>')
        let label3 = $("<div class='form-group'><label>").text('Droits lecture');
        let input3 = $('<select name="custom_data['+indice_tableau+'][droits_lecture]"  class="form-control"/>')
        let label4 = $("<div class='form-group'><label>").text("Droits d'écriture");
        let input4 = $('<select name="custom_data['+indice_tableau+'][droits_ecriture]"  class="form-control"/>')
        input3.append($('<option>', {
            value: 'oui',
            text : 'Oui'
        }));
        input3.append($('<option>', {
            value: 'non',
            text : 'Non'
        }));
        input3.append($('<option>', {
            value: 'inchanges',
            text : 'Inchangés'
        }));
        input4.append($('<option>', {
            value: 'oui',
            text : 'Oui'
        }));
        input4.append($('<option>', {
            value: 'non',
            text : 'Non'
        }));
        input4.append($('<option>', {
            value: 'inchanges',
            text : 'Inchangés'
        }));
        input.className = "form-control";
        input.appendTo(label)
        input2.appendTo(label2)
        input3.appendTo(label3)
        input4.appendTo(label4)
        let form = $('#permission_form')
        if ( $( "#permission_form_edit" ).length ) {
            form = $('#permission_form_edit')
        }
        form.append(separator)
        form.append(label)
        form.append(label2)
        form.append(label3)
        form.append(label4)
        form.append($(".add_custom_data"))
        let x = document.getElementById("permission_form_champ_a_filtrer0");
        if ( $("#permission_form_edit_champ_a_filtrer1").length ) {
            x = document.getElementById("permission_form_edit_champ_a_filtrer1");
        }
        current_field = document.getElementById('champ_a_filtrer['+indice_tableau+']')
        $.each(x, function (i, item) {
            $(".champ_a_filtrer["+indice_tableau+"]")
            $(item).clone().appendTo(current_field)
        })
        indice_tableau++
    })
}