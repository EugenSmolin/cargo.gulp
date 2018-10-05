// ===== КЛИЕНТ/ЭКРАН ===== //
var clientId = location.search.substring(4);

$('#btn-back-list-clients').on('click', function () {
    window.location.href = 'clients.html#client-' + clientId;
});


var sendModel = {
    data: {
        "id": $userId,
        "defaultLang": $defaultLang,
        "defaultEmail": $defaultEmail,
        "userId": clientId
    }
};

$.ajax({
    type: "POST",
    url: "/backend/adm_user_page.php",
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

        // === ИНФОРМАЦИЯ О КЛИЕНТЕ === //
        if (data.isJur === true) {
            $('.fio_client').html(data.jurName);
        } else {
            $('.fio_client').html(data.lastName + ' ' + data.firstName + ' ' + data.middleName);
        }

        if (data.isApproved === true) {
            $('#status_client').val('Активный');
            $('.status_client .check_warn').removeClass('check_warn').addClass('check_mark');
        } else {
            $('#status_client').val('Заблокирован');
            $('.status_client .check_mark').removeClass('check_mark').addClass('check_warn');
        }

        $('.last_visit_time').html(parseDate(data.lastLogin));
        $('input.signup_time').val(parseDate(data.registeredDate));

        // === физлицо === //
        $('#last_name_client').val(data.lastName);
        $('#first_name_client').val(data.firstName);
        $('#middle_name_client').val(data.middleName);
        $('#phone_client').val(data.phone).mask('+0 000 000 00 00');
        $('#email_client').val(data.email);

        $('#document_number_client').val(data.documentNumber);
        $('#address_client').val(data.address);
        $('#flat_number_client').val(data.addressCell);

        $('#document_code').val(data.passportDivisionCode).mask("000-000");

        $('#document_issued').val(data.givenName);
        $('#document_when_issued').val(parseDateDiagram(data.givenDate));
        // === юрлицо === //
        $('#jur_name').val(data.jurName);
        $('#jur_phone').val(data.phone).mask('+0 000 000 00 00');
        $('#jur_email').val(data.email);
        $('#jur_legalform').val(data.legalForm);
        $('#jur_ogrn').val(data.OGRN);
        $('#jur_address').val(data.jurAddress);

        let $jurKPP = data.userKPP;
        $jurKPP === 0 ? $jurKPP = '' : $jurKPP;
        $('#jur_kpp').val($jurKPP);
        $('#jur_acc').val(data.userAccNumber);
        $('#jur_bik').val(data.userBIK);
        $('#jur_base').val(data.userJurBase);
        $('#jur_fio').val(data.userChiefName);
        $('#jur_office').val(data.companyAddressCell);


        // === для юридического лица === //
        if (data.isJur === true) {
            $('#legal_entity').attr('checked', true);
            $('#js-individual_form').css('display', 'none'); // прячем форму физлица
            $('#js-legal_entity_form').css('display', 'block'); // показываем форму юрлица
            $('.fio_client').html(data.jurName);
            $('#jur_inn').val(data.INN);
            $('#inn_client').val('');
        } else {
            $('#legal_entity').attr('checked', false);
            $('#js-individual_form').css('display', 'block'); // показываем форму физлица
            $('#js-legal_entity_form').css('display', 'none'); // прячем форму юрлица
            $('.fio_client').html(data.lastName + ' ' + data.firstName + ' ' + data.middleName);
            $('#inn_client').val(data.INN);
            $('#jur_inn').val('');
        }

        // === Документ, удостоверяющий личность === //
        if (data.documentTypes !== undefined && data.documentTypes !== null && data.documentTypes.length > 0) {
            for (let i = 0; i < data.documentTypes.length; i++) {
                let documentTypes = '<option value="' + data.documentTypes[i].id + '">' + data.documentTypes[i].name + '</option>';
                $('#document_client').append(documentTypes);
                if (data.documentTypes[i].id == data.docTypeId) {
                    $('#document_client').val(data.documentTypes[i].id);
                }
            }
        }

        // === Правовая форма === //
        if (data.legalFormList !== undefined && data.legalFormList !== null && data.legalFormList.length > 0) {
            for (let i = 0; i < data.legalFormList.length; i++) {
                let $legalForm = '<option value="' + data.legalFormList[i].id + '">' + data.legalFormList[i].name + '</option>';
                $('#jur_legalform').append($legalForm);
                if (data.legalFormList[i].id === data.jurFormId) {
                    $('#jur_legalform').val(data.legalFormList[i].id);
                }
            }
        }

        // === переключение для юрлица === //
        $('#legal_entity').on('click', function () {
            if ($('#legal_entity').prop('checked') === true) {
                $(this).attr('checked', true);
                $('#js-individual_form').css('display', 'none');
                $('#js-legal_entity_form').css('display', 'block');
            } else {
                $(this).attr('checked', false);
                $('#js-individual_form').css('display', 'block');
                $('#js-legal_entity_form').css('display', 'none');
            }
        });

        // === СКИДКИ КЛИЕНТА === //
        if (data.discounts !== undefined && data.discounts !== null && data.discounts.length > 0) {
            for (let i = 0; i < data.discounts.length; i++) {
                let $addDiscountList =
                    '<tr class="tr_client_discounts">' +
                    '   <td>' + data.discounts[i].id + '</td>' +
                    '   <td class="name_discount" id="' + data.discounts[i].id + '">' + data.discounts[i].name + '</td>' +
                    '   <td>' + data.discounts[i].value + '</td>' +
                    '   <td><div class="td_overflow" title="' + companies(data.discounts[i]) + '">' + companies(data.discounts[i]) + '</div></td>' +
                    '   <td><div class="td_overflow" title="' + countries(data.discounts[i]) + '">' + countries(data.discounts[i]) + '</div><i class="far fa-edit fa-lg icon_edit_discount" id="icon_edit_discount"></i></td>' +
                    '</tr>';

                $('table#table_client_discount tbody').append($addDiscountList);
            }
        }

        function companies(index) {
            let $companies = '';
            if (index.companies !== undefined && index.companies !== null && index.companies.length > 0) {
                for (let i in index.companies) {
                    $companies += index.companies[i] + ', ';
                }
            }
            return $companies;
        }

        function countries(index) {
            let $countries = '';
            if (index.countries !== undefined && index.countries !== null && index.countries.length > 0) {
                for (let i in index.countries) {
                    $countries += index.countries[i] + ', ';
                }
            }
            return $countries;
        }

        preloader(false);
    }
});

// === Поменять статус клиента === //
$('#status_client').on('change', function () {
    var $checked = $(this).val();

    if ($checked == 'Заблокирован') {
        $('.status_client .check_mark').removeClass('check_mark').addClass('check_warn');
    } else if ($checked == 'Активный') {
        $('.status_client .check_warn').removeClass('check_warn').addClass('check_mark');
    }
});

// === Валидация формы клиента (красное поле) === //
function errorInput(idInput) {
    idInput.css({
        border: '1px solid red',
        backgroundColor: '#FECFCC'
    });
}

$('.client_form input, .legal_form input').on('focus', function () {
    $(this).css({
        border: '1px solid #D7D7D7',
        backgroundColor: '#FFF'
    });
});


// === Ввод только цифр === //
$('#phone_client, #number_client, #inn_client, #jur_ogrn, #jur_inn, #jur_acc, #jur_bik, #jur_kpp, #jur_office, #document_code, #jur_phone, #flat_number_client').on('keypress', function (event) {
    return !(/[А-Яа-яA-Za-z ]/.test(String.fromCharCode(event.charCode)));
});


// === МАСКА ДЛЯ ВВОДА ДАТЫ === //
$("#document_when_issued").attr('placeholder', 'dd.mm.yyyy'); // .mask('00.00.0000')


// === DATE PICKER === //
$("#document_when_issued").datepicker({
    dateFormat: "dd.mm.yy",
    changeMonth: true,
    changeYear: true,
    maxDate: new Date()
});

$('body').on('click', '.btn-save-form', function () {
    var $valid = true;

    if (!emailValidClient()) {
        $valid = false;
    }

    if (!innValidClient()) {
        $valid = false;
    }

    if ($valid == false) {
        $('.warning_window_false').css('display', 'inline-block');
        setTimeout(function () {
            $('.warning_window_false').fadeOut(400);
        }, 5000);
    } else {
        $('#modal_window_bg').fadeIn(400); // вызывает модальное окно
    }


    /* === физлицо === */
    // if ($('#legal_entity').prop('checked') == false) {

    /*if (!lastNameValidClient()) {
        $valid = false;
    }
    if (!firstNameValidClient()) {
        $valid = false;
    }
    if (!middleNameValidClient()) {
        $valid = false;
    }
    if (!phoneValidClient()) {
        $valid = false;
    }
    if (!documentValidClient()) {
        $valid = false;
    }
    if (!numberValidClient()) {
        $valid = false;
    }
    if (!addressValidClient()) {
        $valid = false;
    }
    if (!innValidClient()) {
        $valid = false;
    }
    }*/

    /* === юрлицо === */
    /*if ($('#legal_entity').prop('checked') == true) {

        if (!jurNameValidClient()) {
            $valid = false;
        }
        if (!phoneValidClient()) {
            $valid = false;
        }
        if (!emailValidClient()) {
            $valid = false;
        }
        if (!jurLegalFormValidClient()) {
            $valid = false;
        }
        if (!ogrnValidClient()) {
            $valid = false;
        }
        if (!jurAddressValidClient()) {
            $valid = false;
        }
        if (!innValidClient()) {
            $valid = false;
        }

        if ($valid == false) {
            $('.warning_window_false').css('display', 'inline-block');
            setTimeout(function () {
                $('.warning_window_false').fadeOut(400);
            }, 5000);
        } else {
            $('#modal_window_bg').fadeIn(400); // вызывает модальное окно
        }
    }*/
});

// === Сбор и сохранение информации о клиенте === //
function saveUserForm() {
    // === Отправка статуса клиента === //
    var statusClientCheck;

    if ($('#status_client').val() == 'Активный') {
        statusClientCheck = 1;
    }
    if ($('#status_client').val() == 'Заблокирован') {
        statusClientCheck = 0;
    }

    var userStatusChange = $.extend(true, {}, sendModel);
    userStatusChange.data.mode = "STATUS";
    userStatusChange.data.val = statusClientCheck;
    userStatusChange.data.userId = clientId;

    $.ajax({
        type: "POST",
        url: "/backend/adm_user_status_change.php",
        contentType: "application/json",
        headers: $headers,
        data: JSON.stringify(userStatusChange),
        success: function (data) {
            //console.log(data);
        }
    });


    // === Отправка подтверждения юрлица === //
    var legalNameCheck;

    if ($('#legal_entity').prop('checked') == true) {
        legalNameCheck = 1;
    }
    if ($('#legal_entity').prop('checked') == false) {
        legalNameCheck = 0;
    }

    var legalName = $.extend(true, {}, sendModel);
    legalName.data.mode = "LEGAL";
    legalName.data.val = legalNameCheck;
    legalName.data.userId = clientId;

    $.ajax({
        type: "POST",
        url: "/backend/adm_user_status_change.php",
        contentType: "application/json",
        headers: $headers,
        data: JSON.stringify(legalName),
        success: function (data) {
            //console.log(data);
        }
    });


    // === Cбор данных о клиенте === //
    let innClient = '';
    if ($('#legal_entity').prop('checked') === true) {
        innClient = $('#jur_inn').val();
    } else {
        innClient = $('#inn_client').val();
    }

    let $phone = '';
    ($('#legal_entity').prop('checked') === true) ? $phone = $('#jur_phone').val() : $phone = $('#phone_client').val();

    var userSendInfo = $.extend(true, {}, sendModel);
    userSendInfo.data.user = {};
    userSendInfo.data.user.id = parseInt(clientId);
    userSendInfo.data.user.lastName = $('#last_name_client').val();
    userSendInfo.data.user.middleName = $('#middle_name_client').val();
    userSendInfo.data.user.firstName = $('#first_name_client').val();
    userSendInfo.data.user.jurName = $('#jur_name').val();
    userSendInfo.data.user.email = $('#email_client').val();
    userSendInfo.data.user.phone = $phone;
    userSendInfo.data.user.docTypeId = parseInt($('#document_client').val());
    userSendInfo.data.user.documentNumber = $('#document_number_client').val();
    userSendInfo.data.user.jurFormId = parseInt($('#jur_legalform').val());
    userSendInfo.data.user.OGRN = parseInt($('#jur_ogrn').val());
    userSendInfo.data.user.address = $('#address_client').val();
    userSendInfo.data.user.jurAddress = $('#jur_address').val();
    userSendInfo.data.user.companyAddressCell = $('#jur_office').val();
    userSendInfo.data.user.INN = parseInt(innClient);
    userSendInfo.data.user.addressCell = parseInt($('#flat_number_client').val());
    userSendInfo.data.user.passportDivisionCode = parseInt($('#document_code').val().replace(/[^0-9.]/g, ""));
    userSendInfo.data.user.givenName = $('#document_issued').val();
    userSendInfo.data.user.givenDate = reverseDate($('#document_when_issued').val());

    userSendInfo.data.user.userKPP = $('#jur_kpp').val();
    userSendInfo.data.user.userAccNumber = $('#jur_acc').val();
    userSendInfo.data.user.userBIK = parseInt($('#jur_bik').val());
    userSendInfo.data.user.userJurBase = $('#jur_base').val();
    userSendInfo.data.user.userChiefName = $('#jur_fio').val();

    // console.log(userSendInfo.data.user.userAccNumber);

    $.ajax({
        type: "POST",
        url: "/backend/adm_save_user.php",
        contentType: "application/json",
        headers: $headers,
        data: JSON.stringify(userSendInfo),
        beforeSend: function () {
            preloader(true);
        },
        error: function () {
            preloader(false);
            serverError();
        },
        success: function (data) {
            if (data.status === 'good') {
                $('.warning_window_true').css('display', 'inline-block');
                setTimeout(function () {
                    $('.warning_window_true').fadeOut(400);
                    window.location.reload();
                }, 5000);
            } else {
                serverError();
            }
            preloader(false);
        }
    });
}

// === Валидация формы на физлицо === //
/*function lastNameValidClient() {
    var $input = $('#last_name_client').val();

    if ($input == '') {
        errorInput($('#last_name_client'));
        return false;
    } else {
        return true;
    }
}

function firstNameValidClient() {
    var $input = $('#first_name_client').val();

    if ($input == '' || $input == ' ') {
        errorInput($('#first_name_client'));
        return false;
    } else {
        return true;
    }
}

function middleNameValidClient() {
    var $input = $('#middle_name_client').val();

    if ($input == '') {
        errorInput($('#middle_name_client'));
        return false;
    } else {
        return true;
    }
}

function phoneValidClient() {
    var $input = $('#phone_client').val();

    if ($input == '') {
        errorInput($('#phone_client'));
        return false;
    } else {
        return true;
    }
}*/

function emailValidClient() {
    var $input = $('#email_client').val();
    var regExp = /^(|(([A-Za-z0-9]+_+)|([A-Za-z0-9]+\-+)|([A-Za-z0-9]+\.+)|([A-Za-z0-9]+\++))*[A-Za-z0-9]+@((\w+\-+)|(\w+\.))*\w{1,63}\.[a-zA-Z]{2,6})$/;


    if ($input == '' || !regExp.test($input)) {
        errorInput($('#email_client'));
        return false;
    } else {
        return true;
    }
}


function innValidClient() {
    if ($('#legal_entity').prop('checked') === true) {
        var jur_inn = $('#jur_inn').val();
        if (jur_inn == '' || jur_inn.length < 12) {
            errorInput($('#jur_inn'));
            return false;
        } else {
            return true;
        }
    } else {
        var inn_client = $('#inn_client').val();
        if (inn_client == '' || inn_client.length < 10) {
            errorInput($('#inn_client'));
            return false;
        } else {
            return true;
        }
    }
}


/*function documentValidClient() {
    var $input = $('#document_client').val();

    if ($input == '') {
        errorInput($('#document_client'));
        return false;
    } else {
        return true;
    }
}

function numberValidClient() {
    var $input = $('#number_client').val();

    if ($input == '') {
        errorInput($('#number_client'));
        return false;
    } else {
        return true;
    }
}

function addressValidClient() {
    var $input = $('#address_client').val();

    if ($input == '') {
        errorInput($('#address_client'));
        return false;
    } else {
        return true;
    }
}

function innValidClient() {
    var $input = $('#inn_client').val();

    if ($input == '') {
        errorInput($('#inn_client'));
        return false;
    } else {
        return true;
    }
}

// === Валидация формы на юрлицо === //
function jurNameValidClient() {
    var $input = $('#jur_name').val();

    if ($input == '') {
        errorInput($('#jur_name'));
        return false;
    } else {
        return true;
    }
}

function jurLegalFormValidClient() {
    var $input = $('#jur_legalform').val();

    if ($input == '') {
        errorInput($('#jur_legalform'));
        return false;
    } else {
        return true;
    }
}

function ogrnValidClient() {
    var $input = $('#jur_ogrn').val();

    if ($input == '') {
        errorInput($('#jur_ogrn'));
        return false;
    } else {
        return true;
    }
}

function jurAddressValidClient() {
    var $input = $('#jur_address').val();

    if ($input == '') {
        errorInput($('#jur_address'));
        return false;
    } else {
        return true;
    }
}*/

// === Модальное окно подтверждения === //
$('body').on('click', '#btn-modal-cancel', function () {
    $('#modal_window_bg').fadeOut(400);
});
$('body').on('click', '#btn-modal-confirm', function () {
    saveUserForm();
    $('#modal_window_bg').fadeOut(400);
});

// === Модальное окно на удаление клиента === //
$('body').on('click', '.btn-delete-form', function () {
    $('#modal_window_bg_delete').fadeIn(400);
});
$('body').on('click', '#btn-modal-cancel-delete', function () {
    $('#modal_window_bg_delete').fadeOut(400);
});
$('body').on('click', '#btn-modal-confirm-delete', function () {

    $.ajax({
        type: "POST",
        url: "/backend/adm_delete_user.php",
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
            if (data.status === "good") {
                var $replace = '<div class="user_delete">Клиент удален!</div>';
                $($replace).replaceAll('.clients_screen');
                setTimeout(function () {
                    window.location.replace('clients.html');
                }, 2000);
            } else {
                serverError();
            }
            preloader(false);
        }
    });

    $('#modal_window_bg_delete').fadeOut(400);
});


// === ВСЕ ЗАКАЗЫ КЛИЕНТА === //
$('body').on('click', '#orders_client', function () {
    window.location.href = 'orders.html?id=' + clientId;
});


// === СКИДКИ КЛИЕНТА === //
$('body').on('click', '.icon_edit_discount', function () {
    let $discountLink = $(this).parents('.tr_client_discounts').find('.name_discount').attr('id');
    if ($discountLink !== undefined && $discountLink !== null && $discountLink.length > 0) {
        window.location.href = 'discount.html?ui=' + clientId + '&di=' + $discountLink;
    }
});

// === ДОБАВИТЬ НОВУЮ СКИДКУ === //
$('body').on('click', '#add_new_discount', function () {
    window.location.href = 'discount.html?ui=' + clientId;
});





// === АДРЕСС === //
var inputs = $('#address_client, #jur_address');
$(inputs).each(function (i, el) {
    var input = inputs.eq(i);
    var autocomplete;
    var place;
    var geoCoder = new google.maps.Geocoder();

    var options = {
        types: ['address'],
        componentRestrictions: {country: 'ru'},
        place_changed: function (result) {

            var locBounds;
            var fullAddress;

            place = autocomplete.getPlace();

            var componentForm = {
                street_number: 'short_name',
                route: 'long_name',
                locality: 'long_name',
                administrative_area_level_1: 'short_name',
                country: 'long_name',
                postal_code: 'short_name'
            };

            // Get each component of the address from the place details
            // and fill the corresponding field on the form.
            for (var i = 0; i < place.address_components.length; i++) {
                var addressType = place.address_components[i].types[0];
                if (componentForm[addressType]) {
                    componentForm[addressType] = place.address_components[i][componentForm[addressType]];
                }
            }

            var street = (componentForm.street_number == "short_name") ? "" : ", " + componentForm.street_number;

            fullAddress = componentForm.locality + ", " + componentForm.route + street;

            $(input).val(fullAddress)
        }
    };

    autocomplete = new google.maps.places.Autocomplete(this, options);
});




