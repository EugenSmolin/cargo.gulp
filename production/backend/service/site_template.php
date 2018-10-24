<?php


class Site
{
    public function ShowMessage($message)
    {
        echo
            "
        <head>
        <link rel=\"stylesheet\" href=\"content/main.css\">
        </head>
        <body>
        <div id=\"issue_order_form\" class=\"fullheight flex\">
        <div class=\"container flex_content\">
         <header class=\"header clearfix\">
          <div class=\"main_logo pull-left\">
            <a href=\"".HOME_SITE_URL."\" class=\"inline-block\">
              <img src=\"content/logo.png\" alt=\"\">
              <span class=\"text-uppercase hidden-xs\"> Cargo.guru </span>
            </a>
          </div>
          <div class=\"right_side_header\">
            <div class=\"right_side_header_overlay\"></div>
            <div class=\"right_side_header_inner\">
              <div class=\"bg_contain\">
                <div class=\"list_menu inline-block\">
                <ul class=\"list-inline main_ul\">
                  <li> <a tabindex=\"0\" href=\"".HOME_SITE_URL."/order_calc.php\" class=\"underborder\"> Калькулятор </a> </li>
                  <li> <a href=\"#!\" class=\"underborder\"> О компании  </a> </li>
                  <li> <a href=\"#!\" class=\"underborder\"> Справка     </a> </li>
                  <li>
                    <a tabindex=\"0\" href=\"#!\" class=\"dropdown-toggle\" data-toggle=\"dropdown\">
                      <span class=\"drop_li\">
                        <img src=\"content/ru.png\" alt=\"\">
                        <span class=\"underborder\"> Русский </span>
                        <i class=\"glyphicon glyphicon-menu-down\" aria-hidden=\"true\"></i>
                      </span>
                    </a>
                    <ul class=\"dropdown-menu\">
                      <li></li>
                      <li></li>
                      <li></li>
                    </ul>
                  </li>
        
                  <li>
                    <a href=\"#!\" class=\"dropdown-toggle drop_li\" data-toggle=\"dropdown\">
                      <span class=\"currency\">
                        <span class=\"underborder\">₽ RUB</span>
                      </span>
                      <i class=\"glyphicon glyphicon-menu-down\" aria-hidden=\"true\"></i>
                    </a>
                    <ul class=\"text-center currency_list dropdown-menu\">
                        <li>
                          <a href=\"#!\" onclick=\"mainCurr(this)\">
                              <span class=\"underborder\"> $ CAD </span>
                          </a>
                        </li>
                        <li>
                          <a href=\"#!\" onclick=\"mainCurr(this)\">
                              <span class=\"underborder\"> ¥ CNY </span>
                          </a>
                        </li>
                        <li>
                          <a href=\"#!\" onclick=\"mainCurr(this)\">
                              <span class=\"underborder\"> € EUR </span>
                          </a>
                        </li>
                        <li>
                          <a href=\"#!\" onclick=\"mainCurr(this)\">
                              <span class=\"underborder\"> £ GBP </span>
                          </a>
                        </li>
                        <li>
                          <a href=\"#!\" onclick=\"mainCurr(this)\">
                              <span class=\"underborder\"> $ HKD </span>
                          </a>
                        </li>
                        <li>
                          <a href=\"#!\" onclick=\"mainCurr(this)\">
                              <span class=\"underborder\"> ₸ KZT </span>
                          </a>
                        </li>
                        <li>
                          <a href=\"#!\" onclick=\"mainCurr(this)\">
                              <span class=\"underborder\"> $ NZD </span>
                          </a>
                        </li>
                        <li>
                          <a href=\"#!\" onclick=\"mainCurr(this)\">
                              <span class=\"underborder\"> $ USD </span>
                          </a>
                        </li>
        
                        <li>
                          <a href=\"#!\" onclick=\"mainCurr(this)\">
                              <span class=\"underborder\"> R ZAR </span>
                          </a>
                        </li>
                        <li>
                          <a href=\"#!\" onclick=\"mainCurr(this)\">
                              <span class=\"underborder\"> ₽ RUB </span>
                          </a>
                        </li>
                    </ul>
                  </li>
                </ul>
              </div>
              </div>
            </div>
          </div>
                <button type=\"button\" class=\"mobile_btn visible-sm visible-xs\"></button>
            </header>
              <div style=\"margin: 80px;color: #9c3b8b;font-size: 3rem;\"> <h1>".$message."</h1><div>
              <div style=\"margin-top: 60px;font-size: 1.5rem;\">
               <a href=\"".HOME_SITE_URL."\" class=\"inline-block\">
                  <span class=\"underborder text-uppercase hidden-xs\"> Вернуться на сайт </span>
                </a>
              </div>
                </div>
        </body>";
    }
}

?>