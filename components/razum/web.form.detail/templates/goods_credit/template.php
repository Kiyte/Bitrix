<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>

<div id="small-dialog-found-credit" class="zoom-anim-dialog mfp-hide found-cheape">
    <div class="success-in" id="success-in-credit">
	<div class="suc-wraperr">
            <div class="image-wrap-icon">
		<img src="<?=SITE_TEMPLATE_PATH?>/img/ic_check.svg" alt="ic_check">
            </div>
            <h5>Ваша заявка получена</h5>
            <p class="suc-text">Мы свяжемся с вами в ближайшее время</p>
            <div class="text-center">
                <a href="javascript:void();" class="buttons-form" onClick="$('.mfp-close').trigger('click');">Хорошо</a>
            </div>
	</div>
    </div>
    <div class="success-error" id="success-error-credit">
	<div class="suc-wraperr">
            <div class="image-wrap-icon">
            	<img src="<?=SITE_TEMPLATE_PATH?>/img/en_check.svg" alt="en_check">
            </div>
            <h5>Данные не отправлены</h5>
            <p class="suc-text">Мы не свяжемся с вами в ближайшее время</p>
            <div class="text-center">
                <a href="javascript:void();" class="buttons-form" id="try-again">Попробовать еще раз</a>
            </div>
	</div>
    </div>
    <form class="form-cheape" id="credit-form">
        <h2>
            <?=$arParams['NAME_PRODUCT']?> - купить в кредит.
        </h2>
        <img src="<?=$arParams['PICTURE'];?>" width='70px'/>
	<label>
            <span>Ваше имя</span>
            <input type="text" id="name-credit">
	</label>
	<label>
            <span>Телефон</span>
            <input type="text" id="contact-credit">
	</label>
        <input type="hidden" id='product' value='<?=$arParams['ID_PRODUCT']?>'>
	<label>
            <span></span>
            <a href="javascript:void(0)" class="buttons-form" id="credit">Отправить заявку</a>
	</label>
    </form>
</div>
