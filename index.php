<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Painel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/styles.css" rel="stylesheet">
    <script src="js/jquery-1.11.0.min.js"></script>
    <script src="js/jquery-migrate-1.2.1.min.js"></script>
    <script type="text/javascript" src="js/slick.min.js"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <!-- CABEÇALHO COM BOVINOS E OFERTA LADO A LADO -->
        <div class="header">
            <div class="header-left">
                <div class="header-title">BOVINOS</div>
                <img src="logo-principal.jpeg" alt="Logo" class="logo">
            </div>
            <div class="header-right">
                <div class="header-title">OFERTA</div>
                <div class="promo-container" id="promo-container"></div>
            </div>
        </div>

        <div class="divider"></div>

        <ul class="product-container" id="product-list">
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
            ?>
        </ul>
    </div>

    <script>
        var images = <?php echo json_encode(glob("promo/*.{jpg,jpeg,png,gif}", GLOB_BRACE)); ?>;
        var products = <?php echo json_encode($products); ?>;
        var numExibir = 4;
        var index = 0;
        var promoContainer = document.getElementById('promo-container');
        var productList = document.getElementById('product-list');

        function createProductElement(product, idSuffix) {
            let productElement = document.createElement('li');
            productElement.className = 'product';
            productElement.id = 'product-' + idSuffix;

            let productNameElement = document.createElement('span');
            productNameElement.className = 'product-name';
            productNameElement.textContent = product[0] + ':';

            let productPriceElement = document.createElement('span');
            productPriceElement.className = 'product-price';
            productPriceElement.textContent = 'R$ ' + product[1];

            productElement.appendChild(productNameElement);
            productElement.appendChild(productPriceElement);

            return productElement;
        }

        function changeImages() {
            promoContainer.innerHTML = '';
            productList.innerHTML = '';
            let usados = [];

            for (let i = 0; i < numExibir; i++) {
                let imgIndex = (index + i) % images.length;
                let imageSrc = images[imgIndex];
                let productName = imageSrc.match(/([^\/]+)(?=\.[^\/]+$)/)[0].toLowerCase();

                let product = products.find(p => p[0].toLowerCase().includes(productName));
                let price = "00,00";
                let nomeProdutoFormatado = productName.charAt(0).toUpperCase() + productName.slice(1);
                let productInfo = [nomeProdutoFormatado, price];

                if (product) {
                    usados.push(product[0].toLowerCase());
                    price = product[1];
                    productInfo = [product[0], product[1]];
                } else {
                    usados.push(productName);
                }

                let promoBox = document.createElement('div');
                promoBox.className = 'promo-box';

                let imgElement = document.createElement('img');
                imgElement.src = imageSrc;
                imgElement.className = 'promo-image';
                imgElement.setAttribute('data-product', productName);

                let priceBox = document.createElement('div');
                priceBox.className = 'promo-info-box';
                priceBox.textContent = price + " R$";

                promoBox.appendChild(imgElement);
                promoBox.appendChild(priceBox);
                promoContainer.appendChild(promoBox);

                let productElement = createProductElement(productInfo, i);
                productElement.classList.add('oferta');
                productList.appendChild(productElement);
            }

            let extras = products.filter(p => !usados.includes(p[0].toLowerCase()));
            let totalExtras = 9 - usados.length;
            let randomExtras = [];

            while (randomExtras.length < totalExtras && extras.length > 0) {
                let randIndex = Math.floor(Math.random() * extras.length);
                randomExtras.push(extras.splice(randIndex, 1)[0]);
            }

            randomExtras.forEach((product, i) => {
                let productElement = createProductElement(product, 'extra-' + i);
                productList.appendChild(productElement);
            });

            index = (index + numExibir) % images.length;

            setTimeout(() => {
                document.querySelectorAll('.promo-image').forEach((img, i) => {
                    img.classList.remove('animate-in');
                    setTimeout(() => {
                        img.classList.add('animate-in');
                    }, i * 150);
                });
            }, 100);
        }

        changeImages();
        setInterval(changeImages, 7000);
    </script>
</body>
</html>
