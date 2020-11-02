<?
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");
?>
<?
if ($_POST['AJAX_ENABLE'] == 'Y'){
    $PROP = array();
    $action_type = $_REQUEST['action_type'];
    if($action_type == 'credit'){
        $name           = $_REQUEST['name'];
        $phone          = $_REQUEST['phone'];
        $product        = $_REQUEST['product'];
        $PROP[1319] = $name;
        $PROP[1320] = $phone;
        $PROP[1321] = $product;
        $IBLOCK_ID = 20;
        $NAME = "Кредитная заявка";
    } else {
        $name           = $_REQUEST['name'];
        $contact        = $_REQUEST['contact'];
        $price          = $_REQUEST['price'];
        $product_url    = $_REQUEST['product_url'];
        $PROP[1315] = $name;
        $PROP[1316] = $contact;
        $PROP[1317] = $price;
        $PROP[1318] = $product_url;
        $IBLOCK_ID = 19;
        $NAME = "Нашёл дешевле";
    }
    $el = new CIBlockElement;
    $arLoadProductArray = Array(
      "MODIFIED_BY"    => $USER->GetID(),
      "IBLOCK_SECTION_ID" => false,        
      "IBLOCK_ID"      => $IBLOCK_ID,
      "PROPERTY_VALUES"=> $PROP,
      "NAME"           => $NAME,
      "ACTIVE"         => "Y"
      );

    if($PRODUCT_ID = $el->Add($arLoadProductArray)){
      echo "New ID: ".$PRODUCT_ID;
    }else{
      echo "Error: ".$el->LAST_ERROR;
    }
} else {
    LocalRedirect('/');
}
?>