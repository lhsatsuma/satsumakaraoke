$('#clearWaitList').click(() => {
    Swal.fire({
        title: 'Deseja deletar todas as músicas que estão na fila?',
        icon: 'question',
        text: 'Atenção: Esta ação não poderá ser revertida!',
        showCloseButton: true,
        showCancelButton: true,
        focusConfirm: true,
    }).then((result) => {
        if(result.isConfirmed){
            fireAjaxLoading({
                url: _APP.app_url+'admin/waitlist/clear_waitlist',
                callback: (res) => {
                    if(res.status){
                        Swal.fire({
                            title: 'Músicas na fila deletados com sucesso!',
                            text: res.detail+ ' deletado(s)',
                            icon: 'success',
                            width: '400px',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        });
                    }
                },
            })
        }
    });
});