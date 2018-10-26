// === СОЗДАНИЕ/РЕДАКТИРОВАНИЕ ДОКУМЕНТА === //
var $documentId = location.search.substring(4);
($documentId !== '') ? $documentId = parseInt($documentId) : $documentId = '';
var $blockId = ''; // id документа при сохранении

console.log($documentId);

var $sendModel = {
    "data": {
        "id": $userId,
        "defaultLang": $defaultLang,
        "defaultEmail": $defaultEmail,
        "documentId": $documentId
    }
};

/*$.ajax({
    type: "POST",
    url: "/backend/adm_document_page.php",
    contentType: "application/json",
    headers: $headers,
    data: JSON.stringify($sendModel),
    beforeSend: function () {
        preloader(true);
    },
    error: function () {
        preloader(false);
        serverError();
    },
    success: function (data) {

        console.log(data);


        preloader(false);
    }
});*/


// === ВЕРНУТЬСЯ НАЗАД === //
$('body').on('click', '#js-btn_back', function () {
    window.history.back();
});


// === ПОИСК ПО БЛОКАМ === //
$('body').on('keypress', '#js-input_search', function (event) {
    if (event.keyCode == 13) $('#js-btn_search').click();
});

$('body').on('click', '#js-btn_search', function () {
    let $inpVal = $('#js-input_search').val().toLowerCase();

    $('#js-selection_list').children().find('.list__name').each(function () {
        let $listName = $(this).html().toLowerCase();
        ($listName.indexOf($inpVal) >= 0) ? $(this).parent().fadeIn() : $(this).parent().fadeOut();
    });
});


// === ПОДКЛЮЧЕНИЕ ТЕКСТОВОГО РЕДАКТОРА === //
CKEDITOR.replace('js-document_content');


// === СОХРАНИТЬ ДОКУМЕНТ === //
$('body').on('click', '#js-save_document', function () {
    $('#js-window_save_doc').fadeIn();
});

$('body').on('click', '#js-confirm_window_save', function () {
    $('#js-window_save_doc').fadeOut();
    $blockId = 15;
});

$('body').on('click', '#js-cancel_window_save', function () {
    $('#js-window_save_doc').fadeOut();
});


// === ПОСМОТРЕТЬ ДОКУМЕНТ === //
$('body').on('click', '#js-watch_document', function () {
    if ($blockId !== '') {
        let $documentContent = $('iframe').contents().find('body').html();
        console.log($documentContent);
        window.location.href = 'document_info.html';
    } else {
        $('#js-window_watch_doc').fadeIn(300);
    }
});

$('body').on('click', '#js-close_window', function () {
    $('#js-window_watch_doc').fadeOut(300);
});











