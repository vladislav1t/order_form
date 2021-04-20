<?php
session_start();
require_once __DIR__ . '/items.php';
require_once __DIR__ . '/functions.php';


// @todo: сделать проверки
// @todo: добавить дату заказа
$data = [
    'userInfo' => [
        $_POST['name'],
        $_POST['email'],
        $_POST['phone'],
        date('Y-m-d H:i')
    ]
];
$userInfo = [
    $_POST['name'],
    $_POST['email'],
    $_POST['phone'],
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
        $_SESSION['cart'][$key] = $quantity;
        $product = $itemsdata[$key];
        $data[$key] = [
            $product['name'],
            $product['price'],
            $quantity,
            $product['price'] * $quantity,
        ];
    }

    if(empty($_POST['name'])) {
        $_SESSION['errors']['name'] = 'Заполните Имя!';
        header('Location: / ');
    }

    if(empty($_POST['email'])) {
        $_SESSION['errors']['email'] = 'Заполните Email!';
        header('Location: / ');
    } else {
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $_SESSION['errors']['email'] = 'Email заполнен не правильно!';
            header('Location: / ');
        }
    }

    if (empty($_POST['phone'])) {
        $_SESSION['errors']['phone'] = 'Заполните Телефон!';
        header('Location: / ');
    }
    elseif (validate_russian_phone_number($_POST['phone'])) {
        $_SESSION['errors']['phone'] = 'Телефон заполнен не правильно!';
        header('Location: / ');
    }

    if (!empty($_SESSION['errors'])) {
        exit();
    }

    $file = 'orders/' . date("YmdHis") . '.csv';
    $fp = fopen($file, 'w+');
    if (is_resource($fp)) {
        foreach ($data as $item) {
            fputcsv($fp, $item);
        }

        fclose($fp);
    }


    if (is_readable($file)) {
        echo 'Успешно';
        session_destroy();
    } else {
        echo 'Произошла какая то лажа!';
    }
} else {
    echo "Выберите хоть один продукт для заказа";
}