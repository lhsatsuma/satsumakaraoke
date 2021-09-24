$('#grupos').change(() => {
    $('#tbodyPermissaoGrupo').html('');
    if($('#grupos').val()){
        if($('#grupos').val() == '1'){
            $('#grupos').after('<p class="required" id="grupo1required">Todas as permissões para este grupo são permitidas automaticamente.</p>');
        }else{
            $('#grupo1required').remove();
        }
        fireAjaxLoading({
            url: _app_vars.app_url+'admin/permissao_grupo/procurarPermissoes',
            text: 'Estamos buscando as permissões para este grupo...',
            data: JSON.stringify({
                grupo_id: $('#grupos').val(),
            }),
            callback: (res) => {
                if(res.status){
                    let html = '';
                    let close = false;
                    res.detail.forEach((ipt, idx) => {
                        let value_permissao = (ipt.permissao_grupo_id) ? ipt.permissao_grupo_id: '';
                        if(close){
                            html += "\n</tr>";
                        }
                        if(close == true || idx == 0){
                            html += "\n<tr class='d-flex'>";
                        }
                        let sideTd = (idx % 2 == 1) ? 'direitaSide' : 'esquerdaSide';
                        
                        let nivel = ipt.nivel;
                        let has_r = '';
                        let has_w = '';
                        let has_d = '';

                        nivel -= 4;
                        if(nivel < 0){
                            nivel = ipt.nivel;
                        }else{
                            ipt.nivel -= 4;
                            has_r = 'checked="true"';
                        }

                        nivel -= 2;
                        if(nivel < 0){
                            nivel = ipt.nivel;
                        }else{
                            ipt.nivel -= 2;
                            has_w = 'checked="true"';
                        }
                        nivel -= 1;
                        if(nivel < 0){
                            nivel = ipt.nivel;
                        }else{
                            ipt.nivel -= 1;
                            has_d = 'checked="true"';
                        }
                        html += `<td scope="col" class="col-4">
                            <input type="hidden" name="permissao[${ipt.id}]" value="${value_permissao}" />
                            [${ipt.id}] ${ipt.nome}
                        </td>
                        <td scope="col"class="col-2 text-center ${sideTd}">
                            R: <input type="checkbox" name="permissao_checked[${ipt.id}][r]" permission="r" ${has_r} />
                            W: <input type="checkbox" name="permissao_checked[${ipt.id}][w]" permission="w" ${has_w} />
                            D: <input type="checkbox" name="permissao_checked[${ipt.id}][d]" permission="d" ${has_d} />
                        </td>`;

                        if(idx % 2 == 1 && idx > 0){
                            close = true;
                        }else{
                            close = false;
                        }
                    });
                    if(html.substr(html.length - 5) !== '</tr>'){
                        html += '</tr>';
                    }
                    $('#tbodyPermissaoGrupo').html(html);
                }
                Swal.close();
            }
        });
    }
});
function togglePermissoes(side, dom, per)
{
    $('.'+side+'Side > input[permission="'+per+'"]').each((idx, ipt) => {
        if(!$(dom).is(':checked')){
            $(ipt).prop('checked', false);
        }else{
            console.log('b');
            $(ipt).prop('checked', true);
        }
    })
}