<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>

<script type="text/javascript">
    document.getElementById('variable_quantity').getElementsByTagName('span')[0].innerHTML = <?=count($arResult['ITEMS'])?>;
</script>

<div class="row">
    <div class="col-lg-9 order-2 order-md-1">
			<div class="rew-block">
                            <?foreach ($arResult['ITEMS'] as $arItem) {?>
				<div class="rew-block__item">
					<div class="rew-block__item-head mb-2">
                                            <div class="row">
                                                <div class="col d-flex align-items-center">
                                                    <div class="rew-block__user">
                                                        <div class="rew-block__user-name"><?=$arItem['NAME']?></div>
                                                        <div class="rew-block__user-rating">
                                                            <?for($i=0;$i<$arItem['SCORES'];$i++){?>
                                                            <svg class="rating-star rating-star--full" width="20" height="20">
                                                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprite-svg.svg#ic_star">
                                                                </use>
                                                            </svg>
                                                            <?}?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                            	<div class="text-muted small">04 января 2018 г.</div>
                                                </div>
                                            </div>
					</div>
					<div class="rew-block__item-body">
						<div><?=$arItem['COMMENT']?></div>
                                                <?if ($arItem['WORTH'] != ''){?>
                                                    <div class="mt-1"><span class="font-weight-bold">Достоинства: </span><?=$arItem['WORTH']?></div>
                                                <?}?>
                                                <?if ($arItem['DISADVANTAGES'] != ''){?>
                                                    <div class="mt-1"><span class="font-weight-bold">Недостатки: </span><?=$arItem['DISADVANTAGES']?></div>
                                                <?}?>
                                                <div class="d-flex mt-2">
                                                    <? foreach ($arItem['PHOTOS'] as $photo) {?>
                                                    <div class="rew-block__img-wrap mr-2"><img width="64" height="64" src="<?=$photo?>"></div>
                                                    <?}?>
						</div>
						<div class="dropdown">
							<a href="#" data-toggle="dropdown">Ответить</a>
							<form class="dropdown-menu shadow p-4 answer" id="answer-form">
								<div class="row form-group">
									<label for="" class="col-md-5 col-form-label">Сообщение (укажите ваш город и суть проблемы)</label>
									<div class="col-md-7">
										<input type="text" class="form-control" name="comment">
									</div>
								</div>
								<div class="row form-group">
									<label for="" class="col-md-5 col-form-label">Ваше Имя</label>
									<div class="col-md-7">
										<input type="text" class="form-control" name="fio">
									</div>
								</div>
								<div class="row form-group">
									<label for="" class="col-md-5 col-form-label">Ваш телефон или email</label>
									<div class="col-md-7">
										<input type="text" class="form-control" name="email">
									</div>
								</div>
								<div class="row form-group">
									<div class="col-md-5 col-form-label"></div>
									<div class="col-md-7">
										<div class="row mb-3">
											<div class="col-6">
												<button type="button" id="<?=$arItem['ID']?>" class="btn btn-block btn-primary answer-quest">Отправить</button>
											</div>
											<div class="col-6">
												<button type="button" data-toggle="dropdown" class="btn btn-block btn-light">Отменить</button>
											</div>
										</div>
										<div class="small">
											Чтобы Ваш отзыв либо комментарий прошел модерацию и был опубликован, ознакомьтесь, пожалуйста, с <a href="#">нашими правилами</a>!
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
                                    <?foreach ($arItem['CHILD'] as $valueChild) {?>
                                        <div class="rew-block__item answer">
                                            <div class="rew-block__item-head mb-2">
						<div class="row">
                                                    <div class="col d-flex align-items-center">
							<div class="rew-block__user">
                                                            <div class="rew-block__user-name"><?=$valueChild['NAME']?></div>
							</div>
                                                    </div>
                                                    <div class="col-auto">
                                                    	<div class="text-muted small">04 января 2018 г.</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="rew-block__item-body">
                                            	<div><?=$valueChild['COMMENT']?></div>
                                            </div>
					</div>
                                    <?}?>
				</div>                                
                                <?}?>
                            <a href="#" class="btn btn-gray d-block d-md-inline-block" style="display:none !important">Показать ещё</a>
			</div>
	
			<form id="rew-form" action="" class="rew-form mb-4">
				<ul class="nav nav-tabs">
					<li class="nav-item">
						<a class="nav-link active" data-toggle="tab" href="#tab-rew-01">Отзывы о товаре</a>
					</li>
					<li class="nav-item d-none d-md-block">
						<a class="nav-link" data-toggle="tab" href="#tab-rew-02">Краткий комментарий</a>
					</li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane fade show active" id="tab-rew-01">
						<div class="rew-form__box p-4">
							<div class="row form-group">
								<label for="" class="col-md-4 col-form-label">Оценка</label>
								<div class="col-md-8">
									<div class="rating-block">
										<div class="rating edit">
											<div class="rating__line"></div>
											<div class="rating__activeline"></div>
											<input value="3" type="hidden" name="rating" />
											<div class="stars">
												<span class="star"><i>Ужасно</i></span>
												<span class="star"><i>Плохо</i></span>
												<span class="star"><i>Нормально</i></span>
												<span class="star"><i>Хорошо</i></span>
												<span class="star"><i>Отлично</i></span>
											</div>
										</div>
								</div>
								</div>
							</div>
							<div class="row form-group">
								<label for="" class="col-md-4 col-form-label">Достоинства</label>
								<div class="col-md-8">
									<input type="text" class="form-control" name="worth">
								</div>
							</div>
							<div class="row form-group">
								<label for="" class="col-md-4 col-form-label">Недостатки</label>
								<div class="col-md-8">
									<input type="text" class="form-control" name="disadvantages">
								</div>
							</div>
							<div class="row form-group">
								<label for="" class="col-md-4 col-form-label">Комментарий</label>
								<div class="col-md-8">
									<input type="text" class="form-control" name="comment">
								</div>
							</div>
                                                    <?/*?>
                                                    <form action="/upload.php" method="post" enctype="multipart/form-data">

                                                    <input type="file" name="file[]" multiple>
                                                    <input type="submit" value="Отправить">

                                                    </form>
                                                    <?*/?>
                                                        <?/*?>
							<div class="row form-group">
								<div class="col-md-4 col-form-label">Фото</div>
								<div class="col-md-8">
									<div class="custom-file">
										<input type="file" class="custom-file-input" id="validatedCustomFile" required>
										<label class="custom-file-label" for="validatedCustomFile"></label>
										<div class="invalid-feedback">Example invalid custom file feedback</div>
									</div>
								</div>
							</div>
                                                        <?*/?>
						</div>
						<div class="rew-form__box p-4">
							<div class="row form-group">
								<label for="" class="col-md-4 col-form-label">Ваше имя и фамилия</label>
								<div class="col-md-8">
									<input type="text" class="form-control" name="fio">
								</div>
							</div>
							<div class="row form-group">
								<label for="" class="col-md-4 col-form-label">Ваше email</label>
								<div class="col-md-8">
									<input type="text" class="form-control" name="email">
								</div>
							</div>
							<div class="row form-group">
								<div class="col-md-4"></div>
								<div class="col-md-8">
									<div class="custom-control custom-checkbox mb-3 mr-sm-2">
										<input type="checkbox" class="custom-control-input" id="rew-checkbox">
										<label class="custom-control-label" for="rew-checkbox">Уведомлять об ответах по эл. почте</label>
									</div>
									<button type="button" class="btn mb-3 btn-primary btn-mw add-comment" id="new-comment">Отправить</button>
									<p class="small">Чтобы Ваш отзыв либо комментарий прошел модерацию и был опубликован, ознакомьтесь, пожалуйста, с <a href="#">нашими правилами</a>!</p>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane fade" id="tab-rew-02">
						<div class="rew-form__box p-4">
							<div class="row form-group">
								<label for="" class="col-md-4 col-form-label">Комментарий</label>
								<div class="col-md-8">
									<input type="text" class="form-control" name="comment-brief">
								</div>
							</div>
                                                        <?/*?>
							<div class="row form-group">
								<label for="" class="col-md-4 col-form-label">Фото</label>
								<div class="col-md-8">
									<input type="text" class="form-control" id="">
								</div>
							</div><?*/?>
						</div>
						<div class="rew-form__box p-4">
							<div class="row form-group">
								<label for="" class="col-md-4 col-form-label">Ваше имя и фамилия</label>
								<div class="col-md-8">
									<input type="text" class="form-control" name="fio-bref" id="">
								</div>
							</div>
							<div class="row form-group">
								<label for="" class="col-md-4 col-form-label">Ваше email</label>
								<div class="col-md-8">
									<input type="text" class="form-control" name="breif-email" id="">
								</div>
							</div>
							<div class="row form-group">
								<div class="col-md-4"></div>
								<div class="col-md-8">
									<div class="custom-control custom-checkbox mb-3 mr-sm-2">
										<input type="checkbox" class="custom-control-input" id="rew-checkbox2">
										<label class="custom-control-label" for="rew-checkbox2">Уведомлять об ответах по эл. почте</label>
									</div>
									<button type="button" class="btn mb-3 btn-primary btn-mw add-comment" id="brief_comment">Отправить</button>
									<p class="small">Чтобы Ваш отзыв либо комментарий прошел модерацию и был опубликован, ознакомьтесь, пожалуйста, с <a href="#">нашими правилами</a>!</p>
								</div>
							</div>
						</div>
					</div>
				</div>
                            <input type="hidden" value="<?=$arParams['ID_PRODUCT']?>" name="ID_PRODUCT">
			</form>
		</div>
		<div class="col-lg-3 mb-3 order-1 order-md-2">
			<h4 class="font-weight-bold"><?=num_word(count($arResult['ITEMS']),array('отзыв', 'отзыва', 'отзывов'))?></h4>
			<div class="rating-nav mb-3">
				<div class="rating-nav__item">
					<div class="p-rating">
						<div class="p-rating__group">
							<svg class="rating-star rating-star--full" width="20" height="20">
								<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprite-svg.svg#ic_star">
								</use>
							</svg>
							<svg class="rating-star rating-star--full" width="20" height="20">
								<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprite-svg.svg#ic_star">
								</use>
							</svg>
							<svg class="rating-star rating-star--full" width="20" height="20">
								<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprite-svg.svg#ic_star">
								</use>
							</svg>
							<svg class="rating-star rating-star--full" width="20" height="20">
								<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprite-svg.svg#ic_star">
								</use>
							</svg>
							<svg class="rating-star rating-star--full" width="20" height="20">
								<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprite-svg.svg#ic_star">
								</use>
							</svg>
							<span class="text-muted small mx-2"><?=($arResult['FULL_SCORE'][5] == 0 ? '0%' : floor($arResult['FULL_SCORE'][5] *100/count($arResult['ITEMS'])).'%')?></span>
						</div>
	
						<a href="#" class="p-rating__link ml-auto"><?=num_word(intval($arResult['FULL_SCORE'][5]),array('отзыв', 'отзыва', 'отзывов'))?></a>
					</div>
				</div>
				<div class="rating-nav__item">
					<div class="p-rating">
						<div class="p-rating__group">
							<svg class="rating-star rating-star--full" width="20" height="20">
								<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprite-svg.svg#ic_star">
								</use>
							</svg>
							<svg class="rating-star rating-star--full" width="20" height="20">
								<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprite-svg.svg#ic_star">
								</use>
							</svg>
							<svg class="rating-star rating-star--full" width="20" height="20">
								<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprite-svg.svg#ic_star">
								</use>
							</svg>
							<svg class="rating-star rating-star--full" width="20" height="20">
								<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprite-svg.svg#ic_star">
								</use>
							</svg>
							<svg class="rating-star" width="20" height="20">
								<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprite-svg.svg#ic_star">
								</use>
							</svg>
							<span class="text-muted small mx-2"><?=($arResult['FULL_SCORE'][4] == 0 ? '0%' : floor($arResult['FULL_SCORE'][4] *100/count($arResult['ITEMS'])).'%')?></span>
						</div>
						<a href="#" class="p-rating__link ml-auto"><?=num_word(intval($arResult['FULL_SCORE'][4]),array('отзыв', 'отзыва', 'отзывов'))?></a>
					</div>
				</div>
				<div class="rating-nav__item">
					<div class="p-rating">
						<div class="p-rating__group">
							<svg class="rating-star rating-star--full" width="20" height="20">
								<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprite-svg.svg#ic_star">
								</use>
							</svg>
							<svg class="rating-star rating-star--full" width="20" height="20">
								<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprite-svg.svg#ic_star">
								</use>
							</svg>
							<svg class="rating-star rating-star--full" width="20" height="20">
								<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprite-svg.svg#ic_star">
								</use>
							</svg>
							<svg class="rating-star" width="20" height="20">
								<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprite-svg.svg#ic_star">
								</use>
							</svg>
							<svg class="rating-star" width="20" height="20">
								<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprite-svg.svg#ic_star">
								</use>
							</svg>
							<span class="text-muted small mx-2"><?=($arResult['FULL_SCORE'][3] == 0 ? '0%' : floor($arResult['FULL_SCORE'][3] *100/count($arResult['ITEMS'])).'%')?></span>
						</div>
						<a href="#" class="p-rating__link ml-auto"><?=num_word(intval($arResult['FULL_SCORE'][3]),array('отзыв', 'отзыва', 'отзывов'))?></a>
					</div>
				</div>
				<div class="rating-nav__item">
					<div class="p-rating">
						<div class="p-rating__group">
							<svg class="rating-star rating-star--full" width="20" height="20">
								<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprite-svg.svg#ic_star">
								</use>
							</svg>
							<svg class="rating-star rating-star--full" width="20" height="20">
								<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprite-svg.svg#ic_star">
								</use>
							</svg>
							<svg class="rating-star" width="20" height="20">
								<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprite-svg.svg#ic_star">
								</use>
							</svg>
							<svg class="rating-star" width="20" height="20">
								<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprite-svg.svg#ic_star">
								</use>
							</svg>
							<svg class="rating-star" width="20" height="20">
								<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprite-svg.svg#ic_star">
								</use>
							</svg>
							<span class="text-muted small mx-2"><?=($arResult['FULL_SCORE'][2] == 0 ? '0%' : floor($arResult['FULL_SCORE'][2] *100/count($arResult['ITEMS'])).'%')?></span>
						</div>
						<a href="#" class="p-rating__link ml-auto"><?=num_word(intval($arResult['FULL_SCORE'][2]),array('отзыв', 'отзыва', 'отзывов'))?></a>
					</div>
				</div>
				<div class="rating-nav__item">
					<div class="p-rating">
						<div class="p-rating__group">
							<svg class="rating-star rating-star--full" width="20" height="20">
								<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprite-svg.svg#ic_star">
								</use>
							</svg>
							<svg class="rating-star" width="20" height="20">
								<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprite-svg.svg#ic_star">
								</use>
							</svg>
							<svg class="rating-star" width="20" height="20">
								<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprite-svg.svg#ic_star">
								</use>
							</svg>
							<svg class="rating-star" width="20" height="20">
								<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprite-svg.svg#ic_star">
								</use>
							</svg>
							<svg class="rating-star" width="20" height="20">
								<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprite-svg.svg#ic_star">
								</use>
							</svg>
							<span class="text-muted small mx-2"><?=($arResult['FULL_SCORE'][1] == 0 ? '0%' : floor($arResult['FULL_SCORE'][1] *100/count($arResult['ITEMS'])).'%')?></span>
						</div>
						<a href="#" class="p-rating__link ml-auto"><?=num_word(intval($arResult['FULL_SCORE'][1]),array('отзыв', 'отзыва', 'отзывов'))?></a>
					</div>
				</div>
			</div>
			<a href="#rew-form" class="btn btn-block btn-primary">Оставить отзыв</a>
		</div>
	</div>
