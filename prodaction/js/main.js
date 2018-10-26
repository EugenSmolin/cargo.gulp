// === Cookies === //
if (!$.cookie('admin_session')) {
    var $endSession = '<div class="endSession">Авторизируйтесь снова!</div>';
    $('.main-content').before($endSession);
    // localStorage.removeItem('lastTab');
    window.location.href = 'login.html';
}

var $userName = $.cookie('user-name');
var $userId = parseFloat($.cookie('user-id'));
var $defaultLang = "ru";
var $defaultEmail = "developer@developer.ru";
var $headers = {
    'api-key': 'RN42O4ntxJJen8GBixIf5BGCMPwwie',
    'admin_session': $.cookie('admin_session')
};

// === Имя пользователя === //
$('.user_profile').html($userName);

// === Активность вкладки === //
$(function() { 
  $('a[data-active="tab"]').on('click', function (e) {
    localStorage.setItem('lastTab', $(e.target).attr('href'));
  });

  var lastTab = localStorage.getItem('lastTab');
  var arrList = $('.li_active');
  for (var i = 0; i < arrList.length; i++) {
      if ($(arrList[i]).attr('href') == lastTab) {
        $(arrList[i]).addClass('active');
      }
  }
});

// ======= Выпадающий список на профиле в header-е ========//
$('#user').on('click', function () {
    if ($('.icon-profile').hasClass('icon-profile_rotate')) {
        $('.icon-profile').removeClass('icon-profile_rotate');
    } else {
        $('.icon-profile').addClass('icon-profile_rotate');
    }
    $('.user_list').slideToggle(200);
});


// === Выход из админ панели === //
$('body').on('click', '.btn-logout', function () {
    $.ajax({
        type: "POST",
        url: "/backend/adm_logout.php",
        headers: $headers,
        beforeSend: function () {
            preloader(true);
        },
        error: function () {
            preloader(false);
            serverError();
        },
        success: function (data) {
            if (data.status == "good") {
                // localStorage.removeItem('lastTab');
                window.location.href = 'login.html';
            }
            preloader(false);
        }
    });
});


// === Preloader === //
var $preloader = '<div id="preloader"><div id="loader"></div></div>';
$('body').prepend($preloader);

function preloader(bool) {
    if (bool === true) {
        $('#preloader').fadeIn(300);
    } else {
        $('#preloader').fadeOut(300);
    }
}


// === Server error === //
var $serverError = '<div id="server_error" class="server_error">Ошибка сервера!</div>';
$('.main-content').prepend($serverError);

function serverError() {
    $('.server_error').fadeIn(300);
    setTimeout(function () {
        $('.server_error').fadeOut(300);
    }, 3000);
}

// === РАБОТА С ДАТАМИ === //
// === ПЕРЕВОРАЧИВАЕТ МАССИВ ДАТЫ И ПРЕОБРАЗОВЫВАЕТ В СЕКУНДЫ === //
function reverseDate(date) {
    return new Date(date.split(".").reverse().join("-")).getTime() / 1000;
}

// === СОЗДАНИЕ ДАТЫ === //
function formatDate(date) {
    let d = new Date(date),
        $month = '' + (d.getMonth() + 1),
        $day = '' + d.getDate(),
        $year = d.getFullYear();
    // $hours = d.getHours(),
    // $minutes = d.getMinutes();

    // console.log(d);

    if ($month < 10) $month = '0' + $month;
    if ($day < 10) $day = '0' + $day;
    // if ($hours < 10) $hours = '0' + $hours;
    // if ($minutes < 10) $minutes = '0' + $minutes;

    return $day + "." + $month + "." + $year; // + " " + $hours + ":" + $minutes;
}

// === ПРЕОБРАЗОВАНИЕ СЕКУНД В ДАТУ === //
function parseDate(date) {
    if (date > 0) {
        let $timestamp = date * 1000;
        let $date = new Date();
        $date.setTime($timestamp);

        let $day = $date.getDate();
        let $month = ($date.getMonth() + 1);
        let $year = $date.getFullYear();
        let $hours = $date.getHours();
        let $minutes = $date.getMinutes();

        if ($day < 10) $day = "0" + $day;
        if ($month < 10) $month = "0" + $month;
        if ($hours < 10) $hours = "0" + $hours;
        if ($minutes < 10) $minutes = "0" + $minutes;

        return $day + "." + $month + "." + $year + " " + $hours + ":" + $minutes;
    }
    return '';
}

// === ПРЕОБРАЗОВАНИЕ СЕКУНД В ДАТУ БЕЗ ВРЕМЕНИ === //
function parseDateDiagram(date) {
    if (date > 0) {
        let $timestamp = date * 1000;
        let $date = new Date();
        $date.setTime($timestamp);

        let $day = $date.getDate();
        let $month = ($date.getMonth() + 1);
        let $year = $date.getFullYear();

        if ($day < 10) $day = "0" + $day;
        if ($month < 10) $month = "0" + $month;

        return $day + "." + $month + "." + $year;
    }
    return '';
}


// === ПРЯЧЕМ SIDEBAR === //
$('body').on('click', '#js-open_menu', function () {
    if ($(this).hasClass('toggle')) {
        $('#sidebar-wrapper').css('margin-left', '0px');
        $('.main-content').css('margin-left', '300px');
        $(this).removeClass('toggle').css('margin-left', '315px');
    } else {
        $('#sidebar-wrapper').css('margin-left', '-300px');
        $('.main-content').css('margin-left', '0px');
        $(this).addClass('toggle').css('margin-left', '15px');
    }
});










