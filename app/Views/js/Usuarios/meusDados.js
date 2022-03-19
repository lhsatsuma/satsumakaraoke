function resetPreferences()
{
    Swal.fire({
        title: 'Deseja resetar suas preferências?',
        icon: 'question',
        text: 'Todas as suas preferências serão deletadas e não poderão ser revertidas.',
        showCloseButton: true,
        showCancelButton: true,
        focusConfirm: true,
    }).then((result) => {
        if(result.isConfirmed){
            fireAjaxLoading({
                url: _APP.app_url+'usuarios/reset_preferences',
                callback: (res) => {
                    toggleDarkMode(true, 1);
                    Swal.close();
                    if(res.detail){
                        Swal.fire({
                            title: 'Preferências resetadas com sucesso!',
                            text: '',
                            icon: 'success',
                            width: '400px',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        });
                    }else{
                        fireErrorGeneric('<p>Ocorreu um erro ao resetar suas preferências.</p><p>Entre em contato com o administrador');
                    }
                },
            })
        }
    });
}