<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
	$arFilter = array(		
	    'ACTIVE' => 'Y',
	    'IBLOCK_ID' => intval($arParams['IBLOCK_ID']),
	    'GLOBAL_ACTIVE'=>'Y'
	);
	$arSelect = array('IBLOCK_ID','ID','NAME','DEPTH_LEVEL','IBLOCK_SECTION_ID','SECTION_PAGE_URL','CODE');
	$arOrder = array('DEPTH_LEVEL'=>'ASC','SORT'=>'ASC');
	$rsSections = CIBlockSection::GetList($arOrder, $arFilter, false, $arSelect);
	$sectionLinc = array();
	$arResult['ROOT'] = array();
	$sectionLinc[0] = &$arResult['ROOT'];
	while($arSection = $rsSections->GetNext()) {
	    $sectionLinc[intval($arSection['IBLOCK_SECTION_ID'])]['CHILD'][$arSection['ID']] = $arSection;
	    $sectionLinc[$arSection['ID']] = &$sectionLinc[intval($arSection['IBLOCK_SECTION_ID'])]['CHILD'][$arSection['ID']];
	}
	$arResult = $sectionLinc[0]['CHILD']['3416'];


	/*-------------*/
 	$this->IncludeComponentTemplate(); 
 	/*-------------*/

 	
	?>