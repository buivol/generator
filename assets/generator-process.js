Generator.prototype.create = function(){
	self.setState(4,"Создание презентаций",2);
	self.log("Создание презентаций",1);
	var x = $.ajax({
	  dataType: "json",
	  url: 'api/create.php'
	});

	x.done(function(r){
		if(r.status == 'ok'){
			self.setState(4,"Создание презентаций ("+r.time.toFixed(2)+' сек)',3);
			self.log('Ок. Презентации созданы. Скрипт выполнен за '+r.time+' сек. Максимальная нагрузка составила '+self.getPeek(r.peek),2);
			self.gentime += parseFloat(r.time.toFixed(5));
			self.createAll();
		} else {
			self.setState(4,"Создание презентаций",4);
			$.each(r.messages,function(i, message){
				self.log(message,4);
			});
			self.stopError();
		}
	});
}

Generator.prototype.createAll = function(){
	if(self.config.groups[0].generate == "true" || self.config.groups[0].generate == true){
		self.setState(5,"Создание общей презентации",2);
		self.log("Создание общей презентации",1);
		var x = $.ajax({
		  dataType: "json",
		  url: 'api/createall.php'
		});

		x.done(function(r){
			if(r.status == 'ok'){
				self.setState(5,"Создание общей презентации ("+r.time.toFixed(2)+' сек)',3);
				self.log('Ок. Общая презентация создана. Скрипт выполнен за '+r.time+' сек. Максимальная нагрузка составила '+self.getPeek(r.peek),2);
				self.gentime += parseFloat(r.time.toFixed(5));
				self.clearTemp();
			} else {
				self.setState(5,"Создание общей презентации",4);
				$.each(r.messages,function(i, message){
					self.log(message,4);
				});
				self.stopError();
			}
		});
	} else {
		self.clearTemp();
	}
}

Generator.prototype.finish = function(){
	var t = '';
	if(self.gentime<60){
		t = +self.gentime.toFixed(3)+' сек';
	} else {
		var z = self.gentime.toFixed(3);
		var m = parseInt(Math.floor(z / 60));
		var s = (z - (m * 60)).toFixed(0);
		t = m + ' мин ' + s + 'сек'; 
	}
	$('#workgroup').append('<h2 id="secs">'+t+'</h2>');
	$('#generator-start').show(0);
}


Generator.prototype.clearTemp = function(){
	self.setState(6,"Удаление временных файлов",2);
	self.log("Удаление временных файлов",1);
	var x = $.ajax({
	  dataType: "json",
	  url: 'api/clear.php'
	});

	x.done(function(r){
		if(r.status == 'ok'){
			self.setState(6,"Удаление временных файлов ("+r.time.toFixed(2)+' сек)',3);
			self.log('Ок. Временные файлы удалены. Скрипт выполнен за '+r.time+' сек. Максимальная нагрузка составила '+self.getPeek(r.peek),2);
			self.gentime += parseFloat(r.time.toFixed(5));
			self.finish();
		} else {
			self.setState(6,"Удаление временных файлов",4);
			self.stopError();
		}
	});
}


Generator.prototype.marks = function(){
	self.setState(3,"Обработка отметок",2);
	self.log('Обработка отметок',1);
	var x = $.ajax({
	  dataType: "json",
	  url: 'api/marks.php'
	});

	x.done(function(r){
		if(r.status == 'ok'){
			self.setState(3,"Обработка отметок ("+r.time.toFixed(2)+' сек)',3);
			self.log('Ок. Отметки обработаны. Скрипт выполнен за '+r.time+' сек. Максимальная нагрузка составила '+self.getPeek(r.peek),2);
			self.gentime += parseFloat(r.time.toFixed(5));
			self.create();
		} else {
			self.setState(3,"Обработка отметок",4);
			self.stopError();
		}
	});
}

Generator.prototype.prepareTemp = function(){
	self.setState(2,"Подготовка временных файлов",2);
	self.log('Подготовка временных файлов',1);
	var x = $.ajax({
	  dataType: "json",
	  url: 'api/preparetemp.php'
	});

	x.done(function(r){
		if(r.status == 'ok'){
			self.setState(2,"Подготовка временных файлов ("+r.time.toFixed(2)+' сек)',3);
			self.log('Ок. Файлы подготовлены. Скрипт выполнен за '+r.time+' сек. Максимальная нагрузка составила '+self.getPeek(r.peek),2);
			self.gentime += parseFloat(r.time.toFixed(5));
			self.marks();
		} else {
			self.setState(2,"Подготовка временных файлов",4);
			self.stopError();
		}
	});

}


Generator.prototype.checkpaths = function(){
	self.setState(1,"Подготовка прайсов",2);
	self.log('Подготовка прайсов',1);
	var x = $.ajax({
	  dataType: "json",
	  url: 'api/checkpaths.php'
	});

	x.done(function(r){
		if(r.status == 'ok'){
			self.log('Ок. Все пути существуют. Скрипт выполнен за '+r.time+' сек. Максимальная нагрузка составила '+self.getPeek(r.peek),2);
			self.setState(1,"Подготовка прайсов ("+r.time.toFixed(2)+' сек)',3);
			self.gentime += parseFloat(r.time.toFixed(5));
			self.prepareTemp();
		} else {
			var _html = '';
			$.each(r.errors, function(i, g) {
				_html += '<br>' + g;
			});
			self.setState(1,"Подготовка прайсов",4);
			self.log('Ошибка. Эти файлы не найдены: '+_html,4);
			self.stopError();
		}
	});

}

Generator.prototype.getPeek = function(length){
	var i = 0, type = ['б','Кб','Мб','Гб','Тб','Пб'];
	while((length / 1000 | 0) && i < type.length - 1) {
		length /= 1024;
		i++;
	}
	return length.toFixed(2) + ' ' + type[i];
}

Generator.prototype.resetLabels = function(){
	self.gentime = 0.0;
	$('#secs').remove();
	$('.work').remove();
	self.setState(1,"Подготовка прайсов",1);
	self.setState(2,"Подготовка временных файлов",1);
	self.setState(3,"Обработка отметок",1);
	self.setState(4,"Создание презентаций",1);
	if(self.config.groups[0].generate == "true" || self.config.groups[0].generate == true)
		self.setState(5,"Создание общей презентации",1);
	self.setState(6,"Удаление временных файлов",1);
}
Generator.prototype.start = function(){
	self.log('------------------------------',3);
	self.log('Начало работы генератора',1);
	$('#generator-start').hide(0);
	self.resetLabels();
	var ch = $('#aftLink').is(':checked');
	// console.log(ch); return;
	if(ch){
		self.startLink(true);
	} else {
		self.checkpaths();
	}
}
Generator.prototype.stopError = function(){
	self.log("Генератор остановлен",4);
	self.log('------------------------------',3);
	$('#generator-start').show(0);
}