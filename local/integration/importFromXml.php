<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
pre('start');
#-------------ТУТ ВАШ КОД
#Что нужно сделать: Загрузить XML в PHP (рекомендую simplexml_load_file). Далее вывести на экран каждый элемент со свойствами
#Цель: Аккуратно окунуть Вас в работу с самим PHP и посмотреть у кого возникнут сложности с чистым PHP. Далее мы имея данные в массивах/обьектах научимся загружать это непосредственно в Bitrix.
#-------------КОНЕЦ КОДА

if (file_exists('data/data.xml')) {
    $xml = simplexml_load_file('data/data.xml');
    foreach ($xml->product as $product) {
        foreach ($product as $item) {
            echo $item;
            if (is_object($item)) {
                foreach ($item as $it) {
                    echo $it;
                    if (is_object($it)) {
                        foreach ($it as $i) {
                            echo $i;
                            if (is_object($i)) {
                                foreach ($i as $offer) {
                                    echo $offer;
                                    echo "<br>";
                                }
                            }
                            echo "<br>";
                        }
                    }
                    echo "<br>";
                }
            }
            //var_dump($item);

            foreach ($item->OFFERS as $offer) {
                echo $offer;
                echo "<br>";
            }

            echo "<br>";
        }
        echo "____________________________________________________";
        echo "<br>";
    }
} else {
    exit('Не удалось открыть файл data.xml.');
}
pre('done.');