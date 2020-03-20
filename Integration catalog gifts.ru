<?
set_time_limit(0);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if($curlTree = curl_init() ):
    $urlTree    = "http://login:passwords@api2.gifts.ru/export/v2/catalogue/catalogue.xml";
    curl_setopt($curlTree, CURLOPT_URL, $urlTree);
    curl_setopt($curlTree, CURLOPT_RETURNTRANSFER,true);
    $outTree = curl_exec($curlTree);
    $arraySec = simplexml_load_string($outTree);
    curl_close($curlTree);
endif;

//ID инфоблока каталога и инфоблока торговых предложений    
$IBLOCK_ID = ;
$IBLOCK_ID_SELL = ;

foreach ($arrayProduct->page as $value) {
    foreach ($value->page as $valuePage) {
        foreach ($valuePage->page as $valuePage1) {
            foreach ($valuePage1->product as $product) {
                $arrayProductArr[$valuePage1->uri->__toString()][] = $product->product->__toString();
            }
        } 
    }
}

foreach ($arrayProductArr as $key => $productArr) {
    foreach ($arraySec as $valueP) {
        if (in_array($valueP->product_id->__toString(),$productArr)) {
            $valueP->iblock_section_id = $key;
        }
    }
}

foreach ($arraySec as $key => $value) {

    foreach ($value->pack as $amount) {
        $packAmount = $amount->amount->__toString();
        $packWeight = $amount->weight->__toString();
        $packVolume = $amount->volume->__toString();
        $packSize = $amount->sizex->__toString()."x".$amount->sizey->__toString()."x".$amount->sizez->__toString();
    }

    foreach ($value->print as $print) {
        $printArr[] = $print->name->__toString()."-".$print->description->__toString();
    }
    foreach ($value->product_attachment as $product_attachment) {
        $product_attachmentArr[] = CFile::MakeFileArray("http://43068_xmlexport:W26DdC61@api2.gifts.ru/export/v2/catalogue/".$product_attachment->image->__toString());
    }

    foreach ($value->price as $priceEl) {
        $price = $priceEl->value->__toString();
    }

    $el = new CIBlockElement;   
    $PROP = array();
    $PROP[273] = $value->code->__toString(); 
    $PROP[274] = $value->product_id->__toString();
    $PROP[275] = $value->product_size->__toString();
    $PROP[276] = $value->matherial->__toString();
    $PROP[277] = $value->brand->__toString();
    $PROP[278] = $value->weight->__toString();
    $PROP[280] = $packAmount;
    $PROP[281] = $packWeight;
    $PROP[282] = $packVolume;
    $PROP[283] = $packSize;
    $PROP[279] = $printArr;
    $PROP[284] = $product_attachmentArr;

    $params = Array(
        "max_len" => "100", // обрезает символьный код до 75 символов
        "change_case" => "L", // буквы преобразуются к нижнему регистру
        "replace_space" => "-", // меняем пробелы на нижнее подчеркивание
        "replace_other" => "-", // меняем левые символы на нижнее подчеркивание
        "delete_repeat_replace" => "true", // удаляем повторяющиеся нижние подчеркивания
        "use_google" => "false", // отключаем использование google
         );
         $symCode = Cutil::translit($value->name->__toString(),"ru",$params);

    $arLoadProductArray = Array(
        "MODIFIED_BY"    => $USER->GetID(),
        "IBLOCK_SECTION_ID" => false,
        "IBLOCK_ID"      => $IBLOCK_ID,
        "PROPERTY_VALUES"=> $PROP,
        "NAME"           => $value->name->__toString(),
        "CODE"           => $symCode,
        "ACTIVE"         => "Y",
        "DETAIL_TEXT_TYPE"	=> "html",
        "DETAIL_TEXT"    => $value->content->__toString()
        );

    if($PRODUCT_ID = $el->Add($arLoadProductArray)):
      echo "New ID: ".$PRODUCT_ID;

      $arFields = array(
        "ID" => $PRODUCT_ID, 
        "VAT_ID" => 1, //тип ндс
        "VAT_INCLUDED" => "Y" //НДС входит в стоимость
        );

    if(CCatalogProduct::Add($arFields)):
        //echo "Добавил параметры товара к элементу каталога ".$PRODUCT_ID.'<br>';
    else:
        echo 'Ошибка добавления параметров<br>';
    endif;

    $PRICE_TYPE_ID = 1;
    $arFields = Array(
        "PRODUCT_ID" => $PRODUCT_ID,
        "CATALOG_GROUP_ID" => $PRICE_TYPE_ID,
        "PRICE" => $price,
        "CURRENCY" => "RUB"
    );

    $res = CPrice::GetList(
        array(),
        array(
            "PRODUCT_ID" => $PRODUCT_ID,
            "CATALOG_GROUP_ID" => $PRICE_TYPE_ID
        )
    );
    
    if ($arr = $res->Fetch())
    {
        CPrice::Update($arr["ID"], $arFields);
        echo "Цена обновлена";
    }
    else
    {
        CPrice::Add($arFields);
        echo "Цена добавлена";
    }

    else:
      echo "Error: ".$el->LAST_ERROR;
    endif;

    foreach ($value->product as $value_sell) {
        $newIblockElement = new CIBlockElement;
        $PROP_SELL = array();
        $PROP_SELL[272] = $PRODUCT_ID;
        $PROP_SELL[285] = $value_sell->product_id->__toString();
        $PROP_SELL[286] = $value_sell->size_code->__toString();
        $PROP_SELL[287] = $value_sell->weight->__toString();
        // добавляем нужное кол-во торговых предложений
        $arLoadProductArray = array(
            "IBLOCK_ID"      => $IBLOCK_ID_SELL, // IBLOCK торговых предложений
            "NAME"           => $value_sell->name->__toString(),
            "ACTIVE"         => "Y",
            'PROPERTY_VALUES' => $PROP_SELL
        );
        $product_offer_id = $newIblockElement->Add($arLoadProductArray);
        // проверка на ошибки
        if (!empty($newIblockElement->LAST_ERROR)) {
            echo "Ошибка добавления торгового предложения: ". $newIblockElement->LAST_ERROR;
            die();
        }
        // Добавляем параметры к торг. предложению
        CCatalogProduct::Add(
            array(
                "ID" => $product_offer_id,
                "VAT_ID" => 1, //тип ндс
                "VAT_INCLUDED" => "Y" //НДС входит в стоимость
            )
        );
        // Добавляем цены к торг. предложению
        foreach ($value->price as $value_price) {
            $arFields = array(
                "CURRENCY" => "RUB",
                "PRICE" => $value_price->price->__toString(),
                "CATALOG_GROUP_ID" => 1,
                "PRODUCT_ID" => $product_offer_id
            );
            CPrice::Add($arFields);
        }
    }
    /*echo "<pre>";
    print_r ($value);
    echo "</pre>";*/
break;
}
?>
