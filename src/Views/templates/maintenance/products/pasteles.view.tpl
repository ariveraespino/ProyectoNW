<h1>Pasteles</h1>
<section class="WWList">
    <table>
        <thead>
            <tr>
                <th>Id</th>
                <th>Nombre</th>
                <th>Tipo</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Estado</th>
                <th>Imagen</th>
                <th>
                    {{if isNewEnabled}}
                    <a href="index.php?page=Maintenance-Products-Pastel&mode=INS&id=" class="">Nuevo</a>
                    {{endif isNewEnabled}}
                </th>
            </tr>
        </thead>
        <tbody>
            {{foreach pasteles}}
            <tr>
                <td>{{pastel_id}}</td>
                <td>{{nombre}}</td>
                <td>{{tipo}}</td>
                <td>{{precio}}</td>
                <td>{{cantidad}}</td>
                <td>{{estado_pastel}}</td>
                <td>
                    <img src="public/imgs/hero/{{url_img}}" alt="Imagen del pastel" width="80px" />
                </td>
                <td>
                    {{if ~isUpdateEnabled}}
                    <a href="index.php?page=Maintenance-Products-Pastel&mode=UPD&id={{pastel_id}}">
                        Editar
                    </a> &nbsp;
                    {{endif ~isUpdateEnabled}}
                    <a href="index.php?page=Maintenance-Products-Pastel&mode=DSP&id={{pastel_id}}">
                        Ver
                    </a> &nbsp;
                    {{if ~isDeleteEnabled}}
                    <a href="index.php?page=Maintenance-Products-Pastel&mode=DEL&id={{pastel_id}}">
                        Eliminar
                    </a>
                    {{endif ~isDeleteEnabled}}
                </td>
            </tr>
            {{endfor pasteles}}
        </tbody>
    </table>
</section>