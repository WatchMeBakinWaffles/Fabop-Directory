/** When the submit button of id "form_entity_tags_new" is clicked, a tag is added, the modal is no longer visible
 and the options for the select tags are updated */
$(document).on("submit", "#form_entity_tags_new", function(e){
    e.preventDefault();
    $.ajax({
        url:'/manager/tags/new',
        method: "POST",
        data: $('#form_entity_tags_new').serialize(),
    }).done(function(data, status, xhr){
        if(status == "success") {
            alert("Le tag a bien été ajouté");
            $('#exampleModalCenter').modal("hide");
            $.ajax({
                url: "/manager/tags/list",
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
    $.ajax({
        url:'/manager/performances/new',
        method: "POST",
        data:     $('#form_entity_performances_new').serialize(),
    }).done(function(data, status, xhr){
        if(status == "success") {
            alert("La représentation a bien été ajoutée");
            $('#exampleModalCenter').modal("hide");
            $.ajax({
                url: "/manager/performances/list",
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
    $.ajax({
        url:'/manager/tags-affect/new',
        method: "POST",
        data: $('#form_tags_affect_new').serialize(),
    }).done(function(data, status, xhr){
        if(status == "success") {
            alert("L'affectation de tag a bien été ajoutée");
            $.ajax({
                url: "/manager/tags-affect/",
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
