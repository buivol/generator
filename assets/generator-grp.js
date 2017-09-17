Generator.prototype.grpRefreshUI = function () {
    $(".gp-edit, #grp-save, .gp-del, #add-grp").unbind();
    $(".gp-edit").on('click', function (e) {
        var id = $(this).parent().parent().data('id');
        self.grpEdit(id);
    });
    $("#grp-save").on('click', function (e) {
        var id = $("#gpe-id").val();
        self.grpSave(id);
    });
    $("#add-grp").on('click', function (e) {
        self.grpNew();
    });
    $(".gp-del").on('click', function (e) {
        var id = $(this).parent().parent().data('id');
        self.grpDelete(id);
    });
    self.groupsname = [];
    $.each(self.config.grp, function (k, g) {
        self.groupsname.push(g.name);
    });
}
Generator.prototype.grpEdit = function (id) {
    var sel = 'tr.grp-item[data-id="' + id + '"]';
    $('#gpe-id').val(id);
    $('#gpe-name').val($(sel + ' .gp-name').text());
    var _ids = $(sel + ' .gp-ids').html();
    console.log(_ids);
    var ids = _ids.split('<br>');
    $('#gpe-ids').tagsinput('removeAll');
    $.each(ids, function (k, v) {
        $('#gpe-ids').tagsinput('add', v);
    });
    $('#gpe-title').prop('checked', $(sel + ' .gp-title-cb').prop('checked'));
    $('#editgrp').modal('show');
}
Generator.prototype.grpNew = function () {
    $('#gpe-id').val('new');
    $('#gpe-name').val('');
    $('#gpe-ids').tagsinput('removeAll');
    $('#gpe-title').attr('checked', 'checked');
    $('#editgrp').modal('show');
}
Generator.prototype.grpSave = function (id) {
    var isNew = false;
    if (id == 'new') {
        isNew = true;
    }
    var g = self.grpValidate(isNew);
    if (g == false) {
        return false;
    }
    var rename = false;
    if (!isNew) {
        var old_name = self.config.grp[id].name;
        if (g.name != old_name) {
            $.each(self.config.groups, function (k, b) {
                var ids = b.id.split(',');
                $.each(ids, function (i, z) {
                    if (z == old_name) {
                        ids[i] = g.name;
                    }
                });
                b.id = ids.join(',');
            });
        }
    }
    if (!isNew) {
        self.config.grp[id] = g;
    } else {
        self.config.grp.push(g);
    }

    self.saveSettingsAndCallBack(function (data) {
        self.grpPrint(data.config.grp);
        self.groupsPrint(data.config.groups);
        $('#editgrp').modal('hide');
    });
}
Generator.prototype.grpDelete = function (id) {
    var name = $('tr.grp-item[data-id="' + id + '"] .gp-name').text();
    var deltext = prompt("Вы уверены, что хотите удалить группу \"" + name + "\"?\r\nЧтобы подтвердить действие введите \"удалить\" маленькими русскими буквами в поле ниже", '');
    if (deltext == "удалить") {
        self.config.grp.splice(id, 1);
        self.saveSettingsAndCallBack(function (data) {
            self.grpPrint(data.config.grp);
        });
    } else {

    }
}
Generator.prototype.grpValidate = function (isNew) {
    var g = new Object();
    g.name = $('#gpe-name').val().trim();
    if (g.name.length < 2) {
        $('#gpe-name').focus();
        return false;
    }
    if (isNew) {
        $.each(self.groupsname, function (k, v) {
            if (v == g.name) {
                $('#gpe-name').focus();
                return false;
            }
        });
    }
    var _ids = $('#gpe-ids').tagsinput('items');
    if (_ids.length < 1) {
        return false;
    }
    g.ids = _ids.join(',');
    g.title = $('#gpe-title').is(':checked');
    return g;
}
Generator.prototype.grpPrint = function (grp) {
    $(".grp-item").remove();
    $.each(grp, function (id, g) {
        var _html = '<tr class="grp-item" data-id="' + id + '">';
        _html += '<td class="gp-name">' + g.name + '</td>';
        _html += '<td class="gp-ids">' + g.ids.split(',').join('<br>') + '</td>';
        _html += '<td class="gp-title"><p><input class="gp-title-cb" type="checkbox" disabled="disabled"';
        if (g.title == "true") {
            _html += ' checked="checked"';
        }
        _html += '></td>';
        _html += '<td class="gp-actions"><button class="btn btn-default btn-ig gp-edit"><i class="fa fa-pencil" aria-hidden="true"></i></button><button class="btn btn-danger btn-ig gp-del"><i class="fa fa-trash" aria-hidden="true"></i></button></td>';
        _html += '</tr>';
        $("#grp tbody").append(_html);
    });
    self.grpRefreshUI();
}


/*
	"grp": [
      {
        "name": "Тестовая",
        "ids": "1,2,3,4,5",
        "title": "true"
      },
      {
        "name": "Все товары из прайса",
        "ids": "allgroups",
        "title": "false"
      }
    ],
*/