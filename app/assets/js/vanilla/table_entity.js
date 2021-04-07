if (path != '/dashboard') {
    if ($("table").length) {
        $(document).ready(function () {
            const tableElementCount = $('table thead tr').get()[0].childElementCount;
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

                        if (index != 0 && index != tableElementCount - 1) {
                            var select = $('<select class="form-control form-control-sm"><option value=""></option></select>')
                                .appendTo($(column.footer()).empty())
                                .on('change', function () {
                                    var val = $.fn.dataTable.util.escapeRegex(
                                        $(this).val()
                                    );
                                    column
                                        .search(val ? '^' + val + '$' : '', true, false)
                                        .draw();
                                });

                            column.data().unique().sort().each(function (d, j) {
                                select.append('<option value="' + d + '">' + d + '</option>')
                            });
                        } else {
                            $(column.footer()).appendTo($(column.footer()).empty())
                        }
                    });
                },
                order: [],
                'columnDefs': [{
                    'orderable': false,
                    'targets': [0]
                },
                    {
                        'orderable': false,
                        'targets': [tableElementCount - 1]
                    }],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.15/i18n/French.json'
                }
            })
        });
    }
    // Select all function7 in index tables
    $('#select-all').click(function (event) {
        if (this.checked) {
            // Iterate each checkbox
            $(':checkbox').each(function () {
                this.checked = true;
            });
        } else {
            $(':checkbox').each(function () {
                this.checked = false;
            });
        }
    });
}
