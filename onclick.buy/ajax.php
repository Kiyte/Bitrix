<?
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main\Context,
    Bitrix\Currency\CurrencyManager,
    Bitrix\Sale\Order,
    Bitrix\Sale\Basket,
    Bitrix\Sale\Delivery,
    Bitrix\Sale\PaySystem;

global $USER;
?>
<?
if ($_POST['AJAX_ENABLE'] == 'Y'){
    Bitrix\Main\Loader::includeModule("sale");
    Bitrix\Main\Loader::includeModule("catalog");

    // Допустим некоторые поля приходит в запросе
    $productId = intval($_POST["ID_PRODUCT"]);
    $phone     = intval($_POST["phone"]);
    $name      = $_POST["name"];
    $ID_USER = '';
    $siteId = Context::getCurrent()->getSite();
    $currencyCode = CurrencyManager::getBaseCurrency();
    if ($USER->IsAuthorized()){
        $ID_USER = $USER->GetID();
    }else{
        $newUser = new CUser;
        $arrayName = explode(' ', $name);
        $pass = randString(7);
        $fields = array(
            "LOGIN"               => $phone,
            "NAME"                => $arrayName[0],
            "LAST_NAME"           => ($arrayName[1]!=null?$arrayName[1]:""),
            "EMAIL"               => 'mail@example.com',
            "SECOND_NAME"         => ($arrayName[2]!=null?$arrayName[2]:""),
            "PASSWORD"	          => $pass,
            "CONFIRM_PASSWORD"    => $pass,
            "PERSONAL_PHONE"      => $phone
        );
        
        $ID_USER = $newUser->Add($fields);
        var_dump($newUser->LAST_ERROR);
    }
    
    // Создаёт новый заказ
    $order = Order::create($siteId, $ID_USER);
    $order->setPersonTypeId(1);
    $order->setField('CURRENCY', $currencyCode);

    // Создаём корзину с одним товаром
    $basket = Basket::create($siteId);
    $item = $basket->createItem('catalog', $productId);
    $item->setFields(array(
        'QUANTITY' => 1,
        'CURRENCY' => $currencyCode,
        'LID' => $siteId,
        'PRODUCT_PROVIDER_CLASS' => '\CCatalogProductProvider',
    ));
    $order->setBasket($basket);

    // Создаём одну отгрузку и устанавливаем способ доставки - "Без доставки" (он служебный)
    $shipmentCollection = $order->getShipmentCollection();
    $shipment = $shipmentCollection->createItem();
    $service = Delivery\Services\Manager::getById(Delivery\Services\EmptyDeliveryService::getEmptyDeliveryServiceId());
    $shipment->setFields(array(
        'DELIVERY_ID' => $service['ID'],
        'DELIVERY_NAME' => $service['NAME'],
    ));
    $shipmentItemCollection = $shipment->getShipmentItemCollection();
    $shipmentItem = $shipmentItemCollection->createItem($item);
    $shipmentItem->setQuantity($item->getQuantity());

    // Создаём оплату со способом #1
    $paymentCollection = $order->getPaymentCollection();
    $payment = $paymentCollection->createItem();
    $paySystemService = PaySystem\Manager::getObjectById(1);
    $payment->setFields(array(
        'PAY_SYSTEM_ID' => $paySystemService->getField("PAY_SYSTEM_ID"),
        'PAY_SYSTEM_NAME' => $paySystemService->getField("NAME"),
    ));

    $payment->setField("SUM", $order->getPrice());
    $payment->setField("CURRENCY", $order->getCurrency());
    
    // Устанавливаем свойства
    $propertyCollection = $order->getPropertyCollection();
    
    $nameProp = $propertyCollection->getPayerName();
    $nameProp->setValue($name);

    // Сохраняем
    $order->doFinalAction(true);
    $result = $order->save();
    $orderId = $order->getId();
    echo 'ID заказа:'.$orderId;
} else {
    LocalRedirect('/');
}
?>