$('#SearchByAssigned').click(function(){
	if($('#search_usuario_criacao').val() == '|ASSIGNED_ONLY|'){
		$('#search_usuario_criacao').val('');
	}else{
		$('#search_usuario_criacao').val('|ASSIGNED_ONLY|');
	}
	$('#filtroForm').submit();
});
$('#SearchByPendente').click(function(){
	if($('#search_status').val() == 'pendente'){
		$('#search_status').val('');
	}else{
		$('#search_status').val('pendente');
	}
	$('#filtroForm').submit();
});
$('#SearchByEncerrados').click(function(){
	if($('#search_status').val() == 'encerrado'){
		$('#search_status').val('');
	}else{
		$('#search_status').val('encerrado');
	}
	$('#filtroForm').submit();
});