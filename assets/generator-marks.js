String.prototype.logicConvert1 = function(){
	switch (this.toString()) {
	  case "default":
	    return "Брать из Price";
	  case "minus":
	    return "Price минус %";
	  case "excel":
	    return "Брать из Excel";
	  default:
	    return "Неизвестно ("+this+")";
	}
}

String.prototype.logicConvert2 = function(){
	switch (this.toString()) {
	  case "default":
	    return "Из базовой";
	  case "fixbase":
	    return "Фикс из базовой";
	  case "minus":
	    return "Базовая минус %";
	  case "fixminus":
	    return "Фикс базовая минус %";
	  case "excel":
	    return "Фикс из Excel";
	  default:
	    return "Неизвестно ("+this+")";
	}
}

String.prototype.logicConvert3 = function(){
	switch (this.toString()) {
	  case "default":
	    return "Стандартная формула";
	  case "fixprice":
	    return "Фикс из Ваша цена";
	  case "fixminus":
	    return "Фикс Ваша цена минус %";
	  default:
	    return "Неизвестно ("+this+")";
	}
}
Generator.prototype.marksPrint = function(marks){
	$(".mark-item").remove();
	$.each(marks,function(id,mark){
		var _html =  '<tr class="mark-item" data-id="'+id+'">';
		_html += '<td class="mk-active"><input class="mk-activetrigger" type="checkbox"';
		if(mark.active == "true"){
			_html += ' checked="checked"';
		}
		_html += '></td>';
		_html += '<td class="mk-name">'+mark.name+'</td>';
		_html += '<td class="mk-path"><code class="mk-path-val">'+mark.path+'</code><p>Колонка с артикулом: <code class="mk-code">'+mark.code+'</code></td>';
		_html += '<td class="mk-cols"><p>Колонка истины <code class="mk-col-c">'+mark.col+'</code></p><p>Значение <code class="mk-col-v">'+mark.val+'</code></p></td>';
		_html += '<td class="mk-cols-price"><p>Фикc <code class="mk-col-pfix">'+mark.pfix+'</code></p><p>Новая <code class="mk-col-pnew">'+mark.pnew+'</code></p><p>Базовая <code class="mk-col-pbase">'+mark.pbase+'</code></p></td>';
		_html += '<td class="mk-present"><p><input class="mk-present-main" type="checkbox" disabled="disabled"';
		if(mark.main == "true"){
			_html += ' checked="checked"';
		}
		_html += '> менять в общей</p><p><input class="mk-present-self" type="checkbox" disabled="disabled"';
		if(mark.self == "true"){
			_html += ' checked="checked"';
		}
		_html += '> собственная</p>';
		_html += '<p><input class="mk-present-cut" type="checkbox" disabled="disabled"';
		if(mark.cut == "true"){
			_html += ' checked="checked"';
		}
		_html += '> вырезать из общей</p></td>';
		_html += '<td class="mk-price"><p>Базовая <code class="mk-cbase">';
		_html += mark.cbase.logicConvert1()+'</code></p><p>Значение <code class="mk-cbaseval">'+mark.cbaseval+'</code></p>';
		_html += '<p>Ваша цена <code class="mk-cprice">';
		_html += mark.cprice.logicConvert2()+'</code></p><p>Значение <code class="mk-cpriceval">'+mark.cpriceval+'</code></p>';
		_html += '<p>По предоплате <code class="mk-cpreorder">';
		_html += mark.cpreorder.logicConvert3()+'</code></p><p>Значение <code class="mk-cpreorderval">'+mark.cpreorderval+'</code></p></td>';
		_html += '<td class="mk-img"><p>В колонке <code class="mk-image">'+mark.image+'</code></p><p>В шапке<code class="mk-header">'+mark.header+'</code></p></td>';
		_html += '<td class="mk-descr"><input type="checkbox" class="mk-pastedesc" disabled="disabled"';
		if(mark.pastedesc == "true"){
			_html += ' checked="checked"';
		}
		_html += '><p class="mk-desc">'+mark.desc+'</p></td>';
		_html += '<td class="mk-actions"><button class="btn btn-default btn-ig mk-edit"><i class="fa fa-pencil" aria-hidden="true"></i></button><button class="btn btn-danger btn-ig mk-del"><i class="fa fa-trash" aria-hidden="true"></i></button></td>';
		_html += '</tr>';
		$("#marks tbody").append(_html);
	});	
	self.marksRefreshUI();
}

Generator.prototype.marksActivateTrigger = function(id){
	var ch = $('tr.mark-item[data-id="'+id+'"] .mk-activetrigger').is(':checked');
	self.config.marks[id].active = ch;
	if(!ch){
		self.log('Отметка '+self.config.marks[id].name+' отключена',1);
	} else {
		self.log('Отметка '+self.config.marks[id].name+' включена',1);
	}
	self.saveSettings();
}

Generator.prototype.markDelete = function(id){
	var name = $('tr.mark-item[data-id="'+id+'"] .mk-name').text();
	var deltext = prompt("Вы уверены, что хотите удалить отметку \""+name+"\"?\r\nЧтобы подтвердить действие введите \"удалить\" маленькими русскими буквами в поле ниже", '');
	if(deltext == "удалить"){
		self.config.marks.splice(id, 1);
		self.saveSettingsAndCallBack(function(data){
			self.marksPrint(data.config.marks);
		});
	} else {
		
	}
}

Generator.prototype.marksRefreshUI = function(){
	$(".mk-edit, #mke-save, .mk-del, #add-mark, .mk-activetrigger").unbind();
	$(".mk-edit").on('click',function(e){
		var id = $(this).parent().parent().data('id'); 
		self.markEdit(id);
	});
	$("#mke-save").on('click',function(e){
		var id = $("#mke-id").val(); 
		self.markSave(id);
	});
	$("#add-mark").on('click',function(e){ 
		self.markNew();
	});
	$(".mk-activetrigger").on('click',function(e){
		var id = $(this).parent().parent().data('id'); 
		self.marksActivateTrigger(id);
	});
	$(".mk-del").on('click',function(e){
		var id = $(this).parent().parent().data('id'); 
		self.markDelete(id);
	});
	$('.marktphfile').typeahead({ source:self.files });
	$('.marktphimg').typeahead({ source:self.images });
}


Generator.prototype.markEdit = function(id){
	var sel = 'tr.mark-item[data-id="'+id+'"]';
	$('#mke-id').val(id);
	$('#mke-name').val($(sel+' .mk-name').text());
	$('#mke-path').val($(sel+' .mk-path-val').text());
	$('#mke-col').val($(sel+' .mk-col-c').text());
	$('#mke-val').val($(sel+' .mk-col-v').text());
	$('#mke-code').val($(sel+' .mk-code').text());
	$('#mke-pfix').val($(sel+' .mk-col-pfix').text());
	$('#mke-pnew').val($(sel+' .mk-col-pnew').text());
	$('#mke-pbase').val($(sel+' .mk-col-pbase').text());
	$('#mke-present-main').prop('checked',$(sel+' .mk-present-main').prop('checked'));
	$('#mke-present-self').prop('checked',$(sel+' .mk-present-self').prop('checked'));
	$('#mke-present-cut').prop('checked',$(sel+' .mk-present-cut').prop('checked'));
	$('#mke-cbase').val(self.config.marks[id].cbase);
	$('#mke-cbaseval').val($(sel+' .mk-cbaseval').text());
	$('#mke-cprice').val(self.config.marks[id].cprice);
	$('#mke-cpriceval').val($(sel+' .mk-cpriceval').text());
	$('#mke-cpreorder').val(self.config.marks[id].cpreorder);
	$('#mke-cpreorderval').val($(sel+' .mk-cpreorderval').text());
	$('#mke-image').val($(sel+' .mk-image').text());
	$('#mke-header').val($(sel+' .mk-header').text());
	$('#mke-pastedesc').prop('checked',$(sel+' .mk-pastedesc').prop('checked'));
	$('#mke-desc').html($(sel+' .mk-desc').text());
	$('#editmark').modal('show');
}

Generator.prototype.markNew = function(){
	$('#mke-id').val('new');
	$('#mke-name').val('');
	$('#mke-path').val('');
	$('#mke-col').val('G');
	$('#mke-val').val('да');
	$('#mke-code').val('A');
	$('#mke-pfix').val('E');
	$('#mke-pnew').val('D');
	$('#mke-pbase').val('C');
	$('#mke-present-main').removeAttr('checked');
	$('#mke-present-self').removeAttr('checked');
	$('#mke-present-cut').removeAttr('checked');
	$('#mke-cbase').val('default');
	$('#mke-cbaseval').val('');
	$('#mke-cprice').val('default');
	$('#mke-cpriceval').val('');
	$('#mke-cpreorder').val('default');
	$('#mke-cpreorderval').val('');
	$('#mke-image').val('');
	$('#mke-header').val('');
	$('#mke-pastedesc').removeAttr('checked');
	$('#mke-desc').html('');
	$('#editmark').modal('show');
}


Generator.prototype.markValidate = function(){
	var m = new Object();
	m.name = $('#mke-name').val().trim();
	if(m.name.length < 2){
		$('#mke-name').focus();
		return false;
	}
	m.path = $('#mke-path').val().trim();
	if(m.path.length < 2){
		$('#mke-path').focus();
		return false;
	}
	m.col = $('#mke-col').val().trim();
	if(m.col.length < 1){
		$('#mke-col').focus();
		return false;
	}
	m.val = $('#mke-val').val().trim();
	if(m.val.length < 1){
		$('#mke-val').focus();
		return false;
	}
	m.code = $('#mke-code').val().trim();
	if(m.code.length < 1){
		$('#mke-code').focus();
		return false;
	}
	m.pfix = $('#mke-pfix').val().trim();
	if(m.pfix.length < 1){
		$('#mke-pfix').focus();
		return false;
	}
	m.pnew = $('#mke-pnew').val().trim();
	if(m.pnew.length < 1){
		$('#mke-pnew').focus();
		return false;
	}
	m.pbase = $('#mke-pbase').val().trim();
	if(m.pbase.length < 1){
		$('#mke-pbase').focus();
		return false;
	}
	m.main = $('#mke-present-main').is(':checked');
	m.self = $('#mke-present-self').is(':checked');
	m.cut = $('#mke-present-cut').is(':checked');
	m.cbase = $('#mke-cbase').val();
	m.cbaseval = $('#mke-cbaseval').val();
	m.cprice = $('#mke-cprice').val();
	m.cpriceval = $('#mke-cpriceval').val();
	m.cpreorder = $('#mke-cpreorder').val();
	m.cpreorderval = $('#mke-cpreorderval').val();
	m.image = $('#mke-image').val();
	m.header = $('#mke-header').val();
	m.pastedesc = $('#mke-pastedesc').is(':checked');
	m.desc = $('#mke-desc').val().trim();
	m.active = false;
	console.log(m);
	return m;
}

Generator.prototype.markSave = function(id){
	var isNew = false;
	if(id == 'new'){
		isNew = true;
	}
	var sel = 'tr.mark-item[data-id="'+id+'"]';
	var m = self.markValidate();
	if(m == false){
		return false;
	} 
	if(!isNew){
		m.active = self.config.marks[id].active;
		self.config.marks[id] = m;
	} else {
		self.config.marks.push(m);
	}
	self.saveSettingsAndCallBack(function(data){
		self.marksPrint(data.config.marks);
		$('#editmark').modal('hide');
	});
}
/*
				

				 "marks": [
			      {
			        "active": "false",
			        "name": "Товар месяца",
			        "path": "tovarmesyaca.xls",
			        "code": "A"
			        "col": "C",
			        "val": "да",
			        "pfix": "F",
			        "pnew": "E",
			        "pbase": "D",
			        "main": "true",
			        "self": "false",
			        "logic": "perc",
			        "logicval": "11",
			        "image": "mesyac.bmp",
			        "header": "header.bmp",
			        "pastedesc": "true",
			        "desc": "Товар месяца!!!"
			      }
			    ],

				<tr class="mark-item">
			  		<td class="mk-active"><input type="checkbox"></td>
			  		<td class="mk-name">Товары месяца</td>
			  		<td class="mk-path"><code class="mk-path-val">tovarmesyaca.xls</code></td>
			  		<td class="mk-cols"><p>Колонка <code class="mk-col-c">D</code></p><p>Значение <code class="mk-col-v">true</code></p></td>
			  		<td class="mk-present"><p><input class="mk-present-main" type="checkbox" disabled="disabled"> в общей</p><p><input class="mk-present-self" type="checkbox" disabled="disabled"> собственная</p><p><input type="checkbox" disabled="disabled"> в других</p></td>
			  		<td class="mk-price"><p>Расчёт <code class="mk-price-c">минус %</code></p><p>Значение <code class="mk-price-v">60</code></p></td>
			  		<td class="mk-imageart"><code class="mk-image-art-val">mesyac.bmp</code></td>
			  		<td class="mk-image"><code class="mk-image-header-val">mesyacheader.bmp</code></td>
			  		<td class="mk-descr">Товар месяца!</td>
			  		<td class="mk-actions"><button class="btn btn-default btn-ig mk-edit"><i class="fa fa-pencil" aria-hidden="true"></i></button><button class="btn btn-danger btn-ig mk-del"><i class="fa fa-trash" aria-hidden="true"></i></button></td>
		  		</tr>



*/