<section class="depth-2 px-4 py-5">
    <h2>{{modeDsc}}</h2>
</section>

<section class="depth-2 px-4 py-4 my-4 grid row">
    <form method="POST" action="index.php?page=Maintenance-Products-Pastel&mode={{mode}}&id={{id}}"
        enctype="multipart/form-data" class="grid col-12 col-m-8 offset-m-2 col-l-6 offset-l-3">
        <div class="row my-2">
            <label for="id" class="col-12 col-m-4 col-l-3">Id:</label>
            <input type="text" name="id" id="id" value="{{id}}" placeholder="Pastel Id" class="col-12 col-m-8 col-l-9"
                readonly />
            <input type="hidden" name="xsrtoken" value="{{xsrtoken}}" />
        </div>

        <div class="row my-2">
            <label for="nombre" class="col-12 col-m-4 col-l-3">Nombre:</label>
            <input type="text" name="nombre" id="nombre" value="{{nombre}}" placeholder="Nombre del pastel"
                class="col-12 col-m-8 col-l-9" {{readonly}} />
            {{foreach errors_nombre}}
            <div class="error col-12">{{this}}</div>
            {{endfor errors_nombre}}
        </div>

        <div class="row my-2">
            <label for="tipo" class="col-12 col-m-4 col-l-3">Tipo:</label>
            <input type="text" name="tipo" id="tipo" value="{{tipo}}" placeholder="Tipo de pastel"
                class="col-12 col-m-8 col-l-9" {{readonly}} />
            {{foreach errors_tipo}}
            <div class="error col-12">{{this}}</div>
            {{endfor errors_tipo}}
        </div>

        <div class="row my-2">
            <label for="descripcion" class="col-12 col-m-4 col-l-3">Descripción:</label>
            <input type="text" name="descripcion" id="descripcion" value="{{descripcion}}"
                placeholder="Descripción del pastel" class="col-12 col-m-8 col-l-9" {{readonly}} />
            {{foreach errors_descripcion}}
            <div class="error col-12">{{this}}</div>
            {{endfor errors_descripcion}}
        </div>

        <div class="row my-2">
            <label for="precio" class="col-12 col-m-4 col-l-3">Precio:</label>
            <input type="number" name="precio" id="precio" value="{{precio}}" placeholder="Precio del pastel"
                class="col-12 col-m-8 col-l-9" min="0" {{readonly}} />
            {{foreach errors_precio}}
            <div class="error col-12">{{this}}</div>
            {{endfor errors_precio}}
        </div>

        <div class="row my-2">
            <label for="cantidad" class="col-12 col-m-4 col-l-3">Cantidad:</label>
            <input type="number" name="cantidad" id="cantidad" value="{{cantidad}}" placeholder="Cantidad en inventario"
                class="col-12 col-m-8 col-l-9" min="0" {{readonly}} />
            {{foreach errors_cantidad}}
            <div class="error col-12">{{this}}</div>
            {{endfor errors_cantidad}}
        </div>

        <div class="row my-2">
            <label class="col-12 col-m-4 col-l-3">Estado:</label>
            <div class="col-12 col-m-8 col-l-9">
                <label>
                    <input type="radio" name="estado_pastel" value="ACT" {{estado_act}} {{readonly}} /> Activo
                </label>
                &nbsp;
                <label>
                    <input type="radio" name="estado_pastel" value="INT" {{estado_int}} {{readonly}} /> Inactivo
                </label>
            </div>
            {{foreach errors_estado_pastel}}
            <div class="error col-12">{{this}}</div>
            {{endfor errors_estado_pastel}}
        </div>

        <div class="row my-2">
            <img src="public/imgs/hero/{{url_img}}" alt="Imagen subida" width="100px"><br>

            <label for="url_img" class="col-12 col-m-4 col-l-3">Subir imagen:</label>
            <input type="file" name="url_img" id="url_img" accept="image/*" class="col-12 col-m-8 col-l-9"
                {{readonly}} />
            {{foreach errors_url_img}}
            <div class="error col-12">{{this}}</div>
            {{endfor errors_url_img}}
        </div>

        <div class="row">
            <div class="col-12 right">
                <button class="" id="btnCancel" type="button">{{cancelLabel}}</button>
                &nbsp;
                {{if showConfirm}}
                <button class="primary" type="submit">Confirmar</button>
                {{endif showConfirm}}
            </div>
        </div>

        {{if errors_global}}
        <div class="row">
            <ul class="col-12">
                {{foreach errors_global}}
                <li class="error">{{this}}</li>
                {{endfor errors_global}}
            </ul>
        </div>
        {{endif errors_global}}
    </form>
</section>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        document.getElementById("btnCancel")
            .addEventListener("click", (e) => {
                e.preventDefault();
                e.stopPropagation();
                window.location.assign("index.php?page=Maintenance-Products-Pasteles");
            });
    });
</script>