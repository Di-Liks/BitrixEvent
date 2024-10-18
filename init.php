<?php
    AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", "OnBeforeElementUpdateHandler");

    function OnBeforeElementUpdateHandler(&$arFields) {
        // ID инфоблоков
        $iblockModelsId = 6; // ID инфоблока "Модели авто"
        $iblockArchiveId = 7; // ID инфоблока "Архив моделей авто"
        
        // Проверяем, обновляется ли элемент в инфоблоке "Модели авто"
        if ($arFields['IBLOCK_ID'] == $iblockModelsId) {
            
            // Получаем текущее значение элемента
            $element = CIBlockElement::GetByID($arFields['ID'])->GetNext();
            
            // Проверяем, установлено ли поле "архивировать" в значение "Архивировать"
            if ($arFields['PROPERTY_VALUES']['ARCHIVE_AUTO'][0]['VALUE_XML_ID'] == '1') {
                // Если да, то создаем элемент в инфоблоке "Архив моделей авто"
                $el = new CIBlockElement;
                
                // Параметры нового элемента
                $arLoadArray = array(
                    "IBLOCK_ID"      => $iblockArchiveId,
                    "NAME"           => $element['NAME'], // Имя модели
                    "PROPERTY_VALUES"=> array(
                        "MARK_AUTO"  => $element['PROPERTY_MARKA_AUTO_VALUE'], // Марка
                        "MODEL_AUTO" => $element['PROPERTY_MODEL_AUTO_VALUE'], // Модель
                        "PHOTO_AUTO" => $element['PROPERTY_PHOTO_AUTO_VALUE'], // Фото
                    ),
                    "ACTIVE"         => "Y", // Активность элемента
                );
                
                // Добавляем элемент в архивный инфоблок
                if ($newElementId = $el->Add($arLoadArray)) {
                    // Элемент успешно добавлен в архив
                    AddMessage2Log("Элемент добавлен в архив с ID: ".$newElementId);
                } else {
                    // Ошибка при добавлении элемента
                    AddMessage2Log("Ошибка добавления элемента в архив: ".$el->LAST_ERROR);
                }
            }
        }
    }
?>
