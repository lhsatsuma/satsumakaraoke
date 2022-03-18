<div class="modal-dialog modal-dialog-centered" role="document">
	<div class="modal-content">
		<div class="modal-header text-center" style="display: block">
			<h6 class="modal-title" id="helpSongsModalLabel">Ajuda sobre as músicas</h6>
		</div>
		<div class="modal-body">
			<div class="row">
				<div class="col-12 margin-b-10">
					<label for="ImportModalLink">Como funciona?</label>
					<ul>
						<li>Você pode buscar músicas através da caixa geral pesquisando por name, cantor, codigo ou idioma.</li>
						<li><p>Caso queira cantar uma música, clique sobre ela, e confirme se você deseja colocar na fila.</p>
						<p><img src="{$app_url}images/tutorial_musics/1.png" style="width: 100%"/></p></li>
						<li><p>Automaticamente sua música estará na fila e você poderá acompanhar através da tela principal ou pelo menu "Músicas na Fila".</p>
						<p><img src="{$app_url}images/tutorial_musics/5.png" style="width: 100%"/></p></li>
						<li><p>Você pode buscar pelo que estão em seu nome, todos ou até mesmo as músicas que já foram colocadas anteriormente.</p>
						<p><img src="{$app_url}images/tutorial_musics/6.png" style="width: 100%"/></p></li>
						<li><p>Ao clicar na música, você pode inseri-la na fila novamente.</p>
						<p><img src="{$app_url}images/tutorial_musics/7.png" style="width: 100%"/></p></li>
					</ul>
				</div>
				<div class="col-12 margin-b-10">
					<label for="ImportModalLink">Quero cantar uma música que não existe na lista</label>
					<ul>
						<li>Todos os vídeos são importados do YouTube.</li>
						<li>Você pode pedir para um dos colaboradores para verificar se existe um vídeo disponível para importação.</li>
					</ul>
				</div>
				<div class="col-12 margin-b-10">
					<label for="ImportModalLink">Músicas favoritas</label>
					<ul>
						<li>Ao clicar em cima de uma música, você pode colocá-la nos favoritos ou removê-la.</li>
						<img src="{$app_url}images/tutorial_musics/2.png" style="width: 100%"/></li>
						<li><p>A música favoritada estará disponível para busca através do botão de busca avançada.</p>
						<p><img src="{$app_url}images/tutorial_musics/3.png" style="width: 100%"/></p>
						<p><img src="{$app_url}images/tutorial_musics/4.png" style="width: 100%"/></p></li>
					</ul>
				</div>
				<div class="col-12 margin-b-10">
					<label for="ImportModalLink">Direitos Autorais</label>
					<ul>
						<li>Todos os direitos autorais das músicas são mantidos pelos próprios criadores que podem ser encontrados através do vídeo original no YouTube.</li>
						<li>Todos os videos foram baixados do YouTube, caso você seja o proprietário de um deles e queira a remoção do nosso sistema, entre em contato com o administrador.</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-outline-info btn-rounded" data-dismiss="modal">Fechar</button>
			{if $showPopupWizard}<button type="button" class="btn btn-outline-success btn-rounded" onclick="hideAlwaysPopupWizard()">Não mostrar novamente</button>{/if}
		</div>
	</div>
</div>