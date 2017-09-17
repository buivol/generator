function Generator(config) {
    this.config = config;
    self = this;
    self.log('Приложение запущено', 1);
}

Generator.prototype.log = function (message, type) {
    /*
        1 -- default
        2 -- success
        3 -- warning
        4 -- error
    */
    var p1 = '<span class="log-message">';
    var t1 = '<span class="log-time">';
    var t2 = '</span>';
    var m1 = '<span class="log-content log-type-' + type + '">';
    var m2 = '</span>';
    var p2 = '</span>';
    var date = new Date();
    var h = date.getHours();
    if (h < 10) h = '0' + h;
    var m = date.getMinutes();
    if (m < 10) m = '0' + m;
    var s = date.getSeconds();
    if (s < 10) s = '0' + s;
    var tc = h + ':' + m + ':' + s;
    $("#log").append(p1 + t1 + tc + t2 + m1 + message + m2 + p2);
    $('#log').animate({
        scrollTop: $('#log').get(0).scrollHeight
    }, 100);
    return 'message sended';
}


Generator.prototype.loadSettings = function () {
    var x = $.ajax({
        dataType: "json",
        url: 'api/settings.php',
        data: {action: 'load'}
    });

    x.done(function (data) {
        try {
            self.config = data.config;
        } catch (e) {
            self.log('Ошибка загрузки настроек. Файл конфигурации поврждён', 4);
            return;
        }
        self.log('Настройки генератора загружены', 1);
        self.initSettings();
    });
}

Generator.prototype.setState = function (id, stateText, stateCode) {
    var _html = '<i class="fa fa-';
    //1 -- idle
    //2 -- work
    //3 -- success
    //4 -- error
    if (stateCode == 1) {
        _html += 'circle-o';
    } else if (stateCode == 2) {
        _html += 'spinner fa-spin';
    } else if (stateCode == 3) {
        _html += 'check-circle-o';
    } else if (stateCode == 4) {
        _html += 'circle';
    } else {
        _html += 'circle-o';
    }
    _html += "\" aria-hidden=\"true\"></i> " + stateText;
    if ($('#work-' + id).length == 0) {
        $("#workgroup").append('<p class="work" id="work-' + id + '">' + _html + '</p>');
    } else {
        $('#work-' + id).html(_html);
    }
}

Generator.prototype.checkLogs = function (first) {
    var x = $.ajax({
        dataType: "json",
        url: 'api/log.json'
    });
    x.done(function (data) {
        $.each(data.m, function (i, m) {
            var can_add = true;
            $.each(self.logs, function (z, l) {
                if (l.c == m.c) {
                    can_add = false;
                }
            });
            if (can_add) {
                if (!first) {
                    self.log(m.t, 1);
                }
                self.logs.push(m);
            }
        });
        setTimeout(function () {
            self.checkLogs(false)
        }, 500);
    });
}


Generator.prototype.initSettings = function () {
    self.logs = [];
    self.checkLogs(true);
    self.initSettingsTab(self.config);
    self.loadFiles();
    $('#add-group').on('click', function (e) {
        self.clickGroupAdd();
    });
    $('#generator-start').on('click', function (e) {
        self.start();
    });
    $('#save-settings').on('click', function (e) {
        self.saveSettingsTab();
    });
    $('#gpe-ids').tagsinput({
        trimValue: true,
        cancelConfirmKeysOnEmpty: true,
        freeInput: true
    });
    if (self.config.aftLink == 'true') {
        $('#aftLink').prop('checked', 'checked');
    }
    $('#aftLink').on('click', function (e) {
        var ch = $(this).is(':checked');
        self.config.aftLink = ch;
        self.saveSettings();
    });
    self.resetLabels();
}

Generator.prototype.refreshButtons = function () {
    $('.ig-gen,.ig-edit,.ig-del,.ig-save,.ig-cancel').unbind();
    $(".ig-gen").on('click', function (e) {
        var id = $(this).parent().parent().data('id');
        self.clickGroupActivate(id);
    });
    $(".ig-edit").on('click', function (e) {
        var id = $(this).parent().parent().data('id');
        self.clickGroupEdit(id);
    });
    $(".ig-del").on('click', function (e) {
        var id = $(this).parent().parent().data('id');
        self.clickGroupDelete(id);
    });
    $(".ig-save").on('click', function (e) {
        var id = $(this).parent().parent().data('id');
        self.clickGroupSave(id);
    });
    $(".ig-cancel").on('click', function (e) {
        var id = $(this).parent().parent().data('id');
        self.clickGroupCancel(id);
    });
}
Generator.prototype.saveSettings = function () {
    var x = $.ajax({
        dataType: "json",
        type: "POST",
        url: 'api/settings.php?action=save',
        data: {config: self.config}
    });

    x.done(function (data) {
        $.notify("Настройки сохранены", "success");
    });
}

Generator.prototype.saveSettingsAndCallBack = function (callback) {
    var x = $.ajax({
        dataType: "json",
        type: "POST",
        url: 'api/settings.php?action=save',
        data: {config: self.config}
    });

    x.done(function (data) {
        $.notify("Настройки сохранены", "success");
        callback(data);
    });
}


Generator.prototype.loadFiles = function () {
    var x = $.ajax({
        dataType: "json",
        url: 'api/files.php',
        data: {action: 'get'}
    });

    x.done(function (data) {
        self.files = data.input;
        self.images = data.images;
        self.groupsPrint(self.config.groups);
        self.marksPrint(self.config.marks);
        self.grpPrint(self.config.grp);
        self.linksPrint(self.config.links);
        $("#preloader").fadeOut(800);
    });
}

