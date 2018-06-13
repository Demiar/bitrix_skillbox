<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
pre('start');
#-------------ТУТ ВАШ КОД
#Что нужно сделать: Загрузить XML в PHP (рекомендую simplexml_load_file). Далее вывести на экран каждый элемент со свойствами
#Цель: Аккуратно окунуть Вас в работу с самим PHP и посмотреть у кого возникнут сложности с чистым PHP. Далее мы имея данные в массивах/обьектах научимся загружать это непосредственно в Bitrix.
#-------------КОНЕЦ КОДА
$el = new CIBlockElement;
$els = new CCatalogProduct;
if (file_exists('data/data.xml')) {
    $xml = simplexml_load_file('data/data.xml');
    foreach ($xml->product as $product) {
        $id = $product->OLDID;
        $name = $product->NAME;
        $code = $product->CODE;
        $description = $product->DESCRIPTION;
        $images = array();
        foreach ($product->IMAGES as $image=>$img){
            for ($i = 0; $i < count($img); $i++){
                array_push($images, $img->OPTION[$i]);
            }
        }
        $arFields = array(
            "NAME" => $name,
            "CODE" => $code,
            "DETAIL_TEXT" => $description,
            "IBLOCK_ID" => 4,
        );
        $item = $el->Add($arFields);
        var_dump($item);
        if (!$item){
            break;
        }
        foreach ($product->OFFERS as $offers=>$offer) {
            for ($i = 0; $i < count($offer); $i++) {
                $sizeField = $offer->OFFER[$i]->SIZE_FIELD;
                $gameType = $offer->OFFER[$i]->GAME_TYPE;
                $arFields = array(
                    "NAME" => $name,
                    "CODE" => $code,
                    "DETAIL_TEXT" => $description,
                    "IBLOCK_ID" => 5,
                    "PROPERTY_VALUES" => array(
                        "SIZE_FIELD" => $sizeField,
                        "GAME_TYPE" => $gameType,
                        "CML2_LINK" => $item,
                    ),
                );
                $off = $el->Add($arFields);
            }
        }
        break;
    }
} else {
    exit('Не удалось открыть файл data.xml.');
}
pre('done.');