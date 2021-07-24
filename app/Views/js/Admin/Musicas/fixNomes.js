function changeNameTo(elm)
{
    let row = $(elm).parent();
    $('#changeRowID').val(row.attr('data-row-id'));

    $(row).find('td').each(function(){
        if($(this).attr('data-row-nome')){
            var newName = decodeURIComponent($(this).attr('data-row-nome'));
            var oldName = decodeURIComponent($(this).attr('data-row-nome'));
            $.ajax({
                'url': app_url+'admin/musicas/sanitanizeName',
                'method': 'post',
                'dataType': 'json',
                'async': false,
                headers: {
                  "Content-Type": "application/json",
                  "X-Requested-With": "XMLHttpRequest"
                },
                data: JSON.stringify({
                    'nome': $(this).attr('data-row-nome'),
                }),
                success: function(d){
                    
                },
                complete: function(d){
                    var r = d.responseJSON;
                    if(!!r){
                        if(r.detail){
                            newName = r.detail;
                            $('#changeRowOldNome').val(oldName);
                            $('#changeRowNewNome').val(newName);
                        }
                    }
                },
                error: function(d){
                    console.log(d);
                }
            });
        }else if($(this).attr('data-row-tipo')){
            $('#changeRowTipo').val($(this).attr('data-row-tipo'));
        }
    });
    $('#ChangeNameToModal').modal('show');
}
function changeNameToDel(elm)
{
    var idDel = $(elm).parent().parent().attr('data-row-id');
    Swal.fire({
        title: 'Deseja mesmo deleta esta música?',
        text: 'O registro não estará mais disponível no sistema.',
        icon: 'warning',
        showConfirmButton: true,
        confirmButtonText: 'Deletar',
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
    }).then((result) => {
        if(result.isConfirmed){
            $.ajax({
                'url': app_url+'admin/musicas/fixNomesSaveDel',
                'method': 'post',
                'dataType': 'json',
                'async': false,
                headers: {
                  "Content-Type": "application/json",
                  "X-Requested-With": "XMLHttpRequest"
                },
                data: JSON.stringify({
                    'id': idDel,
                }),
                success: function(d){
                    
                },
                complete: function(d){
                    var r = d.responseJSON;
                    if(!!r){
                        if(r.detail){
                            Swal.fire({
                                title: 'Deletado com sucesso!',
                                text: '',
                                icon: 'success',
                                width: '400px',
                                showConfirmButton: false,
                                timer: 500,
                                timerProgressBar: true,
                                didClose: () => {
                                    //Nothing
                                    $('#filtroForm').submit();
                                }
                            });
                        }else{
                            
                        }
                    }
                },
                error: function(d){
                    console.log(d);
                }
            });
        }
    });
}
function saveChangeName(elm)
{
    showLoadingIcon(elm);
    $('.validate-error').remove();
    if(!$('#changeRowNewNome').val()){
        $('#changeRowNewNome').after("<p class='validate-error required'>Campo obrigatório!</p>");
        hideLoadingIcon(elm);
        return;
    }

    if($('#changeRowNewNome').val().length < 10){
        Swal.fire({
            heightAuto: false,
            title: 'Deseja mesmo salvar a música?',
            text: 'O nome possui menos de 10 caracteres.',
            icon: 'warning',
            showConfirmButton: true,
            confirmButtonText: 'Salvar',
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
        }).then((result) => {
            if(result.isConfirmed){
                ajaxSaveChanges();
                hideLoadingIcon(elm);
            }
        });
    }else{
        ajaxSaveChanges();
        hideLoadingIcon(elm);
    }
}
function ajaxSaveChanges(){
    $.ajax({
		'url': app_url+'admin/musicas/fixNomesSave',
		'method': 'post',
        'dataType': 'json',
        'async': false,
		headers: {
		  "Content-Type": "application/json",
		  "X-Requested-With": "XMLHttpRequest"
		},
		data: JSON.stringify({
			'id': $('#changeRowID').val(),
			'newNome': $('#changeRowNewNome').val(),
			'tipo': $('#changeRowTipo').val(),
		}),
		success: function(d){
			
		},
		complete: function(d){
            var r = d.responseJSON;
            var scrollOld = document.getScrollTop();
			if(!!r){
                if(r.detail){
                    Swal.fire({
                        scrollbarPadding: false,
                        returnFocus: false,
                        focusConfirm: false,
                        title: 'Salvo com sucesso!',
                        text: '',
                        icon: 'success',
                        width: '400px',
                        showConfirmButton: false,
                        timer: 500,
                        timerProgressBar: true,
                        didClose: () => {
                            //Nothing
                            document.setScrollTop(scrollOld);
                            $('#ChangeNameToModal').modal('hide');
                        }
                    });
                }else{
                    
                }
			}
		},
		error: function(d){
			console.log(d);
		}
	});
}

$('#changeByTraco').click(function(){
    let oldVal = $('#changeRowNewNome').val();
    let exploded = [];
    
    exploded = oldVal.split(' - ');
    if(exploded.length > 1){
        $('#changeRowNewNome').val(exploded[1]+' - '+exploded[0]);
        return;
    }
    
    exploded = oldVal.split(' : ');
    if(exploded.length > 1){
        $('#changeRowNewNome').val(exploded[1]+' - '+exploded[0]);
        return;
    }
    
    exploded = oldVal.split(' in the Style of ');
    if(exploded.length > 1){
        $('#changeRowNewNome').val(exploded[1]+' - '+exploded[0]);
        return;
    }
});