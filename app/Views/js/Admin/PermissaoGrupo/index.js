$('#grupos').change(() => {
    $('#tbodyPermissaoGrupo').html('');
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
                    let checked = (ipt.permissao_grupo_id) ? 'checked="checked"': '';

                    let value_permissao = (ipt.permissao_grupo_id) ? ipt.permissao_grupo_id: '';
                    if(close){
                        html += "\n</tr>";
                    }
                    if(close == true || idx == 0){
                        html += "\n<tr class='d-flex'>";
                    }
                    let sideTd = (idx % 2 == 1) ? 'direitaSide' : 'esquerdaSide';
                    html += `<td scope="col" class="col-5">
                        <input type="hidden" name="permissao[${ipt.id}]" value="${value_permissao}" />
                        [${ipt.cod_permissao}] ${ipt.nome}
                    </td>
                    <td scope="col"class="col-1  ${sideTd}">
                        <input type="checkbox" name="permissao_checked[${ipt.id}]" ${checked} />
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
});
function togglePermissoes(side, dom)
{
    $('.'+side+'Side > input').each((idx, ipt) => {
        if(!$(dom).is(':checked')){
            $(ipt).prop('checked', false);
        }else{
            console.log('b');
            $(ipt).prop('checked', true);
        }
    })
}