<?php
session_start();
$a = file('.secret.txt');
$true_password = trim($a[0]);
if (!isset($_SESSION['ep'])) {
    $entered_password = '';
} else {
    $entered_password = $_SESSION['ep'];
}
if ($true_password != $entered_password) {
    header('location: login.php');
}
?><!DOCTYPE html>
<html lang="ru">
<head>
    <title>Генератор презентаций</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/bootstrap-tagsinput.css">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="container">
    <h2>Генератор презентаций</h2>
    <div class="cont">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#phome" role="tab">Генерировать</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#pgroups" role="tab">Презентации</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#pmarks" role="tab">Отметки</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#pgrp" role="tab">Группы</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#pprc" role="tab">Дубли</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#pout" role="tab">Настройки</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/get" target="_blank">Скачать презентации</a>
            </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content container">
            <div class="tab-pane active" id="phome" role="tabpanel">
                <div class="row">
                    <div class="col-md-6">
                        <p class="pt">Процесс выполнения:</p>
                        <div id="workgroup">

                        </div>
                    </div>
                    <div class="col-md-6">
                        <p class="pt">Лог операций:</p>
                        <div id="log"></div>
                    </div>
                </div>
                <p><label for="aftLink"><input type="checkbox" name="aftLink" id="aftLink">&nbsp;Проверить на
                        дубли</label></p>
                <button class="btn btn-success" id="generator-start">Начать</button>
            </div>

            <div class="tab-pane" id="pgroups" role="tabpanel">
                <table id="groups" class="table table-bordered table-hover">
                    <tr class="header">
                        <th>Вкл</th>
                        <th>Группы</th>
                        <th>Название</th>
                        <th>Название файла</th>
                        <th>Файлы прайса</th>
                        <th>Опции</th>
                        <th>Картинка</th>
                        <th>Действия</th>
                    </tr>
                </table>
                <button class="btn btn-success" id="add-group"><i class="fa fa-plus" aria-hidden="true"></i> Добавить
                    презентацию
                </button>
            </div>

            <div class="tab-pane" id="pmarks" role="tabpanel">
                <table id="marks" class="table table-bordered table-hover">
                    <tr class="header">
                        <th>Вкл</th>
                        <th>Название</th>
                        <th>Файл Excel</th>
                        <th>Условие</th>
                        <th>Цены</th>
                        <th>Презентации</th>
                        <th>Расчет цены</th>
                        <th>Картинки</th>
                        <th>+Описание</th>
                    </tr>
                </table>
                <button class="btn btn-success" id="add-mark"><i class="fa fa-plus" aria-hidden="true"></i> Добавить
                    отметку
                </button>
            </div>

            <div class="tab-pane" id="pgrp" role="tabpanel">
                <table id="grp" class="table table-bordered table-hover">
                    <tr class="header">
                        <th>Название</th>
                        <th>Список идентификаторов</th>
                        <th>Показывать название</th>
                        <th>Действия</th>
                    </tr>
                </table>
                <button class="btn btn-success" id="add-grp"><i class="fa fa-plus" aria-hidden="true"></i> Добавить
                    группу
                </button>
            </div>

            <div class="tab-pane" id="pout" role="tabpanel">

                <div class="s-set-bl">

                    <div class="form-group row">
                        <label for="s-headertext" class="col-3 col-form-label">Текст в шапке презентации</label>
                        <div class="col-9">
                            <textarea class="form-control" rows=5 id="s-headertext"></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="s-creator" class="col-3 col-form-label">Автор презентации</label>
                        <div class="col-9">
                            <input type="text" class="form-control" id="s-creator">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="s-colorheader" class="col-3 col-form-label">Фон шапки</label>
                        <div class="col-9">
                            <input type="color" class="form-control" id="s-colorheader">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="s-colortitle" class="col-3 col-form-label">Фон заголовков</label>
                        <div class="col-9">
                            <input type="color" class="form-control" id="s-colortitle">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="s-colorgrouptext" class="col-3 col-form-label">Цвет текста заголовков</label>
                        <div class="col-9">
                            <input type="color" class="form-control" id="s-colorgrouptext">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="s-fontheadername" class="col-3 col-form-label">Шрифт в шапке</label>
                        <div class="col-9">
                            <select id="s-fontheadername" class="form-control">
                                <option value="Arial" style="font-family: Arial, Helvetica, sans-serif;">Arial</option>
                                <option style="font-family: 'Arial Black', Gadget, sans-serif;" value="Arial Black">
                                    Arial Black
                                </option>
                                <option style="font-family: 'Comic Sans MS', cursive;" value="Comic Sans MS">Comic Sans
                                    MS
                                </option>
                                <option style="font-family: 'Courier New', Courier, monospace;" value="Courier New">
                                    Courier New
                                </option>
                                <option style="font-family: Georgia, serif;" value="Georgia">Georgia</option>
                                <option style="font-family: Impact,Charcoal, sans-serif;" value="Impact">Impact</option>
                                <option style="font-family: Tahoma, Geneva, sans-serif;" value="Tahoma">Tahoma</option>
                                <option style="font-family: 'Times New Roman', Times, serif;" value="Times New Roman">
                                    Times New Roman
                                </option>
                                <option style="font-family: Verdana, Geneva, sans-serif;" value="Verdana">Verdana
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="s-fontheadersize" class="col-3 col-form-label">Размер шрифта в шапке</label>
                        <div class="col-9">
                            <select id="s-fontheadersize" class="form-control">
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="14">14</option>
                                <option value="16">16</option>
                                <option value="18">18</option>
                                <option value="20">20</option>
                                <option value="22">22</option>
                                <option value="24">24</option>
                                <option value="26">26</option>
                                <option value="28">28</option>
                                <option value="36">36</option>
                                <option value="48">48</option>
                                <option value="72">72</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="s-colorcena" class="col-3 col-form-label">Цвет надписи Цена</label>
                        <div class="col-9">
                            <input type="color" class="form-control" id="s-colorcena">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="s-fontcolname" class="col-3 col-form-label">Шрифт товаров</label>
                        <div class="col-9">
                            <select id="s-fontcolname" class="form-control">
                                <option value="Arial" style="font-family: Arial, Helvetica, sans-serif;">Arial</option>
                                <option style="font-family: 'Arial Black', Gadget, sans-serif;" value="Arial Black">
                                    Arial Black
                                </option>
                                <option style="font-family: 'Comic Sans MS', cursive;" value="Comic Sans MS">Comic Sans
                                    MS
                                </option>
                                <option style="font-family: 'Courier New', Courier, monospace;" value="Courier New">
                                    Courier New
                                </option>
                                <option style="font-family: Georgia, serif;" value="Georgia">Georgia</option>
                                <option style="font-family: Impact,Charcoal, sans-serif;" value="Impact">Impact</option>
                                <option style="font-family: Tahoma, Geneva, sans-serif;" value="Tahoma">Tahoma</option>
                                <option style="font-family: 'Times New Roman', Times, serif;" value="Times New Roman">
                                    Times New Roman
                                </option>
                                <option style="font-family: Verdana, Geneva, sans-serif;" value="Verdana">Verdana
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="s-fontcolsize" class="col-3 col-form-label">Размер шрифта товаров</label>
                        <div class="col-9">
                            <select id="s-fontcolsize" class="form-control">
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="14">14</option>
                                <option value="16">16</option>
                                <option value="18">18</option>
                                <option value="20">20</option>
                                <option value="22">22</option>
                                <option value="24">24</option>
                                <option value="26">26</option>
                                <option value="28">28</option>
                                <option value="36">36</option>
                                <option value="48">48</option>
                                <option value="72">72</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="s-colftext" class="col-3 col-form-label">Цвет текста базовая цена</label>
                        <div class="col-9">
                            <input type="color" class="form-control" id="s-colftext">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="s-colfsize" class="col-3 col-form-label">Размер текста базовая цена</label>
                        <div class="col-9">
                            <select id="s-colfsize" class="form-control">
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="14">14</option>
                                <option value="16">16</option>
                                <option value="18">18</option>
                                <option value="20">20</option>
                                <option value="22">22</option>
                                <option value="24">24</option>
                                <option value="26">26</option>
                                <option value="28">28</option>
                                <option value="36">36</option>
                                <option value="48">48</option>
                                <option value="72">72</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-3"></div>
                        <div class="col-9">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" id="s-colfbold">
                                Жирный текст базовая цена
                            </label>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="s-colfback" class="col-3 col-form-label">Цвет фона базовая цена</label>
                        <div class="col-9">
                            <input type="color" class="form-control" id="s-colfback">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="s-colgtext" class="col-3 col-form-label">Цвет текста цена</label>
                        <div class="col-9">
                            <input type="color" class="form-control" id="s-colgtext">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="s-colgsize" class="col-3 col-form-label">Размер текста цена</label>
                        <div class="col-9">
                            <select id="s-colgsize" class="form-control">
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="14">14</option>
                                <option value="16">16</option>
                                <option value="18">18</option>
                                <option value="20">20</option>
                                <option value="22">22</option>
                                <option value="24">24</option>
                                <option value="26">26</option>
                                <option value="28">28</option>
                                <option value="36">36</option>
                                <option value="48">48</option>
                                <option value="72">72</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-3"></div>
                        <div class="col-9">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" id="s-colgbold">
                                Жирный текст цена
                            </label>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="s-colgback" class="col-3 col-form-label">Цвет фона цена</label>
                        <div class="col-9">
                            <input type="color" class="form-control" id="s-colgback">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="s-colhtext" class="col-3 col-form-label">Цвет текста предоплата</label>
                        <div class="col-9">
                            <input type="color" class="form-control" id="s-colhtext">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="s-colhsize" class="col-3 col-form-label">Размер текста предоплата</label>
                        <div class="col-9">
                            <select id="s-colhsize" class="form-control">
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="14">14</option>
                                <option value="16">16</option>
                                <option value="18">18</option>
                                <option value="20">20</option>
                                <option value="22">22</option>
                                <option value="24">24</option>
                                <option value="26">26</option>
                                <option value="28">28</option>
                                <option value="36">36</option>
                                <option value="48">48</option>
                                <option value="72">72</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-3"></div>
                        <div class="col-9">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" id="s-colhbold">
                                Жирный текст предоплата
                            </label>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="s-colhback" class="col-3 col-form-label">Цвет фона предоплата</label>
                        <div class="col-9">
                            <input type="color" class="form-control" id="s-colhback">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="s-colitext" class="col-3 col-form-label">Цвет текста остаток</label>
                        <div class="col-9">
                            <input type="color" class="form-control" id="s-colitext">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="s-colisize" class="col-3 col-form-label">Размер текста остаток</label>
                        <div class="col-9">
                            <select id="s-colisize" class="form-control">
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="14">14</option>
                                <option value="16">16</option>
                                <option value="18">18</option>
                                <option value="20">20</option>
                                <option value="22">22</option>
                                <option value="24">24</option>
                                <option value="26">26</option>
                                <option value="28">28</option>
                                <option value="36">36</option>
                                <option value="48">48</option>
                                <option value="72">72</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-3"></div>
                        <div class="col-9">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" id="s-colibold">
                                Жирный текст остаток
                            </label>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="s-coliback" class="col-3 col-form-label">Цвет фона остаток</label>
                        <div class="col-9">
                            <input type="color" class="form-control" id="s-coliback">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="s-colfotext" class="col-3 col-form-label">Отметка. Цвет текста базовая цена</label>
                        <div class="col-9">
                            <input type="color" class="form-control" id="s-colfotext">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="s-colfosize" class="col-3 col-form-label">Отметка. Размер текста базовая
                            цена</label>
                        <div class="col-9">
                            <select id="s-colfosize" class="form-control">
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="14">14</option>
                                <option value="16">16</option>
                                <option value="18">18</option>
                                <option value="20">20</option>
                                <option value="22">22</option>
                                <option value="24">24</option>
                                <option value="26">26</option>
                                <option value="28">28</option>
                                <option value="36">36</option>
                                <option value="48">48</option>
                                <option value="72">72</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-3"></div>
                        <div class="col-9">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" id="s-colfobold">
                                Отметка. Жирный текст базовая цена
                            </label>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="s-colfoback" class="col-3 col-form-label">Отметка. Цвет фона базовая цена</label>
                        <div class="col-9">
                            <input type="color" class="form-control" id="s-colfoback">
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="s-colgotext" class="col-3 col-form-label">Отметка. Цвет текста цена</label>
                        <div class="col-9">
                            <input type="color" class="form-control" id="s-colgotext">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="s-colgosize" class="col-3 col-form-label">Отметка. Размер текста цена</label>
                        <div class="col-9">
                            <select id="s-colgosize" class="form-control">
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="14">14</option>
                                <option value="16">16</option>
                                <option value="18">18</option>
                                <option value="20">20</option>
                                <option value="22">22</option>
                                <option value="24">24</option>
                                <option value="26">26</option>
                                <option value="28">28</option>
                                <option value="36">36</option>
                                <option value="48">48</option>
                                <option value="72">72</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-3"></div>
                        <div class="col-9">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" id="s-colgobold">
                                Отметка. Жирный текст цена
                            </label>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="s-colgoback" class="col-3 col-form-label">Отметка. Цвет фона цена</label>
                        <div class="col-9">
                            <input type="color" class="form-control" id="s-colgoback">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="s-colhotext" class="col-3 col-form-label">Отметка. Цвет текста предоплата</label>
                        <div class="col-9">
                            <input type="color" class="form-control" id="s-colhotext">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="s-colhosize" class="col-3 col-form-label">Отметка. Размер текста предоплата</label>
                        <div class="col-9">
                            <select id="s-colhosize" class="form-control">
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="14">14</option>
                                <option value="16">16</option>
                                <option value="18">18</option>
                                <option value="20">20</option>
                                <option value="22">22</option>
                                <option value="24">24</option>
                                <option value="26">26</option>
                                <option value="28">28</option>
                                <option value="36">36</option>
                                <option value="48">48</option>
                                <option value="72">72</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-3"></div>
                        <div class="col-9">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" id="s-colhobold">
                                Отметка. Жирный текст предоплата
                            </label>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="s-colhoback" class="col-3 col-form-label">Отметка. Цвет фона предоплата</label>
                        <div class="col-9">
                            <input type="color" class="form-control" id="s-colhoback">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="s-coliotext" class="col-3 col-form-label">Отметка. Цвет текста остаток</label>
                        <div class="col-9">
                            <input type="color" class="form-control" id="s-coliotext">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="s-coliosize" class="col-3 col-form-label">Отметка. Размер текста остаток</label>
                        <div class="col-9">
                            <select id="s-coliosize" class="form-control">
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="14">14</option>
                                <option value="16">16</option>
                                <option value="18">18</option>
                                <option value="20">20</option>
                                <option value="22">22</option>
                                <option value="24">24</option>
                                <option value="26">26</option>
                                <option value="28">28</option>
                                <option value="36">36</option>
                                <option value="48">48</option>
                                <option value="72">72</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-3"></div>
                        <div class="col-9">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" id="s-coliobold">
                                Отметка. Жирный текст остаток
                            </label>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="s-colioback" class="col-3 col-form-label">Отметка. Цвет фона остаток</label>
                        <div class="col-9">
                            <input type="color" class="form-control" id="s-colioback">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="s-opfontsize" class="col-3 col-form-label">Размер Описание+</label>
                        <div class="col-9">
                            <select id="s-opfontsize" class="form-control">
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="14">14</option>
                                <option value="16">16</option>
                                <option value="18">18</option>
                                <option value="20">20</option>
                                <option value="22">22</option>
                                <option value="24">24</option>
                                <option value="26">26</option>
                                <option value="28">28</option>
                                <option value="36">36</option>
                                <option value="48">48</option>
                                <option value="72">72</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="s-opfontcolor" class="col-3 col-form-label">Цвет Описание+</label>
                        <div class="col-9">
                            <input type="color" class="form-control" id="s-opfontcolor">
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="s-logo" class="col-3 col-form-label">Логотип презентаций</label>
                        <div class="col-9">
                            <input type="text" class="form-control marktphimg" id="s-logo">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="s-pkname" class="col-3 col-form-label">ПК. Название</label>
                        <div class="col-9">
                            <input type="text" class="form-control" id="s-pkname">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="s-pkheader" class="col-3 col-form-label">ПК. Картинка в шапке</label>
                        <div class="col-9">
                            <input type="text" class="form-control marktphimg" id="s-pkheader">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-3"></div>
                        <div class="col-9">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" id="s-pksheets">
                                Последняя коробка разделена на листы
                            </label>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="s-p1name" class="col-3 col-form-label">1000. Название</label>
                        <div class="col-9">
                            <input type="text" class="form-control" id="s-p1name">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="s-p1header" class="col-3 col-form-label">1000. Картинка в шапке</label>
                        <div class="col-9">
                            <input type="text" class="form-control marktphimg" id="s-p1header">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-3"></div>
                        <div class="col-9">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" id="s-p1sheets">
                                1000 разделена на листы
                            </label>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-3"></div>
                        <div class="col-9">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" id="s-p1active">
                                Генерировать 1000
                            </label>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="s-p5name" class="col-3 col-form-label">5000. Название</label>
                        <div class="col-9">
                            <input type="text" class="form-control" id="s-p5name">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="s-p5header" class="col-3 col-form-label">5000. Картинка в шапке</label>
                        <div class="col-9">
                            <input type="text" class="form-control marktphimg" id="s-p5header">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-3"></div>
                        <div class="col-9">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" id="s-p5sheets">
                                5000 разделена на листы
                            </label>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-3"></div>
                        <div class="col-9">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" id="s-p5active">
                                Генерировать 5000
                            </label>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-3"></div>
                        <div class="col-9">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" id="s-grouped">
                                Разделять на группы
                            </label>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-3"></div>
                        <div class="col-9">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" id="s-debug">
                                Режим отладки
                            </label>
                        </div>
                    </div>


                    <button class="btn btn-success" id="save-settings"><i class="fa fa-save" aria-hidden="true"></i>
                        Сохранить
                    </button>
                </div>


            </div>
            <div class="tab-pane" id="pprc" role="tabpanel">
                <table id="links" class="table table-bordered table-hover">
                    <tr class="header">
                        <th>Вкл</th>
                        <th>Путь</th>
                        <th>ПК</th>
                        <th>Действия</th>
                    </tr>
                </table>
                <div class="row" style="margin-bottom: 30px;">
                    <div class="col-4">
                        <span>Путь к результату:</span><input type="text" class="form-control marktphfile" id="linker">
                    </div>
                </div>
                <button class="btn btn-default" id="add-link"><i class="fa fa-plus" aria-hidden="true"></i> Добавить
                    файл
                </button>
                <button class="btn btn-success" id="start-link"><i class="fa fa-play" aria-hidden="true"></i> Убрать
                    дубли
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Marks Edit -->
<div class="modal fade" id="editmark" tabindex="-1" role="dialog" aria-labelledby="markeditlabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="markeditlabel">Редактор отметки</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Отменить">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="mke-id" value="">
                <div class="form-group row">
                    <label for="mke-name" class="col-4 col-form-label">Название отметки</label>
                    <div class="col-8">
                        <input type="text" class="form-control" id="mke-name">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="mke-path" class="col-4 col-form-label">Файл Excel</label>
                    <div class="col-8">
                        <input type="text" class="form-control marktphfile" id="mke-path" data-provide="typeahead">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="mke-col" class="col-4 col-form-label">Колонка истины</label>
                    <div class="col-3">
                        <select class="form-control letsel" id="mke-col">
                            <?php
                            for ($i = 0; $i < 26; $i++) echo '<option value="' . chr($i + 65) . '">' . chr($i + 65) . '</option>';
                            ?>
                        </select>
                    </div>
                    <div class="col-2 center">значение</div>
                    <div class="col-3">
                        <input type="text" class="form-control" id="mke-val">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="mke-code" class="col-6 col-form-label">Колонка с артикулом</label>
                    <div class="col-6">
                        <select class="form-control letsel" id="mke-code">
                            <?php
                            for ($i = 0; $i < 26; $i++) echo '<option value="' . chr($i + 65) . '">' . chr($i + 65) . '</option>';
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <legend class="col-12 col-form-legend">Колонки с ценами:</legend>
                    <label for="mke-pfix" class="col-2 col-form-label">Фикс</label>
                    <div class="col-2">
                        <select class="form-control letsel" id="mke-pfix">
                            <?php
                            for ($i = 0; $i < 26; $i++) echo '<option value="' . chr($i + 65) . '">' . chr($i + 65) . '</option>';
                            ?>
                        </select>
                    </div>
                    <label for="mke-pnew" class="col-2 col-form-label">Новая</label>
                    <div class="col-2">
                        <select class="form-control letsel" id="mke-pnew">
                            <?php
                            for ($i = 0; $i < 26; $i++) echo '<option value="' . chr($i + 65) . '">' . chr($i + 65) . '</option>';
                            ?>
                        </select>
                    </div>
                    <label for="mke-pbase" class="col-2 col-form-label">Базовая</label>
                    <div class="col-2">
                        <select class="form-control letsel" id="mke-pbase">
                            <?php
                            for ($i = 0; $i < 26; $i++) echo '<option value="' . chr($i + 65) . '">' . chr($i + 65) . '</option>';
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <span class="col-3 col-form-label">Вставлять в презентацию:</span>
                    <div class="col-3 form-check">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" id="mke-present-main">
                            Менять в общей
                        </label>
                    </div>
                    <div class="col-3 form-check">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" id="mke-present-self">
                            Создавать свою
                        </label>
                    </div>
                    <div class="col-3 form-check">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" id="mke-present-cut">
                            Вырезать из общей
                        </label>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="mke-cbase" class="col-4 col-form-label">Базовая цена</label>
                    <div class="col-5">
                        <select class="form-control letsel" id="mke-cbase">
                            <option value="default">Брать из Price</option>
                            <option value="minus">Price минус %</option>
                            <option value="excel">Брать из Excel</option>
                        </select>
                    </div>
                    <div class="col-3">
                        <input type="text" class="form-control" id="mke-cbaseval">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="mke-cprice" class="col-4 col-form-label">Ваша цена</label>
                    <div class="col-5">
                        <select class="form-control letsel" id="mke-cprice">
                            <option value="default">Из базовой</option>
                            <option value="fixbase">Фикс из базовой</option>
                            <option value="minus">Базовая минус %</option>
                            <option value="fixminus">Фикс базовая минус %</option>
                            <option value="excel">Фикс из Excel</option>
                        </select>
                    </div>
                    <div class="col-3">
                        <input type="text" class="form-control" id="mke-cpriceval">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="mke-cpreorder" class="col-4 col-form-label">По предоплате</label>
                    <div class="col-5">
                        <select class="form-control letsel" id="mke-cpreorder">
                            <option value="default">Стандартная формула</option>
                            <option value="fixprice">Фикс из Ваша цена</option>
                            <option value="fixminus">Фикс Ваша цена минус %</option>
                        </select>
                    </div>
                    <div class="col-3">
                        <input type="text" class="form-control" id="mke-cpreorderval">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="mke-header" class="col-4 col-form-label">Картинка в шапке</label>
                    <div class="col-8">
                        <input type="text" class="form-control marktphimg" id="mke-header" data-provide="typeahead">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="mke-image" class="col-4 col-form-label">Картинка в колонке</label>
                    <div class="col-8">
                        <input type="text" class="form-control marktphimg" id="mke-image" data-provide="typeahead">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="mke-desc" class="col-4 col-form-label">+Описание</label>
                    <div class="col-2 form-check">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" id="mke-pastedesc">
                            Вкл
                        </label>
                    </div>
                    <div class="col-6">
                        <textarea class="form-control" rows="4" id="mke-desc"></textarea>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
                <button type="button" class="btn btn-primary" id="mke-save">Сохранить</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal Grp Edit -->
<div class="modal fade" id="editgrp" tabindex="-1" role="dialog" aria-labelledby="grpeditlabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="grpeditlabel">Редактор группы</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Отменить">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="gpe-id" value="">
                <div class="form-group row">
                    <label for="gpe-name" class="col-4 col-form-label">Название группы</label>
                    <div class="col-8">
                        <input type="text" class="form-control" id="gpe-name">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="grp-ids" class="col-4 col-form-label">Список идентификаторов</label>
                    <div class="col-8">
                        <input type="text" class="form-control" id="gpe-ids">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="gpe-title" class="col-4 col-form-label">Заголовок</label>
                    <div class="col-8 form-check">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" id="gpe-title">
                            Выводить заголовок в презентации
                        </label>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
                <button type="button" class="btn btn-primary" id="grp-save">Сохранить</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal Link Edit -->
<div class="modal fade" id="editlink" tabindex="-1" role="dialog" aria-labelledby="linkeditlabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="linkeditlabel">Редактор ссылки</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Отменить">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="lke-id" value="">
                <div class="form-group row">
                    <label for="lke-path" class="col-4 col-form-label marktphfile">Путь к файлу</label>
                    <div class="col-8">
                        <input type="text" class="form-control" id="lke-path">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
                <button type="button" class="btn btn-primary" id="link-save">Сохранить</button>
            </div>
        </div>
    </div>
</div>

<div id="preloader">
    <div class="sk-folding-cube">
        <div class="sk-cube1 sk-cube"></div>
        <div class="sk-cube2 sk-cube"></div>
        <div class="sk-cube4 sk-cube"></div>
        <div class="sk-cube3 sk-cube"></div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js"></script>
<script src="https://use.fontawesome.com/6d2a93d693.js"></script>
<script src="assets/bootstrap-tagsinput.js?r=<?= rand() ?>"></script>
<script src="assets/generator.js?r=<?= rand() ?>"></script>
<script src="assets/generator-process.js?r=<?= rand() ?>"></script>
<script src="assets/generator-settings.js?r=<?= rand() ?>"></script>
<script src="assets/generator-groups.js?r=<?= rand() ?>"></script>
<script src="assets/generator-marks.js?r=<?= rand() ?>"></script>
<script src="assets/generator-grp.js?r=<?= rand() ?>"></script>
<script src="assets/generator-links.js?r=<?= rand() ?>"></script>
<script src="assets/app.js?r=<?= rand() ?>"></script>
</body>
</html>