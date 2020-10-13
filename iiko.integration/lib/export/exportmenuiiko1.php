<?php
namespace Iiko\Integration\Export;

Class ExportMenuIiko
{
    const IBLOCK_CATALOG_ID = 1;
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
            $groupArr["NAME"] = $group['name'];
            $groupArr["ID"] = $group['id'];
            $groupArr["IS_DELETED"] = $group['isDeleted'];
            $groupArr["PARENT_GROUP"] = $group['parentGroup'];
            $groupsArr[] = $groupArr;
        }
        return $groupsArr;
    }

    public function itemsParse($data)
    {
        $menuArr = $this->jsonDecode($data);
        foreach ($menuArr["products"] as $item) {
            $itemArr["NAME"] = $item['name'];
            $itemArr["ID"] = $item['id'];
            $itemArr["CODE"] = $item['code'];
            $itemArr["PARENT_GROUP"] = $item['parentGroup'];
            $itemArr["PRICE"] = $item['price'];
            $itemArr["IS_DELETED"] = $item['isDeleted'];
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
                'PROPERTY_VALUES' => array(
                    'IIKO_ID' => $item["ID"],
                    'CODE' => $item["CODE"],
					'PRICE' => $item["PRICE"],
                )
            );
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

    public function addProduct($itemId)
    {
        $result = \CCatalogProduct::Add( array("ID" => $itemId) );
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
        $obUserField  = new \CUserTypeEntity;
        $obUserField->Add($arFields);
        /* ---/ */

        foreach($items as $item) {

            $sectionId = $this->getSectionIdByProperty("IIKO_ID", $item["PARENT_GROUP"]);
            $item["STATUS"] = $item["IS_DELETED"] == false ? "Y" : "N";

            $bs = new \CIBlockSection;
            $arFields = Array(
                "ACTIVE" => $item["STATUS"],
                "IBLOCK_SECTION_ID" => $sectionId,
                "UF_IIKO_ID" => $item["ID"],
                "IBLOCK_ID" => self::IBLOCK_CATALOG_ID,
                "NAME" => $item["NAME"],
                "MODIFIED_BY" => $GLOBALS['USER']->GetID(),
              );
            //echo "addSection "; print_r($arFilter); echo "<br>";
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
            return $errors;
        } else {
            return true;
        }
    }

    public function run()
    {
        $token = $this->getToken();
        $guid = $this->getGuid($token);
        $menu = $this->getMenu($token, $guid);
        $groups = $this->groupsParse($menu);
        $items = $this->itemsParse($menu);

        if ($this->addSection($groups) &&  $this->addItem($items)) {
            echo json_encode(['status' => true, 'msg' => 'Экспорт меню завершен.']);
        }
        return null;
    }
}