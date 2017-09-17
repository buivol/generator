Generator.prototype.clickGroupActivate = function (id) {
    var ch = $('tr[data-id="' + id + '"] .ig-gen').is(':checked');
    self.config.groups[id].generate = ch;
    if (!ch) {
        self.log('Группа ' + self.config.groups[id].name + ' отключена', 1);
    } else {
        self.log('Группа ' + self.config.groups[id].name + ' включена', 1);
    }
    self.saveSettings();
}
window.isset = function () {
    if (arguments.length === 0) return false;
    var buff = arguments[0];
    for (var i = 0; i < arguments.length; i++) {
        if (typeof(buff) === 'undefined') return false;
        buff = buff[arguments[i + 1]];
    }
    return true;
}
Generator.prototype.clickGroupEdit = function (id) {
    var isnew = false;
    var noedit = "false";

    if ($('tr.item-group[data-id="' + id + '"].item-new').length > 0) {
        isnew = true;
        console.log('edit new group');
    } else {
        noedit = self.config.groups[id].noedit;
        console.log('addBuffer', id);
        $('body').append('<table class="buffer" id="grouprow' + id + '"><tr>' + $('tr[data-id="' + id + '"]').html() + '</tr></table>');
    }

    if (isnew || isset(self.config.groups[id].ptype)) {
        var ptype = 0;
    } else {
        var ptype = self.config.groups[id].ptype;
    }
    var ismain = false;
    if ($('tr.item-group[data-id="' + id + '"] .ig-id').text() == "all") {
        ismain = true;
    }
    if (!ismain) {
        var priceel = 'tr.item-group[data-id="' + id + '"] .ig-price';
        var str = $(priceel).html();
        var strb = str.replace(/<br>/g, ",");
        var _price = '<input type="text" value="' + strb + '" data-role="tagsinput"/>';
        $(priceel).html(_price);
        $(priceel + ' input').tagsinput({
            trimValue: true,
            typeahead: {
                source: self.files
            },
            cancelConfirmKeysOnEmpty: true,
            freeInput: true
        });
        var p1 = priceel;
        $(priceel + ' input').on('itemAdded', function (event) {
            setTimeout(
                function () {
                    console.log('$(\'' + p1 + ' .bootstrap-tagsinput input\').val(\'\').focus();');
                    $(p1 + ' .bootstrap-tagsinput input').val('').focus();
                }, 100
            );

        });
    }


    $('tr[data-id="' + id + '"] .ig-main, tr[data-id="' + id + '"] .ig-sheets, tr[data-id="' + id + '"] .ig-pk, tr[data-id="' + id + '"] .ig-p1, tr[data-id="' + id + '"] .ig-p5, tr[data-id="' + id + '"] .ptype').removeAttr('disabled');
    if (isnew || noedit == "false") {
        var priceel = 'tr.item-group[data-id="' + id + '"] .ig-id';
        var str = $(priceel).html();
        var strb = str.replace(/<br>/g, ",");
        if (!isnew) {
            var _price = '<input type="text" value="' + strb + '" data-role="tagsinput"/>';
        } else {
            var _price = '<input type="text" data-role="tagsinput"/>';
        }
        $(priceel).html(_price);
        $(priceel + ' input').tagsinput({
            cancelConfirmKeysOnEmpty: true,
            trimValue: true,
            typeahead: {
                source: self.groupsname
            },
            freeInput: false
        });
        var p2 = priceel;
        $(priceel + ' input').on('itemAdded', function (event) {
            setTimeout(
                function () {
                    $(p2 + ' .bootstrap-tagsinput input').val('').focus();
                }, 100
            );

        });
    }

    if (!ismain) {
        var priceel = 'tr.item-group[data-id="' + id + '"] .ig-image';
        var str = $(priceel).text();
        var strb = str;
        var _price = '<input type="text" class="form-control grouptphimg" data-provide="typeahead" value="' + strb + '"/>';
        $(priceel).html(_price);

        //ptype
        $('tr.item-group[data-id="' + id + '"] input.ptype[value=' + ptype + ']').prop("checked", true);
    }


    var priceel = 'tr.item-group[data-id="' + id + '"] .ig-path';
    var str = $(priceel).text();
    var strb = str.replace(/\.[^.]+$/, "");
    var _price = '<input class="form-control" type="text" value="' + strb + '"/>';
    $(priceel).html(_price);

    if (isnew || noedit == "false") {
        var priceel = 'tr.item-group[data-id="' + id + '"] .ig-name';
        var str = $(priceel).text();
        var _price = '<input class="form-control" type="text" value="' + str + '"/>';
        $(priceel).html(_price);
    }
    $('.grouptphimg').typeahead({source: self.images});
    $('tr.item-group[data-id="' + id + '"] .ig-edit, tr[data-id="' + id + '"] .ig-del').hide(0);
    $('tr.item-group[data-id="' + id + '"] .ig-save, tr[data-id="' + id + '"] .ig-cancel').show(0);

}
Generator.prototype.clickGroupAdd = function () {
    $('.item-new').remove();
    var _html = "";
    id = self.config.groups.length;
    _html += '<tr class="item-group item-new" data-id="' + id + '">';
    _html += '<td><input type="checkbox"';
    _html += ' class="ig-gen" style="display:none;"></td>';
    _html += '<td class="ig-id"></td>';
    _html += '<td class="ig-name"><input class="form-control" type="text" placeholder="Имя группы"></td>';
    _html += '<td class="ig-path"><input class="form-control" type="text" placeholder="Имя файла"></td>';
    _html += '<td class="ig-price"></td>';
    _html += '<td><label class="lblcb"><input type="checkbox" class="ig-main">В общей</label>';
    _html += '<label class="lblcb"><input type="checkbox" class="ig-sheets">Делить на листы</label>';
    _html += '<label class="lblcb"><input type="checkbox" class="ig-pk">Последняя коробка</label>';
    _html += '<label class="lblcb"><input type="checkbox" class="ig-p1">1000</label>';
    _html += '<label class="lblcb"><input type="checkbox" class="ig-p5">5000</label>';
    _html += '<label class="radio-inline"><input type="radio" name="ptype' + id + '" class="ptype" value="0"> Обычная</label>';
    _html += '<label class="radio-inline"><input type="radio" name="ptype' + id + '" class="ptype" value="1"> Спец цена</label>';
    _html += '<label class="radio-inline"><input type="radio" name="ptype' + id + '" class="ptype" value="2"> Валюта</label>';
    _html += '</td>';
    _html += '<td class="ig-image"></td>';
    _html += '<td><button class="btn btn-default btn-ig ig-edit"><i class="fa fa-pencil" aria-hidden="true"></i></button>';
    _html += '<button class="btn btn-danger btn-ig ig-del"><i class="fa fa-trash" aria-hidden="true"></i></button>';
    _html += '<button class="btn btn-success btn-ig ig-save"><i class="fa fa-save" aria-hidden="true"></i></button>';
    _html += '<button class="btn btn-default btn-ig ig-cancel"><i class="fa fa-ban" aria-hidden="true"></i></button></td>';
    _html += '</tr>';
    $("#groups tbody").append(_html);
    self.refreshButtons();
    self.clickGroupEdit(id);
}
Generator.prototype.clickGroupDelete = function (id) {
    var groupname = $('tr.item-group[data-id="' + id + '"] .ig-name').text();
    var deltext = prompt("Вы уверены, что хотите удалить группу \"" + groupname + "\"?\r\nЧтобы подтвердить действие введите \"удалить\" маленькими русскими буквами в поле ниже", '');
    if (deltext == "удалить") {
        self.config.groups.splice(id, 1);
        self.saveSettingsAndCallBack(function (data) {
            self.groupsPrint(data.config.groups);
        });
    } else {

    }
}
Generator.prototype.clickGroupCancel = function (id) {
    if ($('tr[data-id="' + id + '"].item-new').length > 0) {
        console.log('cancel new group');
        $('.item-new').remove();
    } else {
        var priceel = 'tr[data-id="' + id + '"] .ig-price';
        $(priceel + ' input').tagsinput('destroy');
        var priceel = 'tr[data-id="' + id + '"] .ig-id';
        $(priceel + ' input').tagsinput('destroy');
        $('tr[data-id="' + id + '"]').html($('#grouprow' + id + ' tr').html());
        $('#grouprow' + id).remove();
        $('tr[data-id="' + id + '"] .ig-main, tr[data-id="' + id + '"] .ig-sheets, tr[data-id="' + id + '"] .ig-pk, tr[data-id="' + id + '"] .ig-p1, tr[data-id="' + id + '"] .ig-p5, tr[data-id="' + id + '"] .ptype').attr('disabled', 'disabled');
        console.log('deleteBuffer', id);
    }

    self.refreshButtons();
}
Generator.prototype.clickGroupSave = function (id) {
    var isnew = false;
    var noedit = "false";
    if ($('tr[data-id="' + id + '"].item-new').length > 0) {
        isnew = true;
        console.log('save new group');
    } else {
        noedit = self.config.groups[id].noedit;
    }
    var ismain = false;
    if ($('tr.item-group[data-id="' + id + '"] .ig-id').text() == "all") {
        ismain = true;
    }
    if (noedit == "false") {
        var priceel = 'tr.item-group[data-id="' + id + '"] .ig-id';
        var _price = $(priceel + ' input').tagsinput('items');
        _price.shift();
        if (_price[0].length < 1) {
            console.log('ig-id empty');
            $(priceel + ' input').tagsinput('destroy');
            var _price = '<input type="text"/>';
            $(priceel).html(_price);
            $(priceel + ' input').tagsinput({
                cancelConfirmKeysOnEmpty: true,
                trimValue: true,
                typeahead: {
                    source: self.groupsname
                },
                freeInput: false
            });
            return false;
        }
    }
    if (noedit == "false") {
        var priceel = 'tr.item-group[data-id="' + id + '"] .ig-name';
        var _price = $(priceel + ' input').val();
        if (_price.length < 2) {
            $(priceel + ' input').focus();
            return false;
        }
    }

    var priceel = 'tr.item-group[data-id="' + id + '"] .ig-path';
    var _price = $(priceel + ' input').val();
    if (_price.length < 1) {
        $(priceel + ' input').focus();
        return false;
    }

    if (!ismain) {
        var priceel = 'tr.item-group[data-id="' + id + '"] .ig-price';
        var _price = $(priceel + ' input').tagsinput('items');
        _price.shift();
        if (_price[0].length < 1) {
            $(priceel + ' input').tagsinput('destroy');
            var _price = '<input type="text"/>';
            $(priceel).html(_price);
            $(priceel + ' input').tagsinput({
                trimValue: true,
                typeahead: {
                    source: self.files
                },
                cancelConfirmKeysOnEmpty: true,
                freeInput: true
            });
            return false;
        }
        var str = _price[0].join(',');
        var strb = str.replace(/,/g, "<br>");
        $(priceel + ' input').tagsinput('destroy');
        $(priceel).html(strb);
    } else {
        var str = '';
    }
    if (isnew) {
        self.config.groups[id] = new Object();
        self.config.groups[id].generate = "false";
        self.config.groups[id].noedit = "false";
        var priceel = 'tr[data-id="' + id + '"] .ig-gen';
        $(priceel).show(0);
    }
    self.config.groups[id].price = str;

    if (noedit == "false") {
        var priceel = 'tr.item-group[data-id="' + id + '"] .ig-id';
        var _price = $(priceel + ' input').tagsinput('items');
        _price.shift();
        $(priceel + ' input').tagsinput('destroy');
        var str = _price[1].join(',');
        var strb = str.replace(/,/g, "<br>");
        $(priceel).html(strb);
        self.config.groups[id].id = str;
    }
    self.config.groups[id].main = $('tr.item-group[data-id="' + id + '"] .ig-main').is(':checked');
    self.config.groups[id].sheets = $('tr.item-group[data-id="' + id + '"] .ig-sheets').is(':checked');
    self.config.groups[id].pk = $('tr.item-group[data-id="' + id + '"] .ig-pk').is(':checked');
    self.config.groups[id].p1 = $('tr.item-group[data-id="' + id + '"] .ig-p1').is(':checked');
    self.config.groups[id].p5 = $('tr.item-group[data-id="' + id + '"] .ig-p5').is(':checked');
    self.config.groups[id].ptype = $('tr.item-group[data-id="' + id + '"] input.ptype:checked').val();
    $('tr[data-id="' + id + '"] .ig-main, tr[data-id="' + id + '"] .ig-sheets, tr[data-id="' + id + '"] .ig-pk, tr[data-id="' + id + '"] .ig-p1, tr[data-id="' + id + '"] .ig-p5, tr[data-id="' + id + '"] .ptype').attr('disabled', 'disabled');
    var priceel = 'tr[data-id="' + id + '"] .ig-path';
    var _price = $(priceel + ' input').val().trim();
    self.config.groups[id].path = _price;
    _price += '.' + self.config.files.extension;
    $(priceel).html(_price);
    if (!ismain) {
        var priceel = 'tr[data-id="' + id + '"] .ig-image';
        var _price = $(priceel + ' input').val().trim();
        self.config.groups[id].image = _price;
        $(priceel).html(_price);
    }

    if (noedit == "false") {
        var priceel = 'tr.item-group[data-id="' + id + '"] .ig-name';
        var _price = $(priceel + ' input').val();
        $(priceel).html(_price);
        self.config.groups[id].name = _price;
    }
    if (!isnew) {
        $('#grouprow' + id).remove();
        console.log('deleteBuffer', id);
    } else {
        $('tr.item-group[data-id="' + id + '"]').removeClass('item-new');
    }
    self.saveSettings();
    $('tr.item-group[data-id="' + id + '"] .ig-save, tr.item-group[data-id="' + id + '"] .ig-cancel').hide(0);
    $('tr.item-group[data-id="' + id + '"] .ig-edit, tr.item-group[data-id="' + id + '"] .ig-del').show(0);

}
Generator.prototype.groupsPrint = function (groups) {
    $(".item-group").remove();
    $.each(groups, function (i, g) {
        var _html = "";
        _html += '<tr class="item-group" data-id="' + i + '">';
        _html += '<td><input type="checkbox"';
        if (g.generate == "true") {
            _html += ' checked="checked"';
        }
        _html += ' class="ig-gen"></td>';
        var strb = g.id.replace(/,/g, "<br>");
        _html += '<td class="ig-id">' + strb + '</td>';
        _html += '<td class="ig-name">' + g.name + '</td>';
        _html += '<td class="ig-path">' + g.path + '.' + self.config.files.extension + '</td>';
        var strb = g.price.replace(/,/g, "<br>");
        if (g.id == "all") {
            strb = "";
        }
        _html += '<td class="ig-price">' + strb + '</td>';
        _html += '<td><label class="lblcb" ';
        if (g.id == "all") {
            _html += ' style="display: none;"';
        }
        _html += '><input type="checkbox" disabled="disabled"';
        if (g.main == "true") {
            _html += ' checked="checked"';
        }
        _html += ' class="ig-main">В общей</label>';
        _html += '<label class="lblcb" ';
        if (g.id == "all") {
            _html += ' style="display: none;"';
        }
        _html += '><input type="checkbox" disabled="disabled"';
        if (g.sheets == "true") {
            _html += ' checked="checked"';
        }
        _html += ' class="ig-sheets">Делить на листы</label>';
        _html += '<label class="lblcb" ';
        if (g.id == "all") {
            _html += ' style="display: none;"';
        }
        _html += '><input type="checkbox" disabled="disabled"';
        if (g.pk == "true") {
            _html += ' checked="checked"';
        }

        _html += ' class="ig-pk">Последняя коробка</label>';
        _html += '<label class="lblcb" ';
        if (g.id == "all") {
            _html += ' style="display: none;"';
        }
        _html += '><input type="checkbox" disabled="disabled"';
        if (g.p1 == "true") {
            _html += ' checked="checked"';
        }

        _html += ' class="ig-p1">1000</label>';
        _html += '<label class="lblcb" ';
        if (g.id == "all") {
            _html += ' style="display: none;"';
        }
        _html += '><input type="checkbox" disabled="disabled"';
        if (g.p5 == "true") {
            _html += ' checked="checked"';
        }

        _html += ' class="ig-p5">5000</label>';
        if (!isset(g.ptype)) {
            g.ptype = 0;
        }

        _html += '<label class="radio-inline"';
        if (g.id == "all") {
            _html += ' style="display: none;"';
        }
        _html += '><input type="radio" name="ptype' + i + '" class="ptype" disabled="disabled" value="0"';
        if (g.ptype == 0) {
            _html += ' checked="checked"';
        }
        _html += '> Обычная</label>';
        _html += '<label class="radio-inline"';
        if (g.id == "all") {
            _html += ' style="display: none;"><input type="radio" ';
        }
        _html += '><input type="radio" name="ptype' + i + '" class="ptype" disabled="disabled" value="1"';
        if (g.ptype == 1) {
            _html += ' checked="checked"';
        }
        _html += '> Спец цена</label>';
        _html += '<label class="radio-inline"';
        if (g.id == "all") {
            _html += ' style="display: none;"';
        }
        _html += '><input type="radio" name="ptype' + i + '" class="ptype" disabled="disabled" value="2"';
        if (g.ptype == 2) {
            _html += ' checked="checked"';
        }
        _html += '> Валюта</label>';
        _html += '</td>';
        _html += '<td class="ig-image">' + g.image + '</td>';
        _html += '<td><button class="btn btn-default btn-ig ig-edit"><i class="fa fa-pencil" aria-hidden="true"></i></button>';
        if (g.noedit == "false") {
            _html += '<button class="btn btn-danger btn-ig ig-del"><i class="fa fa-trash" aria-hidden="true"></i></button>';
        }
        _html += '<button class="btn btn-success btn-ig ig-save"><i class="fa fa-save" aria-hidden="true"></i></button>';
        _html += '<button class="btn btn-default btn-ig ig-cancel"><i class="fa fa-ban" aria-hidden="true"></i></button></td>';
        _html += '</tr>';
        $("#groups tbody").append(_html);
    });

    self.refreshButtons();

}

/*
	{
        "name": "Фарфор",
        "id": "8000,39819",
        "generate": "true",
        "path": "фарфор",
        "noedit": "false",
        "image": "123.bmp",
        "price": "Посуда\\Price.txt,ХТ\\Price.txt",
        "main": "true"
      }
*/