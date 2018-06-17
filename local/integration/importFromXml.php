<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
pre('start');
#-------------ТУТ ВАШ КОД
#Что нужно сделать: Загрузить XML в PHP (рекомендую simplexml_load_file). Далее вывести на экран каждый элемент со свойствами
#Цель: Аккуратно окунуть Вас в работу с самим PHP и посмотреть у кого возникнут сложности с чистым PHP. Далее мы имея данные в массивах/обьектах научимся загружать это непосредственно в Bitrix.
#-------------КОНЕЦ КОДА
$el = new CIBlockElement;
$els = new CCatalogProduct;
//$catalogProduct = new CCatalogProduct;
$cPrice = new CPrice;
$file = new CFile;
if (file_exists('data/data.xml')) {
    $xml = simplexml_load_file('data/data.xml');

    foreach ($xml->product as $product) {
     echo($product->SYKNO->VARIANT[0]);
//break;
        $id = $product->OLDID;
        $name = $product->NAME;
        $code = $product->CODE;
        $description = $product->DESCRIPTION;
        $imagesPath = array();
        $images = array("https://cameralabs.org/media/k2/items/cache/33cf117627c1f4c261fc668625a54f91_L.jpg", "https://cameralabs.org/media/lab18/05/28/fotokonkurs-Comedy-Wildlife-Photography-Awards-2018_2.jpg");
        foreach ($product->IMAGES as $image=>$img){
            for ($i = 0; $i < count($img); $i++){
                //array_push($images, $img->OPTION[$i]);
                $image = $file->MakeFileArray($images[$i]);
                $result = $file->SaveFile($image, "temp");
                $path = $file->GetPath($result);
                array_push($imagesPath, $path);
                //var_dump($path);
            }
        }
        //var_dump($imagesPath);
        //break;

        //var_dump($images);
        //break;
        $arFields = array(
            "NAME" => $name,
            "CODE" => $code,
            "DETAIL_TEXT" => $description,
            "IBLOCK_ID" => 4,
            "DETAIL_PICTURE" => $file->MakeFileArray($imagesPath[0]),
        );
        $item = $el->Add($arFields);
        //$propAdd = $el->SetPropertyValuesEx($item, false, array("SYKNO" => "e81f37f73ee7270a40712195dcf4a99f"/*$product->SYKNO->VARIANT[0]*/));
        //var_dump($propAdd);
        var_dump($item);
        for($i = 0; $i < count($imagesPath); $i++){
            $arFields2 = array(
                "PROPERTY_VALUES" => array(
                    "PHOTO" => $file->MakeFileArray($imagesPath[$i]),
                    "SYKNO" => $product->SYKNO->VARIANT[0],
                ),

            );
            $el->UPDATE($item, $arFields2);
        }
        if (!$item){
            break;
        }
        foreach ($product->OFFERS as $offers=>$offer) {
            for ($i = 0; $i < count($offer); $i++) {
                $sizeField = $offer->OFFER[$i]->SIZE_FIELD;
                $gameType = $offer->OFFER[$i]->GAME_TYPE;
                $ves = $offer->OFFER[$i]->VES;
                $price = $offer->OFFER[$i]->PRICE;
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
                //var_dump($off);
                $arfield =array(
                    "ID" => $off,
                    "WEIGHT" => $ves,
                );
                $off1 = $els->Add($arfield);
                //var_dump($off1);
                $arFieldss = array(
                    "ID" => $off,
                    "PRICE" => $price,
                    "CURRENCY" => "RUB",
                );


                // Ненавижу Битрикс, Просидел пару часов, без этой схемы не добавляются цены
                $CURRENCY = "RUB";
                $dbProduct = CCatalogProduct::GetList( array(),array('ID' => $off) );
                if($arProduct = $dbProduct->Fetch()) {
                    if (!empty($price)) $id = CPrice::SetBasePrice($off, $price, $CURRENCY);
                }
                else {
                    $arFields1 = array(
                        "ID" => $off,
                        // "VAT_ID" => 1, //выставляем тип ндс (задаётся в админке)
                        // "VAT_INCLUDED" => "Y" //НДС входит в стоимость
                    );
                    if(CCatalogProduct::Add($arFields1)){
                        if (!empty($price)) $id = CPrice::SetBasePrice($off, $price, $CURRENCY);
                    }
                }



                //var_dump($APPLICATION->GetException());
                //$addPrice = $cPrice->Update(1, $arFieldss);
                //var_dump($addPrice);
				//var_dump($APPLICATION->GetException());
            }
        }
        break;
    }
} else {
    exit('Не удалось открыть файл data.xml.');
}
pre('done.');

$db_props = CIBlockElement::GetProperty("2","31" , "sort", "asc", array());
$PROPS = array();
while($ar_props = $db_props->Fetch())
{
	$PROPS[$ar_props['CODE']] = $ar_props['VALUE'];
}
pre($PROPS);

$arSelect = Array("ID", "NAME", "DATE_ACTIVE_FROM");
$arFilter = Array( "ID" => 388);
$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), array());
while($ob = $res->GetNextElement())
{
    $arFields = $ob->GetFields();
    //pre($arFields);
}


