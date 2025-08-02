<h1>Pasteles</h1>
<section class="WWList">
    <table>
        <thead>
            <tr>
                <th>Id Orden</th>
                <th>Nombre Comprador</th>
                <th>Email Comprador</th>
                <th>Estado</th>
                <th>Total Bruto</th>
            </tr>
        </thead>
        <tbody>
            {{foreach factura}}
            <tr>
                <td>{{orden_ID}}</td>
                <td>{{nom_comprador}}</td>
                <td>{{email_comprador}}</td>
                <td>{{estado}}</td>
                <td>{{total_bruto}}</td>
            </tr>
            {{endfor factura}}
        </tbody>
    </table>
</section>