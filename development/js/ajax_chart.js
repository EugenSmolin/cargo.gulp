// === График BAR - посещаемость === //
var $newDate = new Date();
var $todayDay = reverseDate(formatDate($newDate.setDate($newDate.getDate() + 1)));
var $yesterdayDay = reverseDate(formatDate($newDate.setDate($newDate.getDate() - 2)));
var $week = reverseDate(formatDate($newDate.setDate($newDate.getDate() - 5)));
var $month = reverseDate(formatDate($newDate.setDate($newDate.getDate() - 24)));
var $treeMonth = reverseDate(formatDate($newDate.setDate($newDate.getDate() - 60)));

// ===== ФОРМИРОВАНИЕ ЗАПРОСА ===== //
var simpleReq = {
    data: {
        "id": $userId,
        "defaultLang": $defaultLang,
        "defaultEmail": $defaultEmail,
        "start": $month,
        "end": $todayDay
    }
};

$.ajax({
    type: "POST",
    url: "/backend/adm_main_page.php",
    contentType: "application/json",
    headers: $headers,
    data: JSON.stringify(simpleReq),
    beforeSend: function () {
        preloader(true);
    },
    error: function () {
        preloader(false);
        serverError();
    },
    success: function (data) {

        console.log(data);

        // === Построение графика === //
        let $attendanceAll = parseInt(data.all);
        let $attendanceUnique = parseInt(data.unique);
        var masAll = data.grouped.all;
        var masUniq = data.grouped.unique;
        var allDates = [];
        var allCounters = [];
        var uniqCounters = [];

        if (masAll !== undefined && masAll.length > 0) {
            for (var i = 0; i < masAll.length; i++) {
                allDates.push(parseDateDiagram(masAll[i].date));
                allCounters.push(masAll[i].count);
            }
        }
        if (masUniq !== undefined && masUniq.length > 0) {
            for (var i = 0; i < masUniq.length; i++) {
                uniqCounters.push(masUniq[i].count);
            }
        }

        buildDiagram(allDates, allCounters, uniqCounters, $attendanceAll, $attendanceUnique);

        // === Предупреждения === //
        if (data.errors.messages !== undefined && data.errors.messages !== null && data.errors.messages.length > 0) {
            for (let i = 0; i < data.errors.messages.length; i++) {
                let $errorMsg =
                    '<div class="errorMsg">' +
                    '   <span class="errorMsg__name">' + data.errors.messages[i].company_name + '</span>' +
                    '   <span class="errorMsg__message">' + data.errors.messages[i].message + '</span>' +
                    '   <span class="errorMsg__date">' + parseDate(data.errors.messages[i].date) + '</span>' +
                    '</div>';

                $('#js-warning').append($errorMsg);
            }
        }


        preloader(false);
    }
});


// === ОТРИСОВКА ГРАФИКА === //
function buildDiagram(dateDiagram, allVisitors, specialVisitors) {
    $('#js-bar_block').children().remove();

    let $canvas = '<canvas id="myBar"></canvas>';
    $('#js-bar_block').append($canvas);

    let ctxBar = document.getElementById('myBar').getContext('2d');
    let myBar = new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: dateDiagram,
            datasets: [
                {
                    label: 'Все посещения',
                    backgroundColor: '#7FD0FF',
                    data: allVisitors
                },
                {
                    label: 'Уникальные посещения',
                    backgroundColor: '#B0EB99',
                    data: specialVisitors,
                    type: 'bar'
                }]
        },

        options: {}
    });
}


// === ВВОД ТОЛЬКО ЦИФР === //
$('body').on('keypress', '#js-start_date, #js-end_date', function (event) {
    return !(/[А-Яа-яA-Za-z ]/.test(String.fromCharCode(event.charCode)));
});


// === DATE PICKER === //
$(function () {
    let dateFormat = "dd.mm.yy",
        from = $("#js-start_date")
            .datepicker({
                maxDate: new Date(),
                changeMonth: true,
                changeYear: true
            })
            .on("change", function () {
                to.datepicker("option", "minDate", getDate(this));
            })
            .mask('00.00.0000')
            .attr('placeholder', 'dd.mm.yyyy'),
        to = $("#js-end_date").datepicker({
            maxDate: new Date(),
            changeMonth: true,
            changeYear: true
        })
            .on("change", function () {
                from.datepicker("option", "maxDate", getDate(this));
            })
            .mask('00.00.0000')
            .attr('placeholder', 'dd.mm.yyyy');

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


// === ВЫБОР ПЕРИОДА ДАТЫ ДЛЯ ОТОБРАЖЕНИЯ НА ГРАФИКЕ === //
$('body').on('click', '#js-apply_date', function () {
    let $startDate, $endDate;
    ($('#js-start_date').val() !== '') ? $startDate = reverseDate($('#js-start_date').val()) : $startDate = '';
    ($('#js-end_date').val() !== '') ? $endDate = reverseDate($('#js-end_date').val()) + 86400 : $endDate = '';

    simpleReq.data.start = $startDate;
    simpleReq.data.end = $endDate;

    $.ajax({
        type: "POST",
        url: "/backend/adm_main_page.php",
        contentType: "application/json",
        headers: $headers,
        data: JSON.stringify(simpleReq),
        beforeSend: function () {
            preloader(true);
        },
        error: function () {
            preloader(false);
            serverError();
        },
        success: function (data) {

            console.log(data);

            // === Построение графика === //
            let masAll = data.grouped.all;
            let masUniq = data.grouped.unique;
            let allDates = [];
            let allCounters = [];
            let uniqCounters = [];

            if (masAll !== undefined && masAll.length > 0) {
                for (let i = 0; i < masAll.length; i++) {
                    allDates.push(parseDateDiagram(masAll[i].date));
                    allCounters.push(masAll[i].count);
                }
            }
            if (masUniq !== undefined && masUniq.length > 0) {
                for (let i = 0; i < masUniq.length; i++) {
                    uniqCounters.push(masUniq[i].count);
                }
            }

            buildDiagram(allDates, allCounters, uniqCounters);

            preloader(false);
        }
    });
});


// === БЫСТРЫЙ ПРОСМОТР СТАТИСТИКИ === //
$('body').on('click', '#js-btn_today', function () {
    statistics($todayDay - 86400, $todayDay);
});

$('body').on('click', '#js-btn_yesterday', function () {
    statistics($yesterdayDay, $todayDay - 86400);
});

$('body').on('click', '#js-btn_week', function () {
    statistics($week, $todayDay);
});

$('body').on('click', '#js-btn_one_month', function () {
    statistics($month, $todayDay);
});

$('body').on('click', '#js-btn_tree_month', function () {
    statistics($treeMonth, $todayDay);
});

function statistics(startDate, endDate) {

    simpleReq.data.start = startDate;
    simpleReq.data.end = endDate;

    $.ajax({
        type: "POST",
        url: "/backend/adm_main_page.php",
        contentType: "application/json",
        headers: $headers,
        data: JSON.stringify(simpleReq),
        beforeSend: function () {
            preloader(true);
        },
        error: function () {
            preloader(false);
            serverError();
        },
        success: function (data) {

            // === Построение графика === //
            let masAll = data.grouped.all;
            let masUniq = data.grouped.unique;
            let allDates = [];
            let allCounters = [];
            let uniqCounters = [];

            if (masAll !== undefined && masAll.length > 0) {
                for (let i = 0; i < masAll.length; i++) {
                    allDates.push(parseDateDiagram(masAll[i].date));
                    allCounters.push(masAll[i].count);
                }
            }
            if (masUniq !== undefined && masUniq.length > 0) {
                for (let i = 0; i < masUniq.length; i++) {
                    uniqCounters.push(masUniq[i].count);
                }
            }

            buildDiagram(allDates, allCounters, uniqCounters);

            preloader(false);
        }
    });
}



















