// ===== ЗАКАЗ/ЭКРАН ===== //
var orderId = parseInt(location.search.substring(4));
var $isOrderPayed = '';

$('#btn-back-list-orders').on('click', function () {
    window.location.href = 'orders.html#ord-' + orderId;
});

var sendModel = {
    "data": {
        "id": $userId,
        "defaultLang": $defaultLang,
        "defaultEmail": $defaultEmail,
        "orderID": orderId
    }
};

$.ajax({
    type: "POST",
    url: "/backend/adm_get_order.php",
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

        // === Номер заказа и дата === //
        $('#order_num').html(data.orderInfo.id); // номер заказа

        $('#order_date').html(parseDate(data.orderInfo.orderTimestamp)); // дата заказа

        // === Откуда, куда === //
        $('#cargo_from').html(data.orderInfo.cargoFrom);
        $('#address_from').html(data.orderInfo.orderSenderAddress);
        $('#cargo_to').html(data.orderInfo.cargoTo);
        $('#address_to').html(data.orderInfo.orderRecepientAddress);

        // === Дата отправления и прибытия === //
        $('#delivery_date').html(parseDateDiagram(data.orderInfo.cargoDeliveryDate)); // дата прибытия
        $('#desired_date').html(parseDateDiagram(data.orderInfo.cargoDesiredDate)); // дата отпрвления

        // === Инфо о клиентах === //
        var $recepient = '',
            $sender = '',
            $recepientLegal = '',
            $senderLegal = '';

        function senderRecepient(clas, varib, fio, doc, phone, email) {
            varib =
                '<div>' +
                '   <span class="name">фио:</span>' +
                '   <span>' + fio + '</span>' +
                '</div>' +
                '<div>' +
                '   <span class="name">документ:</span>' +
                '   <span>' + doc + '</span>' +
                '</div>' +
                '<div>' +
                '   <span class="name">телефон:</span>' +
                '   <span>' + phone + '</span>' +
                '</div>' +
                '<div>' +
                '   <span class="name">email:</span>' +
                '   <span>' + email + '</span>' +
                '</div>';
            $(clas).append(varib);
        }

        function senderRecepientLegalEntity(clas, varib, fName, sName, doc, phone, email, contFirstName, contSecName) {
            varib =
                '<div>' +
                '   <span class="name">наименование:</span>' +
                '   <span>"' + fName + '"</span>' +
                '   <span>' + sName + '</span>' +
                '</div>' +
                '<div>' +
                '   <span class="name">номер:</span>' +
                '   <span>' + doc + '</span>' +
                '</div>' +
                '<div>' +
                '   <span class="name">телефон:</span>' +
                '   <span>' + phone + '</span>' +
                '</div>' +
                '<div>' +
                '   <span class="name">email:</span>' +
                '   <span>' + email + '</span>' +
                '</div>' +
                '<div>' +
                '   <span class="name">контактное лицо:</span>' +
                '   <span>' + contFirstName + '</span>' +
                '   <span>' + contSecName + '</span>' +
                '</div>';
            $(clas).append(varib);
        }

        if (data.orderInfo.orderSenderLegalEntity == 0) { // физлицо
            senderRecepient('.sender', $sender, data.orderInfo.orderSenderFullName, data.orderInfo.orderSenderDocumentType, data.orderInfo.orderSenderPhone, data.orderInfo.orderSenderEmail);
        } else {
            senderRecepientLegalEntity('.sender', $senderLegal, data.orderInfo.orderSenderCompanyForm, data.orderInfo.orderSenderCompanyName, data.orderInfo.orderSenderCompanyInn, data.orderInfo.orderSenderCompanyPhone, data.orderInfo.orderSenderCompanyEmail, data.orderInfo.orderSenderCompanyContactFirstName, data.orderInfo.orderSenderCompanyContactSecondName);
        }

        if (data.orderInfo.orderRecepientLegalEntity == 0) { // физлицо
            senderRecepient('.recepient', $recepient, data.orderInfo.orderRecepientFullName, data.orderInfo.orderRecepientDocumentType, data.orderInfo.orderRecepientPhone, data.orderInfo.orderRecepientEmail);
        } else {
            senderRecepientLegalEntity('.recepient', $recepientLegal, data.orderInfo.orderRecepientCompanyForm, data.orderInfo.orderRecepientCompanyName, data.orderInfo.orderRecepientCompanyInn, data.orderInfo.orderRecepientCompanyPhone, data.orderInfo.orderRecepientCompanyEmail, data.orderInfo.orderRecepientCompanyContactFirstName, data.orderInfo.orderRecepientCompanyContactSecondName);
        }

        // === Инфо о грузе === //
        $('#cargo_name').html(data.orderInfo.orderGoodsName);

        for (let index in data.dangerClasses) {
            var dataDangers = '<option value="' + index + '">' + data.dangerClasses[index] + '</option>';
            $('#order_danger').append(dataDangers);
            if (index == data.orderInfo.orderDangerClassId) {
                $('#order_danger').val(index);
            }
        }
        for (let index in data.temperatureModes) {
            var dataTemperatureModes = '<option value="' + index + '">' + data.temperatureModes[index] + '</option>';
            $('#order_temperature').append(dataTemperatureModes);
            if (index == data.orderInfo.orderTemperatureModeId) {
                $('#order_temperature').val(index);
            }
        }
        $('#order_temperature_mode').html(data.orderInfo.orderTemperatureModeName);
        $('#cargo_method').html(data.orderInfo.cargoMethod);
        $('#cargo_length').html(data.orderInfo.cargoLength + ' м');
        $('#cargo_width').html(data.orderInfo.cargoWidth + ' м');
        $('#cargo_height').html(data.orderInfo.cargoHeight + ' м');
        $('#cargo_vol').html(data.orderInfo.cargoVol);
        $('#cargo_vol_unit').html(data.orderInfo.cargoVolUnitName);
        $('#cargo_weight').html(data.orderInfo.cargoWeight);
        $('#cargo_weight_unit').html(data.orderInfo.cargoWeightUnitName);
        $('#cargo_value').html(data.orderInfo.cargoValue);
        $('#cargo_price').html(data.orderInfo.cargoPrice);

        for (let index in data.payTypes) {
            var payTypes = '<option value="' + index + '">' + data.payTypes[index] + '</option>';
            $('#payment_type').append(payTypes);
            if (index == data.orderInfo.paymentTypeID) {
                $('#payment_type').val(index);
            }
        }
        $('#payer_type').html(data.orderInfo.payerTypeName);

        // === Статус заказа === //
        for (let index in data.orderStates) {
            var orderStates = '<option value="' + index + '">' + data.orderStates[index] + '</option>';
            $('#status_order').append(orderStates);
            if (parseInt(index) === parseInt(data.orderInfo.orderStatus)) {
                $('#status_order').val(index);

                if (parseInt(data.orderInfo.orderStatus) === 0) { // ожидает обработки check_waiting
                    $('#status_check').addClass('check_waiting');
                } else if (parseInt(data.orderInfo.orderStatus) === 1) { // в работе check_in_working
                    $('#status_check').addClass('check_in_working');
                } else if (parseInt(data.orderInfo.orderStatus) === 2) { // исполнена check_executed
                    $('#status_check').addClass('check_executed');
                } else if (parseInt(data.orderInfo.orderStatus) === 3) { // отклонена rejected
                    $('#status_check').addClass('check_rejected');
                }
            }
        }

        // === Статус оплаты === //
        $isOrderPayed = parseInt(data.orderInfo.isOrderPayed);
        if (data.lists.payments !== undefined && data.lists.payments !== null && data.lists.payments.length > 0) {
            for (let i = 0; i < data.lists.payments.length; i++) {
                let $orderStatusPayment = '<option value="' + data.lists.payments[i].id + '">' + data.lists.payments[i].name + '</option>';
                $('#status_payment').append($orderStatusPayment);
                if (parseInt(data.lists.payments[i].id) === parseInt(data.orderInfo.isOrderPayed)) {
                    $('#status_payment').val(data.lists.payments[i].id)

                    if (parseInt(data.orderInfo.isOrderPayed) === 0) {
                        $('#status_payment_check').addClass('check_warn');
                    } else if (parseInt(data.orderInfo.isOrderPayed) === 1) {
                        $('#status_payment_check').addClass('check_mark');
                    }
                }
            }
        }

        $('#real_cost').attr('disabled', true).val(data.orderInfo.orderOrigPrice); // деактивировать реальную стоимость
        $('#paid_arrear').attr('disabled', true); // деактивировать задолженость

        let $orderPrice = parseFloat(data.orderInfo.cargoPrice);
        let $orderPaid = parseFloat(data.orderInfo.orderPaid);
        let $paidArrear = $orderPrice - $orderPaid; // Math.round($result * 100) / 100

        if (parseInt(data.orderInfo.isOrderPayed) === 0) { // не оплачен
            $('#cost').val($orderPrice);
            $('#paid_cost').val($orderPaid);
            $('#paid_arrear').val(Math.round($paidArrear * 100) / 100);
        } else if (parseInt(data.orderInfo.isOrderPayed) === 1) { // оплачен
            $('#cost, #paid_cost').val($orderPrice);
            $('#paid_arrear').val('0.00');
        }

        for (var i = 0; i < data.currencies.length; i++) {
            var $cur_option = '<option value="' + data.currencies[i] + '">' + data.currencies[i] + '</option>';
            $('#currency').append($cur_option);
        }
        preloader(false);
    }
});

// === Смена статуса заказа === //
$('body').on('change', '#status_order', function () {
    var $thisVal = parseInt($(this).val());
    if ($thisVal === 0) { // ожидает обработки check_waiting
        $('#status_check').removeClass('check_in_working check_executed check_rejected').addClass('check_waiting');
    } else if ($thisVal === 1) { // в работе check_in_working
        $('#status_check').removeClass('check_waiting check_executed check_rejected').addClass('check_in_working');
    } else if ($thisVal === 2) { // исполнена check_executed
        $('#status_check').removeClass('check_in_working check_waiting check_rejected').addClass('check_executed');
    } else if ($thisVal === 3) { // отклонена rejected
        $('#status_check').removeClass('check_in_working check_executed check_waiting').addClass('check_rejected');
    }
});

// === СМЕНА СТАТУСА ОПЛАТЫ === //
$('body').on('change', '#status_payment', function () {
    let $thisVal = parseInt($(this).val());
    if ($thisVal === 0) { // статус не оплачен
        $('#status_payment_check').removeClass('check_mark').addClass('check_warn');
    } else if ($thisVal === 1) { // статус оплачен
        $('#status_payment_check').removeClass('check_warn').addClass('check_mark');
    }
});

// === Сохранение заказа === //
$('body').on('click', '#btn-save-order', function () {
    $('#modal_window_bg').fadeIn(400);
});

// === Модальное окно подтверждения === //
$('body').on('click', '#btn-modal-cancel', function () {
    $('#modal_window_bg').fadeOut(400);
});
$('body').on('click', '#btn-modal-confirm', function () {
    saveOrderForm();
    $('#modal_window_bg').fadeOut(400);
});

function saveOrderForm() {
    let cargoPaid = parseFloat($('#paid_cost').val());
    let cargoPrice = parseFloat($('#cost').val());
    let orderTemperatureModeId = parseInt($('#order_temperature').val());
    let orderDangerClassId = parseInt($('#order_danger').val());
    let paymentTypeID = parseInt($('#payment_type').val());
    let ID = parseInt($('#order_num').html());
    let orderStatus = parseInt($('#status_order').val());
    let isOrderPayed = parseInt($('#status_payment').val());

    let saveOrder = $.extend(true, {}, sendModel);
    saveOrder.data.order = {};
    saveOrder.data.order.cargoPrice = cargoPrice;
    saveOrder.data.order.orderTemperatureModeId = orderTemperatureModeId;
    saveOrder.data.order.orderDangerClassId = orderDangerClassId;
    saveOrder.data.order.paymentTypeID = paymentTypeID;
    saveOrder.data.order.id = ID;
    saveOrder.data.order.orderStatus = orderStatus;
    saveOrder.data.order.isOrderPayed = isOrderPayed;
    if ($isOrderPayed === 0) {
        saveOrder.data.order.orderPaid = cargoPaid;
    }

    // console.log(cargoPaid);

    $.ajax({
        type: "POST",
        url: "/backend/adm_save_order.php",
        contentType: "application/json",
        headers: $headers,
        data: JSON.stringify(saveOrder),
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
                    $('.warning_window_true').fadeOut(300);
                    window.location.reload();
                }, 5000);
            } else {
                serverError();
            }
            preloader(false);
        }
    });
}

// === Ввод только цифр === //
$('body').on('keypress', '.paymentBlock__input', function (event) {
    return !(/[А-Яа-яA-Za-z]/.test(String.fromCharCode(event.charCode)));
});


// === ОГРАНИЧЕНИЕ ВВОДА СКИДКИ ОТ 0 ДО 100 === //
$('body').on('blur', '.cost_inp', function () {
    $(this).val(parseFloat($(this).val()).toFixed(2)); // ввод числа до сотых
});


// === КАЛЬКУЛЯТОР СУММЫ ЗАКАЗА === //
$('body').on('keyup', '#cost, #paid_cost', function () {
    amountOrder();
});

function amountOrder(cost, paid) {
    let $cost = $('#cost').val();
    let $paid = $('#paid_cost').val();
    if($cost !== "" && $paid !== "") {
        let $result = parseFloat($cost) - parseFloat($paid);
        $('#paid_arrear').val(Math.round($result * 100) / 100);
    } else {
        $('#paid_arrear').val($cost);
    }
    return '';
}

