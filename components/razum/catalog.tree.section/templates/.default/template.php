<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);?>
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
			<?endforeach;?>	
		</ul>
	</div>
</li>
