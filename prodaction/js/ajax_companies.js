// === ТРАНСПОРТ СПИСОК === //
var allCountCompanies = 0,
    $activeOnly = "",
    $sortCol = "",
    $sortOrder = "",
    $keyword = "";


var sendModel = {
    "data": {
        "id": $userId,
        "defaultLang": $defaultLang,
        "defaultEmail": $defaultEmail,
        "limit": 30,
        "offset": 0,
        "keyword": $keyword,
        "sortCol": $sortCol,
        "order": $sortOrder,
        "activeOnly": $activeOnly
    }
};

$.ajax({
    type: "POST",
    url: "/backend/adm_get_company_list.php",
    contentType: "application/json",
    headers: $headers,
    data: JSON.stringify(sendModel),
    beforeSend: function () {
        preloader(true);
    },
    error: function () {
        preloader(false);
        serverError();
    },
    success: function (data) {

        console.log(data);

        var arrCompanies = data.companies;

        if (arrCompanies !== undefined && arrCompanies.length > 0) {

            for (var i = 0; i < arrCompanies.length; i++) {

                var addNewCompany = '<tr class="transport_tr">';

                if (arrCompanies[i].canOrderNow == true) {
                    addNewCompany += '<td><span class="check_mark"></span>Активный</td>';
                } else {
                    addNewCompany += '<td><span class="check_warn"></span>Заблокирован</td>';
                }

                addNewCompany += '<td><a name="trans-' + arrCompanies[i].transportNumber + '" class="transport_link" href="company.html?id=' + arrCompanies[i].transportNumber + '">' + arrCompanies[i].transportName + '</a></td>' +
                    '<td>' + arrCompanies[i].transportSite + '</td>' +
                    '<td><img class="img_table" src="' + arrCompanies[i].transportLogo + '"></td>' +
                    '</tr>';

                $('#table_transport tbody').append(addNewCompany);
            }
        }

        allCountCompanies = data.count;
        preloader(false);
    }
});


// === Запрос на отображение инфы компании (транспорт/экран компании) === //
$('body').on('click', 'tr.transport_tr', function (event) {
    event.preventDefault();

    var transportLink = $(this).find('.transport_link').attr('href');

    if (transportLink !== undefined && transportLink.length > 0) {
        window.location.href = transportLink;
    }
});


// ===== Показать все транспортные компании === //
$('#btn-all-trans-company').on('click', function () {

    offsetCount = allCountCompanies; // перезаписываем значение
    inProcess = false; // перезаписываем значение

    $('#table_transport tbody tr').remove();

    var allCompanies = $.extend(true, {}, sendModel);
    allCompanies.data.limit = allCountCompanies;
    allCompanies.data.activeOnly = $activeOnly;

    $.ajax({
        type: "POST",
        url: "/backend/adm_get_company_list.php",
        contentType: "application/json",
        headers: $headers,
        data: JSON.stringify(allCompanies),
        beforeSend: function () {
            preloader(true);
        },
        error: function () {
            preloader(false);
            serverError();
        },
        success: function (data) {

            var arrCompanies = data.companies;

            if (arrCompanies !== undefined && arrCompanies.length > 0) {

                for (var i = 0; i < arrCompanies.length; i++) {

                    var addNewCompany = '<tr class="transport_tr">';

                    if (arrCompanies[i].canOrderNow == true) {
                        addNewCompany += '<td><span class="check_mark"></span>Активный</td>';
                    } else {
                        addNewCompany += '<td><span class="check_warn"></span>Заблокирован</td>';
                    }

                    addNewCompany += '<td><a name="trans-' + arrCompanies[i].transportNumber + '" class="transport_link" href="company.html?id=' + arrCompanies[i].transportNumber + '">' + arrCompanies[i].transportName + '</a></td>' +
                        '<td>' + arrCompanies[i].transportSite + '</td>' +
                        '<td><img class="img_table" src="' + arrCompanies[i].transportLogo + '"></td>' +
                        '</tr>';

                    $('#table_transport tbody').append(addNewCompany);
                }
            }
            preloader(false);
        }
    });

});

// === Фильтр по активности компании === //
$('body').on('change', '#check_trans_filter', function () {
    if ($(this).prop('checked') === true) {
        transportActive(true);
    } else {
        transportActive(false);
    }
});

function transportActive(bool) {

    offsetCount = 30; // перезаписываем значение
    inProcess = false; // перезаписываем значение

    $('#table_transport tbody tr').remove();

    $activeOnly = bool;

    var transFilter = $.extend(true, {}, sendModel);
    transFilter.data.keyword = $keyword;
    transFilter.data.activeOnly = $activeOnly;

    $.ajax({
        type: "POST",
        url: "/backend/adm_get_company_list.php",
        contentType: "application/json",
        headers: $headers,
        data: JSON.stringify(transFilter),
        beforeSend: function () {
            preloader(true);
        },
        error: function () {
            preloader(false);
            serverError();
        },
        success: function (data) {

            var arrCompanies = data.companies;

            if (arrCompanies !== undefined && arrCompanies.length > 0) {

                for (var i = 0; i < arrCompanies.length; i++) {

                    var addNewCompany = '<tr class="transport_tr">';

                    if (arrCompanies[i].canOrderNow == true) {
                        addNewCompany += '<td><span class="check_mark"></span>Активный</td>';
                    } else {
                        addNewCompany += '<td><span class="check_warn"></span>Заблокирован</td>';
                    }

                    addNewCompany += '<td><a name="trans-' + arrCompanies[i].transportNumber + '" class="transport_link" href="company.html?id=' + arrCompanies[i].transportNumber + '">' + arrCompanies[i].transportName + '</a></td>' +
                        '<td>' + arrCompanies[i].transportSite + '</td>' +
                        '<td><img class="img_table" src="' + arrCompanies[i].transportLogo + '"></td>' +
                        '</tr>';

                    $('#table_transport tbody').append(addNewCompany);
                }
            }
            preloader(false);
        }
    });
}


// === Поиск по компаниям === //
$('#input_search_trans').on('keyup', function (event) {
    if (event.keyCode === 13) {
        $('#btn_search_trans').click();
    }
});

$('#btn_search_trans').on('click', function () {

    offsetCount = 30; // перезаписываем значение
    inProcess = false; // перезаписываем значение

    $keyword = $('#input_search_trans').val();

    if ($keyword !== '') {

        $('#table_transport tbody tr').remove();

        var transSearch = $.extend(true, {}, sendModel);
        transSearch.data.keyword = $keyword;
        transSearch.data.activeOnly = $activeOnly;

        $.ajax({
            type: "POST",
            url: "/backend/adm_get_company_list.php",
            contentType: "application/json",
            headers: $headers,
            data: JSON.stringify(transSearch),
            beforeSend: function () {
                preloader(true);
            },
            error: function () {
                preloader(false);
                serverError();
            },
            success: function (data) {

                var arrCompanies = data.companies;

                if (arrCompanies !== undefined && arrCompanies.length > 0) {
                    for (var i = 0; i < arrCompanies.length; i++) {

                        var addNewCompany = '<tr class="transport_tr">';

                        if (arrCompanies[i].canOrderNow == true) {
                            addNewCompany += '<td><span class="check_mark"></span>Активный</td>';
                        } else {
                            addNewCompany += '<td><span class="check_warn"></span>Заблокирован</td>';
                        }

                        addNewCompany += '<td><a name="trans-' + arrCompanies[i].transportNumber + '" class="transport_link" href="company.html?id=' + arrCompanies[i].transportNumber + '">' + arrCompanies[i].transportName + '</a></td>' +
                            '<td>' + arrCompanies[i].transportSite + '</td>' +
                            '<td><img class="img_table" src="' + arrCompanies[i].transportLogo + '"></td>' +
                            '</tr>';

                        $('#table_transport tbody').append(addNewCompany);
                    }
                } else {
                    var nothingFound = '<tr><td colspan="4" class="nothing_found">Ничего не найдено</td></tr>';
                    $('#table_transport tbody').append(nothingFound);
                }
                preloader(false);
            }
        });
    } else {
        $('#table_transport tbody tr').remove();

        let $transSearch = $.extend(true, {}, sendModel);
        $transSearch.data.activeOnly = $activeOnly;

        $.ajax({
            type: "POST",
            url: "/backend/adm_get_company_list.php",
            contentType: "application/json",
            headers: $headers,
            data: JSON.stringify($transSearch),
            beforeSend: function () {
                preloader(true);
            },
            error: function () {
                preloader(false);
                serverError();
            },
            success: function (data) {

                var arrCompanies = data.companies;

                if (arrCompanies !== undefined && arrCompanies.length > 0) {

                    for (var i = 0; i < arrCompanies.length; i++) {

                        var addNewCompany = '<tr class="transport_tr">';

                        if (arrCompanies[i].canOrderNow == true) {
                            addNewCompany += '<td><span class="check_mark"></span>Активный</td>';
                        } else {
                            addNewCompany += '<td><span class="check_warn"></span>Заблокирован</td>';
                        }

                        addNewCompany += '<td><a name="trans-' + arrCompanies[i].transportNumber + '" class="transport_link" href="company.html?id=' + arrCompanies[i].transportNumber + '">' + arrCompanies[i].transportName + '</a></td>' +
                            '<td>' + arrCompanies[i].transportSite + '</td>' +
                            '<td><img class="img_table" src="' + arrCompanies[i].transportLogo + '"></td>' +
                            '</tr>';

                        $('#table_transport tbody').append(addNewCompany);
                    }
                }

                allCountCompanies = data.count;
                preloader(false);
            }
        });
    }

});


// ===== Подгрузка контента в таблицу при скролле ===== //
var offsetCount = 30;
var inProcess = false;
$(window).scroll(function () {

    if ($(window).scrollTop() + $(window).height() >= $(document).height() && !inProcess) {

        var addCompanies = $.extend(true, {}, sendModel);
        addCompanies.data.offset = offsetCount;
        addCompanies.data.keyword = $keyword;
        addCompanies.data.sortCol = $sortCol;
        addCompanies.data.order = $sortOrder;
        addCompanies.data.activeOnly = $activeOnly;

        $.ajax({
            type: "POST",
            url: "/backend/adm_get_company_list.php",
            contentType: "application/json",
            headers: $headers,
            data: JSON.stringify(addCompanies),
            beforeSend: function () {
                preloader(true);
                inProcess = true;
            },
            error: function () {
                preloader(false);
                serverError();
            },
            success: function (data) {

                var arrCompanies = data.companies;

                if (arrCompanies !== undefined && arrCompanies.length > 0) {

                    for (var i = 0; i < arrCompanies.length; i++) {

                        var addNewCompany = '<tr class="transport_tr">';

                        if (arrCompanies[i].canOrderNow == true) {
                            addNewCompany += '<td><span class="check_mark"></span>Активный</td>';
                        } else {
                            addNewCompany += '<td><span class="check_warn"></span>Заблокирован</td>';
                        }

                        addNewCompany += '<td><a name="trans-' + arrCompanies[i].transportNumber + '" class="transport_link" href="company.html?id=' + arrCompanies[i].transportNumber + '">' + arrCompanies[i].transportName + '</a></td>' +
                            '<td>' + arrCompanies[i].transportSite + '</td>' +
                            '<td><img class="img_table" src="' + arrCompanies[i].transportLogo + '"></td>' +
                            '</tr>';

                        $('#table_transport tbody').append(addNewCompany);
                    }

                    inProcess = false;
                    offsetCount += 30;
                } else {
                    let $endCompanyLists = '<tr><td colspan="4" class="nothing_found">Конец списка</td></tr>';
                    $('#table_transport tbody').append($endCompanyLists);
                }
                preloader(false);
            }
        });

    }
});


// ===== Сортировка таблицы ===== //
function sortTable(sort_col, order) {

    offsetCount = 30; // перезаписываем значение
    inProcess = false; // перезаписываем значение

    $('#table_transport tbody tr').remove();

    var sortCompanies = $.extend(true, {}, sendModel);
    sortCompanies.data.sortCol = sort_col;
    sortCompanies.data.order = order;
    sortCompanies.data.activeOnly = $activeOnly;

    $.ajax({
        type: "POST",
        url: "/backend/adm_get_company_list.php",
        contentType: "application/json",
        headers: $headers,
        data: JSON.stringify(sortCompanies),
        beforeSend: function () {
            preloader(true);
        },
        error: function () {
            preloader(false);
            serverError();
        },
        success: function (data) {

            var arrCompanies = data.companies;

            if (arrCompanies !== undefined && arrCompanies.length > 0) {

                for (var i = 0; i < arrCompanies.length; i++) {

                    var addNewCompany = '<tr class="transport_tr">';

                    if (arrCompanies[i].canOrderNow == true) {
                        addNewCompany += '<td><span class="check_mark"></span>Активный</td>';
                    } else {
                        addNewCompany += '<td><span class="check_warn"></span>Заблокирован</td>';
                    }

                    addNewCompany += '<td><a name="trans-' + arrCompanies[i].transportNumber + '" class="transport_link" href="transports.html?id=' + arrCompanies[i].transportNumber + '">' + arrCompanies[i].transportName + '</a></td>' +
                        '<td>' + arrCompanies[i].transportSite + '</td>' +
                        '<td><img class="img_table" src="' + arrCompanies[i].transportLogo + '"></td>' +
                        '</tr>';

                    $('#table_transport tbody').append(addNewCompany);
                }
            }
            preloader(false);
        }
    });
}

function sortActiveTh($this) {
    let $class = $('.arrow_down, .arrow_up');
    if (!$class.hasClass('arrow_sort')) {
        $class.removeClass('arrow_down arrow_up').addClass('arrow_sort');
    }
    $('#table_transport thead th').css('background', 'linear-gradient(to top, #D7D7D7, #FFF)');
    $this.css('background', 'linear-gradient(to top, #B8E5FF, #FFF)');
}

$('body').on('click', 'th.sort_active', function () {
    let $this = $(this);
    sortActiveTh($this);

    if (!$this.hasClass('sort_true')) {
        $this.addClass('sort_true');
        $this.find('.arrow_sort, .arrow_up').removeClass('arrow_sort arrow_up').addClass('arrow_down');
        // передаем значение //
        $sortCol = "canOrderNow";
        $sortOrder = "DESC";
        sortTable($sortCol, $sortOrder);
    } else {
        $this.removeClass('sort_true');
        $this.find('.arrow_sort, .arrow_down').removeClass('arrow_sort arrow_down').addClass('arrow_up');
        // передаем значение //
        $sortCol = "canOrderNow";
        $sortOrder = "ASC";
        sortTable($sortCol, $sortOrder);
    }
});

$('body').on('click', 'th.sort_company', function () {
    let $this = $(this);
    sortActiveTh($this);

    if (!$this.hasClass('sort_true')) {
        $this.addClass('sort_true');
        $this.find('.arrow_sort, .arrow_up').removeClass('arrow_sort arrow_up').addClass('arrow_down');
        // передаем значение //
        $sortCol = "transportName";
        $sortOrder = "DESC";
        sortTable($sortCol, $sortOrder);
    } else {
        $this.removeClass('sort_true');
        $this.find('.arrow_sort, .arrow_down').removeClass('arrow_sort arrow_down').addClass('arrow_up');
        // передаем значение //
        $sortCol = "transportName";
        $sortOrder = "ASC";
        sortTable($sortCol, $sortOrder);
    }
});