// ===== ТРАНСПОРТ/ЭКРАН КОМПАНИИ ===== //
var transportNumber = location.search.substring(4);
var $companyId;
var $nameCountry = '';
var $idCountry = '';

$('#btn-back-list-transport').on('click', function () {
    window.location.href = 'companies.html#trans-' + transportNumber;
});

var companyName = {
    "data": {
        "id": $userId,
        "defaultLang": $defaultLang,
        "defaultEmail": $defaultEmail,
        "transportId": transportNumber
    }
};

$.ajax({
    type: "POST",
    url: "/backend/adm_get_transport_page.php",
    contentType: "application/json",
    headers: $headers,
    data: JSON.stringify(companyName),
    beforeSend: function () {
        preloader(true);
    },
    error: function () {
        preloader(false);
        serverError();
    },
    success: function (data) {

        console.log(data);

        if (data.canorder === true) {
            $('#can_order').attr('checked', true);
            $('#status_company').addClass('check_mark');
            $('#status_company_active').html('Компания активна');
        } else {
            $('#can_order').attr('checked', false);
            $('#status_company').addClass('check_warn');
            $('#status_company_active').html('Компания заблокирована');
        }

        $('#name_company').val(data.name); // имя компании
        $companyId = data.id; // id компании
        let $logoCompany = '<img src="' + data.logo + '" alt="Логотип компании">';
        $('#logo_company').append($logoCompany); // logo компании
        for (let i in data.lists.countries) { // страна компании
            if (data.lists.countries[i].data == data.country) {
                $('#lang_company').val(data.lists.countries[i].value);
            }
        }
        $('#last_visit_time').html(parseDate(data.last_access_date)); // последнее посещение
        $('#phone_company').val(data.phones).mask('+0 000 000 00 00'); // телефон компании
        $('#email_company').val(data.email); // email компании
        let $siteCompany = '<a id="site_href" href="' + data.site + '" target="_blank">' + data.site + '</a>';
        $('#site_edit_company').prepend($siteCompany); // сайт компании
        $('#site_company').val(data.site); // сайт компании
        $idCountry = data.country; // страна компании $idCountry
        // $('#module_name_company').val(data.module); // наименование модуля

        // === Autocomplete language company === //
        let $allCountries = data.lists.countries;
        $('#lang_company').autocomplete({
            lookup: $allCountries,
            onSelect: function (suggestion) {
                $nameCountry = suggestion.value;
                $idCountry = suggestion.data;
            }
        });

        // === ТАРИФЫ === //
        if (data.lists.tariff_types !== undefined && data.lists.tariff_types.length > 0) {

            for (let i in data.lists.tariff_types) {

                var tariffTypes =
                    '<div class="tariff_row form_row_data">' +
                    '<span class="tariff_cell">' +
                    '<input type="checkbox" class="check_trans check_tariff" id="tariff-' + data.lists.tariff_types[i].id + '" value="' + data.lists.tariff_types[i].id + '"' + ' ' + tariffActivity(data.lists.tariff_types[i].activity) + '>' +
                    '<label for="tariff-' + data.lists.tariff_types[i].id + '"></label>' +
                    '</span>' +
                    '<span class="tariff_cell tariff_desc">' + data.lists.tariff_types[i].description + '</span>' +
                    '<span class="tariff_cell">Коэфициент тарифа</span>' +
                    '<span class="tariff_cell"><input class="tariff_inp" type="text" value="' + data.lists.tariff_types[i].profit_coefficient + '"></span>' +
                    '</div>';

                $('#tariff_types').append(tariffTypes);
            }
        }

        // === Маска для ввода === //
        $('.tariff_inp').mask('0.0000');

        // === Добавить коэффициент === //
        // function tariffCoeff(tariff_id) {
        //     if (data.selected_options.tariff_activity !== undefined && data.selected_options.tariff_activity.length > 0) {
        //         for (let i = 0; i < data.selected_options.tariff_activity.length; i++) {
        //             if (tariff_id == data.selected_options.tariff_activity[i].id) {
        //                 return parseFloat(data.selected_options.tariff_activity[i].profit_coefficient);
        //             }
        //         }
        //     }
        //     return '1.0';
        // }

        // === Актив тариф === //
        function tariffActivity(_activity) {
            if (parseInt(_activity) === 1) {
                return 'checked';
            } else {
                return '';
            }

            // if (data.selected_options.tariff_activity !== undefined && data.selected_options.tariff_activity.length > 0) {
            //     for (let i = 0; i < data.selected_options.tariff_activity.length; i++) {
            //         if (tariff_id == data.selected_options.tariff_activity[i].id) {
            //             return 'checked';
            //         }
            //     }
            // }
            // return '';
        }


        // === ПЛАТЕЛЬЩИКИ И СПОСОБЫ ОПЛАТЫ === //
        if (data.lists.payer_types !== undefined && data.lists.payer_types.length > 0) {

            for (let i in data.lists.payer_types) {

                var payerTypes =
                    '<div class="payer_row form_row_data">' +
                    '<span class="payer_cell">' +
                    '<input type="checkbox" class="check_payer check_trans" id="payer-' + data.lists.payer_types[i].id + '" value="' + data.lists.payer_types[i].id + '"' + ' ' + checkPayerActivity(data.lists.payer_types[i].id) + '>' +
                    '<label class="head" for="payer-' + data.lists.payer_types[i].id + '"></label>' +
                    '</span>' +
                    '<span class="payer_cell payer_desc">' + data.lists.payer_types[i].description + '</span>' +
                    '</div>' +
                    getPayment(data.lists.payer_types[i].id);

                $('#payer_types').append(payerTypes);
            }
        }

        // === При снятии чек бокса с наименования плательщика автоматически снимаються чекбоксы
        $('body').on('click', '#payer-1', function () {
            $(':checkbox[id^="client_id-"]').prop('checked', this.checked);
        });
        $('body').on('click', '#payer-2', function () {
            $(':checkbox[id^="sender_id-"]').prop('checked', this.checked);
        });
        $('body').on('click', '#payer-3', function () {
            $(':checkbox[id^="recipient_id-"]').prop('checked', this.checked);
        });

        // === Добавить способ оплаты === //
        function getPayment(payerId) {
            let paymentTypes = '';
            if (data.lists.payment_types !== undefined && data.lists.payment_types.length > 0) {
                for (let i = 0; i < data.lists.payment_types.length; i++) {
                    paymentTypes +=
                        '<div class="payment_row form_row_data">' +
                        '<span class="payment_cell">' +
                        '<input type="checkbox" class="check_payment check_trans" id="' + paymentCheckId(payerId, data.lists.payment_types[i].id) + '" value="' + data.lists.payment_types[i].id + '"' + ' ' + paymentActivity(payerId, data.lists.payment_types[i].id) + '>' +
                        '<label for="' + paymentCheckId(payerId, data.lists.payment_types[i].id) + '"></label>' +
                        '</span>' +
                        '<span class="payment_cell payment_desc">' + data.lists.payment_types[i].description + '</span>' +
                        '<span class="payment_cell">' + getDiscount(payerId, data.lists.payment_types[i].id) + '</span>' +
                        '<span class="payment_cell"><input type="text" class="payment_inp" value="' + getDiscountValue(payerId, data.lists.payment_types[i].id) + '" maxlength="6"/></span>' +
                        '</div>';
                }
            }
            return paymentTypes;
        }

        function getDiscountValue(payerId, paymentId) {
            if (data.selected_options.payer_payment_discount_activity !== undefined && data.selected_options.payer_payment_discount_activity.length > 0) {
                for (let i = 0; i < data.selected_options.payer_payment_discount_activity.length; i++) {
                    var item = data.selected_options.payer_payment_discount_activity[i];
                    if (item.payer_id == payerId && item.payment_id == paymentId) {
                        return parseFloat(item.discount_value);
                    }
                }
            }
            return 0;
        }


        function paymentCheckId(payerId, payerValue) {
            var payerDiscription = '';
            if (payerId == '1') {
                payerDiscription = 'client_id-';
                payerDiscription += payerValue;
            } else if (payerId == '2') {
                payerDiscription = 'sender_id-';
                payerDiscription += payerValue;
            } else if (payerId == '3') {
                payerDiscription = 'recipient_id-';
                payerDiscription += payerValue;
            }
            return payerDiscription;
        }

        function getSelectedDiscountId(payerId, paymentId) {
            if (data.selected_options.payer_payment_discount_activity !== undefined && data.selected_options.payer_payment_discount_activity.length > 0) {
                for (let i = 0; i < data.selected_options.payer_payment_discount_activity.length; i++) {
                    if (payerId == data.selected_options.payer_payment_discount_activity[i].payer_id
                        && paymentId == data.selected_options.payer_payment_discount_activity[i].payment_id) {
                        return data.selected_options.payer_payment_discount_activity[i].discount_id;
                    }
                }
            }
            return 0;
        }

        // === Добавить скидку === //
        function getDiscount(payerId, paymentId) {
            let discountTypes = '';
            let selectedDiscountId = getSelectedDiscountId(payerId, paymentId);
            if (data.lists.discount_types !== undefined && data.lists.discount_types.length > 0) {
                for (let i = 0; i < data.lists.discount_types.length; i++) {
                    if (data.lists.discount_types[i].id == selectedDiscountId) {
                        discountTypes += '<input type="text" class="payment_inp payment_coeff" value="' + data.lists.discount_types[i].id + '" />';
                    }
                }
            }
            return discountTypes;
        }

        // === Актив платильщик === //
        function checkPayerActivity(payer_id) {
            if (data.selected_options.payer_payment_discount_activity !== undefined && data.selected_options.payer_payment_discount_activity.length > 0) {
                for (let i = 0; i < data.selected_options.payer_payment_discount_activity.length; i++) {
                    if (payer_id == data.selected_options.payer_payment_discount_activity[i].payer_id) {
                        return 'checked';
                    }
                }
            } else {
                return 'checked';
            }
            return '';
        }

        // === Актив оплата === //
        function paymentActivity(payerId, paymentId) {
            if (data.selected_options.payer_payment_discount_activity !== undefined && data.selected_options.payer_payment_discount_activity.length > 0) {
                for (let i = 0; i < data.selected_options.payer_payment_discount_activity.length; i++) {
                    if (payerId == data.selected_options.payer_payment_discount_activity[i].payer_id
                        && paymentId == data.selected_options.payer_payment_discount_activity[i].payment_id) {
                        return 'checked';
                    }
                }
            } else {
                return 'checked';
            }
            return '';
        }


        // === ДОКУМЕНТЫ === //
        if (data.lists.document_types !== undefined && data.lists.document_types.length > 0) {
            for (let i in data.lists.document_types) {
                var documentTypes =
                    '<div class="doc_row form_row_data">' +
                    '<span class="doc_cell">' +
                    '<input type="checkbox" class="check_trans check_doc" id="document-' + data.lists.document_types[i].id + '" value="' + data.lists.document_types[i].id + '"' + ' ' + documentActivity(data.lists.document_types[i].id) + '>' +
                    '<label for="document-' + data.lists.document_types[i].id + '"></label>' +
                    '</span>' +
                    '<span class="doc_cell doc_desc">' + data.lists.document_types[i].description + '</span>' +
                    '<span class="doc_cell"><span id="icon_edit_doc"></span></span>' +
                    '<span class="doc_cell"><span id="icon_delete_doc"></span></span>' +
                    '</div>';

                $('#document_types').append(documentTypes);
            }
        }

        // === Активность документа === //
        function documentActivity(id) {
            if (data.selected_options.document_activity !== undefined && data.selected_options.document_activity.length > 0) {
                for (let i = 0; i < data.selected_options.document_activity.length; i++) {
                    if (id == data.selected_options.document_activity[i]) {
                        return 'checked';
                    }
                }
            } else {
                return 'checked';
            }
            return '';
        }

        preloader(false);
    }
});


// === РЕДАКТИРОВАНИЕ ССЫЛКИ КОМПАНИИ === //
$('body').on('click', '#js-site__edit', function () {
    $('#site_edit_company').toggle();
    $('#site_company').toggle();

    $('#site_company').focus();
    let $siteVal = $('#site_company').val();
    $('#site_href').attr('href', $siteVal).html($siteVal);

    if ($(this).hasClass('fa-edit')) {
        $(this).removeClass('fa-edit').addClass('fa-check-circle');
    } else {
        $(this).removeClass('fa-check-circle').addClass('fa-edit');
    }
});


// === Поменять активность компании === //
$('body').on('click', '#can_order', function () {
    var $checked = $('#can_order').prop('checked');
    if ($checked === true) {
        $('#status_company').removeClass('check_warn').addClass('check_mark');
        $('#status_company_active').html('Компания активна');
        saveCompanyForm();
    } else {
        $('#status_company').removeClass('check_mark').addClass('check_warn');
        $('#status_company_active').html('Компания заблокирована');
        saveCompanyForm();
    }
});

// === Неправильно введенное значение (красное поле) === //
$('body').on('focus', '#email_company', function () {
    $(this).css({
        border: '1px solid #D7D7D7',
        backgroundColor: '#FFF'
    });
});

// === Валидация данных === //
function isValidEmailCompany() {
    let $inputVal = $('#email_company').val();
    let $regExp = /^(|(([A-Za-z0-9]+_+)|([A-Za-z0-9]+\-+)|([A-Za-z0-9]+\.+)|([A-Za-z0-9]+\++))*[A-Za-z0-9]+@((\w+\-+)|(\w+\.))*\w{1,63}\.[a-zA-Z]{2,6})$/;

    if ($inputVal == '' || !$regExp.test($inputVal)) {
        $('#email_company').css('border', '1px solid #FE4D44');
        return false;
    }
    return true;
}

// ===== Сохранение формы компании ===== //
$('body').on('click', '#btn-save-company-form', function () {
    var $valid = true;

    // if (!tariffCoeff()) {
    //     $valid = false;
    // }

    // if (!isValidEmailCompany()) {
    //     $valid = false;
    // }

    if ($valid == false) {
        $('.warning_window_false').css('display', 'inline-block');
        setTimeout(function () {
            $('.warning_window_false').fadeOut(400);
        }, 5000);
    } else {
        $('#modal_window_bg').fadeIn(400); // запускает модальное окно
    }
});

// === Модальное окно подтверждения === //
$('body').on('click', '#btn-modal-cancel', function () {
    $('#modal_window_bg').fadeOut(400);
});
$('body').on('click', '#btn-modal-confirm', function () {
    saveCompanyForm();
    $('#modal_window_bg').fadeOut(400);
});

// === Сохранение и отправка данных на сервер === //
function saveCompanyForm() {

    var tariff_types = [];
    var payer_payment_discount_activity = [];
    var document_types = [];
    var _tariff_id, _tariff_coeff, _tariff_act, _payer_id, _payer_act, _payment_id, _payment_act, _discount_id, _doc_id,
        _doc_act;
    var form = $('#form_company');
    if (form !== undefined && form.length > 0) {
        form.find('.form_row_data').each(function () {
            let div = $(this);
            if (div.hasClass('tariff_row')) {  //для тарифа
                _tariff_id = div.find('.check_tariff').val();
                _tariff_coeff = div.find('.tariff_inp').val();
                _tariff_act = div.find('.check_tariff').prop('checked');
                (_tariff_act === true) ? _tariff_act = 1 : _tariff_act = 0;
                if ((_tariff_id !== undefined && _tariff_id.length > 0) && (_tariff_coeff !== undefined && _tariff_coeff.length > 0)) {
                    tariff_types.push({
                        id: parseFloat(_tariff_id),
                        profit_coefficient: parseFloat(_tariff_coeff),
                        activity: _tariff_act
                    });
                }
            } else if (div.hasClass('payer_row')) {  //для платильщика
                _payer_id = div.find('.check_payer').val();
                _payer_act = div.find('.check_payer').prop('checked');
            } else if (div.hasClass('payment_row')) {  //для оплаты
                _payment_id = div.find('.check_payment').val();
                _payment_act = div.find('.check_payment').prop('checked');
                _discount_id = div.find('.payment_inp').val();
                if (_payment_id !== undefined && _payment_id.length > 0) {
                    if (_payment_act == true && _payer_act == true) {
                        payer_payment_discount_activity.push({
                            payer_id: parseFloat(_payer_id),
                            payment_id: parseFloat(_payment_id),
                            discount_value: parseFloat(_discount_id)
                        });
                    }
                }
            } else if (div.hasClass('doc_row')) {  //для документа
                _doc_id = div.find('.check_doc').val();
                _doc_act = div.find('.check_doc').prop('checked');
                if (_doc_id !== undefined && _doc_id.length > 0) {
                    if (_doc_act == true) {
                        document_types.push(parseFloat(_doc_id));
                    }
                }
            }
        });
    }

    var $canOrder = $('#can_order').prop('checked');
    var $nameCompany = $('#name_company').val();
    var $phoneCompany = $('#phone_company').val();
    var $emailCompany = $('#email_company').val();
    var $siteCompany = $('#site_company').val();

    var saveCompanyOption = $.extend(true, {}, companyName);
    saveCompanyOption.data.companyId = $companyId;
    saveCompanyOption.data.name = $nameCompany;
    saveCompanyOption.data.country = $idCountry;
    saveCompanyOption.data.phones = $phoneCompany;
    saveCompanyOption.data.email = $emailCompany;
    saveCompanyOption.data.canOrder = $canOrder;
    saveCompanyOption.data.documents = document_types;
    saveCompanyOption.data.discounts = payer_payment_discount_activity;
    saveCompanyOption.data.tariffs = tariff_types;
    saveCompanyOption.data.site = $siteCompany;

    // console.log(saveCompanyOption);

    $.ajax({
        type: "POST",
        url: "/backend/adm_save_transport.php",
        contentType: "application/json",
        headers: $headers,
        data: JSON.stringify(saveCompanyOption),
        beforeSend: function () {
            preloader(true);
        },
        error: function () {
            preloader(false);
            serverError();
        },
        success: function (data) {
            if (data.status === "good") {
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

// === Ввод в поле только цифр === //
$('body').on('keypress', '.tariff_inp, .payment_inp, #coeff_tariff, #phone_company', function (event) {
    return !(/[А-Яа-яA-Za-z ]/.test(String.fromCharCode(event.charCode)));
});

// === Добавление нового тарифа === //
/*$('body').on('click', '#btn-add-tariff', function () {
    $('#modal_window_add_tariff').fadeIn(300);
});

$('#btn-modal-tariff-cancel').on('click', function () {
    $('#modal_window_add_tariff').fadeOut(300);
});

$('#btn-modal-tariff-confirm').on('click', function () {
    addNewTariff();
    $('#modal_window_add_tariff').fadeOut(300);
});

function addNewTariff() {
    var $actTariff = $('#active_tariff').prop('checked');
    var $nameTariff = $('#descr_tariff').val();
    var $profitTariff = parseFloat($('#coeff_tariff').val());

    var $addNewTariff = $.extend(true, {}, companyName);
    $addNewTariff.data.newTariffs = {};
    $addNewTariff.data.companyId = $companyId;
    $addNewTariff.data.newTariffs.isActive = $actTariff;
    $addNewTariff.data.newTariffs.name = $nameTariff;
    $addNewTariff.data.newTariffs.profit = $profitTariff;

    $.ajax({
        type: "POST",
        url: "/backend/adm_add_company_tariff.php",
        contentType: "application/json",
        headers: $headers,
        data: JSON.stringify($addNewTariff),
        beforeSend: function () {
            preloader(true);
        },
        error: function () {
            preloader(false);
            serverError();
        },
        success: function (data) {

            if (data.status === "good") {
                $('.warning_window_true').css('display', 'inline-block');
                setTimeout(function () {
                    $('.warning_window_true').fadeOut(400);
                }, 5000);
                window.location.reload();
            } else {
                serverError();
            }
            preloader(false);
        }
    });

    // === Очищаем поля ввода === //
    $('#active_tariff').prop('checked', false);
    $('#descr_tariff').val('');
    $('#coeff_tariff').val('');
}*/


// === ОГРАНИЧЕНИЕ ВВОДА КОЕФФИЦИЕНТА ТАРИФА ОТ 0 ДО 4 === //
$('body').on('blur', '.tariff_inp', function () {
    if ($(this).val() <= 0 || $(this).val() === '') $(this).val(0);
    if ($(this).val() >= 4) $(this).val(4);
    // $(this).val(parseFloat($(this).val()).toFixed(4)); // ввод числа до сотых
});
// === ОГРАНИЧЕНИЕ ВВОДА СКИДКИ ОТ 0 ДО 100 === //
$('body').on('blur', '.payment_inp', function () {
    if ($(this).val() <= 0 || $(this).val() === '') $(this).val(0);
    if ($(this).val() >= 100) $(this).val(100);
    $(this).val(parseFloat($(this).val()).toFixed(2)); // ввод числа до сотых
});




