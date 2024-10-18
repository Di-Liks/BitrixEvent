<?php
    AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", "OnBeforeElementUpdateHandler");

    function OnBeforeElementUpdateHandler(&$arFields) {
        $iblockModelsId = 6; // ID инфоблока "Модели авто"
        $iblockArchiveId = 7; // ID инфоблока "Архив моделей авто"
        
        AddMessage2Log("Обработчик сработал для элемента ID: " . $arFields['ID']);
        
        if ($arFields['IBLOCK_ID'] == $iblockModelsId) {
            AddMessage2Log("Работаем с инфоблоком Модели авто.");

            $element = CIBlockElement::GetByID($arFields['ID'])->GetNextElement();
            if ($element) {
                $arProps = $element->GetProperties();
                $elementFields = $element->GetFields();
                
                // Проверяем значение поля "архивировать"
                if (!empty($arFields['PROPERTY_VALUES']['ARCHIVE_AUTO']) && $arFields['PROPERTY_VALUES']['ARCHIVE_AUTO'][0] == '1') {
                    AddMessage2Log("Архивируем элемент.");

                    $el = new CIBlockElement;
                    $arLoadArray = array(
                        "IBLOCK_ID"      => $iblockArchiveId,
                        "NAME"           => $elementFields['NAME'],
                        "PROPERTY_VALUES"=> array(
                            "MARK_AUTO"  => $arProps['MARK_AUTO']['VALUE'],
                            "MODEL_AUTO" => $arProps['MODEL_AUTO']['VALUE'],
                            "PHOTO_AUTO" => $arProps['PHOTO_AUTO']['VALUE'],
                        ),
                        "ACTIVE"         => "Y",
                    );
                    
                    if ($newElementId = $el->Add($arLoadArray)) {
                        AddMessage2Log("Элемент успешно добавлен в архив с ID: " . $newElementId);
                    } else {
                        AddMessage2Log("Ошибка добавления элемента в архив: " . $el->LAST_ERROR);
                    }
                } else {
                    AddMessage2Log("Свойство ARCHIVE_AUTO не установлено или его значение не '1'.");
                }
            }
        }
    }
?>
