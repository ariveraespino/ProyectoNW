<link rel="stylesheet" href="{{BASE_DIR}}/public/css/carrito.css" />

<section class="depth-2 px-4 py-5">
    <h2>Carrito de Compras</h2>
</section>

<section class="depth-2 px-4 py-4 my-4">
    <div class="row">
        <div class="col-12">
            <table>
                <thead>
                    <tr>
                        <th class="col-4">Nombre</th>
                        <th class="col-2">Precio Unitario</th>
                        <th class="col-2">Cantidad</th>
                        <th class="col-4">Imagen</th>
                    </tr>
                </thead>
                <tbody>
                  {{foreach carrito}}
                    <tr>
                        <td class="col-4">{{nombre}}</td>
                        <td class="col-2">{{precio}}</td>
                        <td class="col-2">{{crrctd}}</td>
                        <td class="col-4">
                            <img src="public/imgs/hero/{{url_img}}" alt="Pastel" width="100px">
                        </td>
                    </tr>
                  {{endfor carrito}}
                </tbody>
            </table>
        </div>
    </div>

    <form action="index.php?page=checkout_checkout" method="post">
        <button type="submit">Completar Compra</button>
    </form>
</section>