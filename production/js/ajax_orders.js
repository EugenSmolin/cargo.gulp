// === ЗАКАЗЫ === //
var allCountOrders = 0;
var $singleUserId = location.search.substring(4);

/* === сортировка === */
var $sortCol = "",
    $sortOrder = "",
    $keyword = "";

var $newDate = new Date();
var $todayDay = reverseDate(formatDate($newDate.setDate($newDate.getDate() + 1)));
var $yesterdayDay = reverseDate(formatDate($newDate.setDate($newDate.getDate() - 2)));
var $week = reverseDate(formatDate($newDate.setDate($newDate.getDate() - 6)));
var $month = reverseDate(formatDate($newDate.setDate($newDate.getDate() - 24)));
var $treeMonth = reverseDate(formatDate($newDate.setDate($newDate.getDate() - 60)));


// === Основной запрос на отображение данных === //
var sendModel = {
    "data": {
        "id": $userId,
        "defaultLang": $defaultLang,
        "defaultEmail": $defaultEmail,
        "limit": 50,
        "offset": 0,
        "keyword": $keyword,
        "sort_col": $sortCol,
        "order": $sortOrder,
        "startDate": "",
        "endDate": "",
        "singleUserId": $singleUserId
    }
};

$.ajax({
    type: "POST",
    url: "/backend/adm_get_order_list.php",
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

        var allOrders = data.orders;

        if (allOrders !== undefined && allOrders.length > 0) {

            for (var i = 0; i < allOrders.length; i++) {

                var addNewOrder = '<tr class="order_tr">' +
                    '<td>' + allOrders[i].id + '</td>' +
                    '<td><a name="ord-' + allOrders[i].id + '" class="order_link" href="order.html?id=' + allOrders[i].id + '"></a>' + allOrders[i].city_from + '</td>' +
                    '<td>' + allOrders[i].city_to + '</td>' +
                    '<td>' + allOrders[i].rec_first_name + ' ' + allOrders[i].rec_last_name + '</td>' +
                    '<td>' + allOrders[i].sen_first_name + ' ' + allOrders[i].sen_last_name + '</td>' +
                    '<td>' + allOrders[i].company_internal_number + '</td>' +
                    '<td>' + parseDate(allOrders[i].timestamp) + '</td>' +
                    '<td>' + allOrders[i].company_name + '</td>' +
                    '<td>' + orderStatus(allOrders[i].order_status_id, data.statuses) + '</td>' +
                    '</tr>';

                $('#table_orders tbody').append(addNewOrder);
            }
            allCountOrders = data.count;
        } else {
            let $nothingFound = '<tr><td colspan="9" class="nothing_found">Ничего не найдено</td></tr>';
            $('#table_orders tbody').append($nothingFound);
        }
        preloader(false);
    }
});


// === МАСКА ДЛЯ ВВОДА ДАТЫ === //
$('#first_date, #last_date').attr('placeholder', 'dd.mm.yyyy'); // .mask('00.00.0000')


// === DATE PICKER === //
$(function () {
    let dateFormat = "dd.mm.yy",
        from = $("#first_date")
            .datepicker({
                maxDate: new Date(),
                changeMonth: true,
                changeYear: true
            })
            .on("change", function () {
                to.datepicker("option", "minDate", getDate(this));
            }),
        to = $("#last_date").datepicker({
            maxDate: new Date(),
            changeMonth: true,
            changeYear: true
        })
            .on("change", function () {
                from.datepicker("option", "maxDate", getDate(this));
            });

    function getDate(element) {
        let date;
        try {
            date = $.datepicker.parseDate(dateFormat, element.value);
        } catch (error) {
            date = null;
        }

        return date;
    }
});


function orderStatus(statusId, arrStatuses) {
    var orderStatusId = '';
    for (var i in arrStatuses) {
        if (statusId == "0" && i == "0") { // ожидает обработки check_waiting
            orderStatusId = '<span class="check_waiting"></span><span>' + arrStatuses[i] + '</span>';
        } else if (statusId == "1" && i == "1") { // в работе check_in_working
            orderStatusId = '<span class="check_in_working"></span><span>' + arrStatuses[i] + '</span>';
        } else if (statusId == "2" && i == "2") { // исполнена check_executed
            orderStatusId = '<span class="check_executed"></span><span>' + arrStatuses[i] + '</span>';
        } else if (statusId == "3" && i == "3") { // отклонена rejected
            orderStatusId = '<span class="check_rejected"></span><span>' + arrStatuses[i] + '</span>';
        }
    }
    return orderStatusId;
}

// === Запрос на отображение инфы заказа (заказы/экран) === //
$('body').on('click', 'tr.order_tr', function (event) {
    event.preventDefault();

    var orderLink = $(this).find('.order_link').attr('href');

    if (orderLink !== undefined && orderLink.length > 0) {
        window.location.href = orderLink;
    }
});


// === Поиск по заказам === //
$('#input_search_orders').on('keyup', function (event) {
    if (event.keyCode === 13) {
        $('#btn_search_orders').click();
    }
});

$('#btn_search_orders').on('click', function () {

    offsetCount = 50; // перезаписываем значение
    inProcess = false; // перезаписываем значение

    $keyword = $('#input_search_orders').val();

    if ($keyword !== '') {

        $('#table_orders tbody tr').remove();

        var ordersSearch = $.extend(true, {}, sendModel);
        ordersSearch.data.keyword = $keyword;
        ordersSearch.data.singleUserId = $singleUserId;

        $.ajax({
            type: "POST",
            url: "/backend/adm_get_order_list.php",
            contentType: "application/json",
            headers: $headers,
            data: JSON.stringify(ordersSearch),
            beforeSend: function () {
                preloader(true);
            },
            error: function () {
                preloader(false);
                serverError();
            },
            success: function (data) {

                var allOrders = data.orders;

                if (allOrders !== undefined && allOrders.length > 0) {

                    for (var i = 0; i < allOrders.length; i++) {

                        var addNewOrder = '<tr class="order_tr">' +
                            '<td>' + allOrders[i].id + '</td>' +
                            '<td><a name="ord-' + allOrders[i].id + '" class="order_link" href="order.html?id=' + allOrders[i].id + '"></a>' + allOrders[i].city_from + '</td>' +
                            '<td>' + allOrders[i].city_to + '</td>' +
                            '<td>' + allOrders[i].rec_first_name + ' ' + allOrders[i].rec_last_name + '</td>' +
                            '<td>' + allOrders[i].sen_first_name + ' ' + allOrders[i].sen_last_name + '</td>' +
                            '<td>' + allOrders[i].company_internal_number + '</td>' +
                            '<td>' + parseDate(allOrders[i].timestamp) + '</td>' +
                            '<td>' + allOrders[i].company_name + '</td>' +
                            '<td>' + orderStatus(allOrders[i].order_status_id, data.statuses) + '</td>' +
                            '</tr>';

                        $('#table_orders tbody').append(addNewOrder);
                    }
                } else {
                    var nothingFound = '<tr><td colspan="9" class="nothing_found">Ничего не найдено</td></tr>';
                    $('#table_orders tbody').append(nothingFound);
                }
                preloader(false);
            }
        });
    }
    else {
        $('#table_orders tbody tr').remove();

        $.ajax({
            type: "POST",
            url: "/backend/adm_get_order_list.php",
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

                var allOrders = data.orders;

                if (allOrders !== undefined && allOrders.length > 0) {

                    for (var i = 0; i < allOrders.length; i++) {

                        var addNewOrder = '<tr class="order_tr">' +
                            '<td>' + allOrders[i].id + '</td>' +
                            '<td><a name="ord-' + allOrders[i].id + '" class="order_link" href="order.html?id=' + allOrders[i].id + '"></a>' + allOrders[i].city_from + '</td>' +
                            '<td>' + allOrders[i].city_to + '</td>' +
                            '<td>' + allOrders[i].rec_first_name + ' ' + allOrders[i].rec_last_name + '</td>' +
                            '<td>' + allOrders[i].sen_first_name + ' ' + allOrders[i].sen_last_name + '</td>' +
                            '<td>' + allOrders[i].company_internal_number + '</td>' +
                            '<td>' + parseDate(allOrders[i].timestamp) + '</td>' +
                            '<td>' + allOrders[i].company_name + '</td>' +
                            '<td>' + orderStatus(allOrders[i].order_status_id, data.statuses) + '</td>' +
                            '</tr>';

                        $('#table_orders tbody').append(addNewOrder);
                    }
                    allCountOrders = data.count;
                } else {
                    let $nothingFound = '<tr><td colspan="9" class="nothing_found">Ничего не найдено</td></tr>';
                    $('#table_orders tbody').append($nothingFound);
                }
                preloader(false);
            }
        });
    }
});


// ===== Подгрузка контента в таблицу при скролле ===== //
var offsetCount = 50;
var inProcess = false;
$(window).scroll(function () {

    if ($(window).scrollTop() + $(window).height() >= $(document).height() && !inProcess) {

        var addOrders = $.extend(true, {}, sendModel);
        addOrders.data.offset = offsetCount;
        addOrders.data.keyword = $keyword;
        addOrders.data.sort_col = $sortCol;
        addOrders.data.order = $sortOrder;
        addOrders.data.singleUserId = $singleUserId;

        $.ajax({
            type: "POST",
            url: "/backend/adm_get_order_list.php",
            contentType: "application/json",
            headers: $headers,
            data: JSON.stringify(addOrders),
            beforeSend: function () {
                preloader(true);
                inProcess = true;
            },
            error: function () {
                preloader(false);
                serverError();
            },
            success: function (data) {

                var allOrders = data.orders;

                if (allOrders !== undefined && allOrders.length > 0) {

                    for (var i = 0; i < allOrders.length; i++) {

                        var addNewOrder = '<tr class="order_tr">' +
                            '<td>' + allOrders[i].id + '</td>' +
                            '<td><a name="ord-' + allOrders[i].id + '" class="order_link" href="order.html?id=' + allOrders[i].id + '"></a>' + allOrders[i].city_from + '</td>' +
                            '<td>' + allOrders[i].city_to + '</td>' +
                            '<td>' + allOrders[i].rec_first_name + ' ' + allOrders[i].rec_last_name + '</td>' +
                            '<td>' + allOrders[i].sen_first_name + ' ' + allOrders[i].sen_last_name + '</td>' +
                            '<td>' + allOrders[i].company_internal_number + '</td>' +
                            '<td>' + parseDate(allOrders[i].timestamp) + '</td>' +
                            '<td>' + allOrders[i].company_name + '</td>' +
                            '<td>' + orderStatus(allOrders[i].order_status_id, data.statuses) + '</td>' +
                            '</tr>';

                        $('#table_orders tbody').append(addNewOrder);
                    }
                    inProcess = false;
                    offsetCount += 50;
                } else {
                    let $endOrdersLists = '<tr><td colspan="9" class="nothing_found">Конец списка</td></tr>';
                    $('#table_orders tbody').append($endOrdersLists);
                }
                preloader(false);
            }
        });

    }
});


// ===== Сортировка таблицы ===== //
function sortTable(sort_col, order) {

    offsetCount = 50; // перезаписываем значение
    inProcess = false; // перезаписываем значение

    $('#table_orders tbody tr').remove();

    var sortOrders = $.extend(true, {}, sendModel);
    sortOrders.data.sort_col = sort_col;
    sortOrders.data.order = order;
    sortOrders.data.singleUserId = $singleUserId;

    $.ajax({
        type: "POST",
        url: "/backend/adm_get_order_list.php",
        contentType: "application/json",
        headers: $headers,
        data: JSON.stringify(sortOrders),
        beforeSend: function () {
            preloader(true);
        },
        error: function () {
            preloader(false);
            serverError();
        },
        success: function (data) {

            var allOrders = data.orders;

            if (allOrders !== undefined && allOrders.length > 0) {

                for (var i = 0; i < allOrders.length; i++) {

                    var addNewOrder = '<tr class="order_tr">' +
                        '<td>' + allOrders[i].id + '</td>' +
                        '<td><a name="ord-' + allOrders[i].id + '" class="order_link" href="order.html?id=' + allOrders[i].id + '"></a>' + allOrders[i].city_from + '</td>' +
                        '<td>' + allOrders[i].city_to + '</td>' +
                        '<td>' + allOrders[i].rec_first_name + ' ' + allOrders[i].rec_last_name + '</td>' +
                        '<td>' + allOrders[i].sen_first_name + ' ' + allOrders[i].sen_last_name + '</td>' +
                        '<td>' + allOrders[i].company_internal_number + '</td>' +
                        '<td>' + parseDate(allOrders[i].timestamp) + '</td>' +
                        '<td>' + allOrders[i].company_name + '</td>' +
                        '<td>' + orderStatus(allOrders[i].order_status_id, data.statuses) + '</td>' +
                        '</tr>';

                    $('#table_orders tbody').append(addNewOrder);
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
    $('#table_orders thead th').css('background', 'linear-gradient(to top, #D7D7D7, #FFF)');
    $this.css('background', 'linear-gradient(to top, #B8E5FF, #FFF)');
}

$('body').on('click', 'th.sort_city_from', function () {
    let $this = $(this);
    sortActiveTh($this);

    if (!$this.hasClass('sort_true')) {
        $this.addClass('sort_true');
        $this.find('.arrow_sort, .arrow_up').removeClass('arrow_sort arrow_up').addClass('arrow_down');
        // передаем значение //
        $sortCol = "city_from";
        $sortOrder = "DESC";
        sortTable($sortCol, $sortOrder);
    } else {
        $this.removeClass('sort_true');
        $this.find('.arrow_sort, .arrow_down').removeClass('arrow_sort arrow_down').addClass('arrow_up');
        // передаем значение //
        $sortCol = "city_from";
        $sortOrder = "ASC";
        sortTable($sortCol, $sortOrder);
    }
});

$('body').on('click', 'th.sort_city_to', function () {
    let $this = $(this);
    sortActiveTh($this);

    if (!$this.hasClass('sort_true')) {
        $this.addClass('sort_true');
        $this.find('.arrow_sort, .arrow_up').removeClass('arrow_sort arrow_up').addClass('arrow_down');
        // передаем значение //
        $sortCol = "city_to";
        $sortOrder = "DESC";
        sortTable($sortCol, $sortOrder);
    } else {
        $this.removeClass('sort_true');
        $this.find('.arrow_sort, .arrow_down').removeClass('arrow_sort arrow_down').addClass('arrow_up');
        // передаем значение //
        $sortCol = "city_to";
        $sortOrder = "ASC";
        sortTable($sortCol, $sortOrder);
    }
});

$('body').on('click', 'th.sort_recipient', function () {
    let $this = $(this);
    sortActiveTh($this);

    if (!$this.hasClass('sort_true')) {
        $this.addClass('sort_true');
        $this.find('.arrow_sort, .arrow_up').removeClass('arrow_sort arrow_up').addClass('arrow_down');
        // передаем значение //
        $sortCol = "rec_last_name";
        $sortOrder = "DESC";
        sortTable($sortCol, $sortOrder);
    } else {
        $this.removeClass('sort_true');
        $this.find('.arrow_sort, .arrow_down').removeClass('arrow_sort arrow_down').addClass('arrow_up');
        // передаем значение //
        $sortCol = "rec_last_name";
        $sortOrder = "ASC";
        sortTable($sortCol, $sortOrder);
    }
});

$('body').on('click', 'th.sort_sender', function () {
    let $this = $(this);
    sortActiveTh($this);

    if (!$this.hasClass('sort_true')) {
        $this.addClass('sort_true');
        $this.find('.arrow_sort, .arrow_up').removeClass('arrow_sort arrow_up').addClass('arrow_down');
        // передаем значение //
        $sortCol = "sen_last_name";
        $sortOrder = "DESC";
        sortTable($sortCol, $sortOrder);
    } else {
        $this.removeClass('sort_true');
        $this.find('.arrow_sort, .arrow_down').removeClass('arrow_sort arrow_down').addClass('arrow_up');
        // передаем значение //
        $sortCol = "sen_last_name";
        $sortOrder = "ASC";
        sortTable($sortCol, $sortOrder);
    }
});

$('body').on('click', 'th.sort_number', function () {
    let $this = $(this);
    sortActiveTh($this);

    if (!$this.hasClass('sort_true')) {
        $this.addClass('sort_true');
        $this.find('.arrow_sort, .arrow_up').removeClass('arrow_sort arrow_up').addClass('arrow_down');
        // передаем значение //
        $sortCol = "company_internal_number";
        $sortOrder = "DESC";
        sortTable($sortCol, $sortOrder);
    } else {
        $this.removeClass('sort_true');
        $this.find('.arrow_sort, .arrow_down').removeClass('arrow_sort arrow_down').addClass('arrow_up');
        // передаем значение //
        $sortCol = "company_internal_number";
        $sortOrder = "ASC";
        sortTable($sortCol, $sortOrder);
    }
});

$('body').on('click', 'th.sort_date', function () {
    let $this = $(this);
    sortActiveTh($this);

    if (!$this.hasClass('sort_true')) {
        $this.addClass('sort_true');
        $this.find('.arrow_sort, .arrow_up').removeClass('arrow_sort arrow_up').addClass('arrow_down');
        // передаем значение //
        $sortCol = "timestamp";
        $sortOrder = "DESC";
        sortTable($sortCol, $sortOrder);
    } else {
        $this.removeClass('sort_true');
        $this.find('.arrow_sort, .arrow_down').removeClass('arrow_sort arrow_down').addClass('arrow_up');
        // передаем значение //
        $sortCol = "timestamp";
        $sortOrder = "ASC";
        sortTable($sortCol, $sortOrder);
    }
});

$('body').on('click', 'th.sort_executor', function () {
    let $this = $(this);
    sortActiveTh($this);

    if (!$this.hasClass('sort_true')) {
        $this.addClass('sort_true');
        $this.find('.arrow_sort, .arrow_up').removeClass('arrow_sort arrow_up').addClass('arrow_down');
        // передаем значение //
        $sortCol = "company_name";
        $sortOrder = "DESC";
        sortTable($sortCol, $sortOrder);
    } else {
        $this.removeClass('sort_true');
        $this.find('.arrow_sort, .arrow_down').removeClass('arrow_sort arrow_down').addClass('arrow_up');
        // передаем значение //
        $sortCol = "company_name";
        $sortOrder = "ASC";
        sortTable($sortCol, $sortOrder);
    }
});

$('body').on('click', 'th.sort_status', function () {
    let $this = $(this);
    sortActiveTh($this);

    if (!$this.hasClass('sort_true')) {
        $this.addClass('sort_true');
        $this.find('.arrow_sort, .arrow_up').removeClass('arrow_sort arrow_up').addClass('arrow_down');
        // передаем значение //
        $sortCol = "order_status_id";
        $sortOrder = "DESC";
        sortTable($sortCol, $sortOrder);
    } else {
        $this.removeClass('sort_true');
        $this.find('.arrow_sort, .arrow_down').removeClass('arrow_sort arrow_down').addClass('arrow_up');
        // передаем значение //
        $sortCol = "order_status_id";
        $sortOrder = "ASC";
        sortTable($sortCol, $sortOrder);
    }
});


// ===== Сортировка по дате ===== //
function sortDate(startDate, endDate, manualSort) {

    offsetCount = 50; // перезаписываем значение
    inProcess = false; // перезаписываем значение

    $('#table_orders tbody tr').remove();

    var transferData = $.extend(true, {}, sendModel);
    transferData.data.startDate = startDate;
    transferData.data.endDate = endDate;
    transferData.data.singleUserId = $singleUserId;

    $.ajax({
        type: "POST",
        url: "/backend/adm_get_order_list.php",
        contentType: "application/json",
        headers: $headers,
        data: JSON.stringify(transferData),
        beforeSend: function () {
            preloader(true);
        },
        error: function () {
            preloader(false);
            serverError();
        },
        success: function (data) {

            if (manualSort === false) {
                $('#first_date').val(parseDateDiagram(startDate));
                $('#last_date').val(parseDateDiagram(endDate - 86400));
            }

            var allOrders = data.orders;

            if (allOrders !== undefined && allOrders.length > 0) {

                for (var i = 0; i < allOrders.length; i++) {

                    var addNewOrder = '<tr class="order_tr">' +
                        '<td>' + allOrders[i].id + '</td>' +
                        '<td><a name="ord-' + allOrders[i].id + '" class="order_link" href="order.html?id=' + allOrders[i].id + '"></a>' + allOrders[i].city_from + '</td>' +
                        '<td>' + allOrders[i].city_to + '</td>' +
                        '<td>' + allOrders[i].rec_first_name + ' ' + allOrders[i].rec_last_name + '</td>' +
                        '<td>' + allOrders[i].sen_first_name + ' ' + allOrders[i].sen_last_name + '</td>' +
                        '<td>' + allOrders[i].company_internal_number + '</td>' +
                        '<td>' + parseDate(allOrders[i].timestamp) + '</td>' +
                        '<td>' + allOrders[i].company_name + '</td>' +
                        '<td>' + orderStatus(allOrders[i].order_status_id, data.statuses) + '</td>' +
                        '</tr>';

                    $('#table_orders tbody').append(addNewOrder);
                }
            } else {
                var nothingFound = '<tr><td colspan="9" class="nothing_found">Ничего не найдено</td></tr>';
                $('#table_orders tbody').append(nothingFound);
            }
            preloader(false);
        }
    });
}

$('body').on('click', '#btn-today', function () {
    sortDate($todayDay - 86400, $todayDay, false);
});

$('body').on('click', '#btn-yesterday', function () {
    sortDate($yesterdayDay, $todayDay - 86400, false);
});

$('body').on('click', '#btn-week', function () {
    sortDate($week, $todayDay, false);
});

$('body').on('click', '#btn-one-month', function () {
    sortDate($month, $todayDay, false);
});

$('body').on('click', '#btn-tree-month', function () {
    sortDate($treeMonth, $todayDay, false);
});


// === СОРТИРОВКА СПИСКА ПО ДАТЕ ВРУЧНУЮ === //
$('body').on('change', '#first_date, #last_date', function () {
    let $startDate = reverseDate($('#first_date').val());
    let $endDate = reverseDate($('#last_date').val());
    if (isNaN($startDate) === false && isNaN($endDate) === false) {
        sortDate($startDate, $endDate + 86400, true);
    }
});







