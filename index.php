<html>

<head>
    <meta charset="utf-8">
    <title>Painel</title>
    <link href="css/styles.css" rel="stylesheet">
    <script src="js/jquery-1.11.0.min.js"></script>
    <script src="js/jquery-migrate-1.2.1.min.js"></script>
    <script type="text/javascript" src="js/slick.min.js"></script>
    <style>
        .product-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            max-width: 80%;
            margin: 20px auto;
            list-style: none;
        }

        .product {
            width: 32%;
            background-color: yellow;
            border: 2px solid black;
            padding: 15px;
            margin: 10px 0;
            text-align: center;
            font-weight: bold;
            color: black;
            border-radius: 15px;
            display: flex;
            justify-content: space-between;
        }

        .product-name {
            text-align: left;
            flex: 1;
        }

        .product-price {
            text-align: right;
            flex: 1;
        }
    </style>
</head>

<body>
    <div class="main">
        <div class="header">
            <div class="banner"></div>
        </div>

        <div id="slideshowl">
            <div class="content">
                <ul class="product-container">
                    <?php
                    $arquivo = fopen('produto.txt', 'r');
                    $cont = 0;
                    $products = [];

                    while (!feof($arquivo)) {
                        $linha = fgets($arquivo, 1024);
                        $list = explode(";", $linha);

                        if (isset($list[1]) && $list[1] != '0') {
                            $products[] = $list;
                        }
                    }
                    fclose($arquivo);

                    $pageSize = 16;
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
        </div>
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

        setInterval(nextPage, 5000);
    </script>
</body>

</html>
