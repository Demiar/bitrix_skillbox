<?php
/**
 * Created by PhpStorm.
 * User: BelyevAlexey
 * Date: 12.06.2018
 * Time: 16:41
 */
if ($soap = new SoapClient("https://api.n11.com/ws/CityService.wsdl")){
    $city = $soap->GetCities();
    $result = $city->cities->city;
    for($i = 0; $i < count($result); $i++){
        //echo "ID - ".$result[$i]->cityId;
        //echo "   ";
        echo "CITY_NAME - ".$result[$i]->cityName;
        echo "<br>";
    }
    echo "<pre>";
    //var_dump($result);
    echo "</pre>";
}else{
    echo "error";
}