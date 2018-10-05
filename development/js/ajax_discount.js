// === СОЗДАНИЕ НОВОЙ СКИДКИ === //
var $searchId = location.search.substring(1, 3),
    $discountId = '',
    $clientId = '',
    $isActiveDiscount = '',
    $startDateDiscount = '',
    $endDateDiscount = '';


// === ПРИСВАИВАЕМ ID === //
if ($searchId === 'id') {
    $discountId = parseFloat(location.search.substring(4));
} else if ($searchId === 'ui') {
    let $url = location.search.substring(1);

    let $parametr = $url.split("&");

    let $arrParametrs = [];

    for (let i in $parametr) {
        let j = $parametr[i].split("=");
        $arrParametrs[j[0]] = unescape(j[1]);
    }

    for (let i in $arrParametrs) {
        if ($arrParametrs.ui !== undefined && $arrParametrs.ui !== null && $arrParametrs.ui.length > 0) {
            $clientId = parseFloat($arrParametrs.ui);
        } else {
            $clientId = '';
        }
        if ($arrParametrs.di !== undefined && $arrParametrs.di !== null && $arrParametrs.di.length > 0) {
            $discountId = parseFloat($arrParametrs.di);
        } else {
            $discountId = '';
        }
    }

    $('.category_remove').remove();
    $('#btn_delete_discount, #user_name').fadeIn();
    $('#btn_archive_discount, #btn_activate_discount').fadeOut();
}


// === ПРОВЕРКА НА АКТИВНЫЕ СКИДКИ === //
if ($discountId === '') {
    $('#btn_save_discount, #btn_archive_discount, #btn_activate_discount, #btn_delete_discount').css('display', 'none');
} else {
    $('#btn_create_discount').css('display', 'none');
}


// === ВЕРНУТЬСЯ НА ПРЕДЫДУЩУЮ СТРАНИЦУ === //
$('body').on('click', '#btn_back_discounts', function () {
    window.history.back();
});


var $sendModel = {
    "data": {
        "id": $userId,
        "defaultLang": $defaultLang,
        "defaultEmail": $defaultEmail,
        "discountId": $discountId
    }
};

$.ajax({
    type: "POST",
    url: "/backend/adm_get_discount.php",
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

        $('#user_name').html(data.discount.userName);

        if (data.discount.isActive === true) {
            $('#btn_activate_discount').prop('disabled', true);
        } else {
            $('#btn_archive_discount').prop('disabled', true);
        }

        $isActiveDiscount = data.discount.isActive; // активность дисконта

        if ($isActiveDiscount === true) {
            let $isActive = '<span class="check_mark"></span> Скидка активна';
            $('#active_discount').append($isActive);
        } else {
            let $isActive = '<span class="check_warn"></span> Скидка неактивна';
            $('#active_discount').append($isActive);
        }

        $('#name_discount').val(data.discount.discountName).attr('title', data.discount.discountName); // имя дисконта

        if (data.lists.categories !== undefined && data.lists.categories.length > 0) { // категории
            for (let i in data.lists.categories) {
                let $categoryDiscount = '<option value="' + data.lists.categories[i].id + '" ' + selectedCategory(data.lists.categories[i].id) + '>' + data.lists.categories[i].name + '</option>';
                $('#category_discount').append($categoryDiscount);
            }

            function selectedCategory(id) {
                if (id == data.discount.discountCategory) {
                    return 'selected';
                }
                return '';
            }
        }

        if (data.discount.usePromo == true) {
            $('#promo_code_check').prop('checked', true);
        }
        $('#promo_code_discount').val(data.discount.promo); // промокод
        $('#size_discount').val(data.discount.value); // размер
        $('#location_discount').val(); // локация
        $('#transport_discount').val(); // транспорт
        $startDateDiscount = data.discount.dateStart;
        $('#start_date_discount').val(parseDateDiagram($startDateDiscount)); // начало действия
        $endDateDiscount = data.discount.dateEnd;
        $('#end_date_discount').val(parseDateDiagram($endDateDiscount)); // окончание действия

        if (data.discount.isForever === true) { // окончание скидки
            $('#check_end_date_discount').attr('checked', true);
            $('#end_date_discount').attr('disabled', true).val('');
        }


        // === ДОБАВЛЕНИЕ ЛОКАЦИИ === //
        // === Неактивные страны === //
        if (data.lists.countries !== undefined && data.lists.countries.length > 0) {
            for (let i in data.lists.countries) {
                let $locationFalse = '<li class="ui-state-highlight' + ' ' + disabledLocation(data.lists.countries[i].id) + '" id="' + data.lists.countries[i].id + '">' + data.lists.countries[i].name + '</li>';
                $('#sortable_location_false').append($locationFalse);
            }

            function disabledLocation(id) {
                if (data.discount.countries !== undefined && data.discount.countries.length > 0) {
                    for (let i in data.discount.countries) {
                        if (id == data.discount.countries[i]) {
                            return 'ui-state-disabled';
                        }
                    }
                }
                return 'active_countries';
            }
        }


        // === Активные страны === //
        if (data.discount.countries !== undefined && data.discount.countries.length > 0) {
            for (let i in data.discount.countries) {
                let $locationTrue = '<li class="ui-state-default active_countries" id="' + data.discount.countries[i] + '">' + addedLocations(data.discount.countries[i]) + '</li>';
                $('#sortable_location_true').append($locationTrue);
                let $location = '<li>"' + data.discount.countries[i] + '" - ' + addedLocations(data.discount.countries[i]) + '</li>';
                $('#location_discount').append($location);
            }
        }

        function addedLocations(id) {
            if (data.lists.countries !== undefined && data.lists.countries.length > 0) {
                for (let i in data.lists.countries) {
                    if (id == data.lists.countries[i].id) {
                        return data.lists.countries[i].name;
                    }
                }
            }
            return '';
        }


        // === ДОБАВЛЕНИЕ КОМПАНИИ === //
        // === Неактивные компании === //
        if (data.lists.companies !== undefined && data.lists.companies.length > 0) {
            for (let i in data.lists.companies) {
                let $companyFalse = '<li class="ui-state-highlight' + ' ' + disabledCompany(data.lists.companies[i].id) + '" id="' + data.lists.companies[i].id + '">' + data.lists.companies[i].name + '</li>';
                $('#sortable_company_false').append($companyFalse);
            }

            function disabledCompany(id) {
                if (data.discount.companies !== undefined && data.discount.companies.length > 0) {
                    for (let i in data.discount.companies) {
                        if (id == data.discount.companies[i]) {
                            return 'ui-state-disabled';
                        }
                    }
                }
                return 'active_companies';
            }
        }


        // === Активные компании === //
        if (data.discount.companies !== undefined && data.discount.companies.length > 0) {
            for (let i in data.discount.companies) {
                let $companyTrue = '<li class="ui-state-default active_companies" id="' + data.discount.companies[i] + '">' + addedCompanies(data.discount.companies[i]) + '</li>';
                $('#sortable_company_true').append($companyTrue);
                let $company = '<li>' + addedCompanies(data.discount.companies[i]) + '</li>';
                $('#transport_discount').append($company);
            }
        }

        function addedCompanies(id) {
            if (data.lists.companies !== undefined && data.lists.companies.length > 0) {
                for (let i in data.lists.companies) {
                    if (id == data.lists.companies[i].id) {
                        return data.lists.companies[i].name;
                    }
                }
            }
            return '';
        }


        preloader(false);
    }
});


// === АКТИВИРОВАТЬ СКИДКУ === //
$('body').on('click', '#btn_activate_discount', function () {
    isValidForm('#modal_window_activate_discount');
});
$('body').on('click', '#btn_activate_confirm', function () {
    activateDiscount(true);
    $('#modal_window_activate_discount').fadeOut(300);
});
$('body').on('click', '#btn_activate_cancel', function () {
    $('#modal_window_activate_discount').fadeOut(300);
});


// === ДОБАВИТЬ СКИДКУ В АРХИВ === //
$('body').on('click', '#btn_archive_discount', function () {
    $('#modal_window_archive_discount').fadeIn(300);
});
$('body').on('click', '#btn_archive_confirm', function () {
    activateDiscount(false);
    $('#modal_window_archive_discount').fadeOut(300);
});
$('body').on('click', '#btn_archive_cancel', function () {
    $('#modal_window_archive_discount').fadeOut(300);
});

function activateDiscount(isActive) {

    let $activateDiscount = $.extend(true, {}, $sendModel);
    $activateDiscount.data.isActive = isActive;

    console.log($activateDiscount);

    $.ajax({
        type: "POST",
        url: "/backend/adm_set_discount_status.php",
        contentType: "application/json",
        headers: $headers,
        data: JSON.stringify($activateDiscount),
        beforeSend: function () {
            preloader(true);
        },
        error: function () {
            preloader(false);
            serverError();
        },
        success: function (data) {

            console.log(data);

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


// === УДАЛИТЬ СКИДКУ === //
$('body').on('click', '#btn_delete_discount', function () {
    $('#modal_window_delete_discount').fadeIn(300);
});
$('body').on('click', '#btn_delete_confirm', function () {

    $.ajax({
        type: "POST",
        url: "/backend/adm_remove_discount.php",
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

            if (data.status === 'good') {
                $('<div class="discount_delete">Скидка удалена!</div>').replaceAll('.discount');
                setTimeout(function () {
                    window.history.back();
                }, 3000);
            } else {
                serverError();
            }

            preloader(false);

        }
    });

    $('#modal_window_delete_discount').fadeOut(300);

});
$('body').on('click', '#btn_delete_cancel', function () {
    $('#modal_window_delete_discount').fadeOut(300);
});


// === СОЗДАТЬ СКИДКУ === //
$('body').on('click', '#btn_create_discount', function () {
    isValidForm('#modal_window_create_discount');
});
$('body').on('click', '#btn_create_confirm', function () {
    saveDiscount(false);
    $('#modal_window_create_discount').fadeOut(300);
});
$('body').on('click', '#btn_create_cancel', function () {
    $('#modal_window_create_discount').fadeOut(300);
});


// === СОХРАНИТЬ СКИДКУ === //
$('body').on('click', '#btn_save_discount', function () {
    isValidForm('#modal_window_save_discount');
});
$('body').on('click', '#btn_save_confirm', function () {
    saveDiscount(true);
    $('#modal_window_save_discount').fadeOut(300);
});
$('body').on('click', '#btn_save_cancel', function () {
    $('#modal_window_save_discount').fadeOut(300);
});

function saveDiscount(save) {

    let $arrCountries = [];
    let $arrCompanies = [];

    // === Собираем активные компании === //
    let $formCompanies = $('#sortable_company_true');

    if ($formCompanies !== undefined && $formCompanies.length > 0) {
        $formCompanies.find('.active_companies').each(function () {
            let $companyId = parseFloat($(this).attr('id'));
            $arrCompanies.push($companyId);
        });
    }

    // === Собираем активные локации === //
    let $formCountries = $('#sortable_location_true');

    if ($formCountries !== undefined && $formCountries.length > 0) {
        $formCountries.find('.active_countries').each(function () {
            let $countryId = $(this).attr('id');
            $arrCountries.push($countryId);
        });
    }

    let $startDate, $endDate;
    ($('#start_date_discount').val() !== '') ? $startDate = reverseDate($('#start_date_discount').val()) : $startDate = '';
    ($('#end_date_discount').val() !== '') ? $endDate = reverseDate($('#end_date_discount').val()) : $endDate = '';

    let $saveDiscount = {
        "data": {
            "id": $userId,
            "defaultLang": $defaultLang,
            "defaultEmail": $defaultEmail,
            "discount": {
                "userId": $clientId,
                "id": $discountId,
                "discountName": $('#name_discount').val(),
                "usePromo": $('#promo_code_check').prop('checked'),
                "promo": $('#promo_code_discount').val(),
                "value": parseFloat($('#size_discount').val()),
                "dateStart": $startDate,
                "dateEnd": $endDate,
                "discountCategory": parseFloat($('#category_discount').val()),
                "companies": $arrCompanies,
                "countries": $arrCountries,
                "isForever": $('#check_end_date_discount').prop('checked')
            }
        }
    };

    // console.log($saveDiscount);

    $.ajax({
        type: "POST",
        url: "/backend/adm_save_discount.php",
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

            if (data.status === 'good') {
                $('.warning_window_true').css('display', 'inline-block');
                if (save === false) {
                    let $isActive = '<span class="check_mark"></span> Скидка активна';
                    $('#active_discount').html('').append($isActive);
                }
                setTimeout(function () {
                    $('.warning_window_true').fadeOut(300);
                    if (save === true) {
                        window.location.reload();
                    } else {
                        window.history.back();
                    }
                }, 5000);
            } else {
                serverError();
            }

            preloader(false);

        }
    });

}


// === ВАЛИДАЦИЯ ДАННЫХ СКИДКИ === //
function isValidForm(windowId) {
    let $valid = true;

    if (!nameDiscountValid()) {
        $valid = false;
    }
    if (!sizeDiscountValid()) {
        $valid = false;
    }
    // if (!locationValid()) {
    //     $valid = false;
    // }
    // if (!companyValid()) {
    //     $valid = false;
    // }
    if (!startDateValid()) {
        $valid = false;
    }

    if ($valid === false) {
        $('.warning_window_false').css('display', 'inline-block');
        setTimeout(function () {
            $('.warning_window_false').fadeOut(400);
        }, 5000);
    } else {
        $(windowId).fadeIn(300); // вызывает модальное окно
    }
}

function nameDiscountValid() {
    if ($('#name_discount').val() === '') {
        $('#name_discount').addClass('error_valid');
        return false;
    } else {
        return true;
    }
}

function sizeDiscountValid() {
    if ($('#size_discount').val() === '' || parseFloat($('#size_discount').val()) === 0) {
        $('#size_discount').addClass('error_valid');
        return false;
    } else {
        return true;
    }
}

function locationValid() {
    if ($('#location_discount').children().length === 0) {
        $('#location_discount').addClass('error_valid');
        return false;
    } else {
        return true;
    }
}

function companyValid() {
    if ($('#transport_discount').children().length === 0) {
        $('#transport_discount').addClass('error_valid');
        return false;
    } else {
        return true;
    }
}

function startDateValid() {
    if ($('#start_date_discount').val() === '') {
        $('#start_date_discount').addClass('error_valid');
        return false;
    } else {
        return true;
    }
}


$('.discount input').on('focus', function () {
    $(this).removeClass('error_valid');
});


// === СКИДКА БЕЗ СРОКА ДЕЙСТВИЯ === //
$('body').on('click', '#check_end_date_discount', function () {
    if ($(this).prop('checked') === true) {
        $('#end_date_discount').prop('disabled', true).val('');
    } else {
        $('#end_date_discount').prop('disabled', false).val('');
    }
});


// === МАСКА ДЛЯ ВВОДА ДАТЫ === //
$("#start_date_discount, #end_date_discount").attr('placeholder', 'dd.mm.yyyy'); // .mask('00.00.0000')


// === DATE PICKER === //
$(function () {
    let dateFormat = "dd.mm.yy",
        from = $("#start_date_discount")
            .datepicker({
                changeMonth: true,
                changeYear: true
            })
            .on("change", function () {
                to.datepicker("option", "minDate", getDate(this));
            }),
        to = $("#end_date_discount").datepicker({
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


// === ДОБАВИТЬ ДИСКОНТ ДЛЯ ЛОКАЦИИ === //
$('body').on('click', '#btn_add_location_discount', function () {
    $('#modal_window_add_location').fadeIn(300);
    $('#location_discount').removeClass('error_valid');
});

// === подтверждение действий
$('body').on('click', '#btn_modal_location_confirm', function () {
    $('#modal_window_add_location').fadeOut(300);
    $('#location_discount').children().remove();

    let $formTrue = $('#sortable_location_true');
    $formTrue.find('.active_countries').each(function () {
        let $addToList = '<li>"' + $(this).attr('id') + '" - ' + $(this).html() + '</li>';
        $('#location_discount').append($addToList);
    });
});

// === отмена действий
$('body').on('click', '#btn_modal_location_cancel', function () {
    $('#modal_window_add_location').fadeOut(300);

    let $formCountries = $('#sortable_location_true');
    $formCountries.find('.ui-state-highlight').each(function () {
        let $cloneCountry = $(this).clone();
        $('#sortable_location_false').prepend($cloneCountry);
        $(this).remove();
    });

    let $formCount = $('#sortable_location_false');
    $formCount.find('.ui-state-default').each(function () {
        let $cloneCountry = $(this).clone();
        $('#sortable_location_true').prepend($cloneCountry);
        $(this).remove();
    });

    // === добавление новых локаций в список
    $('#location_discount').children().remove();
    let $formTrue = $('#sortable_location_true');
    $formTrue.find('.active_countries').each(function () {
        let $addToList = '<li>"' + $(this).attr('id') + '" - ' + $(this).html() + '</li>';
        $('#location_discount').append($addToList);
    });
});

// === добавить все
$('body').on('click', '#btn_all_locations', function () {
    let $formCompany = $('#sortable_location_false');
    $formCompany.find('.active_countries').each(function () {
        let $cloneCompany = $(this).clone();
        $('#sortable_location_true').prepend($cloneCompany);
        $(this).remove();
    });
});

// === удалить все
$('body').on('click', '#btn_delete_all_locations', function () {
    let $formCompany = $('#sortable_location_true');
    $formCompany.find('.active_countries').each(function () {
        let $cloneCompany = $(this).clone();
        $('#sortable_location_false').prepend($cloneCompany);
        $(this).remove();
    });
});

// === поиск по неактивным локациям
$('body').on('keyup', '#input_search_location_false', function (event) {
    if (event.keyCode === 13) {
        $('#btn_search_location_false').click();
    }
});

$('body').on('click', '#btn_search_location_false', function () {
    let $inpVal = $('#input_search_location_false').val().toLowerCase();

    // проходим циклом по элементам li
    $('#sortable_location_false').children().each(function () {
        let $elemText = $(this).html().toLowerCase();
        searchToModalWindow($(this), $elemText, $inpVal);
    });
});

// === поиск по активным локациям
$('body').on('keyup', '#input_search_location_true', function (event) {
    if (event.keyCode === 13) {
        $('#btn_search_location_true').click();
    }
});

$('body').on('click', '#btn_search_location_true', function () {
    let $inpVal = $('#input_search_location_true').val().toLowerCase();

    // проходим циклом по элементам li
    $('#sortable_location_true').children().each(function () {
        let $elemText = $(this).html().toLowerCase();
        searchToModalWindow($(this), $elemText, $inpVal);
    });
});


// === ДОБАВИТЬ ДИСКОНТ ДЛЯ ТРАНСПОРТНОЙ КОМПАНИИ === //
$('body').on('click', '#btn_add_transport_discount', function () {
    $('#modal_window_add_company').fadeIn(300);
    $('#transport_discount').removeClass('error_valid');
});

// === подтверждение действий
$('body').on('click', '#btn_modal_company_confirm', function () {
    $('#modal_window_add_company').fadeOut(300);
    $('#transport_discount').children().remove();

    let $formTrue = $('#sortable_company_true');
    $formTrue.find('.active_companies').each(function () {
        let $addToList = '<li>' + $(this).html() + '</li>';
        $('#transport_discount').append($addToList);
    });
});

// === отмена действий
$('body').on('click', '#btn_modal_company_cancel', function () {
    $('#modal_window_add_company').fadeOut(300);

    let $formCompany = $('#sortable_company_true');
    $formCompany.find('.ui-state-highlight').each(function () {
        let $cloneCompany = $(this).clone();
        $('#sortable_company_false').prepend($cloneCompany);
        $(this).remove();
    });

    let $formComp = $('#sortable_company_false');
    $formComp.find('.ui-state-default').each(function () {
        let $cloneCompany = $(this).clone();
        $('#sortable_company_true').prepend($cloneCompany);
        $(this).remove();
    });

    // === добавление новых компаний в список
    $('#transport_discount').children().remove();
    let $formTrue = $('#sortable_company_true');
    $formTrue.find('.active_companies').each(function () {
        let $addToList = '<li>' + $(this).html() + '</li>';
        $('#transport_discount').append($addToList);
    });
});

// === добавить все
$('body').on('click', '#btn_all_companies', function () {
    let $formCompany = $('#sortable_company_false');
    $formCompany.find('.active_companies').each(function () {
        let $cloneCompany = $(this).clone();
        $('#sortable_company_true').prepend($cloneCompany);
        $(this).remove();
    });
});

// === удалить все
$('body').on('click', '#btn_delete_all_companies', function () {
    let $formCompany = $('#sortable_company_true');
    $formCompany.find('.active_companies').each(function () {
        let $cloneCompany = $(this).clone();
        $('#sortable_company_false').prepend($cloneCompany);
        $(this).remove();
    });
});

// === поиск по неактивным компаниям
$('body').on('keyup', '#input_search_company_false', function (event) {
    if (event.keyCode === 13) {
        $('#btn_search_company_false').click();
    }
});

$('body').on('click', '#btn_search_company_false', function () {
    let $inpVal = $('#input_search_company_false').val().toLowerCase();

    // проходим циклом по элементам li
    $('#sortable_company_false').children().each(function () {
        let $elemText = $(this).html().toLowerCase();
        searchToModalWindow($(this), $elemText, $inpVal);
    });
});

// === поиск по активным компаниям
$('body').on('keyup', '#input_search_company_true', function (event) {
    if (event.keyCode === 13) {
        $('#btn_search_company_true').click();
    }
});

$('body').on('click', '#btn_search_company_true', function () {
    let $inpVal = $('#input_search_company_true').val().toLowerCase();

    // проходим циклом по элементам li
    $('#sortable_company_true').children().each(function () {
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


// === МОДАЛЬНОЕ ОКНО === //
$('body').on('click', '.btn_close_window', function () {
    $('#modal_window_add_location, #modal_window_add_company').fadeOut(300);
});


// === JQUERY UI SORTABLE LOCATION === //
$("#sortable_location_true, #sortable_location_false, #sortable_company_true, #sortable_company_false").sortable({
    connectWith: ".connectedSortable"
}).disableSelection();


// === ОГРАНИЧЕНИЕ ВВОДА СКИДКИ ОТ 0 ДО 100 === //
$('body').on('blur', '#size_discount', function () {
    if ($(this).val() < 0) $(this).val(0);
    if ($(this).val() > 100) $(this).val(100);
    $(this).val(parseFloat($(this).val()).toFixed(2)); // ввод числа до сотых
});


// === ВВОД ТОЛЬКО ЦИФР === //
$('body').on('keypress', '#size_discount', function (event) {
    return !(/[А-Яа-яA-Za-z ]/.test(String.fromCharCode(event.charCode)));
});
