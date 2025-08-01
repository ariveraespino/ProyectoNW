<link rel="stylesheet" href="{{BASE_DIR}}/public/css/ind.css" />
<link rel="stylesheet" href="{{BASE_DIR}}/public/css/tipospasteles.css" />

<script src="{{BASE_DIR}}/public/js/tipopasteles.js"></script>

<div class="carouse">
    <div class="img-main">
        <img src="public/imgs/hero/main-img.jpg" alt="Imagen 1">
        <h1 class="titulo">Valeria's <br>Bakery</h1>
    </div>


    <div class="h3-container">
        <h3 class="h3-galeria-img">
            En Repostería Valeria, si lo imaginas lo creamos. <br>
            Ven con nosotros y tus recuerdos estarán llenos de sabor
        </h3>
    </div>


    <div class="galeria-img">

        <img src="public/imgs/hero/img-pastel-1.jpg">
        <img src="public/imgs/hero/img-pastel-2.jpg">
        <img class="galeria-img-last" src="public/imgs/hero/img-pastel-3.jpg">

    </div>

    <div class="last-section">
        <div class="last-section-img">
            <img src="public/imgs/hero/abuela-cocina.avif">
        </div>

        <div class="last-section-h4">
            <h4>
                Esta repostería es un legado familiar,
                todo empezó con el amor de mi abuela en la cocina y estamos aquí para que te lleves una experiencia de
                viejos sabores caseros, en repostería Valeria queremos representar esa magia.

                Si quieres tener una celebración inolvidable, ven con nosotros y el sabor te durara para siempre.
            </h4>
        </div>
    </div>

    <div class="nav-img-container">

        <div class="nav-item">

            <div class="nav-item-img">
                <img src="public/imgs/hero/pastelHelado.jpg">
            </div>
            <div class="nav-item-bg">
            </div>

            <div class="nav-item-h4">
                <a href="index.php?page=Maintenance-Products-Pastelesu&tip_pastel=helado">
                    Pasteles Helados
                </a>
            </div>

        </div>

        <div class="nav-item">

            <div class="nav-item-img">
                <img src="public/imgs/hero/pastelNormal.jpg">
            </div>
            <div class="nav-item-bg">
            </div>

            <div class="nav-item-h4">
                <a href="index.php?page=Maintenance-Products-Pastelesu&tip_pastel=seco">
                    Pasteles Normales
                </a>
            </div>

        </div>


    </div>

    <div class="coleccion">
    {{foreach pasteles}}
    <div class="item-container">
        <div class="item">
            <div class="item-img">
                <img src="public/imgs/hero/{{url_img}}" alt="Imagen del pastel" />
            </div>
            <div class="tarjeta-borde">
                <img src="public/imgs/hero/flecha-abajo.svg" alt="flecha" />
            </div>
            <div class="tarjeta">
                <h3> NOMBRE: <br> {{nombre}}<br> <br> DESCRIPCION: <br> {{descripcion}} <br> <br> PRECIO: <br>
                    {{precio}} <br> <br> CANTIDAD: <br> {{cantidad}}</h3>
            </div>
        </div>
        <h3 class="h3-nombrePastel">{{nombre}}</h3>
        <form action="index.php?page=Maintenance-Products-Pastelesu&tip_pastel=HELADO" method="post">
            <input type="hidden" name="pastel_id" value="{{pastel_id}}">
            <button type="submit" name="addToCart" class="btn-cart">Agregar al carrito</button>
        </form>
    </div>
    {{endfor pasteles}}

</div>