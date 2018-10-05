// === СПИСОК ДОКУМЕНТОВ === //
var $limit = 50,
    $offset = 0,
    $keyword = "";


var $sendModel = {
    "data": {
        "id": $userId,
        "defaultLang": $defaultLang,
        "defaultEmail": $defaultEmail,
        "limit": $limit,
        "offset": $offset,
        "keyword": $keyword
    }
};

/*$.ajax({
    type: "POST",
    url: "/backend/adm_get_document_list.php",
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

        console.log(data);*/

        // if (data.lists !== undefined && data.lists !== null && data.lists.length > 0) {
            for (let i = 0; i < 5; i++) {
                let $addDocument =
                    '<tr class="newDocument">' +
                    '   <td>' +
                    '       <a href="' + i + '" class="newDocument__name">' + 'Новый документ' + '</a>' +
                    '       <i class="fas fa-chevron-right newDocument__icon"></i>' +
                    '   </td>' +
                    '</tr>';
                $('#js-documents_lists').append($addDocument);
            }
        // }

        /*preloader(false);
    }
});*/


// === ПОИСК ПО ДОКУМЕНТАМ === //
$('body').on('keypress', '#js-input_search', function (event) {
    if (event.keyCode === 13) $('#js-btn_search').click();
});

$('body').on('click', '#js-btn_search', function () {
    let $keyword = $('#js-input_search').val();

    $sendModel.data.keyword = $keyword;

    console.log($sendModel);

    /*$.ajax({
        type: "POST",
        url: "/backend/adm_get_document_list.php",
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
});


// === СОЗДАТЬ ДОКУМЕНТ === //
$('body').on('click', '#js-btn_create_document', function () {
    window.location.href = 'document.html';
});


// === РЕДАКТИРОВАТЬ ДОКУМЕНТ === //
$('body').on('click', 'tr.newDocument', function (event) {
    event.preventDefault();
    let $documentId = $(this).find('.newDocument__name').attr('href');
    if ($documentId !== undefined && $documentId !== null && $documentId.length > 0) {
        window.location.href = 'document.html?id=' + $documentId;
    }
});









