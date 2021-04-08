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
                    /*Création de la zone de filtre*/
                    let card =  $('<div class="card col-5"> <a class="cross-close"><i class="fas fa-times"/i></a><div class="card-body"></div></div>')
                    let selectColumn = $('<select class="form-control form-control-sm" id="mySelect"><option value="" disabled selected>Choix de la colonne à filtrer</option></select>')
                    let selectOption = $('<select class="form-control form-control-sm mt-1" id="mySelectOptions"></select>')
                    let inputSearch = $('<input class="form-control form-control-sm mt-1 w-100 ml-0" id="inputSearch"/>')
                    let buttonS = $('<a class="btn btn-primary mr-1" id="search"><i class="fa fa-search" aria-hidden="true"/> Rechercher</a>')
                    let buttonPlus = $('<a class="btn btn-primary mr-1" id="plus"><i class="fa fa-plus" aria-hidden="true"/></a>')
                    let buttonFilter = $('<a class="btn btn-primary btn-sm" id="search"><i class="fas fa-filter"/> Filtrer</a>')
                    let columnSelected = $('#mySelect').val()
                    let divFilter =  $('#DataTables_Table_0_filter')
                    $('#DataTables_Table_0_filter label').hide()
                    divFilter.append(card)
                    divFilter.addClass("p-2")
                    buttonFilter.css({"position": "absolute","top": "0px", "right":"1em"})
                    $('.cross-close').css({"position": "relative","top": "0px", "right":"-12px", "color":"black", "cursor":"pointer"})
                    $('.card-body').append(selectColumn)
                    $('.card-body').append(selectOption)
                    $('.card-body').append(inputSearch)
                    divFilter.append(buttonFilter)
                    divFilter.append(buttonS)
                    divFilter.append(buttonPlus)
                    choiceFilter.map(obj => {
                        let $option = $("<option/>", {
                            value: obj.key,
                            text: obj.value
                        });
                        return $('#mySelectOptions').append($option);
                    });
                    selectOption.hide()
                    inputSearch.hide()
                    buttonS.hide()
                    selectColumn.hide()
                    card.hide()
                    buttonPlus.hide()

                    /*Fin création*/
                    buttonFilter.on('click', function (event) {
                        $(this).toggleClass('toggled');
                        if ($(this).hasClass('toggled')) {
                            divFilter.css("background-color", "#c3c3c347")
                            selectColumn.show();
                            card.show();
                            buttonS.show();
                            buttonPlus.show()
                        } else {
                            divFilter.css("background-color", "#fff")
                            selectColumn.hide();
                            card.hide();
                            buttonS.hide();
                            buttonPlus.hide()
                        }
                    })
            /*        buttonPlus.click(function () {
                        let card =  $('<div class="card col-5"> <a class="cross-close card"><i class="fas fa-times"/i></a><div class="card-body"></div></div>')
                        let selectColumn = $('<select class="form-control form-control-sm filter-col-'+nbFiltre+'" id="mySelect"><option value="" disabled selected>Choix de la colonne à filtrer</option></select>')
                        let selectOption = $('<select class="form-control form-control-sm mt-1 filter-option-'+nbFiltre+'" id="mySelectOptions"></select>')
                        let inputSearch = $('<input class="form-control form-control-sm mt-1 w-100 ml-0 filter-search   -'+nbFiltre+'" id="inputSearch"/>')
                        $('.card-body').append(selectColumn)
                        $('.card-body').append(selectOption)
                        $('.card-body').append(inputSearch)
                        nbFiltre++
                    })*/
                    $('.cross-close').click(function () {
                        card.remove()
                    })
                    buttonS.click(function () {
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
                            selectOption.show()
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
