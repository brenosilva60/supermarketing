<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Painel</title>
    <link href="css/styles.css" rel="stylesheet">
    <script src="js/jquery-1.11.0.min.js"></script>
    <script src="js/jquery-migrate-1.2.1.min.js"></script>
    <script type="text/javascript" src="js/slick.min.js"></script>
    <style>
        body {
            background: url('carne.png') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: Arial, sans-serif;
            color: white;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 60%;
            background: rgba(0, 0, 0, 0.6);
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
        }

        .header {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10px;
        }

        .header-left {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        .header-text {
            font-size: 2em;
            font-weight: bold;
        }

        .logo {
            max-width: 180px;
            height: auto;
            border-radius: 10px;
        }

        .divider {
            width: 100%;
            height: 2px;
            background-color: #ffffff;
            margin: 10px 0 20px 0;
        }

        .price-placeholder {
            font-size: 1.8em;
            font-weight: bold;
            color: #FFD700;
            text-align: right;
            width: 100%;
            padding-right: 5px;
            margin-bottom: 10px;
        }

        .offer-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
        }

        .offer-image-right {
            max-width: 250px;
            height: auto;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        .product-container {
            display: flex;
            flex-direction: column;
            width: 100%;
            list-style: none;
            padding: 0;
            gap: 15px;
        }

        .product {
            width: 100%;
            background-color: #003366;
            border: 2px solid #001f4d;
            padding: 15px;
            margin: 10px 0;
            text-align: center;
            font-weight: bold;
            color: white;
            border-radius: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 1.2em;
            box-sizing: border-box;
        }

        .product-name {
            text-align: left;
            flex: 2;
        }

        .product-price {
            text-align: right;
            flex: 1;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="header-left">
                <div class="header-text">BOVINOS</div>
                <img src="WhatsApp Image 2025-03-22 at 17.41.26.jpeg" alt="Logo" class="logo">
            </div>
            <div class="header-text">OFERTA</div>
        </div>

        
        <div class="price-placeholder">PROMO:0,00$</div>

        <div class="divider"></div>

        <ul class="product-container">
            <?php
            $arquivo = fopen('produto.txt', 'r');
            $products = [];

            while (!feof($arquivo)) {
                $linha = fgets($arquivo, 1024);
                $list = explode(";", $linha);

                if (isset($list[1]) && $list[1] != '0') {
                    $products[] = $list;
                }
            }
            fclose($arquivo);

            $pageSize = 4;
            $totalPages = ceil(count($products) / $pageSize);

            $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $currentPage = max(1, min($currentPage, $totalPages));

            $start = ($currentPage - 1) * $pageSize;
            $end = min($start + $pageSize, count($products));

            for ($i = $start; $i < $end; $i++) {
                $product = $products[$i];
            ?>
                <li class="product" id="product-<?php echo $i; ?>">
                    <span class="product-name"><?php echo $product[0]; ?>:</span>
                    <span class="product-price">R$ <?php echo $product[1]; ?></span>
                </li>
            <?php } ?>
        </ul>
    </div>

    <script>
        var currentPage = <?php echo $currentPage; ?>;
        var totalPages = <?php echo $totalPages; ?>;

        function nextPage() {
            currentPage++;
            if (currentPage > totalPages) {
                currentPage = 1;
            }
            window.location.href = '?page=' + currentPage;
        }

        setInterval(nextPage, 6000);
    </script>
</body>

</html>