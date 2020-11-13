<?require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");?>
<?
$arFields['ACTION_TYPE'] = $_REQUEST['action_type'];
$arFields['ID']          = $_REQUEST['element'];
$arFields['NAME']        = $_REQUEST['name'];
$arFields['IBLOCK_ID']   = $_REQUEST['iblock-id'];
if (CModule::IncludeModule("iblock")):
    if ($arFields['ACTION_TYPE'] == "delete") {
        CIBlockElement::Delete(intval($arFields['ID']));
    } elseif($arFields['ACTION_TYPE'] == "add"){
        $el = new CIBlockElement;
        $params = Array(
            "max_len" => "100", 
            "change_case" => "L", 
            "replace_space" => "_", 
            "replace_other" => "_", 
            "delete_repeat_replace" => "true", 
            "use_google" => "false",
         );
        $arLoadProductArray = Array(
            "MODIFIED_BY"    => $USER->GetID(), 
            "IBLOCK_SECTION_ID" => false,          
            "IBLOCK_ID"      => intval($arFields['IBLOCK_ID']),
            "NAME"           => $arFields['NAME'],
            "CODE"           => CUtil::translit($arFields['NAME'], "ru" , $params),
            "ACTIVE"         => "Y",
        );
        $PRODUCT_ID = $el->Add($arLoadProductArray);
    }
endif;
?>

<?$APPLICATION->IncludeComponent("razum:city.list",".default",array(
    "IBLOCK_ID" => 12,
    "CACHE"     => 'Y'
))?>