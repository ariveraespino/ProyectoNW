<link rel="stylesheet" href="{{BASE_DIR}}/public/css/tipospasteles.css" />




 <script src="{{BASE_DIR}}/public/js/tipopasteles.js"></script>





<div class="carousel">
    <div class="carousel-track">
        <div class="img-main">
            <img src="public/imgs/hero/pastel45.jpeg" alt="Imagen 1">
            <h1 class="titulo">PASTELES {{nombre}} <br>Bakery</h1>
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
                <h3> NOMBRE: <br>  {{nombre}}<br> <br> DESCRIPCION: <br> {{descripcion}} <br> <br>  PRECIO: <br> {{precio}} <br> <br> CANTIDAD: <br> {{cantidad}}</h3>
            </div>
        </div>
    </div>
    {{endfor pasteles}}
   
 
 
     

   
</div>