Generator.prototype.linkActive = false;
Generator.prototype.linksPrint = function(links){
	$(".link-item").remove();
	$.each(links,function(id,link){
		var _html =  '<tr class="link-item" data-id="'+id+'">';
		_html += '<td class="lk-active"><input class="lk-activetrigger" type="checkbox"';
		if(link.active == "true"){
			_html += ' checked="checked"';
		}
		_html += '></td>';
		_html += '<td class="lk-path">'+link.path+'</td>';
		_html += '<td class="lk-pk"><input class="lk-pktrigger" type="checkbox"';
		if(link.pk == "true"){
			_html += ' checked="checked"';
		}
		_html += '></td>';
		_html += '<td class="lk-actions"><button class="btn btn-default btn-ig lk-edit"><i class="fa fa-pencil" aria-hidden="true"></i></button><button class="btn btn-danger btn-ig lk-del"><i class="fa fa-trash" aria-hidden="true"></i></button></td>';
		_html += '</tr>';
		$("#links tbody").append(_html);
	});	
	$("#linker").val(self.config.files.linker);
	$('#lke-path').typeahead({ source:self.files });
	self.linkActive = false;
	$('#start-link').on('click',function(e){
		e.preventDefault();
		self.startLink(false);
	});
	self.linksRefreshUI();
}

Generator.prototype.startLink = function (callb){
	if(!self.linkActive){
		self.linkActive = true;
		$('#start-link').prop('disabled', true);
		$('#start-link').html('<i class="fa fa-spinner fa-pulse fa-fw" aria-hidden="true"></i> Обработка');
		self.log("Обработка файлов на дубли",1);
		var x = $.ajax({
		  dataType: "json",
		  url: 'api/dublicate.php'
		});

		x.done(function(r){
			if(r.status == 'ok'){
				self.log('Ок. Ссылки обработаны. Скрипт выполнен за '+r.time+' сек. Максимальная нагрузка составила '+self.getPeek(r.peek),2);
				self.stopLink(callb);
				if(!callb){
					self.config.files.linker = $("#linker").val();
					self.saveSettings();
					$.notify("Ссылки обработаны. Подробности в логе на первой вкладке","success");
				}
			} else {
				$.each(r.messages,function(i, message){
					self.log(message,4);
				});
				self.stopLink(callb);
				$.notify("Ссылки не обработаны. Подробности в логе на первой вкладке","danger");
			}
		});
	}
}
Generator.prototype.stopLink = function (callb){
	if(self.linkActive){
		self.linkActive = false;
		$('#start-link').prop('disabled', false);
		$('#start-link').html('<i class="fa fa-play" aria-hidden="true"></i> Убрать дубли');
		if(callb){
			self.checkpaths();
		}
	}
}


Generator.prototype.linksActivateTrigger = function(id){
	var ch = $('tr.link-item[data-id="'+id+'"] .lk-activetrigger').is(':checked');
	self.config.links[id].active = ch;
	self.saveSettings();
}

Generator.prototype.linksPkTrigger = function(id){
	var ch = $('tr.link-item[data-id="'+id+'"] .lk-pktrigger').is(':checked');
	self.config.links[id].pk = ch;
	self.saveSettings();
}

Generator.prototype.linkDelete = function(id){
	var path = $('tr.link-item[data-id="'+id+'"] .lk-path').text();
	var deltext = prompt("Вы уверены, что хотите удалить ссылку \""+path+"\"?\r\nЧтобы подтвердить действие введите \"да\" маленькими русскими буквами в поле ниже", '');
	if(deltext == "да"){
		self.config.links.splice(id, 1);
		self.saveSettingsAndCallBack(function(data){
			self.linksPrint(data.config.links);
		});
	} else {
		
	}
}

Generator.prototype.linksRefreshUI = function(){
	$(".lk-edit, #link-save, .lk-del, #add-link, .lk-activetrigger").unbind();
	$(".lk-edit").on('click',function(e){
		var id = $(this).parent().parent().data('id'); 
		self.linkEdit(id);
	});
	$("#link-save").on('click',function(e){
		var id = $("#lke-id").val(); 
		self.linkSave(id);
	});
	$("#add-link").on('click',function(e){ 
		self.linkNew();
	});
	$(".lk-activetrigger").on('click',function(e){
		var id = $(this).parent().parent().data('id'); 
		self.linksActivateTrigger(id);
	});
	$(".lk-pktrigger").on('click',function(e){
		var id = $(this).parent().parent().data('id'); 
		self.linksPkTrigger(id);
	});
	$(".lk-del").on('click',function(e){
		var id = $(this).parent().parent().data('id'); 
		self.linkDelete(id);
	});
}


Generator.prototype.linkEdit = function(id){
	var sel = 'tr.link-item[data-id="'+id+'"]';
	$('#lke-id').val(id);
	$('#lke-path').val($(sel+' .lk-path').text());
	$('#editlink').modal('show');
}

Generator.prototype.linkNew = function(){
	$('#lke-id').val('new');
	$('#lke-path').val('');
	$('#editlink').modal('show');
}


Generator.prototype.linkValidate = function(){
	var l = new Object();
	l.path = $('#lke-path').val().trim();
	if(l.path.length < 2){
		return false;
	}
	l.active = false;
	l.pk = false;
	return l;
}

Generator.prototype.linkSave = function(id){
	var isNew = false;
	if(id == 'new'){
		isNew = true;
	}
	var sel = 'tr.link-item[data-id="'+id+'"]';
	var m = self.linkValidate();
	if(m == false){
		return false;
	} 
	if(!isNew){
		m.active = self.config.links[id].active;
		self.config.links[id] = m;
	} else {
		try {
			self.config.links.push(m);
		} catch (err) {
			self.config.links = [];
			self.config.links.push(m);
			console.log('error', self.config.links);
		}
		
	}
	self.saveSettingsAndCallBack(function(data){
		self.linksPrint(data.config.links);
		$('#editlink').modal('hide');
	});
}
/*
				


*/