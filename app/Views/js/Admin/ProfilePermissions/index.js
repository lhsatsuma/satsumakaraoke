$('#grupos').change(() => {
    $('#tbodyProfilePermissions').html('');
    if($('#grupos').val()){
        if($('#grupos').val() == '1'){
            $('#grupos').after('<p class="required" id="grupo1required">'+translate.get('Admin.ProfilePermissions', 'LBL_DEFAULT_PROFILE_ALL')+'</p>');
        }else{
            $('#grupo1required').remove();
        }
        fireAjaxLoading({
            url: _APP.app_url+'admin/profilePermissions/searchPermissions',
            text: 'Estamos buscando as permissões para este grupo...',
            data: JSON.stringify({
                grupo_id: $('#grupos').val(),
            }),
            callback: (res) => {
                let html = '';
                if(res.status && res.detail){
                    let close = false;
                    res.detail.forEach((ipt, idx) => {
                        let value_permissao = (ipt.permissao_grupo_id) ? ipt.permissao_grupo_id: '';
                        if(close){
                            html += "\n</tr>";
                        }
                        if(close == true || idx == 0){
                            html += "\n<tr class='d-flex'>";
                        }
                        let sideTd = (idx % 2 == 1) ? 'rightSide' : 'leftSide';
                        
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
                        html += `<td scope="col" class="col-4 border-right">
                            <input type="hidden" name="permissao[${ipt.id}]" value="${value_permissao}" />
                            [${ipt.id}] ${ipt.name}
                        </td>
                        <td scope="col"class="col-2 text-center ${sideTd} border-right">
                            R:&nbsp;&nbsp;<input type="checkbox" name="permissao_checked[${ipt.id}][r]" permission="r" ${has_r} /><br /><br />
                            W: <input type="checkbox" name="permissao_checked[${ipt.id}][w]" permission="w" ${has_w} /><br /><br />
                            D:&nbsp;&nbsp;<input type="checkbox" name="permissao_checked[${ipt.id}][d]" permission="d" ${has_d} /><br />
                        </td>`;

                        if(idx % 2 == 1 && idx > 0){
                            close = true;
                        }else{
                            close = false;
                        }
                    });
                }
                if(html.substr(html.length - 5) !== '</tr>'){
                    html += '</tr>';
                }
                $('#tbodyProfilePermissions').html(html);
                Swal.close();
            }
        });
    }
});
function togglePermissions(side, dom, per)
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