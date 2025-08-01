<link rel="stylesheet" href="{{BASE_DIR}}/public/css/tipospasteles.css" />

 <script src="{{BASE_DIR}}/public/js/tipopasteles.js"></script>

 <div class="img-main">
            <img src="public/imgs/hero/pastelkawaii.jpg" alt="Imagen 1">
            <h1 class="titulo">PASTELES {{tip_pastel}}s <br>BAKERY</h1>
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
                <h3> NOMBRE: <br>  {{nombre}}<br> <br> DESCRIPCION: <br> {{descripcion}} <br> <br>  PRECIO: <br> {{precio}} <br> <br> CANTIDAD: <br> {{cantidad}}</h3>
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