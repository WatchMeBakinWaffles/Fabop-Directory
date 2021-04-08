
/*
if (path != '/dashboard') {
    if ($("table").length) {
        $(document).ready(function () {
            let choiceFilter = [
                {key: "equals", value: "Est égal à:"},
                {key: "startBy", value: "Commence par:"},
                {key: "endBy", value: "Fini par:"},
                {key: "differentFrom", value: "Différent de:"},
                {key: "contains", value: "Contient:"}]
            $('.table').DataTable({
                initComplete: function () {
                    var selectTest = $('<select class="form-control form-control-sm" id="mySelect"><option value="" disabled selected>Choix de la colonne à filtrer</option></select>')
                    var newSel = $('<select class="form-control form-control-sm" id="mySelectOptions"></select>')
                    var inputSearch = $('<input type="test" class="form-control form-control-sm" id="inputSearch"/>')
                    var buttonS = $('<a  class="btn btn-primary mr-1" id="search"><i class="fa fa-search" aria-hidden="true"/> Rechercher</a>')
                    let columnSelected = $('#mySelect').val()
                    $('#DataTables_Table_0_length').append(selectTest)
                    $('#DataTables_Table_0_length').append(newSel)
                    $('#DataTables_Table_0_length').append(inputSearch)
                    $('#DataTables_Table_0_length').append(buttonS)
                    choiceFilter.map(obj => {
                        let $option = $("<option/>", {
                            value: obj.key,
                            text: obj.value
                        });
                        return $('#mySelectOptions').append($option);
                    });
                    newSel.hide()
                    inputSearch.hide()
                    buttonS.hide()
                    $('#search').click(function () {
                        let val = $.fn.dataTable.util.escapeRegex(
                            $('#inputSearch').val()
                        );
                        let searchOptions = $('#mySelectOptions :selected').val()
                        let tabled =  $('.table').DataTable()
                        tabled.search('').columns().search('').draw();
                        switch (searchOptions) {
                            case 'equals':
                                tabled.column(columnSelected).search(val ? '^' + val + '$' : '', true, false)
                                    .draw();
                                break;
                            case 'startBy':
                                tabled.column(columnSelected).search(val ? '^' + val + '.*$' : '', true, false)
                                    .draw();
                                break;
                            case 'endBy':
                                tabled.column(columnSelected).search(val ? '^.*' + val + '$' : '', true, false)
                                    .draw();
                                break;
                            case 'differentFrom':
                                tabled.column(columnSelected).search(val ? '^((?!' + val + ').)*$' : '', true, false)
                                    .draw();
                                break;
                            case 'contains':
                                tabled.column(columnSelected).search(val ? '^.*' + val + '.*$' : '', true, false)
                                    .draw();
                                break;
                        }
                    });
                    this.api().columns().every(function (index) {
                        let column = this;
                        if (column.header().innerText)
                            $('#mySelect')
                                .append($("<option></option>")
                                    .attr("value", index)
                                    .text(column.header().innerText));
                        $('#mySelect').on('change', function () {
                            columnSelected = $('#mySelect :selected').val()
                            newSel.show()
                            inputSearch.show()
                            buttonS.show()
                        });

                    });
                },
            })
        });
    }

}
*/
