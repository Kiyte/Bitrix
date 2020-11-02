<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>

<div id="small-dialog-on-click-buy" class="zoom-anim-dialog found-cheape mfp-hide">
    <div class="success-in" id="suc_buy">
        <div class="suc-wraperr">
            <div class="image-wrap-icon">
                <img src="<?=SITE_TEMPLATE_PATH?>/img/ic_check.svg" alt="ic_check">
            </div>
            <h5>Ваш заказ принят</h5>
            <p class="suc-text">Наш менеджер свяжется с вами в ближайшее время</p>
            <div class="text-center">
                <a href="#" class="buttons-form close-mag">Хорошо</a>
            </div>
        </div>
    </div>
    <div class="success-error" id="err_buy">
        <div class="suc-wraperr">
            <div class="image-wrap-icon">
                <img src="<?=SITE_TEMPLATE_PATH?>/img/en_check.svg" alt="en_check">
            </div>
            <h5>Произошла непредвиденная ошибка</h5>
            <p class="suc-text">Попробуйте повторить заказ позже</p>
            <div class="text-center">
                <a href="#" class="buttons-form close-mag">Хорошо</a>
            </div>
        </div>
    </div>
    <div id="form_click">
        <h3>Купить в 1 клик</h3>
        <div class="add-basket-wrap">
            <div class="wrap-item">
                <div class="image-wrap">
                    <img src="<?=$arParams['PICTURE']?>" height='150' alt="basket-item" style="width: auto" >
                </div>
                <div class="wrap-text">
                    <p><?=$arParams['NAME_PRODUCT']?></p>
                    <span><?//=number_format(intval($price['RATIO_BASE_PRICE']), 0, '', ' ')₽;?> </span>
                </div>
            </div>
        </div>
        <label>
            <span>Ваше имя</span>
            <input type="text" id="name-one-buy">
        </label>
        <label>
                <span>Телефон</span>
                <input type="text" id="contact-one-buy">
        </label>
        <label>
            <span></span>
            <a href="javascript:void(0)" class="buttons-form one-click-buy" id="onClickBuy">Купить</a>
        </label>
        <input type="hidden" value="<?=$arParams['ID_PRODUCT']?>" id="id_product" />
    </div>
</div>
