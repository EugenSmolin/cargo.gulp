// ===== Авторизация формы ===== //
$('body').on('keyup', '#pass_input', function (event) {
    if (event.keyCode === 13) {
        $('#btn-login').click();
    }
});

$('#btn-login').on('click', function () {
    var login = $('#login_input').val();
    var password = $('#pass_input').val();

    var loginForm = {
        "auth": {
            "login": login,
            "password": password
        }
    };

    $.ajax({
        type: "POST",
        url: "/backend/adm_login.php",
        contentType: "application/json",
        headers: {'api-key': 'RN42O4ntxJJen8GBixIf5BGCMPwwie'},
        data: JSON.stringify(loginForm),
        error: function () {
            $('#error_login').fadeIn();
            setTimeout(function () {
                $('#error_login').fadeOut();
            }, 3000);
        },
        success: function (data) {
            if (data.success == "success") {
                window.location.href = 'index.html';
            }
        },
    });

});