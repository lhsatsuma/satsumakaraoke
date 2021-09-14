"use strict";
function confirmInternal(action)
{
    Swal.fire({
        title: 'Deseja mesmo realizar esta ação?',
        html: "<p>Esta ação não poderá ser desfeita.</p><p>Aguarde o término do processo para evitar erros</p>",
        showCancelButton: true,
        cancelButtonText: 'cancelar',
    }).then((result) => {
        if(result.isConfirmed){
            Swal.fire({
                title: 'Aguarde...',
                text: 'Estamos processando...',
                didOpen: () => {
                    Swal.showLoading();
                    location.href = _app_vars.app_url+'admin/internal/'+action;
                }
            });
        }
    });
}