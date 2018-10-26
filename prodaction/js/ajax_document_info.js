// === ИНФОРМАЦИЯ О ДОКУМЕНТЕ === //
var $sendModel = {
    "data": {
        "id": $userId,
        "defaultLang": $defaultLang,
        "defaultEmail": $defaultEmail
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