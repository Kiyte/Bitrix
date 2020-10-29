<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>

<div id="small-dialog-found-cheape" class="zoom-anim-dialog mfp-hide found-cheape">
		<div class="success-in" id="success-in">
			<div class="suc-wraperr">
				<div class="image-wrap-icon">
					<img src="<?=SITE_TEMPLATE_PATH?>/img/ic_check.svg" alt="ic_check">
				</div>
				<h5>Данные отправлены</h5>
				<p class="suc-text">Мы свяжемся с вами в ближайшее время</p>
				<div class="text-center"><a href="javascript:void()" class="buttons-form" onClick="$('.mfp-close').trigger('click');">Хорошо</a></div>
			</div>
		</div>
		<div class="success-error" id="success-error">
			<div class="suc-wraperr">
				<div class="image-wrap-icon">
					<img src="<?=SITE_TEMPLATE_PATH?>/img/en_check.svg" alt="en_check">
				</div>
				<h5>Данные не отправлены</h5>
				<p class="suc-text">Мы не свяжемся с вами в ближайшее время</p>
				<div class="text-center"><a href="javascript:void()" class="buttons-form" id="try-again">Попробовать еще раз</a></div>
			</div>
		</div>
		<form class="form-cheape" id="cheape-form">
			<h4>Нашли дешевле?</h4>
			<label>
				<span>Ваше имя</span>
                                <input type="text" id="name">
			</label>
			<label>
				<span>Ваше email <br> или телефон</span>
				<input type="text" id="contact">
			</label>
			<label>
				<span>Какую цену вы видели?</span>
				<input type="text" id="price">
			</label>
			<label>
				<span>Ссылка на товар</span>
                                <input type="text" id="product_url">
			</label>
			<label>
				<span></span>
				<p class="smal">Убедитесь, пожалуйста, что в указанном вами магазине реально можно купить данный товар за указанную цену и учтены условия доставки.</p>
			</label>
			<label>
				<span></span>
				<a href="javascript:void(0)" class="buttons-form" id="cheape">Отправить</a>
			</label>
		</form>
	</div>
