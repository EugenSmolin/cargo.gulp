// === КЛИЕНТ === //
var allCountClients = 0,
    $sortCol = "",
    $sortOrder = "",
    $keyword = "";

var sendModel = {
    data: {
        "id": $userId,
        "defaultLang": $defaultLang,
        "defaultEmail": $defaultEmail,
        "limit": 50,
        "offset": 0,
        "q": $keyword,
        "sort_col": $sortCol,
        "order": $sortOrder
    }
};

$.ajax({
    type: "POST",
    url: "/backend/adm_get_users.php",
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

        var allUsers = data.users;

        if (allUsers !== undefined && allUsers.length > 0) {

            for (var i = 0; i < allUsers.length; i++) {

                var addNewClient = '<tr class="client_tr">' +
                    '<td><a name="client-' + allUsers[i].id + '" class="client_link" href="client.html?id=' + allUsers[i].id + '">' + allUsers[i].legal_name + '</a></td>' +
                    '<td>' + allUsers[i].email + '</td>';

                if (allUsers[i].isApproved == true) {
                    addNewClient += '<td><span class="check_mark"></span>Активный</td>';
                }
                else {
                    addNewClient += '<td><span class="check_warn"></span>Заблокирован</td>';
                }
                addNewClient += '<td>' + parseDate(allUsers[i].lastLogin) + '</td>' +
                    '<td>' + allUsers[i].role + '</td>' +
                    '</tr>';

                $('#table_clients tbody').append(addNewClient);
            }

            allCountClients = data.count;
        }
        preloader(false);
    }
});


// === Запрос на отображение инфы клиента (клиент/экран) === //
$('body').on('click', 'tr.client_tr', function (event) {
    event.preventDefault();

    var clientLink = $(this).find('.client_link').attr('href');

    if (clientLink !== undefined && clientLink.length > 0) {
        window.location.href = clientLink;
    }
});


// === Показать всех клиентов === //
$('#btn-all-clients').on('click', function () {

    offsetCount = allCountClients; // перезаписываем значение
    inProcess = false; // перезаписываем значение

    $('#table_clients tbody tr').remove();

    var allClients = $.extend(true, {}, sendModel);
    allClients.data.limit = allCountClients;

    $.ajax({
        type: "POST",
        url: "/backend/adm_get_users.php",
        contentType: "application/json",
        headers: $headers,
        data: JSON.stringify(allClients),
        beforeSend: function () {
            preloader(true);
        },
        error: function () {
            preloader(false);
            serverError();
        },
        success: function (data) {

            var allUsers = data.users;

            if (allUsers !== undefined && allUsers.length > 0) {

                for (var i = 0; i < allUsers.length; i++) {

                    var addNewClient = '<tr class="client_tr">' +
                        '<td><a name="client-' + allUsers[i].id + '" class="client_link" href="client.html?id=' + allUsers[i].id + '">' + allUsers[i].legal_name + '</a></td>' +
                        '<td>' + allUsers[i].email + '</td>';

                    if (allUsers[i].isApproved == true) {
                        addNewClient += '<td><span class="check_mark"></span>Активный</td>';
                    }
                    else {
                        addNewClient += '<td><span class="check_warn"></span>Заблокирован</td>';
                    }
                    addNewClient += '<td>' + parseDate(allUsers[i].lastLogin) + '</td>' +
                        '<td>' + allUsers[i].role + '</td>' +
                        '</tr>';

                    $('#table_clients tbody').append(addNewClient);
                }
            }
            preloader(false);
        }
    });

});


// === Поиск по клиентам === //
$('#input_search_clients').on('keyup', function (event) {
    if (event.keyCode === 13) {
        $('#btn_search_clients').click();
    }
});

$('#btn_search_clients').on('click', function () {

    offsetCount = 50; // перезаписываем значение
    inProcess = false; // перезаписываем значение

    $keyword = $('#input_search_clients').val();

    if ($keyword !== '') {

        $('#table_clients tbody tr').remove();

        var clientsSearch = $.extend(true, {}, sendModel);
        clientsSearch.data.q = $keyword;

        $.ajax({
            type: "POST",
            url: "/backend/adm_get_users.php",
            contentType: "application/json",
            headers: $headers,
            data: JSON.stringify(clientsSearch),
            beforeSend: function () {
                preloader(true);
            },
            error: function () {
                preloader(false);
                serverError();
            },
            success: function (data) {

                var allUsers = data.users;

                if (allUsers !== undefined && allUsers.length > 0) {

                    for (var i = 0; i < allUsers.length; i++) {

                        var addNewClient = '<tr class="client_tr">' +
                            '<td><a name="client-' + allUsers[i].id + '" class="client_link" href="client.html?id=' + allUsers[i].id + '">' + allUsers[i].legal_name + '</a></td>' +
                            '<td>' + allUsers[i].email + '</td>';

                        if (allUsers[i].isApproved == true) {
                            addNewClient += '<td><span class="check_mark"></span>Активный</td>';
                        }
                        else {
                            addNewClient += '<td><span class="check_warn"></span>Заблокирован</td>';
                        }
                        addNewClient += '<td>' + parseDate(allUsers[i].lastLogin) + '</td>' +
                            '<td>' + allUsers[i].role + '</td>' +
                            '</tr>';

                        $('#table_clients tbody').prepend(addNewClient);
                    }
                } else {
                    var nothingFound = '<tr><td colspan="5" class="nothing_found">Ничего не найдено</td></tr>';
                    $('#table_clients tbody').append(nothingFound);
                }
                preloader(false);
            }
        });
    } else {
        $('#table_clients tbody tr').remove();

        $.ajax({
            type: "POST",
            url: "/backend/adm_get_users.php",
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

                var allUsers = data.users;

                if (allUsers !== undefined && allUsers.length > 0) {

                    for (var i = 0; i < allUsers.length; i++) {

                        var addNewClient = '<tr class="client_tr">' +
                            '<td><a name="client-' + allUsers[i].id + '" class="client_link" href="client.html?id=' + allUsers[i].id + '">' + allUsers[i].legal_name + '</a></td>' +
                            '<td>' + allUsers[i].email + '</td>';

                        if (allUsers[i].isApproved == true) {
                            addNewClient += '<td><span class="check_mark"></span>Активный</td>';
                        }
                        else {
                            addNewClient += '<td><span class="check_warn"></span>Заблокирован</td>';
                        }
                        addNewClient += '<td>' + parseDate(allUsers[i].lastLogin) + '</td>' +
                            '<td>' + allUsers[i].role + '</td>' +
                            '</tr>';

                        $('#table_clients tbody').append(addNewClient);
                    }

                    allCountClients = data.count;
                }
                preloader(false);
            }
        });
    }
});


// === Подгрузка контента в таблицу при скролле === //
var offsetCount = 50;
var inProcess = false;
$(window).scroll(function () {

    if ($(window).scrollTop() + $(window).height() >= $(document).height() && !inProcess) {

        var addClients = $.extend(true, {}, sendModel);
        addClients.data.offset = offsetCount;
        addClients.data.keyword = $keyword;
        addClients.data.sort_col = $sortCol;
        addClients.data.order = $sortOrder;

        $.ajax({
            type: "POST",
            url: "/backend/adm_get_users.php",
            contentType: "application/json",
            headers: $headers,
            data: JSON.stringify(addClients),
            beforeSend: function () {
                preloader(true);
                inProcess = true;
            },
            error: function () {
                preloader(false);
                serverError();
            },
            success: function (data) {

                var allUsers = data.users;

                if (allUsers !== undefined && allUsers.length > 0) {

                    for (var i = 0; i < allUsers.length; i++) {

                        var addNewClient = '<tr class="client_tr">' +
                            '<td><a name="client-' + allUsers[i].id + '" class="client_link" href="client.html?id=' + allUsers[i].id + '">' + allUsers[i].legal_name + '</a></td>' +
                            '<td>' + allUsers[i].email + '</td>';

                        if (allUsers[i].isApproved == true) {
                            addNewClient += '<td><span class="check_mark"></span>Активный</td>';
                        }
                        else {
                            addNewClient += '<td><span class="check_warn"></span>Заблокирован</td>';
                        }
                        addNewClient += '<td>' + parseDate(allUsers[i].lastLogin) + '</td>' +
                            '<td>' + allUsers[i].role + '</td>' +
                            '</tr>';

                        $('#table_clients tbody').append(addNewClient);
                    }
                    inProcess = false;
                    offsetCount += 50;

                } else {
                    let $endClientsLists = '<tr><td colspan="5" class="nothing_found">Конец списка</td></tr>';
                    $('#table_clients tbody').append($endClientsLists);
                }
                preloader(false);
            }
        });
    }
});


// === Сортировка таблицы === //
function sortTable(sort_col, order) {

    offsetCount = 50; // перезаписываем значение
    inProcess = false; // перезаписываем значение

    $('#table_clients tbody tr').remove();

    var sortClients = $.extend(true, {}, sendModel);
    sortClients.data.sort_col = sort_col;
    sortClients.data.order = order;

    $.ajax({
        type: "POST",
        url: "/backend/adm_get_users.php",
        contentType: "application/json",
        headers: $headers,
        data: JSON.stringify(sortClients),
        beforeSend: function () {
            preloader(true);
        },
        error: function () {
            preloader(false);
            serverError();
        },
        success: function (data) {

            var allUsers = data.users;

            if (allUsers !== undefined && allUsers.length > 0) {

                for (var i = 0; i < allUsers.length; i++) {

                    var addNewClient = '<tr class="client_tr">' +
                        '<td><a name="client-' + allUsers[i].id + '" class="client_link" href="client.html?id=' + allUsers[i].id + '">' + allUsers[i].legal_name + '</a></td>' +
                        '<td>' + allUsers[i].email + '</td>';

                    if (allUsers[i].isApproved == true) {
                        addNewClient += '<td><span class="check_mark"></span>Активный</td>';
                    }
                    else {
                        addNewClient += '<td><span class="check_warn"></span>Заблокирован</td>';
                    }
                    addNewClient += '<td>' + parseDate(allUsers[i].lastLogin) + '</td>' +
                        '<td>' + allUsers[i].role + '</td>' +
                        '</tr>';

                    $('#table_clients tbody').append(addNewClient);
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
    $('#table_clients thead th').css('background', 'linear-gradient(to top, #D7D7D7, #FFF)');
    $this.css('background', 'linear-gradient(to top, #B8E5FF, #FFF)');
}

$('body').on('click', 'th.sort_last_name', function () {
    let $this = $(this);
    sortActiveTh($this);

    if (!$this.hasClass('sort_true')) {
        $this.addClass('sort_true');
        $this.find('.arrow_sort, .arrow_up').removeClass('arrow_sort arrow_up').addClass('arrow_down');
        // передаем значение //
        $sortCol = "last_name";
        $sortOrder = "DESC";
        sortTable($sortCol, $sortOrder);
    } else {
        $this.removeClass('sort_true');
        $this.find('.arrow_sort, .arrow_down').removeClass('arrow_sort arrow_down').addClass('arrow_up');
        // передаем значение //
        $sortCol = "last_name";
        $sortOrder = "ASC";
        sortTable($sortCol, $sortOrder);
    }
});

$('body').on('click', 'th.sort_email', function () {
    let $this = $(this);
    sortActiveTh($this);

    if (!$this.hasClass('sort_true')) {
        $this.addClass('sort_true');
        $this.find('.arrow_sort, .arrow_up').removeClass('arrow_sort arrow_up').addClass('arrow_down');
        // передаем значения //
        $sortCol = "email";
        $sortOrder = "DESC";
        sortTable($sortCol, $sortOrder);
    } else {
        $this.removeClass('sort_true');
        $this.find('.arrow_sort, .arrow_down').removeClass('arrow_sort arrow_down').addClass('arrow_up');
        // передаем значения //
        $sortCol = "email";
        $sortOrder = "ASC";
        sortTable($sortCol, $sortOrder);
    }
});

$('body').on('click', 'th.sort_approved', function () {
    let $this = $(this);
    sortActiveTh($this);

    if (!$this.hasClass('sort_true')) {
        $this.addClass('sort_true');
        $this.find('.arrow_sort, .arrow_up').removeClass('arrow_sort arrow_up').addClass('arrow_down');
        // передаем значения //
        $sortCol = "approved";
        $sortOrder = "DESC";
        sortTable($sortCol, $sortOrder);
    } else {
        $this.removeClass('sort_true');
        $this.find('.arrow_sort, .arrow_down').removeClass('arrow_sort arrow_down').addClass('arrow_up');
        // передаем значения //
        $sortCol = "approved";
        $sortOrder = "ASC";
        sortTable($sortCol, $sortOrder);
    }
});

$('body').on('click', 'th.sort_last_login', function () {
    let $this = $(this);
    sortActiveTh($this);

    if (!$this.hasClass('sort_true')) {
        $this.addClass('sort_true');
        $this.find('.arrow_sort, .arrow_up').removeClass('arrow_sort arrow_up').addClass('arrow_down');
        // передаем значения //
        $sortCol = "last_login";
        $sortOrder = "DESC";
        sortTable($sortCol, $sortOrder);
    } else {
        $this.removeClass('sort_true');
        $this.find('.arrow_sort, .arrow_down').removeClass('arrow_sort arrow_down').addClass('arrow_up');
        // передаем значения //
        $sortCol = "last_login";
        $sortOrder = "ASC";
        sortTable($sortCol, $sortOrder);
    }
});

$('body').on('click', 'th.sort_admin', function () {
    let $this = $(this);
    sortActiveTh($this);

    if (!$this.hasClass('sort_true')) {
        $this.addClass('sort_true');
        $this.find('.arrow_sort, .arrow_up').removeClass('arrow_sort arrow_up').addClass('arrow_down');
        // передаем значения //
        $sortCol = "admin";
        $sortOrder = "DESC";
        sortTable($sortCol, $sortOrder);
    } else {
        $this.removeClass('sort_true');
        $this.find('.arrow_sort, .arrow_down').removeClass('arrow_sort arrow_down').addClass('arrow_up');
        // передаем значения //
        $sortCol = "admin";
        $sortOrder = "ASC";
        sortTable($sortCol, $sortOrder);
    }
});