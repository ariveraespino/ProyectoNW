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
                        <th class="col-3">Nombre</th>
                        <th class="col-1">Precio Unitario</th>
                        <th class="col-1">Cantidad</th>
                        <th class="col-1">Restar</th>
                        <th class="col-1">Sumar</th>
                        <th class="col-1">Eliminar</th>
                        <th class="col-4">Imagen</th>
                    </tr>
                </thead>
                <tbody>
                  {{foreach carrito}}
                    <tr>
                        <td class="col-3">{{nombre}}</td>
                        <td class="col-1">{{precio}}</td>
                        <td class="col-1">{{crrctd}}</td>

                        <td class="col-1">
                            <form action="index.php?page=Maintenance-Cart-CartUpdate" method="post" style="margin:0;">
                                <input type="hidden" name="pastel_id" value="{{pastel_id}}">
                                <input type="hidden" name="action" value="restar">
                                <button type="submit">-</button>
                            </form>
                        </td>

                        <td class="col-1">
                            <form action="index.php?page=Maintenance-Cart-CartUpdate" method="post" style="margin:0;">
                                <input type="hidden" name="pastel_id" value="{{pastel_id}}">
                                <input type="hidden" name="action" value="sumar">
                                <button type="submit">+</button>
                            </form>
                        </td>

                        <td class="col-1">
                            <form action="index.php?page=Maintenance-Cart-CartUpdate" method="post" style="margin:0;">
                                <input type="hidden" name="pastel_id" value="{{pastel_id}}">
                                <input type="hidden" name="action" value="eliminar">
                                <button type="submit">🗑️</button>
                            </form>
                        </td>

                        <td class="col-4">
                            <img src="public/imgs/hero/{{url_img}}" alt="Pastel" width="100px">
                        </td>
                    </tr>
                  {{endfor carrito}}
                </tbody>
            </table>
        </div>
    </div>

    <button type="button" onclick="window.location.href='index.php?page=Sec_Login'">Iniciar Sesión</button>
</section>