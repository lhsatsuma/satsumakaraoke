var fields = {'nome': 'Nome', 'email': 'Email', 'senha': 'Senha', 'senha_repeat': 'Repita a Senha'};

$('#newForm').find('.btn-success').on('click', (e) =>{
    showLoadingIcon(e.currentTarget);
    $('.validate-error').remove();
    let data = {};
    let validated = true;
    $.each(fields, (idx, ipt) => {
        data[idx] = $('#'+idx).val();
        if(!data[idx]){
            $('#'+idx).after("<p class='validate-error required'>O campo "+ipt+" é obrigatório!</p>");
            validated = false;
        }
    });
    if(validated){
        if(data['senha'] !== data['senha_repeat']){
            $('#senha_repeat').after("<p class='validate-error required'>As senhas não conferem!</p>");
            validated = false;
        }else if(!validateEmail(data['email'])){
            $('#email').after("<p class='validate-error required'>O e-mail não é valido!</p>");
            validated = false;
        }
    }
    if(!validated){
        hideLoadingIcon(e.currentTarget);
        return;
    }
    $.ajax({
		'url': _APP.app_url+'login/checkExistEmail',
		'method': 'post',
		'dataType': 'json',
		headers: {
		  "Content-Type": "application/json",
		  "X-Requested-With": "XMLHttpRequest"
		},
		data: JSON.stringify({
			'chkEmail': data['email'],
		}),
		success: function(d){
			
		},
		complete: function(d){
            var r = d.responseJSON;
			if(!!r){
				if(r.status && r.detail){
                    if(r.detail.exists){
                        $('#email').after("<p class='validate-error required'>Já existe um usuário com este email cadastrado!</p>");
                        $('#email').trigger('focus');
                        hideLoadingIcon(this);
                        validated = false;
                    }else{
                        $('#newForm').trigger('submit');
                    }
				}
			}
		},
		error: function(d){
			console.log(d);
		}
	});
});