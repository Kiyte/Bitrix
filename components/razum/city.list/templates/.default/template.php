<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?CJSCore::Init(array('popup'));?>
<div>
    <div>
        <p>Город</p>
        <input type="text" id="city"/>
    </div>
    <div class="button-add">
        <button class="function-button" data-action="add">Добавить</button>
    </div>
</div>
<?foreach ($arResult['ITEMS'] as $value) {?>
    <div class="city-block" >
        <div class="name-city"><?=$value['NAME']?></div>
        <div class="img-block">
            <img class="function-button delete-img" data-action="delete" src="/local/components/razum/city.list/templates/.default/img/delete.png" alt="">
            <input type="hidden" name="id-elem" id="<?=$value['ID'];?>">
        </div>
    </div>
<?}?>
<input type="hidden" data-id="<?=$arResult['IBLOCK_ID'];?>" id="iblock-id">
<div id="ajax-add-answer"></div>

