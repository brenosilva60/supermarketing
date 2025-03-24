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
            width: 300px; /* Define a largura */
            height: auto; /* Define a altura */
            border-radius: 10px; /* Mantém bordas arredondadas, opcional */
        }

        .promo-image {
            left: -30px;
            position: relative;
            max-width: 200px;
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

        .promo-container {
            width: 180px;
            height: auto;
            border-radius: 10px;
            overflow: hidden;
        }

        .product-container {
            width: 100%;
            list-style: none;
            padding: 0;
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
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="header-left">
                <div class="header-text">BOVINOS</div>
                <img src="logo-principal.jpeg" alt="Logo" class="logo">
            </div>
            <div class="header-right">
                <div class="header-text">OFERTA</div>
                <div class="promo-container">
                    <?php
                    // Busca todas as imagens na pasta promo
                    $path = "promo/";
                    $files = glob($path . "*.{jpg,jpeg,png,gif}", GLOB_BRACE);

                    // Se houver imagens, exibe a primeira
                    if (!empty($files)) {
                        echo '<img src="' . $files[0] . '" class="promo-image" id="promo">';
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="price-placeholder"></div>
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

            $pageSize = 6;
            $totalPages = ceil(count($products) / $pageSize);
            $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $currentPage = max(1, min($currentPage, $totalPages));

            $start = ($currentPage - 1) * $pageSize;
            $end = min($start + $pageSize, count($products));

            for ($i = $start; $i < $end; $i++) {
                $product = $products[$i];
            ?>
                <li class="product">
                    <span class="product-name"><?php echo $product[0]; ?>:</span>
                    <span class="product-price">R$ <?php echo $product[1]; ?></span>
                </li>
            <?php } ?>
        </ul>
    </div>

    <script>
        // Lista de imagens carregadas do PHP
        var images = <?php echo json_encode($files); ?>;
        
        var index = 0;
        var imageElement = document.getElementById('promo');
        var intervalTime = 6000; // Tempo para trocar imagem e página juntos
        var totalPages = <?php echo $totalPages; ?>;
        var currentPage = <?php echo $currentPage; ?>;

        function changeImage() {
            if (images.length > 0) {
                index = (index + 1) % images.length;
                imageElement.src = images[index];
            }
        }

        function nextPage() {
            currentPage++;
            if (currentPage > totalPages) {
                currentPage = 1;
            }
            window.location.href = '?page=' + currentPage;
        }

        // Troca a imagem a cada 6 segundos
        setInterval(changeImage, 3000);

        // Troca a página depois de um tempo (exemplo: 30 segundos)
        setInterval(nextPage, 15000);
    </script>
</body>

</html>
