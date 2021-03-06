var fields = {'nome': 'Nome', 'email': 'Email', 'senha': 'Senha', 'senha_repeat': 'Repita a Senha'};

$('#newForm').find('.btn-success').click(function(){
    showLoadingIcon(this);
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
        hideLoadingIcon(this);
        return;
    }
    $.ajax({
		'url': app_url+'login/checkExistEmail',
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
				if(r.status){
                    if(r.detail){
                        if(r.detail.exists){
                            $('#email').after("<p class='validate-error required'>Já existe um usuário com este email cadastrado!</p>");
                            $('#email').focus();
                            hideLoadingIcon(this);
                            validated = false;
                        }else{
                            $('#newForm').submit();
                        }
                    }
				}
			}
		},
		error: function(d){
			console.log(d);
		}
	});
});