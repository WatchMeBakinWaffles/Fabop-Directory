"use strict"

$(document).on('click', '#mailingBtn', function(){
    for ( let mail in document.querySelectorAll('mailingSelection') ) {
        console.log(mail);  
    }
})

$(document).on('click', '#sendMailBtn', function(e){
    console.log('ok');
    e.preventDefault();
    $.ajax({
        url:URL+'/mail',
        method: "POST",
        data: {message: $('#message').serialize(),
                subject: $('#subject').serialize()},
    }).done(function(data, status, xhr){
        if(status == "success") {
            $('#modal-mail').modal("hide");
            alert("Le mail a bien été envoyé");
        }
        else alert("Le tag n'a pas été ajouté");
    });
})