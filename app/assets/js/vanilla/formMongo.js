if(path=="/manager/people/new"){
    let indice_tableau=0;
    document.getElementById("add_person_data").addEventListener('click',function () {
        let label_label = document.createElement("label");
        label_label.innerText="Nom du champ :";
        let label_value = document.createElement("label");
        label_value.innerText="Valeur :";
        let input_label = document.createElement("input");
        input_label.className = "form-control";
        input_label.name = "person_data["+indice_tableau+"][label]"
        let input_value = document.createElement("input");
        input_value.className = "form-control";
        input_value.name = "person_data["+indice_tableau+"][value]";
        let row = document.createElement("div",{className : "row"});
        row.append(label_label,input_label,label_value,input_value);
        $("#person_data").before(row);
        indice_tableau++;
    })

}

