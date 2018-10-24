<?php

/*

Abstract class for calculator module

*/

abstract class AbstractCalculator
{
    const	EXTERNAL_PROXY		= 'eksel.su:3128';

    public $aStartTimeVariants	= [
        [
            "number" => 0,
            "visible" => "00:00"
        ],
        [
            "number" => 1,
            "visible" => "01:00"
        ],
        [
            "number" => 2,
            "visible" => "02:00"
        ],
        [
            "number" => 3,
            "visible" => "03:00"
        ],
        [
            "number" => 4,
            "visible" => "04:00"
        ],
        [
            "number" => 5,
            "visible" => "05:00"
        ],
        [
            "number" => 6,
            "visible" => "06:00"
        ],
        [
            "number" => 7,
            "visible" => "07:00"
        ],
        [
            "number" => 8,
            "visible" => "08:00"
        ],
        [
            "number" => 9,
            "selected" => true,
            "visible" => "09:00"
        ],
        [
            "number" => 10,
            "visible" => "10:00"
        ],
        [
            "number" => 11,
            "visible" => "11:00"
        ],
        [
            "number" => 12,
            "visible" => "12:00"
        ],
        [
            "number" => 13,
            "visible" => "13:00"
        ],
        [
            "number" => 14,
            "visible" => "14:00"
        ],
        [
            "number" => 15,
            "visible" => "15:00"
        ],
        [
            "number" => 16,
            "visible" => "16:00"
        ],
        [
            "number" => 17,
            "visible" => "17:00"
        ],
        [
            "number" => 18,
            "visible" => "18:00"
        ],
        [
            "number" => 19,
            "visible" => "19:00"
        ],
        [
            "number" => 20,
            "visible" => "20:00"
        ],
        [
            "number" => 21,
            "visible" => "21:00"
        ],
        [
            "number" => 22,
            "visible" => "22:00"
        ],
        [
            "number" => 23,
            "visible" => "23:00"
        ]
    ];

    public $aEndTimeVariants	= [
        [
            "number" => 0,
            "visible" => "00:00"
        ],
        [
            "number" => 1,
            "visible" => "01:00"
        ],
        [
            "number" => 2,
            "visible" => "02:00"
        ],
        [
            "number" => 3,
            "visible" => "03:00"
        ],
        [
            "number" => 4,
            "visible" => "04:00"
        ],
        [
            "number" => 5,
            "visible" => "05:00"
        ],
        [
            "number" => 6,
            "visible" => "06:00"
        ],
        [
            "number" => 7,
            "visible" => "07:00"
        ],
        [
            "number" => 8,
            "visible" => "08:00"
        ],
        [
            "number" => 9,
            "visible" => "09:00"
        ],
        [
            "number" => 10,
            "visible" => "10:00"
        ],
        [
            "number" => 11,
            "visible" => "11:00"
        ],
        [
            "number" => 12,
            "visible" => "12:00"
        ],
        [
            "number" => 13,
            "visible" => "13:00"
        ],
        [
            "number" => 14,
            "visible" => "14:00"
        ],
        [
            "number" => 15,
            "visible" => "15:00"
        ],
        [
            "number" => 16,
            "visible" => "16:00"
        ],
        [
            "number" => 17,
            "visible" => "17:00"
        ],
        [
            "number" => 18,
            "selected" => true,
            "visible" => "18:00"
        ],
        [
            "number" => 19,
            "visible" => "19:00"
        ],
        [
            "number" => 20,
            "visible" => "20:00"
        ],
        [
            "number" => 21,
            "visible" => "21:00"
        ],
        [
            "number" => 22,
            "visible" => "22:00"
        ],
        [
            "number" => 23,
            "visible" => "23:00"
        ]
    ];

    public $aDangerClasses = [
        [
            "number" => 1,
            "selected" => true,
            "visible" => "Не имеет значения",
        ],
        [
            "number" => 2,
            "visible" => "Взрывчатые материалы с опасностью взрыва массой",
        ],
        [
            "number" => 3,
            "visible" => "Взрывчатые материалы, не взрывающиеся массой",
        ],
        [
            "number" => 4,
            "visible" => "Взрывчатые материалы пожароопасные, не взрывающиеся массой",
        ],
        [
            "number" => 5,
            "visible" => "Взрывчатые материалы, не представляющие значительной опасности",
        ],
        [
            "number" => 6,
            "visible" => "Очень нечувствительные взрывчатые материалы",
        ],
        [
            "number" => 7,
            "visible" => "Изделия чрезвычайно низкой чувствительности",
        ],
        [
            "number" => 8,
            "visible" => "Невоспламеняющиеся неядовитые газы",
        ],
        [
            "number" => 9,
            "visible" => "Ядовитые газы",
        ],
        [
            "number" => 10,
            "visible" => "Воспламеняющиеся (горючие) газы",
        ],
        [
            "number" => 11,
            "visible" => "Ядовитые и воспламеняющиеся газы",
        ],
        [
            "number" => 12,
            "visible" => "Легковоспламеняющиеся жидкости с температурой вспышки менее минус 18 °C в закрытом тигле",
        ],
        [
            "number" => 13,
            "visible" => "Легковоспламеняющиеся жидкости с температурой вспышки не менее минус 18 °C, но менее 23 °C, в закрытом тигле",
        ],
        [
            "number" => 14,
            "visible" => "Легковоспламеняющиеся жидкости с температурой вспышки не менее 23 °C, но не более 61 °C, в закрытом тигле",
        ],
        [
            "number" => 15,
            "visible" => "Легковоспламеняющиеся твердые вещества",
        ],
        [
            "number" => 16,
            "visible" => "Самовозгорающиеся вещества",
        ],
        [
            "number" => 17,
            "visible" => "Вещества, выделяющие воспламеняющиеся газы при взаимодействии с водой",
        ],
        [
            "number" => 18,
            "visible" => "Окисляющие вещества",
        ],
        [
            "number" => 19,
            "visible" => "Органические пероксиды",
        ],
        [
            "number" => 20,
            "visible" => "Ядовитые вещества",
        ],
        [
            "number" => 21,
            "visible" => "Инфекционные вещества",
        ],
        [
            "number" => 22,
            "visible" => "Радиоактивные материалы",
        ],
        [
            "number" => 23,
            "visible" => "Едкие и (или) коррозионные вещества, обладающие кислотными свойствами",
        ],
        [
            "number" => 24,
            "visible" => "Едкие и (или) коррозионные вещества, обладающие основными свойствами",
        ],
        [
            "number" => 25,
            "visible" => "Разные едкие и (или) коррозионные вещества",
        ],
        [
            "number" => 26,
            "visible" => "Грузы, не отнесенные к классам 1 - 8",
        ],
        [
            "number" => 27,
            "visible" => "Грузы, обладающие видами опасности, проявление которых представляет опасность только при их транспортировании навалом водным транспортом",
        ],
    ];

    public $jurOKPF = [
        [ 'number' => 1, 'visible' => 'Публичное акционерное общество'],
        [ 'number' => 2, 'visible' => 'Непубличное акционерное общество'],
        [ 'number' => 3, 'visible' => 'Жилищное или жилищно-строительные кооператив'],
        [ 'number' => 4, 'visible' => 'Общественная организация'],
        [ 'number' => 5, 'visible' => 'Общество с ограниченной ответственностью', "selected" => true],
        [ 'number' => 6, 'visible' => 'Общество взаимного страхования'],
        [ 'number' => 7, 'visible' => 'Садоводческий, огороднический или дачно потребительский кооператив'],
        [ 'number' => 8, 'visible' => 'Фонд проката'],
        [ 'number' => 9, 'visible' => 'Территориальное общественное самоуправление'],
        [ 'number' => 10, 'visible' => 'Адвокатская палата'],
        [ 'number' => 11, 'visible' => 'Нотариальная палата'],
        [ 'number' => 12, 'visible' => 'Торгово-промышленная палата'],
        [ 'number' => 13, 'visible' => 'Объединение работодателей'],
        [ 'number' => 14, 'visible' => 'Объединение фермерских хозяйств'],
        [ 'number' => 15, 'visible' => 'Некоммерческое партнерство'],
        [ 'number' => 16, 'visible' => 'Адвокатское бюро'],
        [ 'number' => 17, 'visible' => 'Коллегиия адвокатов'],
        [ 'number' => 18, 'visible' => 'Садоводческое, огородническое или дачное некоммерческое партнерство'],
        [ 'number' => 19, 'visible' => 'Ассоциация (союз) садоводческих, огороднических и дачных некоммерческих объединений'],
        [ 'number' => 20, 'visible' => 'Саморегулируемая организация'],
        [ 'number' => 21, 'visible' => 'Объединение (ассоциация и союз) благотворительных организаций'],
        [ 'number' => 22, 'visible' => 'Товарищество собственников недвижимости'],
        [ 'number' => 23, 'visible' => 'Садоводческое, огородническое или дачное некоммерческое товарищество'],
        [ 'number' => 24, 'visible' => 'Товарищество собственников жилья'],
        [ 'number' => 25, 'visible' => 'Казачье общество, внесенныо в государственный реестр казачьих обществ в Российской Федерации'],
        [ 'number' => 26, 'visible' => 'Община коренных малочисленных народов Российской Федерации'],
        [ 'number' => 27, 'visible' => 'Унитарное предприятие'],
        [ 'number' => 28, 'visible' => 'Унитарное предприятие, основанное на праве оперативного управления (казенное предприятие)'],
        [ 'number' => 29, 'visible' => 'Федеральное казенное предприятие'],
        [ 'number' => 30, 'visible' => 'Казенное предприятия субъектов Российской Федерации'],
        [ 'number' => 31, 'visible' => 'Муниципальное казенное предприятия'],
        [ 'number' => 32, 'visible' => 'Унитарное предприятие, основанное на праве хозяйственного ведения'],
        [ 'number' => 33, 'visible' => 'Федеральное государственное унитарное предприятие'],
        [ 'number' => 34, 'visible' => 'Государственное унитарное предприятие субъектов Российской Федерации'],
        [ 'number' => 35, 'visible' => 'Муниципальное унитарное предприятие'],
        [ 'number' => 36, 'visible' => 'Фонд'],
        [ 'number' => 37, 'visible' => 'Благотворительный фонд'],
        [ 'number' => 38, 'visible' => 'Негосударственный пенсионный фонд'],
        [ 'number' => 39, 'visible' => 'Общественный фонд'],
        [ 'number' => 40, 'visible' => 'Экологический фонд'],
        [ 'number' => 41, 'visible' => 'Автономная некоммерческая организация'],
        [ 'number' => 42, 'visible' => 'Религиозная организация'],
        [ 'number' => 43, 'visible' => 'Публично-правовая компания'],
        [ 'number' => 44, 'visible' => 'Государственная корпорация'],
        [ 'number' => 45, 'visible' => 'Государственная компания'],
        [ 'number' => 46, 'visible' => 'Отделение иностранных некоммерческих неправительственных организаций'],
        [ 'number' => 47, 'visible' => 'Учреждение'],
        [ 'number' => 48, 'visible' => 'Учреждение, созданное Российской Федерацией'],
        [ 'number' => 49, 'visible' => 'Федеральное государственное автономное учреждения'],
        [ 'number' => 50, 'visible' => 'Федеральное государственное бюджетное учреждения'],
        [ 'number' => 51, 'visible' => 'Федеральное государственное казенное учреждения'],
        [ 'number' => 52, 'visible' => 'Учреждение, созданное субъектом Российской Федерации'],
        [ 'number' => 53, 'visible' => 'Государственное автономное учрежденоя субъектов Российской Федерации'],
        [ 'number' => 54, 'visible' => 'Государственное бюджетное учреждение субъектов Российской Федерации'],
        [ 'number' => 55, 'visible' => 'Государственное казенное учреждение субъектов Российской Федерации'],
        [ 'number' => 56, 'visible' => 'Государственная академия наук'],
        [ 'number' => 57, 'visible' => 'Учреждение, созданное муниципальным образованием (муниципальное учреждение)'],
        [ 'number' => 58, 'visible' => 'Муниципальное автономное учреждение'],
        [ 'number' => 59, 'visible' => 'Муниципальное бюджетное учреждения'],
        [ 'number' => 60, 'visible' => 'Муниципальное казенное учреждения'],
        [ 'number' => 61, 'visible' => 'Частное учреждение'],
        [ 'number' => 62, 'visible' => 'Благотворительное учреждение'],
        [ 'number' => 63, 'visible' => 'Общественное учреждение'],
    ];

    public $docType = [
        [
            "number" => 1,
            "visible" => "Паспорт",
            "selected" => true
        ],
        [
            "number" => 2,
            "visible" => "Заграничный паспорт"
        ],
        [
            "number" => 3,
            "visible" => "Водительское удостоверение"
        ]
    ];

    public $aWidthTypes = [
        [
            "number" => 1,
            "visible" => "кг",
            "selected" => true
        ],
        [
            "number" => 2,
            "visible" => "г"
        ],
        [
            "number" => 3,
            "visible" => "тн"
        ],
        [
            "number" => 4,
            "visible" => "lb"
        ],
        [
            "number" => 5,
            "visible" => "oz"
        ]
    ];

    public $aLengthTypes = [
        [
            "number" => 1,
            "visible" => "м",
            "selected" => true
        ],
        [
            "number" => 2,
            "visible" => "см"
        ],
        [
            "number" => 3,
            "visible" => "ft"
        ]
    ];

    public $aVolTypes = [
        [
            "number" => 1,
            "visible" => "м³",
            "selected" => true
        ],
        [
            "number" => 2,
            "visible" => "см³"
        ],
        [
            "number" => 3,
            "visible" => "ft³"
        ]
    ];

    abstract public function Calculate($from,$to,$weight,$vol,$insPrice,$clientLang,$clientCurr,
                                       $cargoCountryFrom,$cargoCountryTo,$cargoStateFrom,$cargoStateTo,
                                       $isActiveLineParams, $width, $length, $height, $options = []);


    public function RequestExec($sURL, $aData, $sAdditional)
    {
        /**
         * makes curl request through exec call
         *
         * @param string $sURL      URL string
         * @param array  $aData     Data array
         *
         */

        exec("curl '" . $sURL . "' " . $sAdditional .
            ((count($aData) > 0) ? (" --data '" . http_build_query($aData) . "'") : "") .
            " --compressed -s", $sTmp);
//        print("curl '" . $sURL . "' " . $sAdditional . " --data '" . http_build_query($aData) . "' --compressed -s");

        $sRetVal = '';

        foreach ($sTmp as $sString)
            $sRetVal .= $sString . PHP_EOL;

        return $sRetVal;
    }

    public function GetOptions()
    {
        // Get ordering options

        $aStandardOptions = array();
        $aStandardGroups = array();

        /** Where group
         * @var $aWhereOptions */

        $aWhereOptions = array();

        $aWhereOptions['clientID'] = array(
            "displayName" => "ИД Клиента",
            "fieldName" => "clientID",
            "type" => "int32",
            "required" => TRUE,
            "hidden" => TRUE
        );

        $aWhereOptions['cargoCompanyID'] = array(
            "displayName" => "ИД Транспортной компании",
            "fieldName" => "cargoCompanyID",
            "type" => "int32",
            "required" => TRUE,
            "hidden" => TRUE
        );

        $aWhereOptions['cargoPrice'] = array(
            "displayName" => "Цена",
            "fieldName" => "cargoPrice",
            "type" => "float",
            "required" => TRUE,
            "hidden" => TRUE
        );

        $aWhereOptions['cargoTo'] = array(
            "displayName" => "Куда",
            "fieldName" => "cargoTo",
            "type" => "string",
            "required" => TRUE
        );
        /** Where group
         * End block */

        /** Add Where group to Basic StandardGroup */
        $aStandardGroups['where'] = array(
            'name' => 'Куда',
            'visibleOrder' => 1,
            'aoptions' => //$aWhereOptions
                [
                    [
                        "displayName" => "Куда Область",
                        "fieldName" => "cargoToRegion",
                        "type" => "string",
                        "required" => TRUE,
                        "hidden" => true,
                        "visibleOrder" => 0
                    ],
                    [
                        "displayName" => "Куда",
                        "fieldName" => "cargoTo",
                        "type" => "string",
                        "required" => TRUE,
                        "visibleOrder" => 1
                    ],
                    [
                        "displayName" => "Куда Индекс",
                        "fieldName" => "cargoToZip",
                        "type" => "string",
                        "required" => TRUE,
                        "hidden" => true,
                        "visibleOrder" => 2
                    ],

                ]
        );

        /** Recipient group
         * @var  $aRecipientOptions */

        $aRecipientOptions = array();

        $aRecipientOptions['recepientPhys'] = array(
            "displayName" => "Тип получателя",
            "fieldName" => "recepientPhysOrJur",
            "type" => "enum",
            "required" => TRUE,
            "variants" => [
                [
                    "number" => 1,
                    "visible" => "Физическое лицо",
                    "makesVisible" => ["recepientPhysBlock"],
                    "makesInvisible" => ["recepientJurBlock"],
                    "selected" => true
                ],
                [
                    "number" => 2,
                    "visible" => "Юридическое лицо",
                    "makesVisible" => ["recepientJurBlock"],
                    "makesInvisible" => ["recepientPhysBlock"]
                ]
            ]
        );


        /** Person option parameters */
        $aRecipientOptions['recepientPhysBlock'] = [
            "id" => "recepientPhysBlock",
            "name" => "recepientPhysBlock",
            "hidden" => false,
            "aoptions" => [
                [
                    "displayName" => "Имя",
                    "fieldName" => "cargoRecepientFirstName",
                    "type" => "string",
                    "required" => TRUE,
                    "visibleOrder" => 2,
                    "presentation" => [
                        "size" => 33
                    ]
                ],
                [
                    "displayName" => "Фамилия",
                    "fieldName" => "cargoRecepientLastName",
                    "type" => "string",
                    "required" => TRUE,
                    "visibleOrder" => 1,
                    "presentation" => [
                        "size" => 33
                    ]
                ],
                [
                    "displayName" => "Отчество",
                    "fieldName" => "cargoRecepientSecondName",
                    "type" => "string",
                    "required" => FALSE,
                    "visibleOrder" => 3,
                    "presentation" => [
                        "size" => 33
                    ]
                ],

                [
                    "displayName" => "Телефон",
                    "fieldName" => "cargoRecepientPhone",
                    "type" => "string",
                    "required" => TRUE,
                    "pattern" => "/\+\d{1,3}\s\d{1,3}\s\d{1,3}\s\d{1,2}\s\d{1,2}/",
                    "hint" => "+x xxx xxx xx xx",
                    "visibleOrder" => 4,
                    "presentation" => [
                        "size" => 33
                    ]
                ],
                [
                    "displayName" => "E-mail",
                    "fieldName" => "cargoRecepientEmail",
                    "type" => "string",
                    "required" => TRUE,
                    "pattern" => "/[0-9a-zA-Z\.]+@[0-9a-zA-Z]+\.[0-9a-zA-Z\.]*/",
                    "hint" => "address@domain.country",
                    "visibleOrder" => 5,
                    "presentation" => [
                        "size" => 67
                    ]
                ],
                [
                    "displayName" => "Тип документа",
                    "fieldName" => "cargoRecepientDocumentTypeId",
                    "type" => "enum",
                    "visibleOrder" => 6,
                    "required" => TRUE,
                    "variants" => $this->docType,
                    "presentation" => [
                        "size" => 67
                    ]
                ],
                [
                    "displayName" => "Номер документа",
                    "fieldName" => "cargoRecepientDocumentNumber",
                    "type" => "string",
                    "required" => TRUE,
                    "pattern" => "/^\d{10}$/",
                    "hint" => "",//"ССССНННННН",
                    "visibleOrder" => 7,
                    "inputSize" => 10,
                    "presentation" => [
                        "size" => 33
                    ]
                ],



            ]
        ];

        /** Legal option parameters */
        $aRecipientOptions['recepientJurBlock'] = [
            "id" => "recepientJurBlock",
            "name" => "recepientJurBlock",
            "hidden" => true,
            "aoptions" => [
                [
                    "displayName" => "Наименование предприятия",
                    "fieldName" => "cargoRecepientCompanyName",
                    "type" => "string",
                    "required" => TRUE,
                    "visibleOrder" => 10,
                    "presentation" => [
                        "size" => 100
                    ]
                ],
                [
                    "displayName" => "Организационно-правовая форма",
                    "fieldName" => "cargoRecepientCompanyFormId",
                    "type" => "enum",
                    "required" => TRUE,
                    "variants" => $this->jurOKPF,
                    "visibleOrder" => 12,
                    "presentation" => [
                        "size" => 60
                    ]
                ],
                [
                    "displayName" => "Телефон",
                    "fieldName" => "cargoRecepientCompanyPhone",
                    "type" => "string",
                    "required" => TRUE,
                    "pattern" => "/\+\d{1,3}\s\d{1,3}\s\d{1,3}\s\d{1,2}\s\d{1,2}/",
                    "hint" => "+x xxx xxx xx xx",
                    "visibleOrder" => 13,
                    "presentation" => [
                        "size" => 40
                    ]
                ],
                [
                    "displayName" => "E-mail",
                    "fieldName" => "cargoRecepientCompanyEmail",
                    "type" => "string",
                    "required" => FALSE,
                    "pattern" => "/[0-9a-zA-Z\.]+@[0-9a-zA-Z]+\.[0-9a-zA-Z\.]*/",
                    "hint" => "address@domain.country",
                    "visibleOrder" => 14,
                    "presentation" => [
                        "size" => 60
                    ]
                ],
                [
                    "displayName" => "Юридический адрес: Индекс, город, улица, дом, корпус",
                    "fieldName" => "cargoRecepientCompanyAddress",
                    "type" => "string",
                    "required" => TRUE,
                    "hint" => "Пример: ул. Заветная, 1",
                    "visibleOrder" => 15,
                    "presentation" => [
                        "size" => 67
                    ]
                ],
                [
                    "displayName" => "Офис",
                    "fieldName" => "cargoRecepientCompanyAddressCell",
                    "type" => "int32",
                    "inputSize" => 15,
                    "visibleOrder" => 16,
                    "required" => false,
                    "presentation" => [
                        "size" => 33
                    ]
                ],



                [
                    "displayName" => "ИНН",
                    "fieldName" => "cargoRecepientCompanyINN",
                    "type" => "int32",
                    "required" => TRUE,
                    "inputSize" => 11,
                    "visibleOrder" => 11,
                    "presentation" => [
                        "size" => 40
                    ]
                ],
                [
                    "displayName" => "Имя контактного лица",
                    "fieldName" => "cargoRecepientContactFirstName",
                    "type" => "string",
                    "required" => TRUE,
                    "visibleOrder" => 2,
                    "presentation" => [
                        "size" => 50
                    ]
                ],
                [
                    "displayName" => "Фамилия контактного лица",
                    "fieldName" => "cargoRecepientContactLastName",
                    "type" => "string",
                    "required" => TRUE,
                    "visibleOrder" => 1,
                    "presentation" => [
                        "size" => 50
                    ]
                ]
            ]
        ];
        /** Recipient group
         * End block */

        /** Add Recipient group to Basic StandardGroup */
        $aStandardGroups['recepient'] = array(
            'name' => 'Получатель',
            'visibleOrder' => 4,
            'aoptions' => $aRecipientOptions
        );

        /** From group
         * @var  $aFromOptions */
        $aFromOptions = array();

        $aFromOptions['cargoFrom'] = array(
            "displayName" => "Откуда",
            "fieldName" => "cargoFrom",
            "type" => "string",
            "required" => TRUE
        );
        /** From group
         * End block */

        /** Add From group to Basic StandardGroup */
        $aStandardGroups['from'] = array(
            'name' => 'Откуда',
            'visibleOrder' => 0,
            'aoptions' =>//$aFromOptions
                [
                    [
                        "displayName" => "Откуда Область",
                        "fieldName" => "cargoFromRegion",
                        "type" => "string",
                        "required" => TRUE,
                        "hidden" => true,
                        "visibleOrder" => 0
                    ],
                    [
                        "displayName" => "Откуда",
                        "fieldName" => "cargoFrom",
                        "type" => "string",
                        "required" => TRUE,
                        "visibleOrder" => 1
                    ],
                    [
                        "displayName" => "Откуда Индекс",
                        "fieldName" => "cargoFromZip",
                        "type" => "string",
                        "required" => TRUE,
                        "hidden" => true,
                         "visibleOrder" => 2
                    ],

                ]
        );

        /** Sender group */
        $aSenderOptions = array();

        $aSenderOptions['cargoSenderPhys'] = array(
            "displayName" => "Тип отправителя",
            "fieldName" => "senderPhysOrJur",
            "type" => "enum",
            "required" => TRUE,
            "variants" => [
                [
                    "number" => 1,
                    "visible" => "Физическое лицо",
                    "makesVisible" => ["senderPhysblock"],
                    "makesInvisible" => ["senderJurblock"],
                    "selected" => true
                ],
                [
                    "number" => 2,
                    "visible" => "Юридическое лицо",
                    "makesVisible" => ["senderJurblock"],
                    "makesInvisible" => ["senderPhysblock"]
                ]
            ]
        );



        /** block for person */
        $aSenderOptions['senderPhysBlock'] = [
            "id" => "senderPhysblock",
            "name" => "senderPhysblock",
            "hidden" => false,
            "aoptions" => [
                [
                    "displayName" => "Имя",
                    "fieldName" => "cargoSenderFirstName",
                    "type" => "string",
                    "required" => TRUE,
                    "visibleOrder" => 2,
                    "presentation" => [
                        "size" => 33
                    ]
                ],
                [
                    "displayName" => "Фамилия",
                    "fieldName" => "cargoSenderLastName",
                    "type" => "string",
                    "required" => TRUE,
                    "visibleOrder" => 1,
                    "presentation" => [
                        "size" => 33
                    ]
                ],
                [
                    "displayName" => "Отчество",
                    "fieldName" => "cargoSenderSecondName",
                    "type" => "string",
                    "required" => FALSE,
                    "visibleOrder" => 3,
                    "presentation" => [
                        "size" => 33
                    ]
                ],
                [
                    "displayName" => "Телефон",
                    "fieldName" => "cargoSenderPhone",
                    "type" => "string",
                    "required" => TRUE,
                    "pattern" => "/\+\d{1,3}\s\d{1,3}\s\d{1,3}\s\d{1,2}\s\d{1,2}/",
                    "hint" => "+x xxx xxx xx xx",
                    "visibleOrder" => 4,
                    "presentation" => [
                        "size" => 33
                    ]
                ],
                [
                    "displayName" => "E-mail",
                    "fieldName" => "cargoSenderEmail",
                    "type" => "string",
                    "required" => FALSE,
                    "pattern" => "/[0-9a-zA-Z\.]+@[0-9a-zA-Z]+\.[0-9a-zA-Z\.]*/",
                    "hint" => "address@domain.country",
                    "visibleOrder" => 5,
                    "presentation" => [
                        "size" => 66
                    ]
                ],
                [
                    "displayName" => "Тип документа",
                    "fieldName" => "cargoSenderDocumentTypeId",
                    "type" => "enum",
                    "required" => TRUE,
                    "visibleOrder" => 6,
                    "variants" => $this->docType,
                    "presentation" => [
                        "size" => 66
                    ]
                ],
                [
                    "displayName" => "Номер документа",
                    "fieldName" => "cargoSenderDocumentNumber",
                    "type" => "string",
                    "required" => TRUE,
                    "visibleOrder" => 7,
                    "inputSize" => 10,
                    "pattern" => "/^\d{10}$/",
                    "hint" => "",//"ССССНННННН",
                    "presentation" => [
                        "size" => 33
                    ]
                ],


            ]
        ];

        /** block for legal */
        $aSenderOptions['senderJurBlock'] =
            [
                "id" => "senderJurblock",
                "name" => "senderJurblock",
                "hidden" => true,
                "aoptions" => [
                    [
                        "displayName" => "Наименование предприятия",
                        "fieldName" => "cargoSenderCompanyName",
                        "type" => "string",
                        "required" => TRUE,
                        "visibleOrder" => 10,
                        "presentation" => [
                            "size" => 100
                        ]
                    ],
                    [
                        "displayName" => "Организационно-правовая форма",
                        "fieldName" => "cargoSenderCompanyFormId",
                        "type" => "enum",
                        "required" => TRUE,
                        "variants" => $this->jurOKPF,
                        "visibleOrder" => 12,
                        "presentation" => [
                            "size" => 60
                        ]
                    ],
                    [
                        "displayName" => "Телефон",
                        "fieldName" => "cargoSenderCompanyPhone",
                        "type" => "string",
                        "required" => TRUE,
                        "pattern" => "/\+\d{1,3}\s\d{1,3}\s\d{1,3}\s\d{1,2}\s\d{1,2}/",
                        "hint" => "+x xxx xxx xx xx",
                        "visibleOrder" => 13,
                        "presentation" => [
                            "size" => 40
                        ]
                    ],
                    [
                        "displayName" => "E-mail",
                        "fieldName" => "cargoSenderCompanyEmail",
                        "type" => "string",
                        "required" => FALSE,
                        "pattern" => "/[0-9a-zA-Z\.]+@[0-9a-zA-Z]+\.[0-9a-zA-Z\.]*/",
                        "hint" => "address@domain.country",
                        "visibleOrder" => 14,
                        "presentation" => [
                            "size" => 60
                        ]
                    ],
                    [
                        "displayName" => "Юридический адрес: Индекс, город, улица, дом, корпус",
                        "fieldName" => "cargoSenderCompanyAddress",
                        "type" => "string",
                        "required" => TRUE,
                        "hint" => "Пример: ул. Заветная, 1",
                        "visibleOrder" => 15,
                        "presentation" => [
                            "size" => 67
                        ]
                    ],
                    [
                        "displayName" => "Офис",
                        "fieldName" => "cargoSenderCompanyAddressCell",
                        "type" => "int32",
                        "inputSize" => 15,
                        "visibleOrder" => 16,
                        "required" => false,
                        "presentation" => [
                            "size" => 33
                        ]
                    ],

                    [
                        "displayName" => "ИНН",
                        "fieldName" => "cargoSenderCompanyINN",
                        "type" => "int32",
                        "required" => TRUE,
                        "inputSize" => 11,
                        "visibleOrder" => 11,
                        "presentation" => [
                            "size" => 40
                        ]
                    ],
                    [
                        "displayName" => "Имя контактного лица",
                        "fieldName" => "cargoSenderContactFirstName",
                        "type" => "string",
                        "required" => TRUE,
                        "visibleOrder" => 2,
                        "presentation" => [
                            "size" => 50
                        ]
                    ],
                    [
                        "displayName" => "Фамилия контактного лица",
                        "fieldName" => "cargoSenderContactLastName",
                        "type" => "string",
                        "required" => TRUE,
                        "visibleOrder" => 1,
                        "presentation" => [
                            "size" => 50
                        ]
                    ]
                ]
            ];

        /** Sender group
        End block */

        /** Add Sender group to Basic StandardGroup */
        $aStandardGroups['sender'] = array(
            'name' => 'Отправитель',
            'visibleOrder' => 2,
            'aoptions' => $aSenderOptions
        );

        /** When group
         * @var  $aTimeOptions */

        $aTimeOptions = array();

        $aTimeOptions['cargoDesireDate'] = array(
            "displayName" => "Забрать груз",
            "fieldName" => "cargoDesireDate",
            "type" => "date",
            "presentation" => [
                "size" => 20
            ],
            "required" => FALSE,
            "inputSize" => 9,
            "pattern" => "/\d{1,2}\.\d{1,2}\.\d{1,4}/"
        );

        $aTimeOptions['cargoDeliveryDate'] = array(
            "displayName" => "Доставить груз",
            "fieldName" => "cargoDeliveryDate",
            "type" => "date",
            "presentation" => [
                "size" => 20
            ],
            "inputSize" => 9,
            "required" => FALSE,
            "pattern" => "/\d{1,2}\.\d{1,2}\.\d{1,4}/"
        );

        $aStandardGroups['when'] = array(
            'name' => 'Дата отгрузки/доставки',
            'visibleOrder' => 5,
            'aoptions' => $aTimeOptions
        );

        /** When group
         * End block */

        /**  Cargo group*/




        $aCargoOptions['cargoWeightTypeID'] = array(
            "displayName" => "Единица измерения веса",
            "fieldName" => "cargoWeightTypeID",
            "type" => "enum",
            "variants" => $this->aWidthTypes
        );


        $aCargoOptions['cargoVolTypeID'] = array(
            "displayName" => "Единица измерения объёма",
            "fieldName" => "cargoVolTypeID",
            "type" => "enum",
            "variants" => $this->aVolTypes

        );



        $aCargoOptions['cargoGoodsName'] = array(
            "displayName" => "Наименование груза",
            "fieldName" => "cargoGoodsName",
            "type" => "string",
            "visibleOrder" => 1,
            "required" => TRUE,
            "visibleOrder" => 1,
            "presentation" => [
                "size" => 100
            ]
        );

        $aCargoOptions['cargoWeight'] = array(
            "displayName" => "Вес",
            "fieldName" => "cargoWeight",
            "type" => "float",
            'visibleOrder' => 4,
            "recalcTotalPrice" => true,
            "required" => TRUE,
            "hidden" => FALSE,
            "presentation" => [
                "size" => 33
            ]
        );

        $aCargoOptions['cargoVol'] = array(
            "displayName" => "Объем",
            "fieldName" => "cargoVol",
            "type" => "float",
            "visibleOrder" => 5,
            "recalcTotalPrice" => true,
            "required" => TRUE,
            "hidden" => FALSE,
            "presentation" => [
                "size" => 33
            ]
        );

        $aCargoOptions['cargoGoodsPrice'] = array(
            "displayName" => "Ценность груза",
            "fieldName" => "cargoGoodsPrice",
            "type" => "float",
            "visibleOrder" => 6,
            "value" => 100,
            "recalcTotalPrice" => TRUE,
            "required" => TRUE,
            "hidden" => FALSE,
            "presentation" => [
                "size" => 33
            ]
        );

        $aCargoOptions['cargoWidth'] = array(
            "displayName" => "Ширина",
            "fieldName" => "cargoWidth",
            "hint" => "не более 2.4 м",
            "type" => "float",
            "visibleOrder" => 8,
            "inputSize" => 6,
            "focusOrder" => 0,
            "recalcTotalPrice" => true,
            "required" => true,
            "presentation" => [
                "size" => 33
            ]
        );

        $aCargoOptions['cargoLength'] = array(
            "displayName" => "Длина",
            "fieldName" => "cargoLength",
            "hint" => "не более 13.2 м",
            "type" => "float",
            "visibleOrder" => 7,
            "inputSize" => 6,
            "focusOrder" => 2,
            "recalcTotalPrice" => true,
            "required" => true,
            "presentation" => [
                "size" => 33
            ]

        );

        $aCargoOptions['cargoHeight'] = array(
            "displayName" => "Высота",
            "fieldName" => "cargoHeight",
            "type" => "float",
            "hint" => "не более 2.4 м",
            "visibleOrder" => 9,
            "inputSize" => 6,
            "focusOrder" => 444,
            "recalcTotalPrice" => true,
            "required" => true,
            "presentation" => [
                "size" => 33
            ]
        );

        $aCargoOptions['cargoTemperatureMode'] = array(
            "displayName" => "Температурные условия",
            "fieldName" => "cargoTemperatureModeId",
            "type" => "enum",
            "visibleOrder" => 2,
            "required" => FALSE,
            "presentation" => [
                "size" => 50
            ],
            "variants" => [
                [
                    "number" => 1,
                    "visible" => "Не имеет значения",
                ],
                [
                    "number" => 2,
                    "visible" => "Тепло",
                ],
                [
                    "number" => 3,
                    "visible" => "Холодно",
                ],
            ]
        );

        $aCargoOptions['cargoDangerClass'] = array(
            "displayName" => "Класс опасности",
            "fieldName" => "cargoDangerClassId",
            "type" => "enum",
            "visibleOrder" => 3,
            "required" => FALSE,
            "presentation" => [
                "size" => 50
            ],
            "variants" => $this->aDangerClasses
        );
        /**  Cargo group
        End block*/

        /** Add Sender group to Basic StandardGroup */
        $aStandardGroups['cargo'] = array(
            'name' => 'Груз',
            'visibleOrder' => 10,
            'aoptions' => $aCargoOptions
        );


        /** PaymentType */
        $aPaymentOptions['paymentType'] = [
            'displayName' => 'Способ оплаты',
            'fieldName' => 'paymentType',
            'type' => 'enum',
            'required' => TRUE,
            "recalcTotalPrice" => true,
            'variants' => [
                [
                    'number' => 1,
                    'visible' => 'Банковская карта VISA, MasterCard, МИР',
                    'description' => 'Скидка 1% от стоимости доставки.<br>Моментальное подтверждение оплаты.'
                ],
                [
                    'number' => 2,
                    'visible' => 'ЯндексДеньги',
                    'description' => 'Скидка 1% от стоимости доставки.<br>Моментальное подтверждение оплаты.'
                ],
                [
                    'number' => 3,
                    'visible' => 'Qiwi',
                    'description' => 'Скидка 1% от стоимости доставки.<br>Моментальное подтверждение оплаты.'
                ],

                [
                    'number' => 10,
                    'visible' => 'Счет на оплату',
                    'description' => 'Скидка 3,5% от стоимости доставки'
                ],
                [
                    'number' => 11,
                    'visible' => 'Оплата при сдаче отправителя',
                    'description' => 'Скидка не предусмотрена.<br>Услуга оплачивается при отправке груза.'
                ],
                [
                    'number' => 12,
                    'visible' => 'Оплата при получении отправителя',
                    'description' => 'Скидка не предусмотрена.<br>Услуга оплачивается при получении груза.'
                ],
            ]
        ];

        /** Payer */
        $aPaymentOptions['payerType'] = array(
            "displayName" => "Плательщик",
            "fieldName" => "payerType",
            "type" => "enum",
            "required" => true,
            "variants" => [
                [
                    "number" => 1,
                    "visible" => "Заказчик",
                    "standard"=> true,
                    "paymentType"=>
                        [
                            'displayName' => 'Способ оплаты',
                            'fieldName' => 'paymentType',
                            'type' => 'enum',
                            'required' => TRUE,
                            'variants' => [
                            [
                                'number' => 1,
                                'visible' => 'Банковская карта VISA, MasterCard, МИР',
                                'description' => 'Скидка 1% от стоимости доставки.<br>Моментальное подтверждение оплаты.'
                            ],
                            [
                                'number' => 2,
                                'visible' => 'ЯндексДеньги',
                                'description' => 'Скидка 1% от стоимости доставки.<br>Моментальное подтверждение оплаты.'
                            ],
                            [
                                'number' => 3,
                                'visible' => 'Qiwi',
                                'description' => 'Скидка 1% от стоимости доставки.<br>Моментальное подтверждение оплаты.'
                            ],

                            [
                                'number' => 10,
                                'visible' => 'Счет на оплату',
                                'description' => 'Скидка 3,5% от стоимости доставки'
                            ],
                            [
                                'number' => 11,
                                'visible' => 'Оплата при сдаче отправителя',
                                'description' => 'Скидка не предусмотрена.<br>Услуга оплачивается при отправке груза.'
                            ],
                            [
                                'number' => 12,
                                'visible' => 'Оплата при получении отправителя',
                                'description' => 'Скидка не предусмотрена.<br>Услуга оплачивается при получении груза.'
                            ]
                        ]
                    ]
                ],
                [
                    "number" => 2,
                    "visible" => "Отправитель",
                    "standard"=> false,
                    "paymentType"=>[
                        'displayName' => 'Способ оплаты',
                        'fieldName' => 'paymentType',
                        'type' => 'enum',
                        'required' => TRUE,
                        'variants' =>
                            [
                                [
                                    'number' => 10,
                                    'visible' => 'Счет на оплату',
                                    'description' => 'Скидка 3,5% от стоимости доставки'
                                ],
                                [
                                    'number' => 11,
                                    'visible' => 'Оплата при сдаче отправителя',
                                    'description' => 'Скидка не предусмотрена.<br>Услуга оплачивается при отправке груза.'
                                ],
                                [
                                    'number' => 12,
                                    'visible' => 'Оплата при получении отправителя',
                                    'description' => 'Скидка не предусмотрена.<br>Услуга оплачивается при получении груза.'
                                ]
                            ]
                    ]
                ],
                [
                    "number" => 3,
                    "visible" => "Получатель",
                    "standard"=> true,
                    "paymentType"=>[
                        'displayName' => 'Способ оплаты',
                        'fieldName' => 'paymentType',
                        'type' => 'enum',
                        'required' => TRUE,
                        'variants' =>
                            [
                                [
                                    'number' => 10,
                                    'visible' => 'Счет на оплату',
                                    'description' => 'Скидка 3,5% от стоимости доставки'
                                ],
                                [
                                    'number' => 12,
                                    'visible' => 'Оплата при получении отправителя',
                                    'description' => 'Скидка не предусмотрена.<br>Услуга оплачивается при получении груза.'
                                ]
                            ]
                    ]
                ]
            ]
        );


        /*         // PaymentType /
               $aPaymentOptions['paymentType'] = [
                   'displayName' => 'Способ оплаты',
                   'fieldName' => 'paymentType',
                   'type' => 'enum',
                   'required' => TRUE,
                   'variants' => [
              [
                   'number' => 1,
                   'visible' => 'оплата банковской картой, электронный кошелек - 3%'
               ],
               [
                   'number' => 2,
                   'visible' => 'безналичная оплата на основании счета - 2%'
               ],
               [
                   'number' => 3,
                   'visible' => 'оплата в кассу транспортной компании 0 %'
               ],

           ]
       ];*/

        $aStandardGroups['payment'] = array(
            'name' => 'Способ оплаты',
            'visibleOrder' => 13,
            'aoptions' => $aPaymentOptions
        );
        /** PaymentType
        End block*/


        $aStandardOptions['success'] = 'ok';
        $aStandardOptions['groups'] = $aStandardGroups;

        return $aStandardOptions;
    }

    public function MakeOrder($sCityFrom,
                              $sCityTo,
                              $sCargoFromZip,
                              $sCargoToZip,
                              $sCargoFromRegion,
                              $sCargoToRegion,
                              $weight,
                              $vol,
                              $insPrice,
                              $length,
                              $width,
                              $height,
                              $cargoName,
                              $cargoDate,
                              $oOptions,

                              $isRecipientJur,
                              $sRecipientUserFIO,
                              $iRecipientDocumentTypeId,
                              $sRecipientDocumentNumber,
                              $sRecipientPhone,
                              $sRecipientEmail,
                              $iRecipientTerminalID,
                              $sRecipientCompanyName,
                              $sRecipientCompanyFormShortName,
                              $sRecipientCompanyINN,
                              $sRecipientCompanyAddress,
                              $sRecipientCompanyAddressCell,
                              $sRecipientCompanyPhone,
                              $sRecipientCompanyEmail,
                              $sRecipientContactFIO,

                              $sRecipientAddress,
                              $sRecipientAddressCell,

                              $isSenderJur,
                              $sSenderUserFIO,
                              $iSenderDocumentTypeId,
                              $sSenderDocumentNumber,
                              $sSenderPhone,
                              $sSenderEmail,
                              $iSenderTerminalID,
                              $sSenderCompanyName,
                              $sSenderCompanyFormShortName,
                              $sSenderCompanyINN,
                              $sSenderCompanyAddress,
                              $sSenderCompanyAddressCell,
                              $sSenderCompanyPhone,
                              $sSenderCompanyEmail,
                              $sSenderContactFIO,

                              $sSenderAddress,
                              $sSenderAddressCell,

                              $isDerivalCourier,
                              $isArrivalCourier,
                              $dCargoDesireDate,
                              $dCargoDeliveryDate
    )
    {
        // make order with options
        return false;
    }

    public function GetRequisites()
    {
        // get requisites of company
        return array();
    }

    public function GetOrderState($sOrderID)
    {
        // get order state by ID
        return 0;
    }
}

?>
