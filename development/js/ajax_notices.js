// === УВЕДОМЛЕНИЯ === //
var $notices = {
    "data": {
        "id": $userId,
        "defaultLang": $defaultLang,
        "defaultEmail": $defaultEmail,
        "limit": 50,
        "offset": 0
    }
};

$.ajax({
    type: "POST",
    url: "/backend/adm_get_error_list.php",
    contentType: "application/json",
    headers: $headers,
    data: JSON.stringify($notices),
    beforeSend: function () {
        preloader(true);
    },
    error: function () {
        preloader(false);
        serverError();
    },
    success: function (data) {

        console.log(data);

        let $messages = data.messages;

        // === Проверка на null!
        if ($messages !== undefined && $messages !== null && $messages.length > 0) {
            for (let i = 0; i < $messages.length; i++) {
                let $addNewNotice = '<tr class="notices_tr">';

                if ($messages[i].is_read === true) {
                    $addNewNotice += '<td><i class="far fa-envelope-open fa-lg icon-message"></i></td>';
                } else {
                    $addNewNotice += '<td><i class="far fa-envelope fa-lg icon-message"></i></td>';
                }

                $addNewNotice +=
                    '   <td><div class="company_name" id="' + $messages[i].id + '">' + $messages[i].company_name + '</div></td>' +
                    '   <td><div class="message">' + $messages[i].message + '</div></td>' +
                    '   <td>' + parseDate($messages[i].date) + '</td>' +
                    '</tr>';

                $('#table_notices tbody').append($addNewNotice);
            }
        } else {
            let $nothingFound = '<tr><td colspan="4" class="nothing_found">Ничего не найдено.</td></tr>';
            $('#table_notices tbody').append($nothingFound);
        }
        preloader(false);
    }
});


// === ЗАПРОС НА ОТОБРАЖЕНИЕ ОШИБКИ === //
$('body').on('click', 'tr.notices_tr', function () {
    $('#modal_window_error').fadeIn(300);

    let $messageId = parseFloat($(this).find('.company_name').attr('id'));

    let $error = {
        "data": {
            "id": $userId,
            "defaultLang": $defaultLang,
            "defaultEmail": $defaultEmail,
            "msgId": $messageId
        }
    };

    $.ajax({
        type: "POST",
        url: "/backend/adm_get_error.php",
        contentType: "application/json",
        headers: $headers,
        data: JSON.stringify($error),
        beforeSend: function () {
            preloader(true);
        },
        error: function () {
            preloader(false);
            serverError();
        },
        success: function (data) {

            console.log(data);

            $('#company_name').html(data.companyName);
            $('#date_msg').html(parseDate(data.date));
            $('#message').html(data.message);
            $('#request_json').html(data.request_json);
            $('#request_url').html(data.request_url);

            preloader(false);
        }
    });

});
// === закрыть ошибку
$('body').on('click', '#btn_modal_error_cancel', function () {
   $('#modal_window_error').fadeOut(300);
   window.location.reload();
});


// === ФИЛЬТР ПО КОМПАНИЯМ === //
/*$('body').on('keyup', '#notices_company_filter', function (event) {
    if (event.keyCode === 13) {
        $('#icon-search-notices').click();
    }
});

$('body').on('click', '#icon-search-notices', function () {

    let $inpVal = $('#notices_company_filter').val();

    let $noticesCompanyFilter = $.extend(true, {}, $notices);
    $noticesCompanyFilter.data.search = $inpVal; // переделать, когда будет сформирован запрос

    //console.log($noticesCompanyFilter);

    $.ajax({
        type: "POST",
        url: "/backend/", // добавить запрос
        contentType: "application/json",
        headers: $headers,
        data: JSON.stringify($noticesCompanyFilter),
        beforeSend: function () {
            preloader(true);
        },
        error: function () {
            preloader(false);
            serverError();
        },
        success: function (data) {

            // console.log(data);

            // if (data !== undefined && data.length > 0) {

            $('#table_notices tbody tr').remove();

            for (let i = 0; i < 10; i++) {

                let $addNewNotice =
                    '<tr>' +
                    '   <td>' +
                    '       <i class="far fa-envelope fa-lg icon-message"></i>' +
                    '       <!-- <i class="far fa-envelope-open fa-lg icon-message"></i> -->' +
                    '   </td>' +
                    '   <td>Деловые линии</td>' +
                    '   <td><div class="message">сообщение сообщение сообщение сообщение сообщение сообщение сообщение сообщение</div></td>' +
                    '   <td>00.00.000 14:15:45</td>' +
                    '</tr>';

                $('#table_notices tbody').append($addNewNotice);
            }
            // } else {
            //      let $nothingFound = '<tr><td colspan="4" class="nothing_found">Ничего не найдено.</td></tr>';
            //      $('#table_notices tbody').append($nothingFound);
            // }
            preloader(false);
        }
    });
});*/


// === ФИЛЬТР ПО НЕПРОЧИТАННЫМ УВЕДОМЛЕНИЯМ === //
/*$('body').on('change', '#check_notices_filter', function () {

    let $checked = $(this).prop('checked');

    let $noticesFilter = $.extend(true, {}, $notices);
    $noticesFilter.data.filter = $checked; // переделать, когда будет сформирован запрос

    console.log($noticesFilter);

    $.ajax({
        type: "POST",
        url: "/backend/", // добавить запрос
        contentType: "application/json",
        headers: $headers,
        data: JSON.stringify($noticesFilter),
        beforeSend: function () {
            preloader(true);
        },
        error: function () {
            preloader(false);
            serverError();
        },
        success: function (data) {

            // console.log(data);

            // if (data !== undefined && data.length > 0) {

            $('#table_notices tbody tr').remove();

            for (let i = 0; i < 10; i++) {

                let $addNewNotice =
                    '<tr>' +
                    '   <td>' +
                    '       <i class="far fa-envelope fa-lg icon-message"></i>' +
                    '       <!-- <i class="far fa-envelope-open fa-lg icon-message"></i> -->' +
                    '   </td>' +
                    '   <td>Деловые линии</td>' +
                    '   <td><div class="message">сообщение сообщение сообщение сообщение сообщение сообщение сообщение сообщение</div></td>' +
                    '   <td>00.00.000 14:15:45</td>' +
                    '</tr>';

                $('#table_notices tbody').append($addNewNotice);
            }
            // } else {
            //      let $nothingFound = '<tr><td colspan="4" class="nothing_found">Ничего не найдено.</td></tr>';
            //      $('#table_notices tbody').append($nothingFound);
            // }
            preloader(false);
        }
    });
});*/


// === AUTOCOMPLETE SEARCH COMPANY === //
var $searchCompany = [
    "Деловые Линии",
    "American Airlines Cargo",
    "ABS group",
    "AIR21",
    "2GO Supply Chain",
    "ACE Courier Services",
    "Адель Сервис"
];
$('#notices_company_filter').autocomplete({
    lookup: $searchCompany,
    onSelect: function (data) {
        console.log('You selected: ' + data.value);
    }
});







