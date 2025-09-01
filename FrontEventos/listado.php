<?php
// listado.php

// Inicializar cURL para obtener los clientes desde la API
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => "http://localhost/APIeventos/clientes/get_clientes.php",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
));

// Ejecutar la petición cURL
$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);

// Manejo de errores cURL
if ($err) {
    echo "cURL Error #:" . $err;
    exit;
}

// Decodificar la respuesta JSON
$objeto = json_decode($response);

// Verificar errores al decodificar JSON
if (json_last_error() !== JSON_ERROR_NONE) {
    echo "Error al decodificar JSON: " . json_last_error_msg();
    var_dump($response);
    exit;
}

// Validar que el objeto sea un array o un objeto válido
if (!is_array($objeto) && !is_object($objeto)) {
    echo "La API no devolvió un formato válido.";
    exit;
}
?>

<!-- ======= INCLUIR CSS Y JS EXTERNOS ======= -->
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<!-- Bootstrap JS Bundle (incluye Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- SweetAlert2 para alertas bonitas -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- ======= BOTÓN PARA NUEVO CLIENTE ======= -->
<button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#exampleModal" id="btnNuevoCliente">
  Nuevo cliente
</button>

<!-- ======= TABLA DE CLIENTES ======= -->
<table class="table table-striped table-hover" id="tblcliente">
    <thead>
        <tr>
            <th>ID</th>
            <th>NOMBRE</th>
            <th>TELEFONO</th>
            <th>CORREO</th>
            <th>ACCIONES</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($objeto as $reg): ?>
        <tr>
            <td><?= htmlspecialchars($reg->id) ?></td>
            <td><?= htmlspecialchars($reg->nombres . " " . $reg->apellidos) ?></td>
            <td><?= htmlspecialchars($reg->telefono) ?></td>
            <td><?= htmlspecialchars($reg->correo) ?></td>
            <td>
              <!-- Botón eliminar -->
              <button type="button" class="btn btn-danger" onclick="Eliminar(<?= $reg->id ?>);">Eliminar</button>
              
              <!-- Botón actualizar con data-attributes para cargar datos al modal -->
              <button type="button"
                      class="btn btn-secondary btn-update"
                      data-bs-toggle="modal"
                      data-bs-target="#exampleModal"
                      data-id="<?= $reg->id ?>"
                      data-nombres="<?= htmlspecialchars($reg->nombres) ?>"
                      data-apellidos="<?= htmlspecialchars($reg->apellidos) ?>"
                      data-direccion="<?= htmlspecialchars($reg->direccion) ?>"
                      data-telefono="<?= htmlspecialchars($reg->telefono) ?>"
                      data-correo="<?= htmlspecialchars($reg->correo) ?>">
                Actualizar
              </button>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <th>ID</th>
            <th>NOMBRE</th>
            <th>TELEFONO</th>
            <th>CORREO</th>
            <th>ACCIONES</th>
        </tr>
    </tfoot>
</table>

<!-- ======= MODAL PARA CREAR/ACTUALIZAR CLIENTE ======= -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="formnew" name="formnew">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">actualizar cliente</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <!-- Campo oculto para almacenar el ID del cliente al actualizar -->
          <input type="hidden" id="cliente_id" name="cliente_id" />
          
          <!-- Campos del formulario -->
          <div class="mb-3">
            <label for="nombres" class="col-form-label">Nombres:</label>
            <input type="text" class="form-control" id="nombres" name="nombres" required />
          </div>

          <div class="mb-3">
            <label for="apellidos" class="col-form-label">Apellidos:</label>
            <input type="text" class="form-control" id="apellidos" name="apellidos" required />
          </div>

          <div class="mb-3">
            <label for="direccion" class="col-form-label">Dirección:</label>
            <input type="text" class="form-control" id="direccion" name="direccion" required />
          </div>

          <div class="mb-3">
            <label for="telefono" class="col-form-label">Teléfono:</label>
            <input type="text" class="form-control" id="telefono" name="telefono" required />
          </div>

          <div class="mb-3">
            <label for="correo" class="col-form-label">Correo:</label>
            <input type="email" class="form-control" id="correo" name="correo" required />
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Guardar cambio</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ======= SCRIPT JAVASCRIPT ======= -->
<script>
$(document).ready(function() {
    // Inicializar DataTables en la tabla
    $('#tblcliente').DataTable();
});

// Función para eliminar cliente con confirmación y AJAX
function Eliminar(idcliente) {
    if (confirm("¿Está seguro que quieres eliminar el registro " + idcliente + "?")) {
        var dato = { id: idcliente };
        var datojson = JSON.stringify(dato);

        $.ajax({
            type: "DELETE",
            url: 'http://localhost/APIeventos/clientes/delete_clientes.php',
            data: datojson,
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function(response) {
                Swal.fire({
                    title: "Eliminado",
                    text: response.mensaje + " Código: " + response.codigo,
                    icon: "success"
                }).then(() => {
                    // Recargar la página para reflejar cambios
                    location.reload();
                });
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    title: "Error",
                    text: "No se pudo eliminar el registro. " + error,
                    icon: "error"
                });
            }
        });
    }
}

// Evento click en "Nuevo cliente" para limpiar formulario y cambiar título modal
$('#btnNuevoCliente').on('click', function() {
    var modal = $('#exampleModal');
    modal.find('.modal-title').text('Nuevo cliente');
    $('#cliente_id').val('');
    $('#formnew')[0].reset();
});

// Evento que se dispara al abrir el modal (para actualizar)
// Carga los datos del cliente en el formulario si es actualización
var exampleModal = document.getElementById('exampleModal');
exampleModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget; // Botón que disparó el modal

    // Si el botón no tiene atributo data-id, es creación, no hace nada
    if (!button.hasAttribute('data-id')) return;

    // Obtener datos del cliente del botón
    var id = button.getAttribute('data-id');
    var nombres = button.getAttribute('data-nombres');
    var apellidos = button.getAttribute('data-apellidos');
    var direccion = button.getAttribute('data-direccion');
    var telefono = button.getAttribute('data-telefono');
    var correo = button.getAttribute('data-correo');

    // Referencia al modal
    var modal = this;

    // Cambiar título del modal y rellenar formulario con datos del cliente
    modal.querySelector('.modal-title').textContent = 'Actualizar cliente';
    modal.querySelector('#cliente_id').value = id;
    modal.querySelector('#nombres').value = nombres;
    modal.querySelector('#apellidos').value = apellidos;
    modal.querySelector('#direccion').value = direccion;
    modal.querySelector('#telefono').value = telefono;
    modal.querySelector('#correo').value = correo;
});

// Evento submit del formulario para crear o actualizar cliente según si cliente_id tiene valor
$("#formnew").on("submit", function (e) {
    e.preventDefault();

    let clienteId = $("#cliente_id").val().trim();

    // Recopilar datos del formulario
    let datos = {
        nombres: $("#nombres").val(),
        apellidos: $("#apellidos").val(),
        direccion: $("#direccion").val(),
        telefono: $("#telefono").val(),
        correo: $("#correo").val()
    };

    let url, type;

    // Si clienteId existe, es actualización (PUT), sino creación (POST)
    if (clienteId) {
        datos.id = clienteId;
        url = "http://localhost/APIeventos/clientes/update_clientes.php";
        type = "PUT";
    } else {
        url = "http://localhost/APIeventos/clientes/save_clientes.php";
        type = "POST";
    }

    // Enviar datos por AJAX
    $.ajax({
        type: type,
        url: url,
        data: JSON.stringify(datos),
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function (response) {
            Swal.fire({
                title: "Resultado",
                text: response.mensaje,
                icon: response.codigo == "1" ? "success" : "warning"
            }).then(() => {
                if (response.codigo == "1") {
                    // Limpiar formulario y ocultar modal si éxito
                    $("#formnew")[0].reset();
                    $("#cliente_id").val('');
                    var modal = bootstrap.Modal.getInstance(document.getElementById('exampleModal'));
                    modal.hide();
                }
                // Recargar para actualizar tabla
                location.reload();
            });
        },
        error: function (xhr, status, error) {
            Swal.fire({
                title: "Error",
                text: "No se pudo guardar el registro. " + error,
                icon: "error"
            });
        }
    });
});
</script>
