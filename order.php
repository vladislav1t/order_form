<?php
session_start();
require_once __DIR__ . '/items.php';

function validate_russian_phone_number($tel)
{
    $tel = trim((string)$tel);
    $tel = preg_replace('#[^0-9+]+#uis', '', $tel);
    if (!preg_match('#^(?:\\+?7|8|)(.*?)$#uis', $tel, $m)) return false;
    $tel = '+7' . preg_replace('#[^0-9]+#uis', '', $m[1]);
    if (!preg_match('#^\\+7[0-9]{10}$#uis', $tel, $m)) return false;
    return $tel;
}

// @todo: сделать проверки
// @todo: добавить дату заказа
$data = [
    'userInfo' => [
        $_POST['name'],
        $_POST['email2'],
        $_POST['phone'],
        date('Y-m-d H:i')
    ]
];
$userInfo = [
    $_POST['name'],
    $_POST['email2'],
    $_POST['phone'],
];

$_SESSION['name'] = $_POST['name'] ?? '';
$_SESSION['email'] = $_POST['email2'] ?? '';
$_SESSION['phone'] = $_POST['phone'] ?? '';

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
        exit();
    }

    if(empty($_POST['email'])) {
        $_SESSION['errors']['email'] = 'Заполните Email!';
        header('Location: / ');exit();
    } else {
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $_SESSION['errors']['email'] = 'Email заполнен не правильно!';
            header('Location: / ');exit();
        }
    }

    if (empty($_POST['phone'])) {
        $_SESSION['errors']['phone'] = 'Заполните Телефон!';
        header('Location: / ');
        exit();
    }
    elseif (validate_russian_phone_number($_POST['phone'])) {
        $_SESSION['errors']['phone'] = 'Телефон заполнен не правильно!';
        header('Location: / ');
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