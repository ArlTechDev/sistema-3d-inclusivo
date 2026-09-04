@props([
    'id' => 'modalConfirmacion',
    'titulo' => 'Confirmar acción',
    'mensaje' => '¿Está seguro de continuar con esta operación?',
    'textoBoton' => 'Confirmar',
])

<div class="modal fade" id="{{ $id }}" tabindex="-1" role="dialog" aria-labelledby="{{ $id }}Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $id }}Label">{{ $titulo }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar ventana">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>{{ $mensaje }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger btn-confirmar-accion">{{ $textoBoton }}</button>
            </div>
        </div>
    </div>
</div>
