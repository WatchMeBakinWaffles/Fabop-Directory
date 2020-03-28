"use strict"

$(document).on('click', '#mailingBtn', function(){
    for ( let mail in document.querySelectorAll('mailingSelection') ) {
        console.log(mail);
    }
})

let mails = [];
$('.checkImport').bind('click', function() {
    if($(this).is(":checked")) {
        mails.push($(this)[0].parentElement.parentElement.children[3].textContent);
    } else {
        mails.splice(mails.indexOf($(this)[0].parentElement.parentElement.children[3].textContent), 1);
    }
});

$(document).on('click', '#sendMailBtn', function(e){

    e.preventDefault();
    $.ajax({
        url:URL+'/mail',
        method: "POST",
        data: { message: $('#message').val(),
                subject: $('#subject').val(),
                mailList: mails},
    }).done(function(data, status, xhr){
        if(status == "success") {
            $('#modal-mail').modal("hide");
            alert("Le mail a bien été envoyé");
        }
        else alert("Le mail a rencontré des difficultés pour s'envoyer.");
    });
})
