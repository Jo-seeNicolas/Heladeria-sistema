<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Heladería y Cafetería los Abuelos</title>
    <link rel="stylesheet" href="css/este.css">
    <script src="https://kit.fontawesome.com/b63ad478eb.js" crossorigin="anonymous"></script>
</head>
<body id="arriba">
    <div class="jijia">
        <?php include("conexion/conexion.php"); ?>
        <header><span title="Heladería y Cafetería los Abuelos">𝓗y𝓁𝓐</span>
            <div style="display: flex;">
                <a href="entrar/entrar.php"><button><i class="fa-solid fa-user"></i>Log in</button></a>
            </div>
        </header>
        <div class="contenedor">
            <input type="checkbox" id="check">
            <label for="check" class="mostrar-menu">
                &#8801;
            </label>
            <nav>
                <label for="check" class="esconder-menu">
                    &#215;
                </label>
                <a href="#arriba"><button><i class="fa-solid fa-mug-saucer"></i>Bebidas calientes</button></a>
                <a href="#helados"><button><i class="fa-solid fa-ice-cream"></i>Helados</button></a> 
                <a href="#postres"><button><i class="fa-solid fa-cake-candles"></i>Postres</button></a>
                <a href="#nosotros"><button><i class="fa-solid fa-people-line"></i>Nosotros</button></a>
            </nav>
        </div>
    </div>
    <main class="hojas">

        <!-- Slider para área "tienda" -->
        <section class="contenetor" id="cafe">
            <div class="sliderw">
                <div class="slider" id="slider">
                    <?php
                    // Consultar productos destacados del área "Tienda"
                    $productos_tienda = mysqli_query($conexion, "SELECT * FROM producto WHERE ID_AREA = (SELECT ID_AREA FROM area WHERE NOMBRE = 'Tienda') AND DESTACADO = 1");
                    while ($p = mysqli_fetch_assoc($productos_tienda)) {
                        echo "
                            <article>
                                <img src='imgenes/productos/{$p['IMAGEN']}' style='height: 40vh;'>
                                <h2>{$p['NOMBRE']}</h2>
                            </article>
                        ";
                    }
                    ?>
                </div>
                <div class="slider-nav" id="dots">
                    <div class="active"></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
                <script>
                    const slider = document.getElementById("slider");
                    const dots = document.querySelectorAll("#dots div");
                    const total = dots.length;
                    let index = 0;

                    function showSlide(i) {
                        slider.style.transform = `translateX(-${i * 100}%)`;
                        dots.forEach(dot => dot.classList.remove("active"));
                        dots[i].classList.add("active");
                    }

                    function autoPlay() {
                        index = (index + 1) % total;
                        showSlide(index);
                    }

                    let intervalo = setInterval(autoPlay, 3000);

                    dots.forEach((dot, i) => {
                        dot.addEventListener("click", () => {
                            index = i;
                            showSlide(index);
                            clearInterval(intervalo);
                            intervalo = setInterval(autoPlay, 3000);
                        });
                    });
                </script>
            </div>
            <div>
                <h1>¡𝓟𝓻𝓾𝓮𝓫𝓪 𝓝𝓾𝓮𝓼𝓽𝓻𝓪𝓼 𝓭𝓲𝓼𝓽𝓲𝓷𝓽𝓪𝓼 <span>bebidas calientes disponibles!</span></h1>
            </div>
        </section>

        
        <?php
        $sabores = [
            "clasicos" => [
                ["img" => "imgenes/helado/Vainilla.webp", "nombre" => "Vainilla"],
                ["img" => "imgenes/helado/Chocolate.webp", "nombre" => "Chocolate"],
                ["img" => "imgenes/helado/Fresa.webp", "nombre" => "Fresa"],
                ["img" => "imgenes/helado/Mandarina-limon.webp", "nombre" => "Mandarina limón"]
            ],
            "especiales" => [
                ["img" => "imgenes/helado/Cookies-cream.webp", "nombre" => "Cookies & Cream"],
                ["img" => "imgenes/helado/BBB-Lulo-flow.webp", "nombre" => "BonBonBum Lulo Flow"],
                ["img" => "imgenes/helado/Mani.webp", "nombre" => "Crema Maní"],
                ["img" => "imgenes/helado/Frutos-rojos.webp", "nombre" => "Frutos Rojos"]
            ],
            "infantiles" => [
                ["img" => "imgenes/helado/Acid-mix.webp", "nombre" => "Ácido Mix"],
                ["img" => "imgenes/helado/Carnaval-vainilla-chicle.webp", "nombre" => "Carnaval Vainilla Chicle"],
                ["img" => "imgenes/helado/Fiesta-chips.webp", "nombre" => "Vainilla Chips Colores"],
                ["img" => "imgenes/helado/Unicornio.webp", "nombre" => "Unicornio"]
            ]
        ];

        // categoría seleccionada (por defecto: clasicos)
        $categoria = $_GET["categoria"] ?? "clasicos";

        // cargar sabores
        $listaSabores = $sabores[$categoria];
        ?>


        <section class="sami" id="helados">

            <h1>¿𝓠𝓾é 𝓼𝓪𝓫𝑜𝓇𝑒𝓼 𝓉𝑒 𝓰𝓾𝓈𝓉𝓪𝓇í𝓪 𝓅𝓇𝓸𝓫𝓪𝓇 𝒽𝑜𝔂?</h1>

            <article class="categoria">
                <a href="?categoria=clasicos#helados">
                    <div>
                        <img src="imgenes/helado/3-Sabores-tradicionales-hover.jpg">
                        <h2>𝒮𝒶𝒷𝑜𝓇𝑒𝓈 𝒸𝓁á𝓈𝒾𝒸𝑜𝓈</h2>
                    </div>
                </a>

                <a href="?categoria=especiales#helados">
                    <div>
                        <img src="imgenes/helado/2-Sabores-especiales-hover.jpg">
                        <h2>𝒮𝒶𝒷𝑜𝓇𝑒𝓈 𝑒𝓈𝓅𝑒𝒸𝒾𝒶𝓁𝑒𝓈</h2>
                    </div>
                </a>

                <a href="?categoria=infantiles#helados">
                    <div>
                        <img src="imgenes/helado/1-Sabores-infantiles-hover.jpg">
                        <h2>𝐹𝒶𝓋𝑜𝓇𝒾𝓉𝑜𝓈 𝒹𝑒 𝓁𝓸𝓈 𝓃𝒾ñ𝓎𝓈</h2>
                    </div>
                </a>
            </article>


            <article class="sabores">
                <?php foreach ($listaSabores as $sabor): ?>
                    <div>
                        <img src="<?= $sabor['img'] ?>">
                        <h2><?= $sabor["nombre"] ?></h2>
                    </div>
                <?php endforeach; ?>
            </article>

        </section>


        <!-- Slider para área "postres" -->
        <section class="postres" id="postres">
            <div class="conteiner">
                <h2>𝒟𝒾𝓈𝒻𝓇𝓊𝓉𝒶 𝒹𝑒 𝓃𝓊𝑒𝓈𝓉𝓇𝑜𝓈 𝓂𝑒𝒿𝑜𝓇𝑒𝓈 𝓅𝓸𝓈𝓉𝓇𝑒𝓈</h2>
                <div class="slidera">
                    <div class="sliders" id="sliders">
                        <?php
                        // Consultar productos destacados del área "Heladería"
                        $productos_heladeria = mysqli_query($conexion, "SELECT * FROM producto WHERE ID_AREA = (SELECT ID_AREA FROM area WHERE NOMBRE = 'Heladería') AND DESTACADO = 1");
                        while ($p = mysqli_fetch_assoc($productos_heladeria)) {
                            echo "
                                <div class='slides'>
                                    <img src='imgenes/productos/{$p['IMAGEN']}' alt=''>
                                </div>
                            ";
                        }
                        ?>
                    </div>
                    <div class="slider-navs" id="dotss">
                        <div class="actives"></div>
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                </div>
            </div>
        </section>

        <script>
            const sliders = document.getElementById("sliders");
            const dotss = document.querySelectorAll("#dotss div");
            const tota = dotss.length;
            let inde = 0;

            function showSlides(i) {
                sliders.style.transform = `translateX(-${i * 100}%)`;
                dotss.forEach(dot => dot.classList.remove("actives"));
                dotss[i].classList.add("actives");
            }

            function autoPlay() {
                inde = (inde + 1) % tota;
                showSlides(inde);
            }

            let interval = setInterval(autoPlay, 4000);

            dotss.forEach((dot, i) => {
                dot.addEventListener("click", () => {
                    inde = i;
                    showSlides(inde);
                    clearInterval(interval);
                    interval = setInterval(autoPlay, 4000);
                });
            });
        </script>

        <section class="ja" id="nosotros">
            <h1>¿𝓠𝓾𝓲é𝓷𝓮𝓼 𝓼𝓸𝓶𝓸𝓼?</h1>
            <div>
                <p>Somos una heladería–cafetería ubicada en el barrio Ciudadela Sol de Oriente en Tunja. Ofrecemos helados Colombina y bebidas ideales para descansar y disfrutar un buen momento. Nuestro objetivo es brindar un servicio cercano y un espacio agradable para nuestros clientes.</p>
                <img src="imgenes/Fondos/imagenloca.jpeg" width="40%">
            </div>
            <br><br><br>
        </section>
    </main>
    <footer>
        <h1>𝓗y𝓁𝓐</h1><br>
        <span style="color: rgba(95, 95, 95, 0.664);">heladeriaycafeterialosabuelos@gmail.com</span><br>
        <br><span style="color: rgba(95, 95, 95, 0.664);">+57 3204303792</span>
    </footer>
</body>
</html>
