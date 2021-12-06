var RelatedFieldsCfg = {};
var SubpanelsCfg = {};
var hasToggleMenu = $(window).width() < 1439;

$(document).ready(function(){
	
	/* SIDEBAR JS */
	$(".sidebar-dropdown > a").on('click', (e) => {
		$(".sidebar-submenu").slideUp(200);
		if ($(e.currentTarget).parent().hasClass("active")) {
			$(".sidebar-dropdown").removeClass("active");
			$(e.currentTarget).parent().removeClass("active");
		} else {
			$(".sidebar-dropdown").removeClass("active");
			$(e.currentTarget).next(".sidebar-submenu").slideDown(200);
			$(e.currentTarget).parent().addClass("active");
		}
	});
	$(window).resize(function(){
		hasToggleMenu = false;
		if($(window).width() < 1439){
			$(".page-wrapper").removeClass("toggled");
			hasToggleMenu = true;
		}else{
			$(".page-wrapper").addClass("toggled");
		}
	});
	
	$("#close-sidebar, .page-content").on('click', () => {
		hasToggleMenu = false;
		if($(".page-wrapper").hasClass("toggled") && $(window).width() < 1439){
			$(".page-wrapper").removeClass("toggled");
			hasToggleMenu = true;
		}
	});
	$("#show-sidebar").on('click', () => {
		$(".page-wrapper").addClass("toggled");
	});
	
	/* -->SIDEBAR JS */
	
	orderByFiltro();
	insertMaskInputs();
});
function orderByFiltro()
{
	let order_by_field = $('#filtroForm').find('input[name="order_by_field"]').val();
	let order_by_order = $('#filtroForm').find('input[name="order_by_order"]').val();
	
	if(order_by_field !== '' && order_by_order !== ''){
		let icon = '';
		if(order_by_order == 'ASC'){
			icon = '<i class="fas fa-sort-amount-down-alt"></i>';
		}else if(order_by_order == 'DESC'){
			icon = '<i class="fas fa-sort-amount-up-alt"></i>';
		}
		if($('th[dt-h-field="'+order_by_field+'"]').find('.icon-order-by').length < 1){
			$('th[dt-h-field="'+order_by_field+'"]').append(' <span class="icon-order-by"></span>');
		}
		$('th[dt-h-field="'+order_by_field+'"]').find('.icon-order-by').html(icon);
	}
}
function ConfirmdeleteRecord(fm)
{
	Swal.fire({
		title: 'Deseja mesmo deletar este registro?',
		text: 'Este registro não estará mais disponível no sistema.',
		icon: 'warning',
		// width: '400px',
		showConfirmButton: true,
		confirmButtonText: 'Deletar',
		showCancelButton: true,
		cancelButtonText: 'Cancelar',
	}).then((result) => {
		if(result.isConfirmed){
			$('#'+fm).find('input[name="deletado"]:first').val('1');
			$('#'+fm).trigger('submit');
		}
	});
}
function clearFiltroForm()
{
	$('#filtroForm').find('input, select').each(function(){
		$(this).val('');
	});
	$('#filtroForm').trigger('submit');
}
function OrderByFiltro(field)
{
	let order_by_field = $('#filtroForm').find('input[name="order_by_field"]').val();
	let order_by_order = $('#filtroForm').find('input[name="order_by_order"]').val();
	
	let new_order = 'ASC';
	
	if(order_by_field == field){
		if(order_by_order == 'ASC'){
			new_order = 'DESC';
		}
	}
	$('#filtroForm').find('input[name="order_by_field"]').val(field);
	$('#filtroForm').find('input[name="order_by_order"]').val(new_order);
	$('#filtroForm').trigger('submit');
}
function OrderByFiltroSubpanel(id_subpanel, field)
{
	let cfg = SubpanelsCfg[id_subpanel];
	
	
	let order_by_field = $('input[name="order_by_field_'+cfg.id+'"]').val();
	let order_by_order = $('input[name="order_by_order_'+cfg.id+'"]').val();
	
	let new_order = 'ASC';
	
	if(order_by_field == field){
		if(order_by_order == 'ASC'){
			new_order = 'DESC';
		}
	}
	
	$('input[name="order_by_field_'+cfg.id+'"]').val(field);
	$('input[name="order_by_order_'+cfg.id+'"]').val(new_order);
	LoadPaginationAjax(id_subpanel);
}
function GoToPageSubpanel(id_subpanel, page)
{
	if($('#filtroForm'+id_subpanel).length > 0){
		LoadPaginationAjax(id_subpanel, page);
	}else{
		location.href = $(elm).attr('org_href');
	}
}
function ToggleSubpanel(elem, id_subpanel)
{
	if(typeof SubpanelsCfg[id_subpanel] !== 'undefined'){
		let cfg = SubpanelsCfg[id_subpanel];
		if($('#'+cfg.id).length < 1){
			LoadPaginationAjax(id_subpanel);
		}
		if($('#'+cfg.id+'_btn').find('.fa-chevron-down').length > 0){
			$('#'+cfg.id+'_btn').find('.fa-chevron-down').removeClass('fa-chevron-down').addClass('fa-chevron-up');
		}else{
			$('#'+cfg.id+'_btn').find('.fa-chevron-up').removeClass('fa-chevron-up').addClass('fa-chevron-down');
		}
	}else{
		console.log('Undefined subpanelsCfg'+id_subpanel);
	}
}
function LoadPaginationAjax(id_subpanel, page = 0)
{
	let cfg = SubpanelsCfg[id_subpanel];
	
	let elem = '#'+cfg.id+'_btn';
	$(elem).find('.loading-icon').show();
	let data_json = JSON.stringify({
		'model': cfg.model,
		'page': page,
		'per_page': cfg.per_page,
		'location_to': cfg.location_to,
		'id': cfg.id,
		'fields_return': cfg.fields_return,
		'initial_order_by': cfg.initial_order_by,
		'initial_filter': cfg.initial_filter,
		'order_by_field': $('input[name="order_by_field_'+cfg.id+'"]').val(),
		'order_by_order': $('input[name="order_by_order_'+cfg.id+'"]').val(),
	});
	if(!!cfg.url){
		var url_fire = cfg.url;
	}else{
		var url_fire = _APP.app_url+'Ajax_requests/pagination_ajax'
	}
	$.ajax({
		'url': url_fire,
		'method': 'post',
		'dataType': 'json',
		headers: {
		  "Content-Type": "application/json",
		  "X-Requested-With": "XMLHttpRequest"
		},
		'data': data_json,
		success: function(d){
			
		},
		complete: function(d){
			$('#'+cfg.id).remove();
			$('#'+cfg.id+'_pagination').remove();
			$('#filtroForm'+cfg.id).remove();
			$(elem).parent().append(d.responseText);
			if($('#'+cfg.id).length > 0){
				
				let order_by_field = $('input[name="order_by_field_'+cfg.id+'"]').val();
				let order_by_order = $('input[name="order_by_order_'+cfg.id+'"]').val();
				
				if(order_by_field !== '' && order_by_order !== ''){
					let icon = '';
					if(order_by_order == 'ASC'){
						icon = '<i class="fas fa-sort-amount-down-alt"></i>';
					}else if(order_by_order == 'DESC'){
						icon = '<i class="fas fa-sort-amount-up-alt"></i>';
					}
					
					if($('#'+cfg.id).find('th[dt-h-field="'+order_by_field+'"]').find('.icon-order-by').length < 1){
						$('#'+cfg.id).find('th[dt-h-field="'+order_by_field+'"]').append(' <span class="icon-order-by"></span>');
					}
					$('#'+cfg.id).find('th[dt-h-field="'+order_by_field+'"]').find('.icon-order-by').html(icon);
				}
			}
			$(elem).find('.loading-icon').hide();
		},
		error: function(d){
			console.log(d);
		}
	});
}

function SetRelatedField(args = {})
{
	RelatedFieldsCfg[args.elm] = args;
	
	elmCfg = RelatedFieldsCfg[args.elm];
	
	let url_fire = (!!elmCfg.is_custom) ? elmCfg.url_ajax : 'ajax_requests/get_related';
	$(elmCfg.elm).autocomplete({
		"source": function( request, response ) {
			elmCfg = RelatedFieldsCfg['input[name="'+$(this.element).attr('name')+'"]'];
			let custom_where = elmCfg.custom_where;
			
			if(elmCfg.custom_where){
				custom_where = {};
				$.each(elmCfg.custom_where, function(idx, ipt){
					let val_ipt = ipt;
					if(ipt.substring(0, 1) == "$"){
						val_ipt = $(ipt.substring(1, 999999)).val();
					}
					custom_where[idx] = val_ipt;
				});
			}
			$.ajax({
				"url": _APP.app_url+url_fire,
				"dataType": 'json',
				"method": 'post',
				headers: {
				  "Content-Type": "application/json",
				  "X-Requested-With": "XMLHttpRequest"
				},
				"data": JSON.stringify({
					"is_custom": elmCfg.is_custom,
					"model": elmCfg.model,
					"search_param": $(elmCfg.elm).val(),
					"custom_where": custom_where,
				}),
				success: function(d){
					
				},
				complete: function(d){
					var r = d.responseJSON;
					let valid = false;
					if(!!r.status){
						let records = [];
						if(r.detail){
							if(!Array.isArray(r.detail)){
								valid = true;
								records.push(r.detail);
							}else if(r.detail.length > 0){
								valid = true;
								response(r.detail);
							}
						}
						if(!valid){
							response([{"label":"Nenhum registro encontrado!", "value": $(elmCfg.elm).val()}]);
						}
					}
				}
			});
		},
		minLength: 1,
		select: function( event, ui ) {
			if(!!ui.item){
				$(elmCfg.elm_id).val(ui.item.id);
				$(elmCfg.elm).val(ui.item.value);
			}else{
				blankRelatedField(elmCfg);
			}
		},
	});
	$(elmCfg.elm).keyup(function(){
		elmCfg = RelatedFieldsCfg['input[name="'+$(this).attr('name')+'"]'];
		$(elmCfg.elm_id).val('');
	});
	$(elmCfg.elm).focusout(function(){
		elmCfg = RelatedFieldsCfg['input[name="'+$(this).attr('name')+'"]'];
		if($(this).val() == "" || $(elmCfg.elm_id).val() == ""){
			blankRelatedField(elmCfg);
		}
		
		callback_select_related(elmCfg);
	});
	$(elmCfg.elm).on( "autocompleteclose", function( event, ui ) {
		elmCfg = RelatedFieldsCfg['input[name="'+$(this).attr('name')+'"]'];
		if($(this).val() == "" || $(elmCfg.elm_id).val() == ""){
			blankRelatedField(elmCfg);
		}
	});
}
function blankRelatedField(elmCfg)
{
	$(elmCfg.elm_id).val('');
	$(elmCfg.elm).val('');
}

function callback_select_related(elmCfg)
{
	if(elmCfg.callback_select){
		var fn = window[elmCfg.callback_select];
		if(typeof fn !== 'function')
			return;
		fn.apply(window, [{'id':$(elmCfg.elm_id).val(), 'nome':$(elmCfg.elm).val()}]);
	}
}
function hideShowFields(hideF, showF)
{
	$.each(hideF, function(idx, ipt){
		$('label[for="'+idx+'"]').hide().parent().hide();
		$('label[for="'+idx+'"]').attr('required', false).parent().find('.required').remove();
	});
	$.each(showF, function(idx, ipt){
		$('label[for="'+idx+'"]').show().parent().show();
		
		//Remover span e atributo de required para evitar duplicatas
		$('label[for="'+idx+'"]').attr('required', false).parent().find('.required').remove();
		
		if(ipt){
			$('label[for="'+idx+'"]').attr('required', true).after(' <span class="required">*</span>');
		}
	});
}