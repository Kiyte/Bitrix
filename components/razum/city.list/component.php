<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?
$arSelect = Array("ID", "NAME");
$arFilter = Array("IBLOCK_ID"=>IntVal($arParams['IBLOCK_ID']), "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
if ($this->StartResultCache())
{
    if (CModule::IncludeModule("iblock")):
        $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
        while($ob = $res->GetNextElement())
        {
            $arFields = $ob->GetFields();
            $arResult['ITEMS'][] = $arFields;
        }
    endif;
    if ($arParams["CACHE"] != 'Y'){
        $this->AbortResultCache();
    }
    $arResult['IBLOCK_ID'] = intval($arParams['IBLOCK_ID']);
}
$this->IncludeComponentTemplate();
?>