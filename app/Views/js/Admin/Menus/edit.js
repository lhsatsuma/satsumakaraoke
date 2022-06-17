$('select[name="tipo"]').on('change', () => {
    let tipo = $('select[name="tipo"]').val();
    rmvRequired('input[name="menu_pai_name"]');
    if(tipo == '2' || tipo == '4'){
        addRequired('input[name="menu_pai_name"]');
    }
}).trigger('change');