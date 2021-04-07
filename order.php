<?php
require_once __DIR__ . '/items.php';

$user = ['name' => $_POST['name'], 'email' => $_POST['email'], 'phone' => $_POST['phone']];

$datas = [];

$datas['user'] = $user;

if (isset($_POST['quantity']) && is_array($_POST['quantity'])):

    foreach ($_POST['quantity'] as $key => $quantity) {
        if ($quantity):
            $datas[$key]['name'] = $itemsdata[$key]['name'];
            $datas[$key]['price'] = $itemsdata[$key]['price'];
            $datas[$key]['quantity'] = $quantity;
        endif;
    }
endif;

$file =  'file_' .date(" H_i_s_d_m_Y"). '.csv';

$fp = fopen($file, 'w');

foreach ($datas as $data) {
    fputcsv($fp, $data);
}

fclose($fp);

if (is_readable($file)) {
    echo 'Успешно';
} else {
    echo 'Произошла какая то лажа!';
}
?>
