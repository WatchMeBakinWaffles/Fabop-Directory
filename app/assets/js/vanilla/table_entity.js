if (path != '/dashboard') {
    if ($("table").length) {
        $(document).ready(function () {
            const tableElementCount = $('table thead tr').get()[0].childElementCount;
            let nbFiltre = 0;
            let choiceFilter = [
                {key: "equals", value: "Est égal à:"},
                {key: "startBy", value: "Commence par:"},
                {key: "endBy", value: "Fini par:"},
                {key: "differentFrom", value: "Différent de:"},
                {key: "contains", value: "Contient:"}]
            $('.table').DataTable({
                initComplete: function () {
                    /*Création de la zone de filtre*/
                    let tableJquery =  $('.table').DataTable()
                    let card =  $('<div class="card col-5"><div class="card-body card-'+nbFiltre+'"></div></div>')
                    let selectColumn = $('<select class="form-control form-control-sm filter-column-'+nbFiltre+'" id="mySelect"><option value="" disabled selected>Choix de la colonne à filtrer</option></select>')
                    let selectOption = $('<select class="form-control form-control-sm mt-1 filter-option-'+nbFiltre+'"  id="mySelectOptions"></select>')
                    let inputSearch = $('<input class="form-control form-control-sm mt-1 w-100 ml-0 filter-search-'+nbFiltre+'" id="inputSearch"/>')
                    let buttonS = $('<a class="btn btn-primary mr-1" id="search"><i class="fa fa-search" aria-hidden="true"/> Rechercher</a>')
                    let buttonPlus = $('<a class="btn btn-primary mr-1" id="plus"><i class="fa fa-plus" aria-hidden="true"/></a>')
                    let buttonFilter = $('<a class="btn btn-primary btn-sm" id="search"><i class="fas fa-filter"/> Filtrer</a>')
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
                        return $('.filter-option-0').append($option);
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

                    buttonPlus.click(function () {
                        nbFiltre++
                        let card1 =  $('<div class="card col-5"><div class="card-body card-'+nbFiltre+'"></div></div>')
                        let selectColumn1 = $('<select class="form-control form-control-sm filter-column-'+nbFiltre+'" id="mySelect"></select>')
                        let selectOption1 = $('<select class="form-control form-control-sm mt-1 filter-option-'+nbFiltre+'"  id="mySelectOptions"></select>')
                        let inputSearch1 = $('<input class="form-control form-control-sm mt-1 w-100 ml-0 filter-search-'+nbFiltre+'" id="inputSearch"/>')
                        divFilter.append(card1)
                        let actualCard = $('.card-'+nbFiltre+'')
                        actualCard.append(selectColumn1)
                        actualCard.append(selectOption1)
                        actualCard.append(inputSearch1)
                        selectOption1.hide()
                        inputSearch1.hide()
                        $(".filter-column-0 option").each(function()
                        {
                            $('.filter-column-'+nbFiltre+'')
                                .append($(this).clone());
                        });
                        $('.filter-column-'+nbFiltre+'').on('change', function () {

                            $('.filter-option-'+nbFiltre+'').show()
                            $('.filter-search-'+nbFiltre+'').show()
                        });
                        choiceFilter.map(obj => {
                            let $option = $("<option/>", {
                                value: obj.key,
                                text: obj.value
                            });
                            return $('.filter-option-'+nbFiltre+'').append($option);
                        });

                    })
                    $('.cross-close').click(function () {
                        card.remove()
                    })

                    buttonS.click(function () {
                        tableJquery.search('').columns().search('').draw();
                        for (let i = 0; i <= nbFiltre; i++) {
                            let val = $.fn.dataTable.util.escapeRegex(
                                $('.filter-search-'+i+'').val()
                            );
                            let searchOptions = $('.filter-option-'+i+' :selected').val()
                            let colSel = $('.filter-column-'+i+' :selected').val()
                            switch (searchOptions) {
                                case 'equals':
                                    tableJquery.column(colSel).search(val ? '^' + val + '$' : '', true, false)
                                        .draw();
                                    break;
                                case 'startBy':
                                    tableJquery.column(colSel).search(val ? '^' + val + '.*$' : '', true, false)
                                        .draw();
                                    break;
                                case 'endBy':
                                    tableJquery.column(colSel).search(val ? '^.*' + val + '$' : '', true, false)
                                        .draw();
                                    break;
                                case 'differentFrom':
                                    tableJquery.column(colSel).search(val ? '^((?!' + val + ').)*$' : '', true, false)
                                        .draw();
                                    break;
                                case 'contains':
                                    tableJquery.column(colSel).search(val ? '^.*' + val + '.*$' : '', true, false)
                                        .draw();
                                    break;
                            }
                        }
                    });

                    $('.filter-column-0').on('change', function () {

                        $('.filter-option-0').show()
                        $('.filter-search-0').show()
                    });

                    this.api().columns().every(function (index) {
                        let column = this;
                        if (column.header().innerText)
                            $('.filter-column-0')
                                .append($("<option></option>")
                                    .attr("value", index)
                                    .text(column.header().innerText));
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
