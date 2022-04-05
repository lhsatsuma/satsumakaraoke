<h2 class="required">AVISO:</h2>
<h5>O uso indevido das ações desta tela podem acarretar em lentidão no sistema.</h5>
{if $msg}
<div class="row mt-3 mb-3">
    <div class="col-12">
        <p>{$msg}</p>
    </div>
</div>
{/if}
<table class="table table-striped table-list table-hover">
	<thead>
		<tr>
			<th scope="col">Ação</th>
			<th scope="col">Descrição</th>
		</tr>
	</thead>
	<tbody>
        <tr class="ptr r-dt-slct" onclick="confirmInternal(this, 'clearCache')">
            <td>Limpar arquivos Caches</td>
            <td>Limpa os caches de template, JavaScript, CSS e logs afim de otimizar o sistema.</td>
        </tr>
        <tr class="ptr r-dt-slct" onclick="confirmInternal(this, 'pruneDatabase')">
            <td>Prune database</td>
            <td>Deleta os registros do banco de dados que estão como deleteds igual a 1.</td>
        </tr>
        <tr class="ptr r-dt-slct" onclick="confirmInternal(this, 'reconstructDB')">
            <td>Reconstruir campos no Banco de Dados</td>
            <td>Reconstruir campos de acordo com os models e as definições do campo.(<span class="required">ATENÇÃO:</span> Experimental... Talvez não funcione corretamente, faça um backup antes).</td>
        </tr>
        <tr class="ptr r-dt-slct" onclick="confirmInternal(this, 'reconstructDBComplete')">
            <td>Reconstruir campos no Banco de Dados Completo</td>
            <td>Reconstruir campos de acordo com os models e as definições do campo.(<span class="required">ATENÇÃO:</span> Experimental... Talvez não funcione corretamente, faça um backup antes).</td>
        </tr>
        <tr class="ptr r-dt-slct" onclick="confirmInternal(this, 'deleteArquivos')">
            <td>Deletar arquivos não existentes no banco de dados</td>
            <td>Deleta os arquivos físicos que não possuem vínculo ao banco de dados (apenas da pasta upload).</td>
        </tr>
        <tr class="ptr r-dt-slct" onclick="confirmInternal(this, 'deleteSessions')">
            <td>Limpar sessões de usuários</td>
            <td>Deleta as sessões em abertas dos usuários (<span class="required">ATENÇÃO:</span> todos os usuários terão que realizar o login novamente caso necessário).</td>
        </tr>
        <tr class="ptr r-dt-slct" onclick="confirmInternal(this, 'deleteMusics')">
            <td>Deletar músicas não existentes no banco de dados</td>
            <td>É isso</td>
        </tr>
        <tr class="ptr r-dt-slct" onclick="confirmInternal(this, 'reorderMusics')">
            <td>Reordenar codigos</td>
            <td>É isso</td>
        </tr>
        <tr class="ptr r-dt-slct" onclick="confirmInternal(this, 'reconstructTotalMusics')">
            <td>Reconstruir totais de músicas</td>
            <td>Reconstrói arquivo JSON com os totais de músicas.</td>
        </tr>
        <tr class="ptr r-dt-slct" onclick="confirmInternal(this, 'reconstructDurationVideos')">
            <td>Reconstruir duração das músicas</td>
            <td>Reconstrói duração em segundos das músicas e realiza o update na tabela.</td>
        </tr>
	</tbody>
</table>
<script type="text/javascript" src="{$app_url}jsManager/Admin/Internal/index.js?v={$ch_ver}"></script>