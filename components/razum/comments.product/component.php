<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Loader; 
Loader::includeModule("highloadblock"); 
use Bitrix\Highloadblock as HL; 
use Bitrix\Main\Entity;
$hlbl = intval($arParams['ID_HIGH_BLOCK']);
$hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch(); 

$entity = HL\HighloadBlockTable::compileEntity($hlblock); 
$entity_data_class = $entity->getDataClass(); 

$rsData = $entity_data_class::getList(array(
   "select"     => array("*"),
   "order"      => array("ID" => "ASC"),
   "filter"     => array("UF_ID_PRODUCT"=>$arParams['ID_PRODUCT'],"UF_ACTIVE"=>'1')
));

/* Block rating */
$stars[1] = 0;
$stars[2] = 0;
$stars[3] = 0;
$stars[4] = 0;
$stars[5] = 0;
/* END */

while($arData = $rsData->Fetch()){
    $stars[intval($arData['UF_SCORES'])] = $stars[intval($arData['UF_SCORES'])] + 1;
    $arItem['ID']                   = $arData['ID'];
    $arItem['NAME']                 = $arData['UF_NAME'];
    $arItem['SCORES']               = $arData['UF_SCORES'];
    $arItem['ID_PARENT_COMMENT']    = $arData['UF_ID_PARENT_COMMENT'];
    $arItem['WORTH']                = $arData['UF_WORTH'];
    $arItem['DISADVANTAGES']        = $arData['UF_DISADVANTAGES'];
    $arItem['EMAIL']                = $arData['UF_EMAIL'];
    $arItem['PHOTOS']               = $arData['UF_PHOTOS'];
    $arItem['COMMENT']              = $arData['UF_COMMENT'];
    $arItem['DATE']                 = $arData['UF_DATE']->format("D M Y");
    if($arData['UF_ID_PARENT_COMMENT'] == ''){
        $arResult['ITEMS'][] = $arItem; 
    } else {
       $arResult['CHILD'][] = $arItem; 
    }        
}

/* Find child comments */
foreach ($arResult['ITEMS'] as $keyItems => $valueItems){
    foreach ($arResult['CHILD'] as $keyChild => $valueChild) {
        if($valueItems['ID'] == $valueChild['ID_PARENT_COMMENT']){
            $arResult['ITEMS'][$keyItems]['CHILD'][] = $arResult['CHILD'][$keyChild];
        }
    }
}
unset($arResult['CHILD']);
/* END */

$arResult['FULL_SCORE'] = $stars;
$this->IncludeComponentTemplate(); 
?>
