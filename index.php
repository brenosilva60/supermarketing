<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Painel</title>
    <link href="css/styles.css" rel="stylesheet">
    <script src="js/jquery-1.11.0.min.js"></script>
    <script src="js/jquery-migrate-1.2.1.min.js"></script>
    <script type="text/javascript" src="js/slick.min.js"></script>
    <link rel="stylesheet" href="style.css">
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
                <div class="promo-container" id="promo-container">
                    <?php
                    $path = "promo/";
                    $files = glob($path . "*.{jpg,jpeg,png,gif}", GLOB_BRACE);
                    $numImagens = count($files);
                    $numExibir = 4;

                    // Exibir as imagens no formato de carrossel
                    for ($i = 0; $i < $numExibir && $i < $numImagens; $i++) {
                        echo '<img src="' . $files[$i] . '" class="promo-image" id="promo-image-' . $i . '" data-product="' . pathinfo($files[$i], PATHINFO_FILENAME) . '">'; 
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="divider"></div>

        <ul class="product-container" id="product-list">
            <?php
            // Carregar os produtos a partir do arquivo produto.txt
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

            // Número de produtos a serem exibidos
            $pageSize = 4;
            $totalProducts = count($products);

            // Lógica de exibição de produtos e imagens
            for ($i = 0; $i < $pageSize && $i < $totalProducts; $i++) {
                $product = $products[$i];
                echo '<li class="product" id="product-' . $i . '">';
                echo '<span class="product-name">' . $product[0] . ':</span>';
                echo '<span class="product-price">R$ ' . $product[1] . '</span>';
                echo '</li>';
            }
            ?>
        </ul>
    </div>

    <script>
    // Carregar as imagens e os produtos no JavaScript
    var images = <?php echo json_encode($files); ?>;
    var products = <?php echo json_encode($products); ?>;
    var numExibir = 4;
    var index = 0;
    var totalProducts = products.length;
    var totalImages = images.length;
    var promoContainer = document.getElementById('promo-container');
    var productList = document.getElementById('product-list');

    // Função para trocar as imagens do carrossel e associar com os produtos
    function changeImages() {
        promoContainer.innerHTML = ''; // Limpa as imagens antes de adicionar novas

        // Adiciona as novas imagens ao carrossel
        for (var i = 0; i < numExibir; i++) {
            var imgIndex = (index + i) % totalImages;
            var imgElement = document.createElement('img');
            imgElement.src = images[imgIndex];
            imgElement.className = 'promo-image';
            imgElement.setAttribute('data-product', images[imgIndex].match(/([^\/]+)(?=\.[^\/]+$)/)[0]
                .toLowerCase()); // Converte para minúsculas
            promoContainer.appendChild(imgElement);
        }

        // Faz a transição suave (opacity)
        var imgs = document.querySelectorAll('.promo-image');
        imgs.forEach(function(img, i) {
            setTimeout(function() {
                img.style.opacity = 1; // Aumenta a opacidade de cada imagem após um pequeno delay
            }, 100 * i); // Atraso em milissegundos para cada imagem
        });

        // Atualiza os produtos da tabela para corresponder com as imagens
        productList.innerHTML = ''; // Limpa a tabela de produtos

        // Adiciona os produtos correspondentes às imagens
        for (var i = 0; i < numExibir; i++) {
            var imgIndex = (index + i) % totalImages;
            var productName = images[imgIndex].match(/([^\/]+)(?=\.[^\/]+$)/)[0]
                .toLowerCase(); // Pega o nome do produto pela imagem e converte para minúsculas

            var product = products.find(p => p[0].toLowerCase().includes(productName)); // Comparação case-insensitive

            if (product) {
                var productElement = document.createElement('li');
                productElement.className = 'product';
                productElement.id = 'product-' + i;

                var productNameElement = document.createElement('span');
                productNameElement.className = 'product-name';
                productNameElement.textContent = product[0] + ':';

                var productPriceElement = document.createElement('span');
                productPriceElement.className = 'product-price';
                productPriceElement.textContent = 'R$ ' + product[1];

                productElement.appendChild(productNameElement);
                productElement.appendChild(productPriceElement);
                productList.appendChild(productElement);
            }
        }

        // Atualiza o índice para a próxima rodada
        index = (index + numExibir) % totalImages;
    }

    // Chama a função para trocar as imagens a cada 10 segundos
    setInterval(changeImages, 3000); // Troca das imagens a cada 10 segundos
    </script>
</body>

</html>