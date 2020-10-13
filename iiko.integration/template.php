<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
use \Bitrix\Main\Localization\Loc;
$whatColor = 0;
if ($arResult["ITEMS"]) {
    ?>
    <? $regs = array(); ?>
    <div class="bx_filter bx_filter_vertical">
        <div class="bx_filter_section">
            <form name="<? echo $arResult["FILTER_NAME"] . "_form" ?>" action="<? echo $arResult["FORM_ACTION"].'catalog-main/' ?>"
                  method="get" class="smartfilter">
                <h2 class="sub-title">Фильтр</h2>
                <input type="hidden" name="del_url" id="del_url"
                       value="<? echo str_replace('/filter/clear/apply/', '/', $arResult["SEF_DEL_FILTER_URL"]); ?>"/>
                <? foreach ($arResult["HIDDEN"] as $arItem):?>
                    <input type="hidden" name="<?
                    echo $arItem["CONTROL_NAME"] ?>" id="<?
                    echo $arItem["CONTROL_ID"] ?>" value="<?
                    echo $arItem["HTML_VALUE"] ?>"/>
                <?endforeach;
                $isFilter = false;
                $numVisiblePropValues = 5;
                //prices
                if (stripos($_SERVER["REQUEST_URI"], "/mapall/") === false):
                    foreach ($arResult["ITEMS"] as $key => $arItem) {
                        $key = $arItem["ENCODED_ID"];
                        if (isset($arItem["PRICE"])):
                            if ($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"] <= 0)
                                continue;
                            ?>
                            <div class="filter__item">
                                <span class="bx_filter_container_modef"></span>
                                <div class="filter__btn">
                                    <span><?= (count($arParams['PRICE_CODE']) > 1 ? $arItem["NAME"] : Loc::getMessage("PRICE")); ?></span>
                                    <i class="fas arrow-switch fa-angle-down"></i>
                                </div>

                                <div class="filter-box" style="display: none;position: relative;">
                                    <div class="bx_filter_parameters_box_container numbers">
                                        <div class="wrapp_all_inputs wrap_md">
                                            <?
                                            $isConvert = false;
                                            if ($arParams["CONVERT_CURRENCY"] == "Y") {
                                                $isConvert = true;
                                            }
                                            $price1 = $arItem["VALUES"]["MIN"]["VALUE"];
                                            $price2 = $arItem["VALUES"]["MIN"]["VALUE"] + round(($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"]) / 4);
                                            $price3 = $arItem["VALUES"]["MIN"]["VALUE"] + round(($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"]) / 2);
                                            $price4 = $arItem["VALUES"]["MIN"]["VALUE"] + round((($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"]) * 3) / 4);
                                            $price5 = $arItem["VALUES"]["MAX"]["VALUE"];

                                            if ($isConvert) {
                                                $price1 = SaleFormatCurrency($price1, $arParams["CURRENCY_ID"], true);
                                                $price2 = SaleFormatCurrency($price2, $arParams["CURRENCY_ID"], true);
                                                $price3 = SaleFormatCurrency($price3, $arParams["CURRENCY_ID"], true);
                                                $price4 = SaleFormatCurrency($price4, $arParams["CURRENCY_ID"], true);
                                                $price5 = SaleFormatCurrency($price5, $arParams["CURRENCY_ID"], true);
                                            }
                                            ?>
                                            <div class="wrapp_change_inputs iblock">
                                                <div class="bx_filter_parameters_box_container_block">
                                                    <div class="bx_filter_input_container form-control bg">
                                                        <input
                                                                class="min-price"
                                                                type="text"
                                                                name="<?
                                                                echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"] ?>"
                                                                id="<?
                                                                echo $arItem["VALUES"]["MIN"]["CONTROL_ID"] ?>"
                                                                value="<?
                                                                echo $arItem["VALUES"]["MIN"]["HTML_VALUE"] ?>"
                                                                size="5"
                                                                placeholder="<?
                                                                echo $price1; ?>"
                                                                onkeyup="smartFilter.keyup(this)"
                                                        />
                                                    </div>
                                                </div>
                                                <div class="bx_filter_parameters_box_container_block">
                                                    <div class="bx_filter_input_container form-control bg">
                                                        <input
                                                                class="max-price"
                                                                type="text"
                                                                name="<?
                                                                echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"] ?>"
                                                                id="<?
                                                                echo $arItem["VALUES"]["MAX"]["CONTROL_ID"] ?>"
                                                                value="<?
                                                                echo $arItem["VALUES"]["MAX"]["HTML_VALUE"] ?>"
                                                                size="5"
                                                                placeholder="<?
                                                                echo $price5; ?>"
                                                                onkeyup="smartFilter.keyup(this)"
                                                        />
                                                    </div>
                                                </div>
                                                <span class="divider"></span>
                                                <div style="clear: both;"></div>
                                            </div>
                                            <?/*<div class="wrapp_slider iblock">
                                                <div class="bx_ui_slider_track" id="drag_track_<?= $key ?>">
                                                    <div class="bx_ui_slider_part first p1"><span><?= $price1 ?></span>
                                                    </div>
                                                    <div class="bx_ui_slider_part p2"><span><?= $price2 ?></span></div>
                                                    <div class="bx_ui_slider_part p3"><span><?= $price3 ?></span></div>
                                                    <div class="bx_ui_slider_part p4"><span><?= $price4 ?></span></div>
                                                    <div class="bx_ui_slider_part last p5"><span><?= $price5 ?></span>
                                                    </div>

                                                    <div class="bx_ui_slider_pricebar_VD" style="left: 0;right: 0;"
                                                         id="colorUnavailableActive_<?= $key ?>"></div>
                                                    <div class="bx_ui_slider_pricebar_VN" style="left: 0;right: 0;"
                                                         id="colorAvailableInactive_<?= $key ?>"></div>
                                                    <div class="bx_ui_slider_pricebar_V" style="left: 0;right: 0;"
                                                         id="colorAvailableActive_<?= $key ?>"></div>
                                                    <div class="bx_ui_slider_range" id="drag_tracker_<?= $key ?>"
                                                         style="left: 0%; right: 0%;">
                                                        <a class="bx_ui_slider_handle left" style="left:0;"
                                                           href="javascript:void(0)" id="left_slider_<?= $key ?>"></a>
                                                        <a class="bx_ui_slider_handle right" style="right:0;"
                                                           href="javascript:void(0)" id="right_slider_<?= $key ?>"></a>
                                                    </div>
                                                </div>
                                                <div style="opacity: 0;height: 1px;"></div>
                                            </div>*/?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?
                        $isFilter = true;
                        $precision = 2;
                        if (Bitrix\Main\Loader::includeModule("currency")) {
                            $res = CCurrencyLang::GetFormatDescription($arItem["VALUES"]["MIN"]["CURRENCY"]);
                            $precision = $res['DECIMALS'];
                        }
                        $arJsParams = array(
                            "leftSlider" => 'left_slider_' . $key,
                            "rightSlider" => 'right_slider_' . $key,
                            "tracker" => "drag_tracker_" . $key,
                            "trackerWrap" => "drag_track_" . $key,
                            "minInputId" => $arItem["VALUES"]["MIN"]["CONTROL_ID"],
                            "maxInputId" => $arItem["VALUES"]["MAX"]["CONTROL_ID"],
                            "minPrice" => $arItem["VALUES"]["MIN"]["VALUE"],
                            "maxPrice" => $arItem["VALUES"]["MAX"]["VALUE"],
                            "curMinPrice" => $arItem["VALUES"]["MIN"]["HTML_VALUE"],
                            "curMaxPrice" => $arItem["VALUES"]["MAX"]["HTML_VALUE"],
                            "fltMinPrice" => intval($arItem["VALUES"]["MIN"]["FILTERED_VALUE"]) ? $arItem["VALUES"]["MIN"]["FILTERED_VALUE"] : $arItem["VALUES"]["MIN"]["VALUE"],
                            "fltMaxPrice" => intval($arItem["VALUES"]["MAX"]["FILTERED_VALUE"]) ? $arItem["VALUES"]["MAX"]["FILTERED_VALUE"] : $arItem["VALUES"]["MAX"]["VALUE"],
                            "precision" => $precision,
                            "colorUnavailableActive" => 'colorUnavailableActive_' . $key,
                            "colorAvailableActive" => 'colorAvailableActive_' . $key,
                            "colorAvailableInactive" => 'colorAvailableInactive_' . $key,
                        );
                        ?>
                            <script type="text/javascript">
                                BX.ready(function () {
                                    if (typeof window['trackBarOptions'] === 'undefined') {
                                        window['trackBarOptions'] = {}
                                    }
                                    window['trackBarOptions']['<?=$key?>'] = <?=CUtil::PhpToJSObject($arJsParams)?>;
                                    window['trackBar<?=$key?>'] = new BX.Iblock.SmartFilter(window['trackBarOptions']['<?=$key?>']);
                                });
                            </script>
                        <?endif;
                        if ($whatColor == 0) {
                            $whatColor = 1;
                        } else {
                            $whatColor = 0;
                        }
                    }
                    // not prices
                endif;?>
                <div class="bx_filter_parameters_box test3 block_filters" style="display:none;">
                    <span class="bx_filter_container_modef"></span>
                    <div class="bx_filter_parameters_box_title icons_fa">
                        <div>
                            Сортировка
                            <div class="char_name">
                                <div class="props_list">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bx_filter_block" style="font-size: 14px">
                        <?
                        $arAvailableSort = array();
                        $arSorts = $GLOBALS['arParams']["SORT_BUTTONS"];
                        //$arSorts[] = "HIT";
                        //$arAvailableSort["STATUS"] = array("STATUS", "asc");
                        if(in_array("POPULARITY", $arSorts)){
                            $arAvailableSort["SHOWS"] = array("SHOWS", "desc");
                        }
                        if(in_array("NAME", $arSorts)){
                            $arAvailableSort["NAME"] = array("NAME", "asc");
                        }
                        if(in_array("NEW", $arSorts)){
                            $arAvailableSort["NEW"] = array("DATE_CREATE", "asc");
                        }
                        if(in_array("PRICE", $arSorts)){
                            $arSortPrices = $GLOBALS['arParams']["SORT_PRICES"];
                            if($arSortPrices == "MINIMUM_PRICE" || $arSortPrices == "MAXIMUM_PRICE"){
                                $arAvailableSort["PRICE"] = array("PROPERTY_".$arSortPrices, "desc");
                            }
                            else{
                                if($arSortPrices == "REGION_PRICE")
                                {
                                    global $arRegion;
                                    if($arRegion)
                                    {
                                        if(!$arRegion["PROPERTY_SORT_REGION_PRICE_VALUE"] || $arRegion["PROPERTY_SORT_REGION_PRICE_VALUE"] == "component")
                                        {
                                            $price = CCatalogGroup::GetList(array(), array("NAME" => $GLOBALS['arParams']["SORT_REGION_PRICE"]), false, false, array("ID", "NAME"))->GetNext();
                                            $arAvailableSort["PRICE"] = array("CATALOG_PRICE_".$price["ID"], "desc");
                                        }
                                        else
                                        {
                                            $arAvailableSort["PRICE"] = array("CATALOG_PRICE_".$arRegion["PROPERTY_SORT_REGION_PRICE_VALUE"], "desc");
                                        }
                                    }
                                    else
                                    {
                                        $price_name = ($GLOBALS['arParams']["SORT_REGION_PRICE"] ? $GLOBALS['arParams']["SORT_REGION_PRICE"] : "BASE");
                                        $price = CCatalogGroup::GetList(array(), array("NAME" => $price_name), false, false, array("ID", "NAME"))->GetNext();
                                        $arAvailableSort["PRICE"] = array("CATALOG_PRICE_".$price["ID"], "desc");
                                    }
                                }
                                else
                                {
                                    $price = CCatalogGroup::GetList(array(), array("NAME" => $GLOBALS['arParams']["SORT_PRICES"]), false, false, array("ID", "NAME"))->GetNext();
                                    $arAvailableSort["PRICE"] = array("CATALOG_PRICE_".$price["ID"], "desc");
                                }
                            }
                        }
                        if(in_array("QUANTITY", $arSorts)){
                            $arAvailableSort["CATALOG_AVAILABLE"] = array("QUANTITY", "desc");
                        }
                        $sort = "PROPERTY_SORT_CATALOG";
                        if((array_key_exists("sort", $_REQUEST) && array_key_exists(ToUpper($_REQUEST["sort"]), $arAvailableSort)) || (array_key_exists("sort", $_SESSION) && array_key_exists(ToUpper($_SESSION["sort"]), $arAvailableSort)) || $GLOBALS['arParams']["ELEMENT_SORT_FIELD2"]){
                            if($_REQUEST["sort"]){
                                $sort = ToUpper($_REQUEST["sort"]);
                                $_SESSION["sort"] = ToUpper($_REQUEST["sort"]);
                            }
                            elseif($_SESSION["sort"]){
                                $sort = ToUpper($_SESSION["sort"]);
                            }
                            else{
                                $sort = ToUpper($GLOBALS['arParams']["ELEMENT_SORT_FIELD2"]);
                            }
                        }
                        $sort_order=$arAvailableSort[$sort][1];
                        if((array_key_exists("order", $_REQUEST) && in_array(ToLower($_REQUEST["order"]), Array("asc", "desc"))) || (array_key_exists("order", $_REQUEST) && in_array(ToLower($_REQUEST["order"]), Array("asc", "desc")) ) || $GLOBALS['arParams']["ELEMENT_SORT_ORDER2"]){
                            if($_REQUEST["order"]){
                                $sort_order = $_REQUEST["order"];
                                $_SESSION["order"] = $_REQUEST["order"];
                            }
                            elseif($_SESSION["order"]){
                                $sort_order = $_SESSION["order"];
                            }
                            else{
                                $sort_order = ToLower($GLOBALS['arParams']["ELEMENT_SORT_ORDER2"]);
                            }
                        }
                        ?>
                        <div class="sort_wrapp">
                            <?foreach($arAvailableSort as $key => $val):?>
                                <?$newSort = $sort_order == 'desc' ? 'asc' : 'desc';
                                $current_url = $APPLICATION->GetCurPageParam('sort='.$key.'&order='.$newSort, 	array('sort', 'order'));
                                $url = str_replace('+', '%2B', $current_url);?>
                                <a href="<?=$url;?>" class="sort_btn <?=($sort == $key ? 'current' : '')?> <?=$sort_order?> <?=$key?>" rel="nofollow">
                                    <i class="icon" title="<?=GetMessage('SECT_SORT_'.$key)?>"></i><span><?=GetMessage('SECT_SORT_'.$key)?></span><i class="arr icons_fa"></i>
                                </a>
                            <?endforeach;?>
                        </div>

                    </div>
                </div>

                    <?
                foreach ($arResult["ITEMS"] as $key => $arItem) {
                    if ($arItem['CODE'] != 'TAGPROPERTY') {
                        #echo "<pre>";print_r($arItem);echo"</pre>";
                        if (
                            empty($arItem["VALUES"])
                            || isset($arItem["PRICE"])
                        )
                            continue;

                        if (
                            $arItem["DISPLAY_TYPE"] == "A"
                            && (
                                $arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"] <= 0
                            )
                        )
                            continue;
                        $class = "";
                        /*if($arItem["OPENED"]){
                            if($arItem["OPENED"]=="Y"){
                                $class="active";
                            }
                        }else*/
                        if ($arItem["DISPLAY_EXPANDED"] == "Y") {
                            $class = "active";
                        }
                        $isFilter = true;
                        ?>

                        <?
                        if ($arItem["CODE"] == "REGION" || $arItem["CODE"] == "CITY" || $arItem["CODE"] == "RAZDEL"):?>
                            <?
                            if ($arItem["CODE"] == "REGION"):?>
                        <div class="filter__item" data-expanded="<?= ($arItem["DISPLAY_EXPANDED"] ? $arItem["DISPLAY_EXPANDED"] : "N"); ?>"
                                     data-prop_code=<?= strtolower($arItem["CODE"]); ?> data-property_id="<?= $arItem["ID"] ?>">
                            
						    <div class="filter__btn">
						    	<span><?= $arItem["NAME"]; ?></span>
						    	<i class="fas arrow-switch fa-angle-down"></i>
						    </div>
                            <div class="filter-box" style="display: none;position: relative;">
                            <?foreach ($arItem["VALUES"] as $valt => $art):?>                          
                                <div class="city-list-filter city-list-filter_main">
                                <span class="city-list-filter_btn city-list-filter_btn_main"></span>
						    		<label>
						    			<input type="checkbox">
						    			<span><?=$valt;?></span>
						    		</label>                              
                                    <?foreach ($art as $val => $ar):?>
                                        <div class="city-list-filter">
                                            <input
                                                    type="checkbox"
                                                    value="<? echo $ar["HTML_VALUE"] ?>"
                                                    name="<? echo $ar["CONTROL_NAME"] ?>"
                                                    id="<? echo $ar["CONTROL_ID"] ?>"
                                                <? echo $ar["DISABLED"] ? 'disabled class="disabled"' : '' ?>
                                                <? echo $ar["CHECKED"] ? 'checked="checked"' : '' ?>
                                                    onclick="smartFilter.click(this)"
                                            />
                                            <label data-role="label_<?= $ar["CONTROL_ID"] ?>"
                                                   class="bx_filter_param_label <?= ($isSize ? "nab sku" : ""); ?> <?= ($i == $count ? "last" : ""); ?> <? echo $ar["DISABLED"] ? 'disabled' : '' ?><?= $isHit ? (" HIT_" . $val) : "" ?>"
                                                   for="<? echo $ar["CONTROL_ID"] ?>">
                                            <span class="bx_filter_input_checkbox">
                                            <span class="bx_filter_param_text"
                                              title="<?= $ar["VALUE"]; ?>"><?= $val; ?><?
                                            if ($arParams["DISPLAY_ELEMENT_COUNT"] !== "N" && isset($ar["ELEMENT_COUNT"]) && !$isSize):
                                                ?> (<span
                                                    data-role="count_<?= $ar["CONTROL_ID"] ?>"><? echo $ar["ELEMENT_COUNT"]; ?></span>)<?
                                            endif; ?></span>
                                            </span>
                                            </label>
                                            <?
                                            //var_dump(count($ar["CHILD"]));
                                            if (count($ar["CHILD"]) != 0){?>
                                                <span class="city-list-filter_btn"></span>
                                            <?}?>
                                            <?
                                            $i++; ?>
                                            <?
                                            $j++; ?>
                                                <div class="city-list-filter">
                                                    <?foreach ($ar["CHILD"] as $valc => $arc){?>
                                                        <div>
                                                        <input
                                                                type="checkbox"
                                                                value="<? echo $arc["HTML_VALUE"] ?>"
                                                                name="<? echo $arc["CONTROL_NAME"] ?>"
                                                                id="<? echo $arc["CONTROL_ID"] ?>"
                                                            <? echo $arc["DISABLED"] ? 'disabled class="disabled"' : '' ?>
                                                            <? echo $arc["CHECKED"] ? 'checked="checked"' : '' ?>
                                                                onclick="smartFilter.click(this)"
                                                        />
                                                        <label data-role="label_<?= $arc["CONTROL_ID"] ?>"
                                                               class="bx_filter_param_label <?= ($isSize ? "nab sku" : ""); ?> <?= ($i == $count ? "last" : ""); ?> <? echo $arc["DISABLED"] ? 'disabled' : '' ?><?= $isHit ? (" HIT_" . $valc) : "" ?>"
                                                               for="<? echo $arc["CONTROL_ID"] ?>">
                                                            <span class="bx_filter_input_checkbox">
                                                                <span class="bx_filter_param_text"
                                                                  title="<?= $arc["VALUE"]; ?>">
                                                                    <?= $arc["VALUE"]; ?>
                                                                    <?if ($arParams["DISPLAY_ELEMENT_COUNT"] !== "N" && isset($arc["ELEMENT_COUNT"]) && !$isSize):
                                                                      ?> 
                                                                      (<span data-role="count_<?= $arc["CONTROL_ID"] ?>">
                                                                          <?echo $arc["ELEMENT_COUNT"]; ?>
                                                                      </span>)
                                                                    <?endif;?>
                                                                </span>
                                                            </span>
                                                        </label>
                                                        </div>
                                                    <?}?> 
                                                </div>                                           
                                        </div>                                       
                                    <?endforeach;?>
                                </div>
                            <?endforeach;?>
						    </div> <!-- /.filter-box-->
                        </div>
                                <?
                                if ($whatColor == 0) {
                                    $whatColor = 1;
                                } else {
                                    $whatColor = 0;
                                } ?>
                            <?endif; ?>

                            <? if ($arItem["CODE"] == "RAZDEL"): ?>
                                <div class="bx_filter_parameters_box test3 <?= $whatColor == 0 ? 'grey' : 'white' ?> <?= $class; ?>"
                                     data-expanded="<?= ($arItem["DISPLAY_EXPANDED"] ? $arItem["DISPLAY_EXPANDED"] : "N"); ?>"
                                     data-prop_code="<?= strtolower($arItem["CODE"]); ?>"
                                     data-property_id="<?= $arItem['ID'] ?>">
                                    <span class="bx_filter_container_modef"></span>
                                    <div class="bx_filter_parameters_box_title icons_fa">
                                        <div>
                                            <?= $arItem["NAME"]; ?>
                                            <div class="char_name">
                                                <div class="props_list">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bx_filter_block <?= ($arItem["PROPERTY_TYPE"] != "N" && ($arItem["DISPLAY_TYPE"] != "P" && $arItem["DISPLAY_TYPE"] != "R") ? "limited_block" : ""); ?>" <? //=$style;
                                    ?> style="max-height: unset; overflow-y: unset;">
                                        <div class="bx_filter_parameters_box_container bx_filter_parameters_box_container_reg <?= ($arItem["DISPLAY_TYPE"] == "G" ? "pict_block" : ""); ?>">
                                            <? $arCur = current($arItem["VALUES"]); ?>
                                            <? $isHit = false;
 /************************************************************************************************************************************************************************************** */                                           
                                            if ($arItem["CODE"] == "HIT") {
                                                $isHit = true;
                                            }
                                            $count = count($arItem["VALUES"]);
                                            $i = 1;
                                            if (!$arItem["FILTER_HINT"]) {
                                                $prop = CIBlockProperty::GetByID($arItem["ID"], $arItem["IBLOCK_ID"])->GetNext();
                                                $arItem["FILTER_HINT"] = $prop["HINT"];
                                            }
                                            if ($arItem["IBLOCK_ID"] != $arParams["IBLOCK_ID"] && strpos($arItem["FILTER_HINT"], 'line') !== false) {
                                                $isSize = true;
                                            } else {
                                                $isSize = false;
                                            } ?>
                                            <? $j = 1;
                                            $isHidden = false; ?>
                                            <? foreach ($arItem["VALUES"] as $valt => $art): ?>
                                                <div class="filter__item">
                                                    <?echo $valt?>
                                                    <? if (!empty($art["CONTROL_ID"])): ?>
                                                        <input
                                                                type="checkbox"
                                                                value="<? echo $art["HTML_VALUE"] ?>"
                                                                name="<? echo $art["CONTROL_NAME"] ?>"
                                                                id="<? echo $art["CONTROL_ID"] ?>"
                                                            <? echo $art["DISABLED"] ? 'disabled class="disabled"' : '' ?>
                                                            <? echo $art["CHECKED"] ? 'checked="checked"' : '' ?>
                                                                onclick="smartFilter.click(this)"
                                                        />
                                                        <label data-role="label_<?= $art["CONTROL_ID"] ?>"
                                                               class="bx_filter_param_label <?= ($isSize ? "nab sku" : ""); ?> <?= ($i == $count ? "last" : ""); ?> <? echo $ar["DISABLED"] ? 'disabled' : '' ?><?= $isHit ? (" HIT_" . $val) : "" ?>"
                                                               for="<? echo $art["CONTROL_ID"] ?>">
                                                            <span class="bx_filter_input_checkbox">
                                                            <span class="bx_filter_param_text"
                                                                  title="<?= $art["VALUE"]; ?>"><?= $art["NAME"]; ?><?
                                                                if ($arParams["DISPLAY_ELEMENT_COUNT"] !== "N" && isset($art["ELEMENT_COUNT"]) && !$isSize):
                                                                    ?> (<span
                                                                        data-role="count_<?= $art["CONTROL_ID"] ?>"><? echo $art["ELEMENT_COUNT"]; ?></span>)<?
                                                                endif; ?></span>
                                                            </span>
                                                        </label>
                                                    <? else: ?>
                                                        <span class="main-regs-flt"><?= $art["NAME"]; ?></span>
                                                    <? endif; ?>
                                                    <? if (!empty($art["CHILD"])): ?>
                                                        <span class="city-list-filter_btn"></span>
                                                    <? endif; ?>
                                                    <? foreach ($art["CHILD"] as $val => $ar): ?>
                                                        <div class="city-list-filter">

                                                            <input
                                                                    type="checkbox"
                                                                    value="<? echo $ar["HTML_VALUE"] ?>"
                                                                    name="<? echo $ar["CONTROL_NAME"] ?>"
                                                                    id="<? echo $ar["CONTROL_ID"] ?>"
                                                                <? echo $ar["DISABLED"] ? 'disabled class="disabled"' : '' ?>
                                                                <? echo $ar["CHECKED"] ? 'checked="checked"' : '' ?>
                                                                    onclick="smartFilter.click(this)"
                                                            />
                                                            <label data-role="label_<?= $ar["CONTROL_ID"] ?>"
                                                                   class="bx_filter_param_label <?= ($isSize ? "nab sku" : ""); ?> <?= ($i == $count ? "last" : ""); ?> <? echo $ar["DISABLED"] ? 'disabled' : '' ?><?= $isHit ? (" HIT_" . $ar["NAME"]) : "" ?>"
                                                                   for="<? echo $ar["CONTROL_ID"] ?>">
                                                        <span class="bx_filter_input_checkbox">
                                                        <span class="bx_filter_param_text"
                                                              title="<?= $ar["VALUE"]; ?>"><?= $ar["NAME"]; ?><?
                                                            if ($arParams["DISPLAY_ELEMENT_COUNT"] !== "N" && isset($ar["ELEMENT_COUNT"]) && !$isSize):
                                                                ?> (<span
                                                                    data-role="count_<?= $ar["CONTROL_ID"] ?>"><? echo $ar["ELEMENT_COUNT"]; ?></span>)<?
                                                            endif; ?></span>
                                                        </span>
                                                            </label>
                                                            <? $i++; ?>
                                                            <? $j++; ?>
                                                            <? if (!empty($ar["CHILD"])): ?>
                                                                <span class="city-list-filter_btn"></span>
                                                                <div class="city-list-filter">
                                                                    <? foreach ($ar["CHILD"] as $valc => $arc): ?>
                                                                        <input
                                                                                type="checkbox"
                                                                                value="<? echo $arc["HTML_VALUE"] ?>"
                                                                                name="<? echo $arc["CONTROL_NAME"] ?>"
                                                                                id="<? echo $arc["CONTROL_ID"] ?>"
                                                                            <? echo $arc["DISABLED"] ? 'disabled class="disabled"' : '' ?>
                                                                            <? echo $arc["CHECKED"] ? 'checked="checked"' : '' ?>
                                                                                onclick="smartFilter.click(this)"
                                                                        />
                                                                        <label data-role="label_<?= $arc["CONTROL_ID"] ?>"
                                                                               class="bx_filter_param_label <?= ($isSize ? "nab sku" : ""); ?> <?= ($i == $count ? "last" : ""); ?> <? echo $arc["DISABLED"] ? 'disabled' : '' ?><?= $isHit ? (" HIT_" . $valc) : "" ?>"
                                                                               for="<? echo $arc["CONTROL_ID"] ?>">
                                                                <span class="bx_filter_input_checkbox">
                                                                <span class="bx_filter_param_text"
                                                                      title="<?= $arc["VALUE"]; ?>"><?= $arc["VALUE"]; ?><?
                                                                    if ($arParams["DISPLAY_ELEMENT_COUNT"] !== "N" && isset($arc["ELEMENT_COUNT"]) && !$isSize):
                                                                        ?> (<span
                                                                            data-role="count_<?= $arc["CONTROL_ID"] ?>"><? echo $arc["ELEMENT_COUNT"]; ?></span>)<?
                                                                    endif; ?></span>
                                                                </span>
                                                                        </label>
                                                                    <? endforeach; ?>
                                                                </div>
                                                            <? endif; ?>
                                                        </div>
                                                    <? endforeach; ?>
                                                </div>
                                            <? endforeach; ?>
<?/**************************************************************************************************************************************************************/?>
                                        </div>
                                        <div class="clb"></div>
                                    </div>

                                </div>
                                <?
                                if ($whatColor == 0) {
                                    $whatColor = 1;
                                } else {
                                    $whatColor = 0;
                                } ?>
                            <? endif; ?>
                        <? else:?>
                            <div class="filter__item"
                                 data-expanded="<?= ($arItem["DISPLAY_EXPANDED"] ? $arItem["DISPLAY_EXPANDED"] : "N"); ?>"
                                 data-prop_code=<?= strtolower($arItem["CODE"]); ?> data-property_id="<?= $arItem["ID"] ?>">
                                <?
                                if ($arItem["CODE"] != "IN_STOCK") {
                                    ?>
                                    
                                    <div class="filter__btn">
                                        <?
                                        if ($arItem["CODE"] == "MINIMUM_PRICE"):?>
                                            <?= Loc::getMessage("PRICE"); ?>
                                        <? else:?>
                                            <?
                                            if (!empty($arItem["FILTER_HINT"])):?>
                                                <?= $arItem["FILTER_HINT"] ?>
                                            <? else:?>
                                                <span><?= $arItem["NAME"]; ?></span>
                                                <i class="fas arrow-switch fa-angle-down"></i>
                                            <?endif; ?>
                                        <?endif; ?>
                                    </div>
                                

                                <?
                                } ?>
                                <?
                                $style = "";
                                if ($arItem["CODE"] == "IN_STOCK") {
                                    $style = "style='display:block;'";
                                } elseif ($arItem["DISPLAY_EXPANDED"] != "Y") {
                                    $style = "style='display:none;'";
                                } ?>
                                <div class="filter-box">
                                    <div class="bx_filter_parameters_box_container <?= ($arItem["DISPLAY_TYPE"] == "G" ? "pict_block" : ""); ?>">
                                        <?
                                        $arCur = current($arItem["VALUES"]);
                                        switch ($arItem["DISPLAY_TYPE"]) {
                                            case "A"://NUMBERS_WITH_SLIDER
                                                ?>
                                                <div class="wrapp_all_inputs wrap_md">
                                                    <?
                                                    $isConvert = false;
                                                    if ($arItem["CODE"] == "MINIMUM_PRICE" && $arParams["CONVERT_CURRENCY"] == "Y") {
                                                        $isConvert = true;
                                                    }
                                                    $value1 = $arItem["VALUES"]["MIN"]["VALUE"];
                                                    $value2 = $arItem["VALUES"]["MIN"]["VALUE"] + round(($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"]) / 4);
                                                    $value3 = $arItem["VALUES"]["MIN"]["VALUE"] + round(($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"]) / 2);
                                                    $value4 = $arItem["VALUES"]["MIN"]["VALUE"] + round((($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"]) * 3) / 4);
                                                    $value5 = $arItem["VALUES"]["MAX"]["VALUE"];
                                                    if ($isConvert) {
                                                        $value1 = SaleFormatCurrency($value1, $arParams["CURRENCY_ID"], true);
                                                        $value2 = SaleFormatCurrency($value2, $arParams["CURRENCY_ID"], true);
                                                        $value3 = SaleFormatCurrency($value3, $arParams["CURRENCY_ID"], true);
                                                        $value4 = SaleFormatCurrency($value4, $arParams["CURRENCY_ID"], true);
                                                        $value5 = SaleFormatCurrency($value5, $arParams["CURRENCY_ID"], true);
                                                    } ?>
                                                    <div class="wrapp_change_inputs iblock">
                                                        <div class="bx_filter_parameters_box_container_block">
                                                            <div class="bx_filter_input_container form-control bg">
                                                                <input
                                                                        class="min-price"
                                                                        type="text"
                                                                        name="<?
                                                                        echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"] ?>"
                                                                        id="<?
                                                                        echo $arItem["VALUES"]["MIN"]["CONTROL_ID"] ?>"
                                                                        value="<?
                                                                        echo $arItem["VALUES"]["MIN"]["HTML_VALUE"] ?>"
                                                                        size="5"
                                                                        placeholder="<?
                                                                        echo $value1; ?>"
                                                                        onkeyup="smartFilter.keyup(this)"
                                                                />
                                                            </div>
                                                        </div>
                                                        <div class="bx_filter_parameters_box_container_block">
                                                            <div class="bx_filter_input_container form-control bg">
                                                                <input
                                                                        class="max-price"
                                                                        type="text"
                                                                        name="<?
                                                                        echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"] ?>"
                                                                        id="<?
                                                                        echo $arItem["VALUES"]["MAX"]["CONTROL_ID"] ?>"
                                                                        value="<?
                                                                        echo $arItem["VALUES"]["MAX"]["HTML_VALUE"] ?>"
                                                                        size="5"
                                                                        placeholder="<?
                                                                        echo $value5; ?>"
                                                                        onkeyup="smartFilter.keyup(this)"
                                                                />
                                                            </div>
                                                        </div>
                                                        <span class="divider"></span>
                                                        <div style="clear: both;"></div>
                                                    </div>
                                                    <div class="wrapp_slider iblock">
                                                        <div class="bx_ui_slider_track" id="drag_track_<?= $key ?>">
                                                        <?echo 'TRUE';?>
                                                            <div class="bx_ui_slider_part first p1">
                                                                <span><?= $value1 ?></span></div>
                                                            <div class="bx_ui_slider_part p2">
                                                                <span><?= $value2 ?></span></div>
                                                            <div class="bx_ui_slider_part p3">
                                                                <span><?= $value3 ?></span></div>
                                                            <div class="bx_ui_slider_part p4">
                                                                <span><?= $value4 ?></span></div>
                                                            <div class="bx_ui_slider_part last p5">
                                                                <span><?= $value5 ?></span></div>

                                                            <div class="bx_ui_slider_pricebar_VD"
                                                                 style="left: 0;right: 0;"
                                                                 id="colorUnavailableActive_<?= $key ?>"></div>
                                                            <div class="bx_ui_slider_pricebar_VN"
                                                                 style="left: 0;right: 0;"
                                                                 id="colorAvailableInactive_<?= $key ?>"></div>
                                                            <div class="bx_ui_slider_pricebar_V"
                                                                 style="left: 0;right: 0;"
                                                                 id="colorAvailableActive_<?= $key ?>"></div>
                                                            <div class="bx_ui_slider_range"
                                                                 id="drag_tracker_<?= $key ?>"
                                                                 style="left: 0;right: 0;">
                                                                <a class="bx_ui_slider_handle left" style="left:0;"
                                                                   href="javascript:void(0)"
                                                                   id="left_slider_<?= $key ?>"></a>
                                                                <a class="bx_ui_slider_handle right" style="right:0;"
                                                                   href="javascript:void(0)"
                                                                   id="right_slider_<?= $key ?>"></a>
                                                            </div>
                                                        </div>
                                                        <?
                                                        $arJsParams = array(
                                                            "leftSlider" => 'left_slider_' . $key,
                                                            "rightSlider" => 'right_slider_' . $key,
                                                            "tracker" => "drag_tracker_" . $key,
                                                            "trackerWrap" => "drag_track_" . $key,
                                                            "minInputId" => $arItem["VALUES"]["MIN"]["CONTROL_ID"],
                                                            "maxInputId" => $arItem["VALUES"]["MAX"]["CONTROL_ID"],
                                                            "minPrice" => $arItem["VALUES"]["MIN"]["VALUE"],
                                                            "maxPrice" => $arItem["VALUES"]["MAX"]["VALUE"],
                                                            "curMinPrice" => $arItem["VALUES"]["MIN"]["HTML_VALUE"],
                                                            "curMaxPrice" => $arItem["VALUES"]["MAX"]["HTML_VALUE"],
                                                            "fltMinPrice" => intval($arItem["VALUES"]["MIN"]["FILTERED_VALUE"]) ? $arItem["VALUES"]["MIN"]["FILTERED_VALUE"] : $arItem["VALUES"]["MIN"]["VALUE"],
                                                            "fltMaxPrice" => intval($arItem["VALUES"]["MAX"]["FILTERED_VALUE"]) ? $arItem["VALUES"]["MAX"]["FILTERED_VALUE"] : $arItem["VALUES"]["MAX"]["VALUE"],
                                                            "precision" => $arItem["DECIMALS"] ? $arItem["DECIMALS"] : 0,
                                                            "colorUnavailableActive" => 'colorUnavailableActive_' . $key,
                                                            "colorAvailableActive" => 'colorAvailableActive_' . $key,
                                                            "colorAvailableInactive" => 'colorAvailableInactive_' . $key,
                                                        );
                                                        ?>
                                                        <script type="text/javascript">
                                                            BX.ready(function () {
                                                                if (typeof window['trackBarOptions'] === 'undefined') {
                                                                    window['trackBarOptions'] = {}
                                                                }
                                                                window['trackBarOptions']['<?=$key?>'] = <?=CUtil::PhpToJSObject($arJsParams)?>;
                                                                window['trackBar<?=$key?>'] = new BX.Iblock.SmartFilter(window['trackBarOptions']['<?=$key?>']);
                                                            });
                                                        </script>
                                                    </div>
                                                </div>
                                                <?
                                                break;
                                            case "B"://NUMBERS
                                                ?>
                                            <div style="display:flex">
                                                <div class="bx_filter_parameters_box_container_block">
                                                    <div class="bx_filter_input_container form-control bg">
                                                        <?
                                                        $minNum = number_format($arItem["VALUES"]["MIN"]["VALUE"], 0, '.', ' '); ?>
                                                        <?
                                                        $minNumVal = number_format($arItem["VALUES"]["MIN"]["HTML_VALUE"], 0, '.', ' '); ?>
                                                        <input
                                                                class="min-price"
                                                                type="text"
                                                                name="<?
                                                                echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"] ?>"
                                                                id="<?
                                                                echo $arItem["VALUES"]["MIN"]["CONTROL_ID"] ?>"
                                                            <?
                                                            if ($arItem["VALUES"]["MIN"]["HTML_VALUE"] != 0):?>
                                                                value="<?
                                                                echo $minNumVal; ?>"
                                                            <? else:?>
                                                                value="<?
                                                                echo $arItem["VALUES"]["MIN"]["HTML_VALUE"] ?>"
                                                            <?endif; ?>
                                                                placeholder="<?
                                                                echo $minNum; ?>"
                                                                size="5"
                                                                onkeyup="smartFilter.keyup(this)"
                                                        />
                                                    </div>
                                                </div>
                                                <div class="bx_filter_parameters_box_container_block">
                                                    <div class="bx_filter_input_container form-control bg">
                                                        <?
                                                        $maxiPrice = number_format($arItem["VALUES"]["MAX"]["VALUE"], 0, '.', ' '); ?>
                                                        <?
                                                        $maxiPriceVal = number_format($arItem["VALUES"]["MAX"]["HTML_VALUE"], 0, '.', ' '); ?>
                                                        <input
                                                                class="max-price"
                                                                type="text"
                                                                name="<?
                                                                echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"] ?>"
                                                                id="<?
                                                                echo $arItem["VALUES"]["MAX"]["CONTROL_ID"] ?>"
                                                            <?
                                                            if ($arItem["VALUES"]["MAX"]["HTML_VALUE"] != 0):?>
                                                                value="<?
                                                                echo $maxiPriceVal; ?>"
                                                            <? else:?>
                                                                value="<?
                                                                echo $arItem["VALUES"]["MAX"]["HTML_VALUE"]; ?>"
                                                            <?endif; ?>
                                                                placeholder="<?
                                                                echo $maxiPrice; ?>"
                                                                size="5"
                                                                onkeyup="smartFilter.keyup(this)"
                                                        />
                                                    </div>
                                                </div>
                                            </div>
                                                <?
                                                break;
                                            case "G"://CHECKBOXES_WITH_PICTURES
                                                ?>
                                                <?
                                                $j = 1;
                                                $isHidden = false; ?>
                                                <?
                                                foreach ($arItem["VALUES"] as $val => $ar):?>
                                                    <?
                                                    if ($ar["VALUE"]) {
                                                        ?>
                                                        <?
                                                        if ($j > $numVisiblePropValues && !$isHidden):
                                                            $isHidden = true; ?>
                                                            <div class="hidden_values">
                                                        <?endif; ?>
                                                        <div class="pict">
                                                            <input
                                                                    style="display: none"
                                                                    type="checkbox"
                                                                    name="<?= $ar["CONTROL_NAME"] ?>"
                                                                    id="<?= $ar["CONTROL_ID"] ?>"
                                                                    value="<?= $ar["HTML_VALUE"] ?>"
                                                                <? echo $ar["DISABLED"] ? 'disabled class="disabled"' : '' ?>
                                                                <? echo $ar["CHECKED"] ? 'checked="checked"' : '' ?>
                                                            />
                                                            <?
                                                            $class = "";
                                                            if ($ar["CHECKED"])
                                                                $class .= " active";
                                                            if ($ar["DISABLED"])
                                                                $class .= " disabled";
                                                            ?>
                                                            <label for="<?= $ar["CONTROL_ID"] ?>"
                                                                   data-role="label_<?= $ar["CONTROL_ID"] ?>"
                                                                   class="bx_filter_param_label nab dib<?= $class ?>"
                                                                   onclick="smartFilter.keyup(BX('<?= CUtil::JSEscape($ar["CONTROL_ID"]) ?>')); BX.toggleClass(this, 'active');">
                                                                <?/*<span class="bx_filter_param_btn bx_color_sl" title="<?=$ar["VALUE"]?>">*/
                                                                ?>
                                                                <?
                                                                if (isset($ar["FILE"]) && !empty($ar["FILE"]["SRC"])):?>
                                                                    <span class="bx_filter_btn_color_icon"
                                                                          title="<?= $ar["VALUE"] ?>"
                                                                          style="background-image:url('<?= $ar["FILE"]["SRC"] ?>');"></span>
                                                                <?endif ?>
                                                                <?/*</span>*/
                                                                ?>
                                                            </label>
                                                        </div>
                                                        <?
                                                        $j++; ?>
                                                    <?
                                                    } ?>
                                                <?endforeach ?>
                                                <?
                                                if ($isHidden):?>
                                                    </div>
                                                    <div class="inner_expand_text"><span
                                                                class="expand_block"><?= Loc::getMessage("FILTER_EXPAND_VALUES"); ?></span>
                                                    </div>
                                                <?endif; ?>
                                                <?
                                                break;
                                            case "H"://CHECKBOXES_WITH_PICTURES_AND_LABELS
                                                ?>
                                                <?
                                                $j = 1;
                                                $isHidden = false; ?>
                                                <?
                                                foreach ($arItem["VALUES"] as $val => $ar):?>
                                                    <?
                                                    if ($ar["VALUE"]) {
                                                        ?>
                                                        <?
                                                        if ($j > $numVisiblePropValues && !$isHidden):
                                                            $isHidden = true; ?>
                                                            <div class="hidden_values">
                                                        <?endif; ?>
                                                        <input
                                                                style="display: none"
                                                                type="checkbox"
                                                                name="<?= $ar["CONTROL_NAME"] ?>"
                                                                id="<?= $ar["CONTROL_ID"] ?>"
                                                                value="<?= $ar["HTML_VALUE"] ?>"
                                                            <? echo $ar["DISABLED"] ? 'disabled class="disabled"' : '' ?>
                                                            <? echo $ar["CHECKED"] ? 'checked="checked"' : '' ?>
                                                        />
                                                        <?
                                                        $class = "";
                                                        if ($ar["CHECKED"])
                                                            $class .= " active";
                                                        if ($ar["DISABLED"])
                                                            $class .= " disabled";
                                                        ?>
                                                        <label for="<?= $ar["CONTROL_ID"] ?>"
                                                               data-role="label_<?= $ar["CONTROL_ID"] ?>"
                                                               class="bx_filter_param_label<?= $class ?> pal nab"
                                                               onclick="smartFilter.keyup(BX('<?= CUtil::JSEscape($ar["CONTROL_ID"]) ?>')); BX.toggleClass(this, 'active');">
                                                            <?/*<span class="bx_filter_param_btn bx_color_sl" title="<?=$ar["VALUE"]?>">*/
                                                            ?>
                                                            <?
                                                            if (isset($ar["FILE"]) && !empty($ar["FILE"]["SRC"])):?>
                                                                <span class="bx_filter_btn_color_icon"
                                                                      style="background-image:url('<?= $ar["FILE"]["SRC"] ?>');"></span>
                                                            <?endif ?>
                                                            <?/*</span>*/
                                                            ?>
                                                            <span class="bx_filter_param_text"
                                                                  title="<?= $ar["VALUE"]; ?>"><?= $ar["VALUE"]; ?><?
                                                                if ($arParams["DISPLAY_ELEMENT_COUNT"] !== "N" && isset($ar["ELEMENT_COUNT"])):
                                                                    ?> (<span
                                                                        data-role="count_<?= $ar["CONTROL_ID"] ?>"><? echo $ar["ELEMENT_COUNT"]; ?></span>)<?
                                                                endif; ?></span>
                                                        </label>
                                                        <?
                                                        $j++; ?>
                                                    <?
                                                    } ?>
                                                <?endforeach ?>
                                                <?
                                                if ($isHidden):?>
                                                    </div>
                                                    <div class="inner_expand_text"><span
                                                                class="expand_block"><?= Loc::getMessage("FILTER_EXPAND_VALUES"); ?></span>
                                                    </div>
                                                <?endif; ?>
                                                <?
                                                break;
                                            case "P"://DROPDOWN
                                                $checkedItemExist = false;
                                                ?>
                                                <div class="bx_filter_select_container">
                                                    <div class="bx_filter_select_block"
                                                         onclick="smartFilter.showDropDownPopup(this, '<?= CUtil::JSEscape($key) ?>')">
                                                        <div class="bx_filter_select_text" data-role="currentOption">
                                                            <?
                                                            foreach ($arItem["VALUES"] as $val => $ar) {
                                                                if ($ar["CHECKED"]) {
                                                                    echo $ar["VALUE"];
                                                                    $checkedItemExist = true;
                                                                }
                                                            }
                                                            if (!$checkedItemExist) {
                                                                echo Loc::getMessage("CT_BCSF_FILTER_ALL");
                                                            }
                                                            ?>
                                                        </div>
                                                        <div class="bx_filter_select_arrow"></div>
                                                        <input
                                                                style="display: none"
                                                                type="radio"
                                                                name="<?= $arCur["CONTROL_NAME_ALT"] ?>"
                                                                id="<? echo "all_" . $arCur["CONTROL_ID"] ?>"
                                                                value=""
                                                        />
                                                        <?
                                                        foreach ($arItem["VALUES"] as $val => $ar):?>
                                                            <input
                                                                    style="display: none"
                                                                    type="radio"
                                                                    name="<?= $ar["CONTROL_NAME_ALT"] ?>"
                                                                    id="<?= $ar["CONTROL_ID"] ?>"
                                                                    value="<? echo $ar["HTML_VALUE_ALT"] ?>"
                                                                <? echo $ar["DISABLED"] ? 'disabled class="disabled"' : '' ?>
                                                                <? echo $ar["CHECKED"] ? 'checked="checked"' : '' ?>
                                                            />
                                                        <?endforeach ?>
                                                        <div class="bx_filter_select_popup" data-role="dropdownContent"
                                                             style="display: none;">
                                                            <ul>
                                                                <li>
                                                                    <label for="<?= "all_" . $arCur["CONTROL_ID"] ?>"
                                                                           class="bx_filter_param_label"
                                                                           data-role="label_<?= $arCur["CONTROL_ID"] ?>"
                                                                           onclick="smartFilter.selectDropDownItem(this, '<?= CUtil::JSEscape("all_" . $arCur["CONTROL_ID"]) ?>')">
                                                                        <? echo Loc::getMessage("CT_BCSF_FILTER_ALL"); ?>
                                                                    </label>
                                                                </li>
                                                                <?
                                                                foreach ($arItem["VALUES"] as $val => $ar):
                                                                    $class = "";
                                                                    if ($ar["CHECKED"])
                                                                        $class .= " selected";
                                                                    if ($ar["DISABLED"])
                                                                        $class .= " disabled";
                                                                    ?>
                                                                    <li>
                                                                        <label for="<?= $ar["CONTROL_ID"] ?>"
                                                                               class="bx_filter_param_label<?= $class ?>"
                                                                               data-role="label_<?= $ar["CONTROL_ID"] ?>"
                                                                               onclick="smartFilter.selectDropDownItem(this, '<?= CUtil::JSEscape($ar["CONTROL_ID"]) ?>')"><?= $ar["VALUE"] ?></label>
                                                                    </li>
                                                                <?endforeach ?>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?
                                                break;
                                            case "R"://DROPDOWN_WITH_PICTURES_AND_LABELS
                                                ?>
                                                <div class="bx_filter_select_container">
                                                    <div class="bx_filter_select_block"
                                                         onclick="smartFilter.showDropDownPopup(this, '<?= CUtil::JSEscape($key) ?>')">
                                                        <div class="bx_filter_select_text" data-role="currentOption">
                                                            <?
                                                            $checkedItemExist = false;
                                                            foreach ($arItem["VALUES"] as $val => $ar):
                                                                if ($ar["CHECKED"]) {
                                                                    ?>
                                                                    <?
                                                                    if (isset($ar["FILE"]) && !empty($ar["FILE"]["SRC"])):?>
                                                                        <span class="bx_filter_btn_color_icon"
                                                                              style="background-image:url('<?= $ar["FILE"]["SRC"] ?>');"></span>
                                                                    <?endif ?>
                                                                    <span class="bx_filter_param_text">
                                                            <?= $ar["VALUE"] ?>
                                                        </span>
                                                                    <?
                                                                    $checkedItemExist = true;
                                                                }
                                                            endforeach;
                                                            if (!$checkedItemExist) {
                                                                ?>
                                                                <?
                                                                echo Loc::getMessage("CT_BCSF_FILTER_ALL");
                                                            }
                                                            ?>
                                                        </div>
                                                        <div class="bx_filter_select_arrow"></div>
                                                        <input
                                                                style="display: none"
                                                                type="radio"
                                                                name="<?= $arCur["CONTROL_NAME_ALT"] ?>"
                                                                id="<? echo "all_" . $arCur["CONTROL_ID"] ?>"
                                                                value=""
                                                        />
                                                        <?
                                                        foreach ($arItem["VALUES"] as $val => $ar):?>
                                                            <input
                                                                    style="display: none"
                                                                    type="radio"
                                                                    name="<?= $ar["CONTROL_NAME_ALT"] ?>"
                                                                    id="<?= $ar["CONTROL_ID"] ?>"
                                                                    value="<?= $ar["HTML_VALUE_ALT"] ?>"
                                                                <? echo $ar["DISABLED"] ? 'disabled class="disabled"' : '' ?>
                                                                <? echo $ar["CHECKED"] ? 'checked="checked"' : '' ?>
                                                            />
                                                        <?endforeach ?>
                                                        <div class="bx_filter_select_popup" data-role="dropdownContent"
                                                             style="display: none">
                                                            <ul>
                                                                <li style="border-bottom: 1px solid #e5e5e5;padding-bottom: 5px;margin-bottom: 5px;">
                                                                    <label for="<?= "all_" . $arCur["CONTROL_ID"] ?>"
                                                                           class="bx_filter_param_label"
                                                                           data-role="label_<?= $arCur["CONTROL_ID"] ?>"
                                                                           onclick="smartFilter.selectDropDownItem(this, '<?= CUtil::JSEscape("all_" . $arCur["CONTROL_ID"]) ?>')">
                                                                        <? echo Loc::getMessage("CT_BCSF_FILTER_ALL"); ?>
                                                                    </label>
                                                                </li>
                                                                <?
                                                                foreach ($arItem["VALUES"] as $val => $ar):
                                                                    $class = "";
                                                                    if ($ar["CHECKED"])
                                                                        $class .= " selected";
                                                                    if ($ar["DISABLED"])
                                                                        $class .= " disabled";
                                                                    ?>
                                                                    <li>
                                                                        <label for="<?= $ar["CONTROL_ID"] ?>"
                                                                               data-role="label_<?= $ar["CONTROL_ID"] ?>"
                                                                               class="bx_filter_param_label<?= $class ?>"
                                                                               onclick="smartFilter.selectDropDownItem(this, '<?= CUtil::JSEscape($ar["CONTROL_ID"]) ?>')">
                                                                            <?
                                                                            if (isset($ar["FILE"]) && !empty($ar["FILE"]["SRC"])):?>
                                                                                <span class="bx_filter_btn_color_icon"
                                                                                      title="<?= $ar["VALUE"] ?>"
                                                                                      style="background-image:url('<?= $ar["FILE"]["SRC"] ?>');"></span>
                                                                            <?endif ?>
                                                                            <span class="bx_filter_param_text">
                                                                <?= $ar["VALUE"] ?>
                                                            </span>
                                                                        </label>
                                                                    </li>
                                                                <?endforeach ?>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?
                                                break;
                                            case "K"://RADIO_BUTTONS
                                                ?>
                                                <div class="filter label_block radio">
                                                    <input
                                                            type="radio"
                                                            value=""
                                                            name="<? echo $arCur["CONTROL_NAME_ALT"] ?>"
                                                            id="<? echo "all_" . $arCur["CONTROL_ID"] ?>"
                                                            onclick="smartFilter.click(this)"
                                                    />
                                                    <label data-role="label_<?= $arCur["CONTROL_ID"] ?>"
                                                           class="bx_filter_param_label"
                                                           for="<? echo "all_" . $arCur["CONTROL_ID"] ?>">
                                                        <span class="bx_filter_input_checkbox"><span><? echo Loc::getMessage("CT_BCSF_FILTER_ALL"); ?></span></span>
                                                    </label>
                                                </div>
                                                <?
                                                $j = 1;
                                                $isHidden = false; ?>
                                                <?
                                                foreach ($arItem["VALUES"] as $val => $ar):?>
                                                    <?
                                                    if ($j > $numVisiblePropValues && !$isHidden):
                                                        $isHidden = true; ?>
                                                        <div class="hidden_values">
                                                    <?endif; ?>
                                                    <div class="filter label_block radio">
                                                        <input
                                                                type="radio"
                                                                value="<? echo $ar["HTML_VALUE_ALT"] ?>"
                                                                name="<? echo $ar["CONTROL_NAME_ALT"] ?>"
                                                                id="<? echo $ar["CONTROL_ID"] ?>"
                                                            <? echo $ar["DISABLED"] ? 'disabled class="disabled"' : '' ?>
                                                            <? echo $ar["CHECKED"] ? 'checked="checked"' : '' ?>
                                                                onclick="smartFilter.click(this)"
                                                        />
                                                        <?
                                                        $class = "";
                                                        if ($ar["CHECKED"])
                                                            $class .= " selected";
                                                        if ($ar["DISABLED"])
                                                            $class .= " disabled"; ?>
                                                        <label data-role="label_<?= $ar["CONTROL_ID"] ?>"
                                                               class="bx_filter_param_label <?= $class; ?>"
                                                               for="<? echo $ar["CONTROL_ID"] ?>">
                                                <span class="bx_filter_input_checkbox">

                                                    <span class="bx_filter_param_text1"
                                                          title="<?= $ar["VALUE"]; ?>"><?= $ar["VALUE"]; ?><?
                                                        if ($arParams["DISPLAY_ELEMENT_COUNT"] !== "N" && isset($ar["ELEMENT_COUNT"])):
                                                            ?> (<span
                                                                data-role="count_<?= $ar["CONTROL_ID"] ?>"><? echo $ar["ELEMENT_COUNT"]; ?></span>)<?
                                                        endif; ?></span>
                                                </span>
                                                        </label>
                                                    </div>
                                                    <?
                                                    $j++; ?>
                                                <?endforeach; ?>
                                                <?
                                                if ($isHidden):?>
                                                    </div>
                                                    <div class="inner_expand_text"><span
                                                                class="expand_block"><?= Loc::getMessage("FILTER_EXPAND_VALUES"); ?></span>
                                                    </div>
                                                <?endif; ?>
                                                <?
                                                break;
                                            case "U"://CALENDAR
                                                ?>
                                                <div class="bx_filter_parameters_box_container_block">
                                                    <div class="bx_filter_input_container bx_filter_calendar_container">
                                                        <?
                                                        $APPLICATION->IncludeComponent(
                                                            'bitrix:main.calendar',
                                                            '',
                                                            array(
                                                                'FORM_NAME' => $arResult["FILTER_NAME"] . "_form",
                                                                'SHOW_INPUT' => 'Y',
                                                                'INPUT_ADDITIONAL_ATTR' => 'class="calendar" placeholder="' . FormatDate("SHORT", $arItem["VALUES"]["MIN"]["VALUE"]) . '" onkeyup="smartFilter.keyup(this)" onchange="smartFilter.keyup(this)"',
                                                                'INPUT_NAME' => $arItem["VALUES"]["MIN"]["CONTROL_NAME"],
                                                                'INPUT_VALUE' => $arItem["VALUES"]["MIN"]["HTML_VALUE"],
                                                                'SHOW_TIME' => 'N',
                                                                'HIDE_TIMEBAR' => 'Y',
                                                            ),
                                                            null,
                                                            array('HIDE_ICONS' => 'Y')
                                                        ); ?>
                                                    </div>
                                                </div>
                                                <div class="bx_filter_parameters_box_container_block">
                                                    <div class="bx_filter_input_container bx_filter_calendar_container">
                                                        <?
                                                        $APPLICATION->IncludeComponent(
                                                            'bitrix:main.calendar',
                                                            '',
                                                            array(
                                                                'FORM_NAME' => $arResult["FILTER_NAME"] . "_form",
                                                                'SHOW_INPUT' => 'Y',
                                                                'INPUT_ADDITIONAL_ATTR' => 'class="calendar" placeholder="' . FormatDate("SHORT", $arItem["VALUES"]["MAX"]["VALUE"]) . '" onkeyup="smartFilter.keyup(this)" onchange="smartFilter.keyup(this)"',
                                                                'INPUT_NAME' => $arItem["VALUES"]["MAX"]["CONTROL_NAME"],
                                                                'INPUT_VALUE' => $arItem["VALUES"]["MAX"]["HTML_VALUE"],
                                                                'SHOW_TIME' => 'N',
                                                                'HIDE_TIMEBAR' => 'Y',
                                                            ),
                                                            null,
                                                            array('HIDE_ICONS' => 'Y')
                                                        ); ?>
                                                    </div>
                                                </div>
                                                <?
                                                break;
                                            default://CHECKBOXES

                                                $isHit = false;
                                                if ($arItem["CODE"] == "HIT") {
                                                    $isHit = true;
                                                }
                                                $count = count($arItem["VALUES"]);
                                                $i = 1;
                                                if (!$arItem["FILTER_HINT"]) {
                                                    $prop = CIBlockProperty::GetByID($arItem["ID"], $arItem["IBLOCK_ID"])->GetNext();
                                                    $arItem["FILTER_HINT"] = $prop["HINT"];
                                                }
                                                if ($arItem["IBLOCK_ID"] != $arParams["IBLOCK_ID"] && strpos($arItem["FILTER_HINT"], 'line') !== false) {
                                                    $isSize = true;
                                                } else {
                                                    $isSize = false;
                                                } ?>
                                                <?
                                                $j = 1;
                                                $isHidden = false; ?>

                                                <?
                                                foreach ($arItem["VALUES"] as $val => $ar):?>

                                                    <?
                                                    if ($ar["URL_ID"] != 'hit'):?>
                                                        <?
                                                        if ($j > $numVisiblePropValues && !$isHidden):
                                                            $isHidden = true; ?>
                                                            <div class="hidden_values">
                                                        <?endif; ?>
                                                        <input
                                                                type="checkbox"
                                                                value="<? echo $ar["HTML_VALUE"] ?>"
                                                                name="<? echo $ar["CONTROL_NAME"] ?>"
                                                                id="<? echo $ar["CONTROL_ID"] ?>"
                                                            <? echo $ar["DISABLED"] ? 'disabled class="disabled"' : '' ?>
                                                            <? echo $ar["CHECKED"] ? 'checked="checked"' : '' ?>
                                                                onclick="smartFilter.click(this)"
                                                        />
                                                        <label data-role="label_<?= $ar["CONTROL_ID"] ?>"
                                                               class="bx_filter_param_label <?= ($isSize ? "nab sku" : ""); ?> <?= ($i == $count ? "last" : ""); ?> <? echo $ar["DISABLED"] ? 'disabled' : '' ?><?= $isHit ? (" HIT_" . $val) : "" ?>"
                                                               for="<? echo $ar["CONTROL_ID"] ?>">
                                            <span class="bx_filter_input_checkbox">

                                                <span class="bx_filter_param_text"
                                                      title="<?= $ar["VALUE"]; ?>"><?= $ar["VALUE"]; ?><?
                                                    if ($arParams["DISPLAY_ELEMENT_COUNT"] !== "N" && isset($ar["ELEMENT_COUNT"]) && !$isSize):
                                                        ?> (<span
                                                            data-role="count_<?= $ar["CONTROL_ID"] ?>"><? echo $ar["ELEMENT_COUNT"]; ?></span>)<?
                                                    endif; ?></span>
                                            </span>
                                                        </label>
                                                        <?
                                                        $i++; ?>
                                                        <?
                                                        $j++; ?>
                                                    <?endif; ?>
                                                <?endforeach; ?>
                                                <?
                                                if ($isHidden):?>
                                                    </div>
                                                    <div class="inner_expand_text"><span
                                                                class="expand_block"><?= Loc::getMessage("FILTER_EXPAND_VALUES"); ?></span>
                                                    </div>
                                                <?endif; ?>
                                            <?
                                        } ?>
                                    </div>
                                    <div class="clb"></div>
                                </div>
                            </div>
                            <?
                            if ($whatColor == 0) {
                                $whatColor = 1;
                            } else {
                                $whatColor = 0;
                            } ?>
                        <?endif; ?>
                    <?
                    } ?>
                <?
                }
                if ($isFilter) {
                    $filterURL = '';
                    ?>
                    <div class="clb"></div>
                    <div class="bx_filter_button_box active" style="border-top: 1px solid #000000">
                        <div class="bx_filter_block">
                            <div class="bx_filter_parameters_box_container block_button_filter">
                                <div class="bx_filter_popup_result right button-filter"
                                     id="modef_mobile" <? if (!isset($arResult["ELEMENT_COUNT"])) echo 'style="display:none"'; ?>>
                                    <? echo Loc::getMessage("CT_BCSF_FILTER_COUNT", array("#ELEMENT_COUNT#" => '<span id="modef_num_mobile">' . intval($arResult["ELEMENT_COUNT"]) . '</span>')); ?>
                                    <a rel="nofollow"
                                       href="/test/<? echo str_replace('/filter/clear/apply/', '/', $arResult["FILTER_URL"]); ?>"
                                       class="btn btn-default white white-bg"><? echo Loc::getMessage("CT_BCSF_FILTER_SHOW") ?></a>
                                </div>
                                <div class="bx_filter_popup_result right button-filter"
                                     id="modef" <? if (!isset($arResult["ELEMENT_COUNT"])) echo 'style="display:none"'; ?>>
                                    <? echo Loc::getMessage("CT_BCSF_FILTER_COUNT", array("#ELEMENT_COUNT#" => '<span id="modef_num">' . intval($arResult["ELEMENT_COUNT"]) . '</span>')); ?>
                                    <a id="custom-filter-button" rel="nofollow"
                                       href="/test/<? echo str_replace('/filter/clear/apply/', '/', $arResult["FILTER_URL"]); ?>"
                                       class="btn btn-default white white-bg"><? echo Loc::getMessage("CT_BCSF_FILTER_SHOW") ?></a>
                                </div>
                                <input class="bx_filter_search_button btn btn-default" type="submit" id="set_filter"
                                       name="set_filter" data-href=""
                                       value="<?= Loc::getMessage("CT_BCSF_SET_FILTER") ?>"/>
                                <button class="bx_filter_search_reset btn btn-default white grey" type="reset"
                                        id="del_filter" name="del_filter" data-href="">
                                    <?= Loc::getMessage("CT_BCSF_DEL_FILTER")?>
                                </button>
                            </div>
                        </div>
                    </div>
                <? } ?>
            </form>
            <div style="clear: both;"></div>
        </div>
    </div>
    <script>
        var smartFilter = new JCSmartFilter('<?echo CUtil::JSEscape($arResult["FORM_ACTION"])?>', '<?=$arParams["VIEW_MODE"];?>', <?=CUtil::PhpToJSObject($arResult["JS_FILTER_PARAMS"])?>);
        <?if(!$isFilter){?>
        $('.bx_filter_vertical').remove();
        <?}?>
        $(document).ready(function () {
            $('.bx_filter_search_reset').on('click', function () {
                <?if($arParams["SEF_MODE"] == "Y"){?>
                location.href = $('form.smartfilter').find('#del_url').val();
                <?}else{?>
                location.href = $('form.smartfilter').attr('action');
                <?}?>
            });
            $(".city-list-filter_btn").on("click", function () {
                $(this).toggleClass('activity');
                $(this).parent('.city-list-filter_main').toggleClass('activMob');
            });

            $('.bx_filter .min-price, .bx_filter .max-price').on('input', function () {
                // var cardCode = this.value.replace(/[^\d]/g, '').substring(0, 27);
                // cardCode = cardCode != '' ? cardCode.match(/.{1,3}/g).join(' ') : '';
                // this.value = cardCode;
                //var numWSP = this.value.replace(/\s/g, '');
                //this.value = new Intl.NumberFormat('ru-RU').format(numWSP);
            });
        })
    </script>
<? } ?>