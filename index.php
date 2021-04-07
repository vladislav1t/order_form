<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <link rel="stylesheet" href="/style.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf"
            crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.slim.js"
            integrity="sha256-HwWONEZrpuoh951cQD1ov2HUK5zA5DwJ1DNUXaM6FsY=" crossorigin="anonymous"></script>
    <script src="/script.js"></script>
    <title>OrderForm</title>
</head>
<body>
<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
        <form method="post" action="order.php">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Название</th>
                    <th scope="col">Цена</th>
                    <th scope="col">Кол-во</th>
                </tr>
                </thead>
                <tbody>
                <?php require_once __DIR__ . '/items.php';?>
                <?php foreach ($itemsdata as $key => $items):?>
                    <tr>
                        <th scope="row"><?php echo $key?></th>
                        <td><?php echo $items['name'] ?></td>
                        <td><?php echo $items['price'] ?></td>
                        <td><input type="number" name="quantity[<?php echo $key ?>]"
                                   value="0"></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <input type="submit" class="btn btn-primary" value="Заказать">
        </form>

    </div>
    <div class="col-md-2"></div>
</div>
</body>
</html>