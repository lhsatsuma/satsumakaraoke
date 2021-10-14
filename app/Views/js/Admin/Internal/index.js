"use strict";
function confirmInternal(elm, action)
{
    Swal.fire({
        title: $(elm).find('td:first').text(),
        html: "<p>Deseja mesmo realizar esta ação?</p><p>Esta ação não poderá ser desfeita.</p><p>Aguarde o término do processo para evitar erros</p>",
        showCancelButton: true,
        cancelButtonText: 'cancelar',
    }).then((result) => {
        if(result.isConfirmed){
            Swal.fire({
                title: $(elm).find('td:first').text(),
                text: 'Aguarde... Estamos processando...',
                footer: '<p><span id="counterSeconds">0</span> segundos</p>',
                didOpen: () => {
                    Swal.showLoading();
                    setInterval(() => {
                        let seconds = parseInt($('#counterSeconds').text());
                        seconds++;
                        $('#counterSeconds').html(seconds);
                    }, 1000);
                    location.href = _app_vars.app_url+'admin/internal/'+action;
                }
            });
        }
    });
}