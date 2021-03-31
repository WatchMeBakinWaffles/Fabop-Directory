if(path==="/admin/permission") {
    indice_tableau = 0
   if ( $( ".add_custom_data" ).length ) {
        $("#toHide").css('visibility', 'hidden');
    }
    $(".add_custom_data").click(function () {
        if(indice_tableau === 0) {
            indice_tableau ++
        }
        let label = $("<div class='form-group'><label></div>").text('Champ a filtrer');
        let input = $('<select name="custom_data['+indice_tableau+'][champ_a_filtrer]" class="form-control" id="champ_a_filtrer['+indice_tableau+']"/>')
        let label2 = $("<div class='form-group'><label>").text('Valeur du filtre');
        let input2 = $('<input name="custom_data['+indice_tableau+'][valeur_du_filtre]" class="form-control"/>')
        let label3 = $("<div class='form-group'><label>").text('Droits lecture');
        let input3 = $('<select name="custom_data['+indice_tableau+'][droits_lecture]"  class="form-control"/>')
        let label4 = $("<div class='form-group'><label>").text("Droits d'écriture");
        let input4 = $('<select name="custom_data['+indice_tableau+'][droits_ecriture]"  class="form-control"/>')
        input3.append($('<option>', {
            value: 'oui',
            text : 'oui'
        }));
        input3.append($('<option>', {
            value: 'non',
            text : 'non'
        }));
        input4.append($('<option>', {
            value: 'oui',
            text : 'oui'
        }));
        input4.append($('<option>', {
            value: 'non',
            text : 'non'
        }));
        input.className = "form-control";
        input.appendTo(label)
        input2.appendTo(label2)
        input3.appendTo(label3)
        input4.appendTo(label4)
        $('#permission_form').append(label)
        $('#permission_form').append(label2)
        $('#permission_form').append(label3)
        $('#permission_form').append(label4)
        let x = document.getElementById("permission_form_champ_a_filtrer0");
        current_field = document.getElementById('champ_a_filtrer['+indice_tableau+']')
        $.each(x, function (i, item) {
            $(".champ_a_filtrer["+indice_tableau+"]")
            $(item).clone().appendTo(current_field)
        })
        indice_tableau++
    })
}