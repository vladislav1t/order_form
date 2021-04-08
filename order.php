<?php
require_once __DIR__ . '/items.php';
// @todo: сделать проверки
// @todo: добавить дату заказа
$data = [
    [
        $_POST['name'],
        $_POST['email'],
        $_POST['phone'],
        date('Y-m-d H:i')
    ]
];

$productOrders = !empty($_POST['quantity']) && is_array($_POST['quantity']) ?
    array_filter(
        array_map(
            function ($value) {
                return is_numeric($value) ? (int)$value : null;
            },
            $_POST['quantity']
        )
    ) : [];
if (count($productOrders)) {
    foreach ($productOrders as $key => $quantity) {
        if (!isset($itemsdata[$key])) {
            continue;
        }
        $product = $itemsdata[$key];
        $data[$key] = [
            $product['name'],
            $product['price'],
            $quantity,
            $product['price'] * $quantity,
        ];
    }
    $file = 'orders/' . date("YmdHis") . '.csv';

    $fp = fopen($file, 'a+');
    if (is_resource($fp)) {

        foreach ($data as $item) {
            fputcsv($fp, $item);
        }

        fclose($fp);
    }

    if (is_readable($file)) {
        echo 'Успешно';
    } else {
        echo 'Произошла какая то лажа!';
    }
} else {
    echo "Выберите хоть один продукт для заказа";
}
