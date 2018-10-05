// === СКИДКА/СТАТИСТИКА === //
// === Вернуться на предыдущую страницу === //
$('body').on('click', '#btn_back_discounts_statistics', function () {
    window.history.back();
});

// === ГЛОБАЛЬНЫЕ ПЕРЕМЕННЫЕ === //
var $arrDate = []; // массив дат
var $arrTotalSale = []; // массив общего объема продаж
var $arrTotalDiscount = []; // массив общего размера скидки

var $newDate = new Date();
var $todayDay = reverseDate(formatDate($newDate.setDate($newDate.getDate() + 1)));
var $yesterdayDay = reverseDate(formatDate($newDate.setDate($newDate.getDate() - 2)));
var $week = reverseDate(formatDate($newDate.setDate($newDate.getDate() - 5)));
var $month = reverseDate(formatDate($newDate.setDate($newDate.getDate() - 24)));
var $treeMonth = reverseDate(formatDate($newDate.setDate($newDate.getDate() - 60)));
// 86400 - один день, в секундах


// === ОСНОВНОЙ ЗАПРОС === //
var $sendModel = {
    "data": {
        "id": $userId,
        "defaultLang": $defaultLang,
        "defaultEmail": $defaultEmail,
        "dateStart": $week,
        "dateEnd": $todayDay
    }
};

$.ajax({
    type: "POST",
    url: "/backend/adm_get_discount_stat.php",
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

        let $lists = data.lists;

        // === ДОБАВЛЕНИЕ НАИМЕНОВАНИЯ СКИДКИ === //
        if ($lists.names !== undefined && $lists.names !== null && $lists.names.length > 0) {
            for (let i in $lists.names) {
                let $names = '<li class="ui-state-default active_discount_name" data-active-name="' + $lists.names[i].isActive + '">' + $lists.names[i].name + '</li>';
                $('#sortable_discount_name_true').append($names);
                $('#discount_name').append('<li>' + $lists.names[i].name + '</li>');
            }
        }

        // === ДОБАВЛЕНИЕ КАТЕГОРИЙ СКИДКИ === //
        if ($lists.categories !== undefined && $lists.categories !== null && $lists.categories.length > 0) {
            for (let i in $lists.categories) {
                let $category = '<option value="' + $lists.categories[i].id + '">' + $lists.categories[i].name + '</option>';
                $('#discount_category').append($category);
            }
        }

        // === ДОБАВЛЕНИЕ ПРОМОКОДА === //
        if ($lists.promos !== undefined && $lists.promos !== null && $lists.promos.length > 0) {
            for (let i in $lists.promos) {
                let $promos = '<li class="ui-state-default active_promo" data-active-promo="' + $lists.promos[i].isActive + '">' + $lists.promos[i].code + '</li>';
                $('#sortable_promo_true').append($promos);
                $('#discount_promo_code').append('<li>' + $lists.promos[i].code + '</li>');
            }
        }

        // === ДОБАВЛЕНИЕ ЛОКАЦИИ === //
        if ($lists.countries !== undefined && $lists.countries !== null && $lists.countries.length > 0) {
            for (let i in $lists.countries) {
                let $countries = '<li class="ui-state-default active_location" id="' + $lists.countries[i].id + '">' + $lists.countries[i].name + '</li>';
                $('#sortable_location_true').append($countries);
                $('#discount_location').append('<li>"' + $lists.countries[i].id + '" - ' + $lists.countries[i].name + '</li>');
            }
        }

        // === ДОБАВЛЕНИЕ КОМПАНИИ === //
        if ($lists.companies !== undefined && $lists.companies !== null && $lists.companies.length > 0) {
            for (let i in $lists.companies) {
                let companies = '<li class="ui-state-default active_company" id="' + $lists.companies[i].id + '">' + $lists.companies[i].name + '</li>';
                $('#sortable_company_true').append(companies);
                $('#discount_company').append('<li>' + $lists.companies[i].name + '</li>');
            }
        }

        // === ДОБАВЛЕНИЕ ЗНАЧЕНИЙ В МАССИВ ДЛЯ ОТРИСОВКИ НА ГРАФИКЕ === //
        if (data.discountData !== undefined && data.discountData !== null && data.discountData.length > 0) {
            for (let i = 0; i < data.discountData.length; i++) {
                $arrDate.push(parseDateDiagram(data.discountData[i].date));
                $arrTotalSale.push(data.discountData[i].total);
                $arrTotalDiscount.push(data.discountData[i].totalDiscount);
            }
        }

        $('#discount_date_start').val(parseDateDiagram($week));
        $('#discount_date_end').val(parseDateDiagram($todayDay - 86400));

        discountsDiagram($arrDate, $arrTotalSale, $arrTotalDiscount);

        preloader(false);

    }
});

// === МАСКА ДЛЯ ВВОДА ДАТЫ === //
$("#discount_date_start, #discount_date_end").attr('placeholder', 'dd.mm.yyyy'); // .mask('00.00.0000')


// === DATE PICKER === //
$(function () {
    let dateFormat = "dd.mm.yy",
        from = $("#discount_date_start")
            .datepicker({
                maxDate: new Date(),
                changeMonth: true,
                changeYear: true
            })
            .on("change", function () {
                to.datepicker("option", "minDate", getDate(this));
            }),
        to = $("#discount_date_end").datepicker({
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


// === SORTABLE === //
$("#sortable_discount_name_true, #sortable_discount_name_false, #sortable_promo_true, #sortable_promo_false, #sortable_location_true, #sortable_location_false, #sortable_company_true, #sortable_company_false").sortable({
    connectWith: ".connectedSortable"
}).disableSelection();


// === ДОБАВИТЬ НАИМЕНОВАНИЕ СКИДКИ === //
$('body').on('click', '#add_discount_name', function () {
    $('#modal_window_add_discount_name').fadeIn(300);
});
// === подтверждение действий
$('body').on('click', '#btn_modal_discount_name_confirm', function () {
    $('#modal_window_add_discount_name').fadeOut(300);
    $('#discount_name').children().remove();

    let $formTrue = $('#sortable_discount_name_true');
    $formTrue.find('.active_discount_name').each(function () {
        let $addToList = '<li>' + $(this).html() + '</li>';
        $('#discount_name').append($addToList);
    });
});
// === отмена действий
$('body').on('click', '#btn_modal_discount_name_cancel', function () {
    $('#modal_window_add_discount_name').fadeOut(300);

    let $formTrue = $('#sortable_discount_name_true');
    $formTrue.find('.ui-state-highlight').each(function () {
        let $clone = $(this).clone();
        $('#sortable_discount_name_false').prepend($clone);
        $(this).remove();
    });

    let $formFalse = $('#sortable_discount_name_false');
    $formFalse.find('.ui-state-default').each(function () {
        let $clone = $(this).clone();
        $('#sortable_discount_name_true').prepend($clone);
        $(this).remove();
    });

    // === добавление новых локаций в список
    $('#discount_name').children().remove();
    let $add = $('#sortable_discount_name_true');
    $add.find('.active_discount_name').each(function () {
        $('#discount_name').append('<li>' + $(this).html() + '</li>');
    });
});
// === добавить все
$('body').on('click', '#btn_all_discount_name', function () {
    let $formFalse = $('#sortable_discount_name_false');
    $formFalse.find('.active_discount_name').each(function () {
        let $clone = $(this).clone();
        $('#sortable_discount_name_true').prepend($clone);
        $(this).remove();
    });
});
// === удалить все
$('body').on('click', '#btn_delete_all_discount_name', function () {
    let $formTrue = $('#sortable_discount_name_true');
    $formTrue.find('.active_discount_name').each(function () {
        let $clone = $(this).clone();
        $('#sortable_discount_name_false').prepend($clone);
        $(this).remove();
    });
});

// === поиск по неактивным именам скидки
$('body').on('keyup', '#input_search_discount_name', function (event) {
    if (event.keyCode === 13) {
        $('#btn_search_discount_name').click();
    }
});

$('body').on('click', '#btn_search_discount_name', function () {
    let $inpVal = $('#input_search_discount_name').val().toLowerCase();

    // проходим циклом по элементам li
    $('#sortable_discount_name_false').children().each(function () {
        let $elemText = $(this).html().toLowerCase();
        searchToModalWindow($(this), $elemText, $inpVal);
    });
});


// === ДОБАВИТЬ ПРОМОКОД === //
$('body').on('click', '#add_discount_promo', function () {
    $('#modal_window_add_promo').fadeIn(300);
});
// === подтверждение действий
$('body').on('click', '#btn_modal_promo_confirm', function () {
    $('#modal_window_add_promo').fadeOut(300);
    $('#discount_promo_code').children().remove();

    let $formTrue = $('#sortable_promo_true');
    $formTrue.find('.active_promo').each(function () {
        let $addToList = '<li>' + $(this).html() + '</li>';
        $('#discount_promo_code').append($addToList);
    });
});
// === отмена действий
$('body').on('click', '#btn_modal_promo_cancel', function () {
    $('#modal_window_add_promo').fadeOut(300);

    let $formTrue = $('#sortable_promo_true');
    $formTrue.find('.ui-state-highlight').each(function () {
        let $clone = $(this).clone();
        $('#sortable_promo_false').prepend($clone);
        $(this).remove();
    });

    let $formFalse = $('#sortable_promo_false');
    $formFalse.find('.ui-state-default').each(function () {
        let $clone = $(this).clone();
        $('#sortable_promo_true').prepend($clone);
        $(this).remove();
    });

    // === добавление новых локаций в список
    $('#discount_promo_code').children().remove();
    let $add = $('#sortable_promo_true');
    $add.find('.active_promo').each(function () {
        $('#discount_promo_code').append('<li>' + $(this).html() + '</li>');
    });
});
// === добавить все
$('body').on('click', '#btn_all_promo', function () {
    let $formFalse = $('#sortable_promo_false');
    $formFalse.find('.active_promo').each(function () {
        let $clone = $(this).clone();
        $('#sortable_promo_true').prepend($clone);
        $(this).remove();
    });
});
// === удалить все
$('body').on('click', '#btn_delete_all_promo', function () {
    let $formTrue = $('#sortable_promo_true');
    $formTrue.find('.active_promo').each(function () {
        let $clone = $(this).clone();
        $('#sortable_promo_false').prepend($clone);
        $(this).remove();
    });
});

// === поиск по неактивным промокодам
$('body').on('keyup', '#input_search_promo', function (event) {
    if (event.keyCode === 13) {
        $('#btn_search_promo').click();
    }
});

$('body').on('click', '#btn_search_promo', function () {
    let $inpVal = $('#input_search_promo').val().toLowerCase();

    // проходим циклом по элементам li
    $('#sortable_promo_false').children().each(function () {
        let $elemText = $(this).html().toLowerCase();
        searchToModalWindow($(this), $elemText, $inpVal);
    });
});


// === ДОБАВИТЬ ЛОКАЦИЮ === //
$('body').on('click', '#add_discount_location', function () {
    $('#modal_window_add_location').fadeIn(300);
});
// === подтверждение действий
$('body').on('click', '#btn_modal_location_confirm', function () {
    $('#modal_window_add_location').fadeOut(300);
    $('#discount_location').children().remove();

    let $formTrue = $('#sortable_location_true');
    $formTrue.find('.active_location').each(function () {
        let $addToList = '<li>"' + $(this).attr('id') + '" - ' + $(this).html() + '</li>';
        $('#discount_location').append($addToList);
    });
});
// === отмена действий
$('body').on('click', '#btn_modal_location_cancel', function () {
    $('#modal_window_add_location').fadeOut(300);

    let $formTrue = $('#sortable_location_true');
    $formTrue.find('.ui-state-highlight').each(function () {
        let $clone = $(this).clone();
        $('#sortable_location_false').prepend($clone);
        $(this).remove();
    });

    let $formFalse = $('#sortable_location_false');
    $formFalse.find('.ui-state-default').each(function () {
        let $clone = $(this).clone();
        $('#sortable_location_true').prepend($clone);
        $(this).remove();
    });

    // === добавление новых локаций в список
    $('#discount_location').children().remove();
    let $add = $('#sortable_location_true');
    $add.find('.active_location').each(function () {
        $('#discount_location').append('<li>"' + $(this).attr('id') + '" - ' + $(this).html() + '</li>');
    });
});
// === добавить все
$('body').on('click', '#btn_all_location', function () {
    let $formFalse = $('#sortable_location_false');
    $formFalse.find('.active_location').each(function () {
        let $clone = $(this).clone();
        $('#sortable_location_true').prepend($clone);
        $(this).remove();
    });
});
// === удалить все
$('body').on('click', '#btn_delete_all_location', function () {
    let $formTrue = $('#sortable_location_true');
    $formTrue.find('.active_location').each(function () {
        let $clone = $(this).clone();
        $('#sortable_location_false').prepend($clone);
        $(this).remove();
    });
});

// === поиск по неактивным локациям
$('body').on('keyup', '#input_search_location', function (event) {
    if (event.keyCode === 13) {
        $('#btn_search_location').click();
    }
});

$('body').on('click', '#btn_search_location', function () {
    let $inpVal = $('#input_search_location').val().toLowerCase();

    // проходим циклом по элементам li
    $('#sortable_location_false').children().each(function () {
        let $elemText = $(this).html().toLowerCase();
        searchToModalWindow($(this), $elemText, $inpVal);
    });
});


// === ДОБАВИТЬ КОМПАНИЮ === //
$('body').on('click', '#add_discount_company', function () {
    $('#modal_window_add_company').fadeIn(300);
});
// === подтверждение действий
$('body').on('click', '#btn_modal_company_confirm', function () {
    $('#modal_window_add_company').fadeOut(300);
    $('#discount_company').children().remove();

    let $formTrue = $('#sortable_company_true');
    $formTrue.find('.active_company').each(function () {
        let $addToList = '<li>' + $(this).html() + '</li>';
        $('#discount_company').append($addToList);
    });
});
// === отмена действий
$('body').on('click', '#btn_modal_company_cancel', function () {
    $('#modal_window_add_company').fadeOut(300);

    let $formTrue = $('#sortable_company_true');
    $formTrue.find('.ui-state-highlight').each(function () {
        let $clone = $(this).clone();
        $('#sortable_company_false').prepend($clone);
        $(this).remove();
    });

    let $formFalse = $('#sortable_company_false');
    $formFalse.find('.ui-state-default').each(function () {
        let $clone = $(this).clone();
        $('#sortable_company_true').prepend($clone);
        $(this).remove();
    });

    // === добавление новых локаций в список
    $('#discount_company').children().remove();
    let $add = $('#sortable_company_true');
    $add.find('.active_company').each(function () {
        $('#discount_company').append('<li>' + $(this).html() + '</li>');
    });
});
// === добавить все
$('body').on('click', '#btn_all_company', function () {
    let $formFalse = $('#sortable_company_false');
    $formFalse.find('.active_company').each(function () {
        let $clone = $(this).clone();
        $('#sortable_company_true').prepend($clone);
        $(this).remove();
    });
});
// === удалить все
$('body').on('click', '#btn_delete_all_company', function () {
    let $formTrue = $('#sortable_company_true');
    $formTrue.find('.active_company').each(function () {
        let $clone = $(this).clone();
        $('#sortable_company_false').prepend($clone);
        $(this).remove();
    });
});

// === поиск по неактивным компаниям
$('body').on('keyup', '#input_search_company', function (event) {
    if (event.keyCode === 13) {
        $('#btn_search_company').click();
    }
});

$('body').on('click', '#btn_search_company', function () {
    let $inpVal = $('#input_search_company').val().toLowerCase();

    // проходим циклом по элементам li
    $('#sortable_company_false').children().each(function () {
        let $elemText = $(this).html().toLowerCase();
        searchToModalWindow($(this), $elemText, $inpVal);
    });
});


// === ПОИСК В МОДАЛЬНОМ ОКНЕ === //
function searchToModalWindow(elem, elemText, value) {
    if (elemText.indexOf(value) >= 0) {
        elem.fadeIn();
    } else {
        elem.fadeOut();
    }
    return false;
}


// === ПОСТРОЕНИЕ ГРАФИКА === //
function discountsDiagram(typeGrouped, totalSale, totalDiscount) {
    $('.discounts_chart_block').children().remove();

    let $canvas = '<canvas id="discounts_statistics"></canvas>';
    $('.discounts_chart_block').append($canvas);

    let $ctxDiagram = document.getElementById('discounts_statistics').getContext('2d');
    let $discountsStatistics = new Chart($ctxDiagram, {
        type: 'bar', // doughnut, pie
        data: {
            labels: typeGrouped,
            datasets: [
                {
                    label: 'Общий объем продаж',
                    data: totalSale,
                    backgroundColor: '#7FD0FF'
                },
                {
                    label: 'Общий размер скидки',
                    backgroundColor: '#B0EB99',
                    data: totalDiscount,
                    type: 'bar'
                }]
        },
        options: {}
    });
}


// === ВЫВОД ИНФЫ ЗА ОПРЕДЕЛЕНЫЙ ПЕРИОД И ОТРИСОВКА ГРАФИКА === //
$('body').on('click', '#discount_today_statistics', function () {
    statistics($todayDay - 86400, $todayDay);
});

$('body').on('click', '#discount_yesterday_statistics', function () {
    statistics($yesterdayDay, $todayDay - 86400);
});

$('body').on('click', '#discount_week_statistics', function () {
    statistics($week, $todayDay);
});

$('body').on('click', '#discount_month_statistics', function () {
    statistics($month, $todayDay);
});

$('body').on('click', '#discount_three_month_statistics', function () {
    statistics($treeMonth, $todayDay);
});

function statistics(startDate, endDate) {
    $sendModel.data.dateStart = startDate;
    $sendModel.data.dateEnd = endDate;

    // console.log($sendModel);

    $.ajax({
        type: "POST",
        url: "/backend/adm_get_discount_stat.php",
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

            // console.log(data);

            // === ДОБАВЛЕНИЕ ЗНАЧЕНИЙ В МАССИВ ДЛЯ ОТРИСОВКИ НА ГРАФИКЕ === //
            let $arrDate = [];
            let $arrTotalSale = [];
            let $arrTotalDiscount = [];
            if (data.discountData !== undefined && data.discountData !== null && data.discountData.length > 0) {
                for (let i = 0; i < data.discountData.length; i++) {
                    $arrDate.push(parseDateDiagram(data.discountData[i].date));
                    $arrTotalSale.push(data.discountData[i].total);
                    $arrTotalDiscount.push(data.discountData[i].totalDiscount);
                }
            }

            discountsDiagram($arrDate, $arrTotalSale, $arrTotalDiscount);

            preloader(false);
        }
    });
}


// === СБРОСИТЬ ФИЛЬТР === //
$('body').on('click', '#btn_reset_filter', function () {
    window.location.reload();
});


// === ФИЛЬТР С ЗАПРОСОМ ДЛЯ ПОСТРОЕНИЯ ГРАФИКА === //
$('body').on('click', '#btn_use_filter', function () {

    let $arrNameDiscount = [];
    let $arrPromo = [];
    let $arrCompany = [];
    let $arrLocation = [];

    // === Собираем активные имена скидок === //
    let $formNameDiscount = $('#sortable_discount_name_true');
    if ($formNameDiscount !== undefined && $formNameDiscount.length > 0) {
        $formNameDiscount.find('.active_discount_name').each(function () {
            let $nameDiscount = $(this).html();
            $arrNameDiscount.push($nameDiscount);
        });
    }

    // === Собираем категорию скидки === //
    let $discountCategory = parseFloat($('#discount_category').val());

    // === Собираем активные промокоды === //
    let $formPromo = $('#sortable_promo_true');
    if ($formPromo !== undefined && $formPromo.length > 0) {
        $formPromo.find('.active_promo').each(function () {
            let $promoCode = $(this).html();
            $arrPromo.push($promoCode);
        });
    }

    // === Собираем активные локации === //
    let $formLocation = $('#sortable_location_true');
    if ($formLocation !== undefined && $formLocation.length > 0) {
        $formLocation.find('.active_location').each(function () {
            let $locationId = $(this).attr('id');
            $arrLocation.push($locationId);
        });
    }

    // === Собираем активные компании === //
    let $formCompany = $('#sortable_company_true');
    if ($formCompany !== undefined && $formCompany.length > 0) {
        $formCompany.find('.active_company').each(function () {
            let $companyId = parseFloat($(this).attr('id'));
            $arrCompany.push($companyId);
        });
    }

    // === Собираем дату скидки === //
    let $startDate, $endDate;
    ($('#discount_date_start').val() !== '') ? $startDate = reverseDate($('#discount_date_start').val()) : $startDate = '';
    ($('#discount_date_end').val() !== '') ? $endDate = reverseDate($('#discount_date_end').val()) : $endDate = '';

    let $saveDiscount = $.extend(true, {}, $sendModel);
    $saveDiscount.data.dateStart = $startDate;
    $saveDiscount.data.dateEnd = $endDate;
    $saveDiscount.data.filter = {};
    $saveDiscount.data.filter.names = $arrNameDiscount;
    $saveDiscount.data.filter.categories = $discountCategory;
    $saveDiscount.data.filter.promos = $arrPromo;
    $saveDiscount.data.filter.countries = $arrLocation;
    $saveDiscount.data.filter.companies = $arrCompany;

    // console.log($saveDiscount);

    $.ajax({
        type: "POST",
        url: "/backend/adm_get_discount_stat.php",
        contentType: "application/json",
        headers: $headers,
        data: JSON.stringify($saveDiscount),
        beforeSend: function () {
            preloader(true);
        },
        error: function () {
            preloader(false);
            serverError();
        },
        success: function (data) {

            // console.log(data);

            // === ДОБАВЛЕНИЕ ЗНАЧЕНИЙ В МАССИВ ДЛЯ ОТРИСОВКИ НА ГРАФИКЕ === //
            let $arrDate = [];
            let $arrTotalSale = [];
            let $arrTotalDiscount = [];
            if (data.discountData !== undefined && data.discountData !== null && data.discountData.length > 0) {
                for (let i = 0; i < data.discountData.length; i++) {
                    $arrDate.push(parseDateDiagram(data.discountData[i].date));
                    $arrTotalSale.push(data.discountData[i].total);
                    $arrTotalDiscount.push(data.discountData[i].totalDiscount);
                }
            }

            discountsDiagram($arrDate, $arrTotalSale, $arrTotalDiscount);

            preloader(false);

        }
    });

});












