if(_APP._ACTION_NAME == 'edit' && $('input[name="id"]').val()){
    $('input[name="client_id"]').attr('readonly', true);
}
//Disable field, only generate
$('input[name="client_secret"]').attr('readonly', true);


$('#gen_client_secret').on('click', () => {    
    $('input[name="client_secret"]').val(create_UUID());
});
function ValidateFormCstm(form)
{
    let client_id = $('input[name="client_id"]').val();
    let id = $('input[name="id"]').val();
    if(!client_id){
        fireWarning(translate.get('EMPTY_CLIENT_ID'));
        return false;
    }
    showLoadingGlobal();
    handleAjax({
        url: _APP.app_url+'admin/OAuthClients/check_client_id',
        data: {
            client_id: client_id,
            id: id,
        },
        dontfireError: true,
        callback: (res) => {
            // ValidateForm(form);
            if(res.detail.exists){
                fireWarning(translate.get(res.detail.lbl));
                return false;
            }
            ValidateForm(form);
        },
        callbackError: (res) => {
            hideLoadingGlobal();
        }
    });
}