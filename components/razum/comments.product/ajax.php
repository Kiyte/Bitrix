<?
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main\Loader,
    Bitrix\Highloadblock as HL,
    Bitrix\Main\Entity,
    Bitrix\Main\Context,
    Bitrix\Currency\CurrencyManager;

Loader::includeModule("highloadblock"); 
global $USER;
?>
<?
if ($_POST['AJAX_ENABLE'] == 'Y'){   
    $arFields = array();
    $arFields['fio']            = $_POST['fields']['fio'];
    $arFields['email']          = $_POST['fields']['email'];
    $arFields['rewcheckbox']    = ($_POST['fields']['rew-checkbox'] == 'false' ? '' : '1');
    $arFields['worth']          = $_POST['fields']['worth'];
    $arFields['disadvantages']  = $_POST['fields']['disadvantages'];
    $arFields['comment']        = $_POST['fields']['comment'];
    $arFields['rating']         = $_POST['fields']['rating'];
    $arFields['id_product']     = $_POST['fields']['id_product'];
    $arFields['parent_comment'] = $_POST['fields']['parent_comment'];
    
$hlbl = 3; // Указываем ID нашего highloadblock блока к которому будет делать запросы.
$hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch(); 

$entity = HL\HighloadBlockTable::compileEntity($hlblock); 
$entity_data_class = $entity->getDataClass(); 

   // Массив полей для добавления
   $data = array(
      'UF_NAME'=>$arFields['fio'],
      'UF_EMAIL'=>$arFields['email'],
      "UF_DISADVANTAGES"=>$arFields['disadvantages'],
      "UF_WORTH"=>$arFields['worth'],
      "UF_SCORES"=>intval($arFields['rating']),
      "UF_NEWSLETTER"=>$arFields['rewcheckbox'],
      "UF_COMMENT"=>$arFields['comment'],
      "UF_ID_PRODUCT"=>intval($arFields['id_product']),
      "UF_ID_PARENT_COMMENT"=>intval($arFields['parent_comment']),
      "UF_ACTIVE"=>''
   );   
   $result = $entity_data_class::add($data);
   echo '<pre>';
   var_dump($result);
   echo '<pre>';
   
} else {
    LocalRedirect('/');
}
?>