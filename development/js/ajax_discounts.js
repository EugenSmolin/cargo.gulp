// === СКИДКИ === //
var $allCountDiscounts = 0,
    $sortCol = "",
    $sortOrder = "",
    $keyword = "",
    $activity = "active";


var $sendModel = {
    "data": {
        "id": $userId,
        "defaultLang": $defaultLang,
        "defaultEmail": $defaultEmail,
        "limit": 50,
        "offset": 0,
        "keyword": $keyword,
        "sortCol": $sortCol,
        "order": $sortOrder,
        "activity": $activity
    }
};

$.ajax({
    type: "POST",
    url: "/backend/adm_get_discount_list.php",
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

        let $allDiscounts = data.discounts;

        if ($allDiscounts !== undefined && $allDiscounts !== null && $allDiscounts.length > 0) {

            for (let i = 0; i < $allDiscounts.length; i++) {

                let $addNewDiscounts =
                    '<tr class="discounts_tr">' +
                    '   <td>' + data.discounts[i].id + '</td>' +
                    '   <td><a class="discounts_link" href="discount.html?id=' + data.discounts[i].id + '"></a>' + data.discounts[i].name + '</td>' +
                    '   <td>' + data.discounts[i].value + '</td>' +
                    '   <td>' + data.discounts[i].category + '</td>' +
                    '   <td>' + parseDate(data.discounts[i].dateEnd) + '</td>' +
                    '   <td><div class="countries_block" title="' + discountsCountries(data.discounts[i]) + '">' + discountsCountries(data.discounts[i]) + '</div></td>' +
                    '</tr>';

                $('#table_discounts tbody').append($addNewDiscounts);
            }
        }

        function discountsCountries(index) {
            let $country = '';
            if (index.countries !== undefined && index.countries.length > 0) {
                for (let i in index.countries) {
                    $country += index.countries[i] + ', ';
                }
            }

            return $country;
        }

        $allCountDiscounts = data.count;
        preloader(false);
    }
});


// === ПЕРЕХОД НА РЕДАКТИРОВАНИ СКИДКИ === //
$('body').on('click', 'tr.discounts_tr', function (event) {
    event.preventDefault();
    let $discountsLink = $(this).find('.discounts_link').attr('href');
    if ($discountsLink !== undefined && $discountsLink.length > 0) {
        window.location.href = $discountsLink;
    }
});


// === ПОКАЗАТЬ АКТИВНЫЕ СКИДКИ === //
$('body').on('click', '#btn_active_discounts', function () {
    discountsActivity("active");
});

// === ПОКАЗАТЬ АРХИВНЫЕ СКИДКИ === //
$('body').on('click', '#btn_archive_discounts', function () {
    discountsActivity("archive");
});

function discountsActivity(activity) {
    offsetCount = 50; // перезаписываем значение
    inProcess = false; // перезаписываем значение

    $('#table_discounts tbody tr').remove(); // очистка таблицы

    $activity = activity; // присваиваем переменной значение актив или архив

    let $discountsActivity = $.extend(true, {}, $sendModel);
    $discountsActivity.data.activity = activity;

    // console.log($discountsActivity);

    $.ajax({
        type: "POST",
        url: "/backend/adm_get_discount_list.php",
        contentType: "application/json",
        headers: $headers,
        data: JSON.stringify($discountsActivity),
        beforeSend: function () {
            preloader(true);
        },
        error: function () {
            preloader(false);
            serverError();
        },
        success: function (data) {

            // console.log(data);

            let $allDiscounts = data.discounts;

            if ($allDiscounts !== undefined && $allDiscounts !== null && $allDiscounts.length > 0) {

                for (let i = 0; i < $allDiscounts.length; i++) {

                    let $addNewDiscounts =
                        '<tr class="discounts_tr">' +
                        '   <td>' + data.discounts[i].id + '</td>' +
                        '   <td><a class="discounts_link" href="discount.html?id=' + data.discounts[i].id + '"></a>' + data.discounts[i].name + '</td>' +
                        '   <td>' + data.discounts[i].value + '</td>' +
                        '   <td>' + data.discounts[i].category + '</td>' +
                        '   <td>' + parseDate(data.discounts[i].dateEnd) + '</td>' +
                        '   <td><div class="countries_block" title="' + discountsCountries(data.discounts[i]) + '">' + discountsCountries(data.discounts[i]) + '</div></td>' +
                        '</tr>';

                    $('#table_discounts tbody').append($addNewDiscounts);
                }
            }

            function discountsCountries(index) {
                let $country = '';
                if (index.countries !== undefined && index.countries.length > 0) {
                    for (let i in index.countries) {
                        $country += index.countries[i] + ', ';
                    }
                }

                return $country;
            }

            preloader(false);
        }
    });
}


// === СОЗДАТЬ НОВУЮ СКИДКУ === //
$('body').on('click', '#btn_create_discounts', function () {
    window.location.href = 'discount.html';
});


// === СТАТИСТИКА СКИДОК === //
$('body').on('click', '#btn_discount_statistics', function () {
    window.location.href = 'discount_statistics.html';
});


// === ПОИСК ПО КОМПАНИЯМ === //
$('#input_search_discounts').on('keyup', function (event) {
    if (event.keyCode === 13) {
        $('#btn_search_discounts').click();
    }
});

$('#btn_search_discounts').on('click', function () {

    offsetCount = 50; // перезаписываем значение
    inProcess = false; // перезаписываем значение

    $keyword = $('#input_search_discounts').val();

    if ($keyword !== '') {

        $('#table_discounts tbody tr').remove(); // очистка таблицы

        let $discountsSearch = $.extend(true, {}, $sendModel);
        $discountsSearch.data.keyword = $keyword;

        $.ajax({
            type: "POST",
            url: "/backend/adm_get_discount_list.php",
            contentType: "application/json",
            headers: $headers,
            data: JSON.stringify($discountsSearch),
            beforeSend: function () {
                preloader(true);
            },
            error: function () {
                preloader(false);
                serverError();
            },
            success: function (data) {

                // console.log(data);

                let $allDiscounts = data.discounts;

                if ($allDiscounts !== undefined && $allDiscounts !== null && $allDiscounts.length > 0) {

                    for (let i = 0; i < $allDiscounts.length; i++) {

                        let $addNewDiscounts =
                            '<tr class="discounts_tr">' +
                            '   <td>' + data.discounts[i].id + '</td>' +
                            '   <td><a class="discounts_link" href="discount.html?id=' + data.discounts[i].id + '"></a>' + data.discounts[i].name + '</td>' +
                            '   <td>' + data.discounts[i].value + '</td>' +
                            '   <td>' + data.discounts[i].category + '</td>' +
                            '   <td>' + parseDate(data.discounts[i].dateEnd) + '</td>' +
                            '   <td><div class="countries_block" title="' + discountsCountries(data.discounts[i]) + '">' + discountsCountries(data.discounts[i]) + '</div></td>' +
                            '</tr>';

                        $('#table_discounts tbody').append($addNewDiscounts);
                    }
                }

                function discountsCountries(index) {
                    let $country = '';
                    if (index.countries !== undefined && index.countries.length > 0) {
                        for (let i in index.countries) {
                            $country += index.countries[i] + ', ';
                        }
                    }

                    return $country;
                }

                preloader(false);
            }
        });
    } else {
        $('#table_discounts tbody tr').remove(); // очистка таблицы

        $.ajax({
            type: "POST",
            url: "/backend/adm_get_discount_list.php",
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

                let $allDiscounts = data.discounts;

                if ($allDiscounts !== undefined && $allDiscounts !== null && $allDiscounts.length > 0) {

                    for (let i = 0; i < $allDiscounts.length; i++) {

                        let $addNewDiscounts =
                            '<tr class="discounts_tr">' +
                            '   <td>' + data.discounts[i].id + '</td>' +
                            '   <td><a class="discounts_link" href="discount.html?id=' + data.discounts[i].id + '"></a>' + data.discounts[i].name + '</td>' +
                            '   <td>' + data.discounts[i].value + '</td>' +
                            '   <td>' + data.discounts[i].category + '</td>' +
                            '   <td>' + parseDate(data.discounts[i].dateEnd) + '</td>' +
                            '   <td><div class="countries_block" title="' + discountsCountries(data.discounts[i]) + '">' + discountsCountries(data.discounts[i]) + '</div></td>' +
                            '</tr>';

                        $('#table_discounts tbody').append($addNewDiscounts);
                    }
                }

                function discountsCountries(index) {
                    let $country = '';
                    if (index.countries !== undefined && index.countries.length > 0) {
                        for (let i in index.countries) {
                            $country += index.countries[i] + ', ';
                        }
                    }

                    return $country;
                }

                $allCountDiscounts = data.count;
                preloader(false);
            }
        });
    }

});


// ===== ПОДГРУЗКА КОНТЕНТА В ТАБЛИЦУ ПРИ СКРОЛЛЕ ===== //
var offsetCount = 50;
var inProcess = false;
$(window).scroll(function () {
    if ($(window).scrollTop() + $(window).height() >= $(document).height() && !inProcess) {

        let $addDiscounts = $.extend(true, {}, $sendModel);
        $addDiscounts.data.offset = offsetCount;
        $addDiscounts.data.keyword = $keyword;
        $addDiscounts.data.sortCol = $sortCol;
        $addDiscounts.data.order = $sortOrder;

        $.ajax({
            type: "POST",
            url: "/backend/adm_get_discount_list.php",
            contentType: "application/json",
            headers: $headers,
            data: JSON.stringify($addDiscounts),
            beforeSend: function () {
                preloader(true);
                inProcess = true;
            },
            error: function () {
                preloader(false);
                serverError();
            },
            success: function (data) {

                // console.log(data);

                let $allDiscounts = data.discounts;

                if ($allDiscounts !== undefined && $allDiscounts !== null && $allDiscounts.length > 0) {

                    for (let i = 0; i < $allDiscounts.length; i++) {

                        let $addNewDiscounts =
                            '<tr class="discounts_tr">' +
                            '   <td>' + data.discounts[i].id + '</td>' +
                            '   <td><a class="discounts_link" href="discount.html?id=' + data.discounts[i].id + '"></a>' + data.discounts[i].name + '</td>' +
                            '   <td>' + data.discounts[i].value + '</td>' +
                            '   <td>' + data.discounts[i].category + '</td>' +
                            '   <td>' + parseDate(data.discounts[i].dateEnd) + '</td>' +
                            '   <td><div class="countries_block" title="' + discountsCountries(data.discounts[i]) + '">' + discountsCountries(data.discounts[i]) + '</div></td>' +
                            '</tr>';

                        $('#table_discounts tbody').append($addNewDiscounts);
                    }
                } else {
                    let $endDiscountsLists = '<tr><td colspan="6" class="nothing_found">Конец списка</td></tr>';
                    $('#table_discounts tbody').append($endDiscountsLists);
                }

                function discountsCountries(index) {
                    let $country = '';
                    if (index.countries !== undefined && index.countries.length > 0) {
                        for (let i in index.countries) {
                            $country += index.countries[i] + ', ';
                        }
                    }

                    return $country;
                }

                preloader(false);
            }
        });
    }
});


// ===== СОРТИРОВКА ТАБЛИЦЫ ===== //
function sortTable(sortCol, order) {

    offsetCount = 50; // перезаписываем значение
    inProcess = false; // перезаписываем значение

    $('#table_discounts tbody tr').remove();

    let $sortDiscounts = $.extend(true, {}, $sendModel);
    $sortDiscounts.data.sortCol = sortCol;
    $sortDiscounts.data.order = order;
    $sortDiscounts.data.activity = $activity;

    $.ajax({
        type: "POST",
        url: "/backend/adm_get_discount_list.php",
        contentType: "application/json",
        headers: $headers,
        data: JSON.stringify($sortDiscounts),
        beforeSend: function () {
            preloader(true);
        },
        error: function () {
            preloader(false);
            serverError();
        },
        success: function (data) {

            // console.log(data);

            let $allDiscounts = data.discounts;

            if ($allDiscounts !== undefined && $allDiscounts !== null && $allDiscounts.length > 0) {

                for (let i = 0; i < $allDiscounts.length; i++) {

                    let $addNewDiscounts =
                        '<tr class="discounts_tr">' +
                        '   <td>' + data.discounts[i].id + '</td>' +
                        '   <td><a class="discounts_link" href="discount.html?id=' + data.discounts[i].id + '"></a>' + data.discounts[i].name + '</td>' +
                        '   <td>' + data.discounts[i].value + '</td>' +
                        '   <td>' + data.discounts[i].category + '</td>' +
                        '   <td>' + parseDate(data.discounts[i].dateEnd) + '</td>' +
                        '   <td><div class="countries_block" title="' + discountsCountries(data.discounts[i]) + '">' + discountsCountries(data.discounts[i]) + '</div></td>' +
                        '</tr>';

                    $('#table_discounts tbody').append($addNewDiscounts);
                }
            }

            function discountsCountries(index) {
                let $country = '';
                if (index.countries !== undefined && index.countries.length > 0) {
                    for (let i in index.countries) {
                        $country += index.countries[i] + ', ';
                    }
                }

                return $country;
            }

            preloader(false);
        }
    });
}

function sortActiveTh($this) {
    let $class = $('.arrow_down, .arrow_up');
    if (!$class.hasClass('arrow_sort')) {
        $class.removeClass('arrow_down arrow_up').addClass('arrow_sort');
    }
    $('#table_discounts thead th').css('background', 'linear-gradient(to top, #D7D7D7, #FFF)');
    $this.css('background', 'linear-gradient(to top, #B8E5FF, #FFF)');
}

$('body').on('click', 'th.sort_name', function () {
    let $this = $(this);
    sortActiveTh($this);

    if (!$this.hasClass('sort_true')) {
        $this.addClass('sort_true');
        $this.find('.arrow_sort, .arrow_up').removeClass('arrow_sort arrow_up').addClass('arrow_down');
        // передаем значение //
        $sortCol = "discountName";
        $sortOrder = "DESC";
        sortTable($sortCol, $sortOrder);
    } else {
        $this.removeClass('sort_true');
        $this.find('.arrow_sort, .arrow_down').removeClass('arrow_sort arrow_down').addClass('arrow_up');
        // передаем значение //
        $sortCol = "discountName";
        $sortOrder = "ASC";
        sortTable($sortCol, $sortOrder);
    }
});

$('body').on('click', 'th.sort_size', function () {
    let $this = $(this);
    sortActiveTh($this);

    if (!$this.hasClass('sort_true')) {
        $this.addClass('sort_true');
        $this.find('.arrow_sort, .arrow_up').removeClass('arrow_sort arrow_up').addClass('arrow_down');
        // передаем значение //
        $sortCol = "discountSize";
        $sortOrder = "DESC";
        sortTable($sortCol, $sortOrder);
    } else {
        $this.removeClass('sort_true');
        $this.find('.arrow_sort, .arrow_down').removeClass('arrow_sort arrow_down').addClass('arrow_up');
        // передаем значение //
        $sortCol = "discountSize";
        $sortOrder = "ASC";
        sortTable($sortCol, $sortOrder);
    }
});

$('body').on('click', 'th.sort_category', function () {
    let $this = $(this);
    sortActiveTh($this);

    if (!$this.hasClass('sort_true')) {
        $this.addClass('sort_true');
        $this.find('.arrow_sort, .arrow_up').removeClass('arrow_sort arrow_up').addClass('arrow_down');
        // передаем значение //
        $sortCol = "discountCategory";
        $sortOrder = "DESC";
        sortTable($sortCol, $sortOrder);
    } else {
        $this.removeClass('sort_true');
        $this.find('.arrow_sort, .arrow_down').removeClass('arrow_sort arrow_down').addClass('arrow_up');
        // передаем значение //
        $sortCol = "discountCategory";
        $sortOrder = "ASC";
        sortTable($sortCol, $sortOrder);
    }
});

$('body').on('click', 'th.sort_date', function () {
    let $this = $(this);
    sortActiveTh($this);

    if (!$this.hasClass('sort_true')) {
        $this.addClass('sort_true');
        $this.find('.arrow_sort, .arrow_up').removeClass('arrow_sort arrow_up').addClass('arrow_down');
        // передаем значение //
        $sortCol = "discountDate";
        $sortOrder = "DESC";
        sortTable($sortCol, $sortOrder);
    } else {
        $this.removeClass('sort_true');
        $this.find('.arrow_sort, .arrow_down').removeClass('arrow_sort arrow_down').addClass('arrow_up');
        // передаем значение //
        $sortCol = "discountDate";
        $sortOrder = "ASC";
        sortTable($sortCol, $sortOrder);
    }
});
