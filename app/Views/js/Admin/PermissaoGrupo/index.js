$('#grupos').change(() => {
    $('#tbodyPermissaoGrupo').html('');
    fireLoading({
        'text': 'Estamos buscando as permissões para este grupo...',
        callback: (res) => {
            console.log(res);
        }
    });
});