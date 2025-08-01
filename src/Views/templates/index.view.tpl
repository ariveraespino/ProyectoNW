<h1>{{SITE_TITLE}}</h1>

<div class="product-list">
    {{foreach pasteles}}
    <div class="product" data-productId="{{pastel_id}}">
        <img src="{{url_img}}" alt="{{nombre}}">
        <h2>{{nombre}}</h2>
        <p>{{descripcion}}</p>
        <span class="price">{{precio}}</span>
        <span class="stock">{{cantidad}}</span>
        <form action="index.php?page=index" method="post">
            <input type="hidden" name="pastel_id" value="{{pastel_id}}">
            <button type="submit" name="addToCart" class="add-to-cart">Agregar al carrito</button>
        </form>
    </div>
    {{endfor pasteles}}
</div>
