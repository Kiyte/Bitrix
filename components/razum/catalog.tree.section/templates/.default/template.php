<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
/*
echo "<pre>";
print_r($arResult);
echo "</pre>";*/
?>
<li class="catalog-main"><a href="#catalog-collapse" class="list-pad collapsed" data-toggle="collapse" aria-expanded="false">
	<div class="gamburger__menu">
		<div class="buttonbb">
			<span class="lines"></span>
			<span class="lines"></span>
			<span class="lines"></span>
		</div>
	</div>
	Каталог</a>
	<div class="collapse" id="catalog-collapse" style="">					
		<ul>
			<?foreach ($arResult['CHILD'] as $value):?>
				<li>
					<a href="<?=($value['CHILD'] != '' ? '#'.$value['CODE'] : $value['SECTION_PAGE_URL'])?>" data-toggle="collapse"><?=$value['NAME']?></a>
					<?if ($value['CHILD'] != ''){?>
						<div class="collapse" id="<?=$value['CODE']?>">
							<ul class="catalog-main__sub-nav">
								<?foreach ($value['CHILD'] as $valueChild) {?>
									<li>
										<a href="<?=$valueChild['SECTION_PAGE_URL']?>"><?=$valueChild['NAME']?>
											<span>336</span>
										</a>
									</li>
								<?}?>
							</ul>
						</div>
					<?}?>
				</li>
			<?endforeach;/*?>
				<li>
					<a href="#catalog-sub-collapse" data-toggle="collapse">Гитары</a>
					<div class="collapse" id="catalog-sub-collapse">
						<ul class="catalog-main__sub-nav">
							<li class="active"><a href="#">Электрогитары <span>1234</span></a></li>
							<li><a href="#">Бас-гитары <span>336</span></a></li>
							<li><a href="#">Акустические <span>5678</span></a></li>
							<li><a href="#">Классические <span>1882</span></a></li>
							<li><a href="#">Электроакустические <span>568</span></a></li>
							<li><a href="#">Полуакустические <span>985</span></a></li>
							<li><a href="#">Уменьшенные гитары <span>1289</span></a></li>
							<li><a href="#">Укулеле <span>87</span></a></li>
							<li><a href="#">Наборы новичкам <span>34</span></a></li>
							<li><a href="#">Аксессуары <span>12</span></a></li>
						</ul>
					</div>
				</li>
				<li><a href="#">Гитарное оборудование</a></li>
				<li><a href="#">Трансляционное оборудование</a></li>
				<li><a href="#">Звуковое оборудование</a></li>
				<li><a href="#">Студия и звукозапись</a></li>
				<li><a href="#">Клавишные</a></li>
				<li><a href="#">Микрофоны</a></li>
				<li><a href="#">Наушники</a></li>
				<li><a href="#">Свет, сцена, эффекты</a></li>
				<li><a href="#">Караоке оборудование</a></li>
				<li><a href="#">Ударные</a></li>
				<li><a href="#">Hi Fi оборудование</a></li>
				<li><a href="#">Перкуссия</a></li>
				<li><a href="#">Оркестровые</a></li>
				<li><a href="#">Народные</a></li>
				<li><a href="#">Сувениры и подарки</a></li>
			<?}*/?>
		</ul>
	</div>
</li>