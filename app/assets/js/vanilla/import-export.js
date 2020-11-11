$('#import-file').change(function(){
    let v = $(this).val().split('\\');
    let m = v[v.length-1]
    $('#labelChoose').text(m);
});

if (path === "/manager/people/"){
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
            alert("Vous n'avez rien sélectionné");
        }
    });
}