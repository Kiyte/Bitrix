<?php
namespace Iiko\Integration\Export;

Class ExportMenuIiko
{
    const IBLOCK_CATALOG_ID = 1;
    const IBLOCK_OFFERS_ID = 6; 
    const CATALOG_GROUP_ID = 1;
    public $login;
    public $password;

    public function __construct($login = 'brooklyn', $password = 'br00klyn2')
    {
        $this->login = $login;
        $this->password = $password;
    }

    public function sendRequest($url, $method = 'GET', $data = '')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);

        if ($method == 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            print_r(curl_error($ch));
        } else {
            curl_close($ch);
        }
        return $result;
    }

    public function getToken()
    {
        $url = 'https://iiko.biz:9900/api/0/auth/access_token?user_id='.$this->login.'&user_secret='.$this->password;
        $token = $this->sendRequest($url);
        $token = str_replace('"', "", $token);
        return $token;
    }

    public function getGuid($token)
    {
        $url = 'https://iiko.biz:9900/api/0/organization/list?access_token='.$token.'&request_timeout=00%3A02%3A00';
        $json = $this->sendRequest($url);
        $dataArr = $this->jsonDecode($json);
        $guid = $dataArr[0]['id'];
        return $guid;
    }

    public function getMenu($token, $guid)
    {
        $url = 'https://iiko.biz:9900/api/0/nomenclature/'.$guid.'?access_token='.$token;
        $menu = $this->sendRequest($url);
        return $menu;
    }

    public function jsonDecode($data)
    {
        $result = json_decode($data, true);
        return $result;
    }

    public function jsonEncode($data)
    {
        $result = json_encode($data);
        return $result;
    }

    public function groupsParse($data)
    {
        $menuArr = $this->jsonDecode($data);
        foreach ($menuArr['groups'] as $group) {
			if (!$group['parentGroup']) {				
				$groupArr["NAME"] = $group['name'];
				$groupArr["ID"] = $group['id'];
				$groupArr["IS_DELETED"] = $group['isDeleted'];
				$groupArr["PARENT_GROUP"] = $group['parentGroup'];
				$groupsArr[] = $groupArr;
			}
        }
        return $groupsArr;
    }
	
	public function productsParse($data)
	{
		$menuArr = $this->jsonDecode($data);
        foreach ($menuArr['groups'] as $product) {
			if ($product['parentGroup']) {	
				$productArr["NAME"] = $product['name'];
				$productArr["ID"] = $product['id'];
				$productArr["IS_DELETED"] = $product['isDeleted'];
				$productArr["PARENT_GROUP"] = $product['parentGroup'];
				$productsArr[] = $productArr;
			}
        }
        return $productsArr;
	}

    public function itemsParse($data)
    {
        $menuArr = $this->jsonDecode($data);
        foreach ($menuArr["products"] as $item) {
            $itemArr["NAME"] = $item['name'];
            $itemArr["ID"] = $item['id'];
            $itemArr["CODE"] = $item['code'];
            $itemArr["PARENT_GROUP"] = $item['parentGroup'];            
            $itemArr["IS_DELETED"] = $item['isDeleted'];
            $itemArr["PRICE"] = $item['price'];

            $itemArr["DESCRIPTION"] = $item['description'];
            $itemArr["WEIGHT"] = $item['weight'];
            $itemArr["CARBOHYDRATE_AMOUNT"] = $item['carbohydrateAmount'];
            $itemArr["CARBOHYDRATE_FULL_AMOUNT"] = $item['carbohydrateFullAmount'];
            $itemArr["FAT_AMOUNT"] = $item['fatAmount'];
            $itemArr["FAT_FULL_AMOUNT"] = $item['fatFullAmount'];
            $itemArr["ENERGY_AMOUNT"] = $item['energyAmount'];
            $itemArr["ENERGY_FULL_AMOUNT"] = $item['energyFullAmount'];            
            $itemArr["FIBER_AMOUNT"] = $item['fiberAmount'];
            $itemArr["FIBER_FULL_AMOUNT"] = $item['fiberFullAmount'];
            $itemArr["MEASURE_UNIT"] = $item['measureUnit'];
            $itemArr["DETAIL_PICTURE_URL"] = stripcslashes(trim(htmlspecialcharsEx($item['images'][0]['imageUrl'])));
            $itemsArr[] = $itemArr;
        }
        return $itemsArr;
    }

    public function addItem($items)
    {
        $errors = [];
        foreach ($items as $item) {
            //echo "<pre>"; print_r($item); echo "</pre> <br>";
			
            if ($item["NAME"] == '') {
                continue;
            }
            $sectionId = $this->getSectionIdByProperty("IIKO_ID", $item["PARENT_GROUP"]);
            $item["STATUS"] = $item["IS_DELETED"] == false ? "Y" : "N"; 
            $el = new \CIBlockElement();
			
            $arFields = Array(
                "ACTIVE" => $item["STATUS"],
                "IBLOCK_SECTION_ID" => $sectionId,
                "IBLOCK_ID" => self::IBLOCK_CATALOG_ID,
                "NAME" => $item["NAME"],
                "MODIFIED_BY" => $GLOBALS['USER']->GetID(),
                "CODE" => $this->codeTranslite($item["NAME"]),
                'PROPERTY_VALUES' => array(
                    'IIKO_ID' => $item["ID"],
                    'CODE' => $item["CODE"],
					'PRICE' => $item["PRICE"],
                    'DESCRIPTION' => $item["DESCRIPTION"],
                    'WEIGHT' => $item["WEIGHT"],
                    'CARBOHYDRATE_AMOUNT' => $item["CARBOHYDRATE_AMOUNT"],
                    'CARBOHYDRATE_FULL_AMOUNT' => $item["CARBOHYDRATE_FULL_AMOUNT"],
                    'FAT_AMOUNT' => $item["FAT_AMOUNT"],
                    'FAT_FULL_AMOUNT' => $item["FAT_FULL_AMOUNT"],
                    'ENERGY_AMOUNT' => $item['ENERGY_AMOUNT'],
                    'ENERGY_FULL_AMOUNT' => $item['ENERGY_FULL_AMOUNT'],
                    'FIBER_AMOUNT' => $item['FIBER_AMOUNT'],
                    'FIBER_FULL_AMOUNT' => $item['FIBER_FULL_AMOUNT'],
                    'MEASURE_UNIT' => $item["MEASURE_UNIT"],
                )
            );
            
            if(!empty($item["DETAIL_PICTURE_URL"])) {
                $detailImg = \CFile::MakeFileArray($item["DETAIL_PICTURE_URL"]);
                $imageId = \CFile::SaveFile(
                    $detailImg,
                    '/iblock',
                    false,
                    false
                );
                $previewImg = \CFile::ResizeImageGet(
                    $imageId,
                    array('width' => 170, 'height' => 170),
                    BX_RESIZE_IMAGE_PROPORTIONAL,
                    false
                );
                $arFields["DETAIL_PICTURE"] = $detailImg;
                $arFields["PREVIEW_PICTURE"] = \CFile::MakeFileArray($previewImg["src"]);
            }
            
            //echo "arrFields"; print_r($arFields); echo "<br>";
            if ($Id = $this->getItemIdByProperty("IIKO_ID", $item["ID"])) {
                $res = $el->Update($Id, $arFields);
            } else {
                /* если товар есть и статус изменился ? */
                if ($item["STATUS"] == "N") {
                    continue;
                }
                $Id = $el->Add($arFields);
                $res = ($Id > 0);
            }
            $item["ID"] = $Id;

            if(!$res) {
                $errors[] = $el->LAST_ERROR;
            }
        }
        if ($errors) {
            return $errors;
        } else {
            return true;
        }
    }

	public function addIblockElement($arFields)
	{
		$el = new \CIBlockElement();
		$iblockElementId = $el->Add($arFields);
        if (!empty($el->LAST_ERROR)) {
            return "Ошибка добавления товара: ". $el->LAST_ERROR;
        }
        return $iblockElementId;
	}
	
	public function updateIblockElement($itemId, $arFields)
	{
		$el = new \CIBlockElement();
		$el->Update($itemId, $arFields);
        if (!empty($el->LAST_ERROR)) {
            return "Ошибка обновления товара: ". $el->LAST_ERROR;
        }
        return true;
	}
	
	public function codeTranslite($itemName)
	{
		$translitParams = array(
		  "max_len" => "100", 
		  "change_case" => "L", 
		  //"replace_space" => "_", 
		  //"replace_other" => "_",
          "replace_space" => "-", 
		  "replace_other" => "-",           
		  "delete_repeat_replace" => "true"
		);
		$transliteResult = \CUtil::translit($itemName, "ru", $translitParams);
		return $transliteResult;
	}
	
	public function addProduct($products)
	{
		foreach ($products as $product) {
			
			$product["STATUS"] = $product["IS_DELETED"] == false ? "Y" : "N";
			
			$arFields = Array(
                "ACTIVE" => $product["STATUS"],
                "NAME" => $product["NAME"],
                "MODIFIED_BY" => $GLOBALS['USER']->GetID(),
                "CODE" => $this->codeTranslite($product["NAME"]),
                'PROPERTY_VALUES' => array(
                    'IIKO_ID' => $product["ID"],
                    'CODE' => $product["CODE"],
					'PRICE' => $product["PRICE"],
                    'DESCRIPTION' => $product["DESCRIPTION"],
                    'WEIGHT' => $product["WEIGHT"],
                    'CARBOHYDRATE_AMOUNT' => $product["CARBOHYDRATE_AMOUNT"],
                    'CARBOHYDRATE_FULL_AMOUNT' => $product["CARBOHYDRATE_FULL_AMOUNT"],
                    'FAT_AMOUNT' => $product["FAT_AMOUNT"],
                    'FAT_FULL_AMOUNT' => $product["FAT_FULL_AMOUNT"],
                    'ENERGY_AMOUNT' => $product['ENERGY_AMOUNT'],
                    'ENERGY_FULL_AMOUNT' => $product['ENERGY_FULL_AMOUNT'],
                    'FIBER_AMOUNT' => $product['FIBER_AMOUNT'],
                    'FIBER_FULL_AMOUNT' => $product['FIBER_FULL_AMOUNT'],
                    'MEASURE_UNIT' => $product["MEASURE_UNIT"],
                )
            );
		
			// есть товар с торговыми предложениями
			if ($product["PARENT_GROUP"] != '' && $itemId = $this->getItemIdByProperty("IIKO_ID", $product["PARENT_GROUP"])) {
                
				//echo "товар есть Id = "; print_r($itemId); echo "<br>";
				// если товар есть, то добавляем предложения
				//echo "arFields before"; print_r($arFields); echo "<br>";
				
				$arFields["IBLOCK_ID"] = self::IBLOCK_OFFERS_ID;
				
				if ($offerId = $this->getProductIdByProperty("IIKO_ID", $product["ID"])) {
					// если есть торговое предложение -> обновить
					//echo "есть предложение productId = "; print_r($offerId); echo "<br>";
					
					$arFields['PROPERTY_VALUES']['CML2_LINK'] = $itemId;
					$this->updateIblockElement($offerId, $arFields);
					$productOfferArr["ID"] = $offerId;
					$productOfferArr["PRICE"] = $product["PRICE"];
					$addPriceRes = $this->addPrice($productOfferArr);
				} else {			
					// создать предложение
					$arFields["IBLOCK_ID"] = self::IBLOCK_OFFERS_ID;
					$arFields['PROPERTY_VALUES']['CML2_LINK'] = $itemId;

					if ($offerId = $this->addIblockElement($arFields)) {	
						//echo "новое предложение offerId"; echo $offerId; echo "<br>";

						if ($this->addCCatalogProduct($offerId)) {
							/* добавлять цену товару нужно после создание товара - CCatalogProduct::Add,
							 * привязав элемент инфоблока и товар, а затем добавлять цену
							 * */
							//echo "addProduct"; echo "<br>";							
							$productOfferArr["ID"] = $offerId;
							$productOfferArr["PRICE"] = $product["PRICE"];
							$addPriceRes = $this->addPrice($productOfferArr);
						}	
					}
				}
			// есть товар без торговых предложений
			} else if ($itemId = $this->getItemIdByProperty("IIKO_ID", $product["ID"])) {
				
				$this->updateIblockElement($itemId, $arFields);
				if ($this->updateCCatalogProduct($itemId, $arFields)) {
					/* добавлять цену товару нужно после создание товара - CCatalogProduct::Add,
					 * привязав элемент инфоблока и товар, а затем добавлять цену
					 * */
					//echo "addProduct"; echo "<br>";
					$arFields["IBLOCK_ID"] = self::IBLOCK_CATALOG_ID;
					$productOfferArr["ID"] = $itemId;
					$productOfferArr["PRICE"] = $product["PRICE"];
					$addPriceRes = $this->addPrice($productOfferArr);
				}	
			// товара без торговых предложений нет - создать
            } else {
				
				// товара нет - добавить товар, потом предлож ?								

				$arFields["IBLOCK_ID"] = self::IBLOCK_CATALOG_ID;				
				if ($product["PARENT_GROUP"] != ''){
					$arFields["IBLOCK_SECTION_ID"] = $this->getSectionIdByProperty("IIKO_ID", $product["PARENT_GROUP"]);
				}
				$itemId = $this->addIblockElement($arFields);
				
				//echo "новый товар Id = "; print_r($itemId); echo "<br>";
				//echo "arFields before"; print_r($arFields); echo "<br>";

				if ($this->addCCatalogProduct($itemId)) {
					/* добавлять цену товару нужно после создание товара - CCatalogProduct::Add,
					 * привязав элемент инфоблока и товар, а затем добавлять цену
					 * */
					//echo "addProduct"; echo "<br>";
					$productOfferArr["ID"] = $itemId;
					$productOfferArr["PRICE"] = $product["PRICE"];
					$addPriceRes = $this->addPrice($productOfferArr);
				}	
            }
		}
		if ($errors) {
            return $errors;
        } else {
            return true;
        }
	}
	
    public function addCCatalogProduct($itemId)
    {
        $result = \CCatalogProduct::Add( array("ID" => $itemId) );
        return $result;
    }
	
	public function updateCCatalogProduct($itemId, $arFields)
    {
        $result = \CCatalogProduct::Update($itemId, $arFields);
        return $result;
    }

    public function addPrice($item)
    {
        /* если не делать выборку цены, а сразу добавлять CPrice::Add( $arFields ),
         * то товар дублируется с новой ценой
         * */
        $res = \CPrice::GetList( array(), array("PRODUCT_ID" => $item["ID"], "CATALOG_GROUP_ID" => self::CATALOG_GROUP_ID));
        if ($arr = $res->Fetch()) {
            $priceId = $arr["ID"];
        }
        $arFields = Array(
            "PRODUCT_ID" => $item["ID"],
            "CATALOG_GROUP_ID" => self::CATALOG_GROUP_ID,
            "CURRENCY" => "RUB",
            "PRICE" => $item["PRICE"],
        );
        if ($priceId) {
            \CPrice::Update($priceId, $arFields);
        } else {
            \CPrice::Add($arFields);
        }
    }

    public function getItemIdByProperty($propertyName, $propertyValue)
    {
        $arFilter = array(
            "IBLOCK_ID" => self::IBLOCK_CATALOG_ID,
            "PROPERTY_".$propertyName => $propertyValue
        );
        $rs = \CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
        if ($ar = $rs->GetNext()) {
            return $ar["ID"];
        }
    }
	
	public function getProductIdByProperty($propertyName, $propertyValue)
	{
		$arFilter = array(
            "IBLOCK_ID" => self::IBLOCK_OFFERS_ID,
            "PROPERTY_".$propertyName => $propertyValue
        );
        $rs = \CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
        if ($ar = $rs->GetNext()) {
			//echo "ar "; print_r($ar); echo "<br>";
            return $ar["ID"];
        }
	}

    public function getSectionIdByProperty($propertyName, $propertyValue)
    {
        $arFilter = array(
            "IBLOCK_ID" => self::IBLOCK_CATALOG_ID,
            "UF_".$propertyName => $propertyValue,
        );
        //echo "getSectionByCodeProp "; print_r($arFilter); echo "<br>";
        $rsSections = \CIBlockSection::GetList($arSort, $arFilter, false, $arSelect);
        while ($arSection = $rsSections->Fetch()) {
            //print_r($arSection);
            return $arSection["ID"];
        }
        return false;
    }

    public function getSectionIdByName($name)
    {
        $arFilter = array(
            "IBLOCK_ID" => self::IBLOCK_CATALOG_ID,
            "NAME" => $name,
        );
        $rsSections = \CIBlockSection::GetList($arSort, $arFilter, false, $arSelect);
        while ($arSction = $rsSections->Fetch()) {
            return $arSction["ID"];
        }
        return false;
    }

    public function addSection($items)
    {
        $errors = [];
        /* добавить свойство раздела
         *
         * */
        $arFields = Array(
            "ENTITY_ID" => "IBLOCK_".self::IBLOCK_CATALOG_ID."_SECTION",
            "FIELD_NAME" => "UF_IIKO_ID",
            "USER_TYPE_ID" => "string",
            "EDIT_FORM_LABEL" => Array("ru" => "IIKO_ID", "en" => "IIKO_ID")
        );
        $obUserField  = new \CUserTypeEntity();
        /* ---/ */

        foreach($items as $item) {
            $sectionId = $this->getSectionIdByProperty("IIKO_ID", $item["PARENT_GROUP"]);
			$item["STATUS"] = $item["IS_DELETED"] == false ? "Y" : "N";
            $bs = new \CIBlockSection;
            $arFields = Array(
                "ACTIVE" => $item["STATUS"],
				"CODE" => $this->codeTranslite($item["NAME"]),
                "IBLOCK_SECTION_ID" => $sectionId,
                "UF_IIKO_ID" => $item["ID"],
                "IBLOCK_ID" => self::IBLOCK_CATALOG_ID,
                "NAME" => $item["NAME"],
                "MODIFIED_BY" => $GLOBALS['USER']->GetID(),
              );
            if($Id = $this->getSectionIdByName($item["NAME"])) {
                $res = $bs->Update($Id, $arFields);
            } else {				
                $ID = $bs->Add($arFields);
                $res = ($ID > 0);
            }
            if(!$res){
                $errors[] = $bs->LAST_ERROR;
            }
        }
        if ($errors) {
            print_r($errors);
        } else {
            return true;
        }
    }
	
	public function deactivateElements()
	{
		$obElement = new \CIBlockElement;
		$arFilter = array(
            "IBLOCK_ID" => self::IBLOCK_CATALOG_ID,
            "ACTIVE" => "Y",
        );
		$rsElements = \CIBlockElement::GetList($arSort, $arFilter, false, $arNavParams, $arSelect);
		while ($arElement = $rsElements->Fetch()) {
			$obElement->Update($arElement["ID"], array("ACTIVE" => "N"));
		}
	}
	
	public function deactivateSections()
	{
		$obSection = new \CIBlockSection;
		$arFilter = array(
            "IBLOCK_ID" => self::IBLOCK_CATALOG_ID,
            "ACTIVE" => "Y",
        );
		$rsSections = \CIBlockSection::GetList($arSort, $arFilter, false, $arNavParams, $arSelect);
        while($arSection = $rsSections->Fetch()) {
			$obSection->Update($arSection["ID"], array("ACTIVE" => "N"));
		}
	}

    public function run()
    {
        $token = $this->getToken();
        $guid = $this->getGuid($token);
        $menu = $this->getMenu($token, $guid);
        $groups = $this->groupsParse($menu);
		$products = $this->productsParse($menu);
        $items = $this->itemsParse($menu);
		$this->deactivateSections();
		$this->deactivateElements();
        if ($this->addSection($groups) && $this->addItem($products) && $this->addProduct($items)) {
            echo json_encode(['status' => true, 'msg' => 'Экспорт меню завершен.']);
        }
        return null;
    }
}