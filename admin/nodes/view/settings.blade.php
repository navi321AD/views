{{-- Pterodactyl - Panel --}}
{{-- Copyright (c) 2015 - 2017 Dane Everitt <dane@daneeveritt.com> --}}

{{-- This software is licensed under the terms of the MIT license. --}}
{{-- https://opensource.org/licenses/MIT --}}
@extends('layouts.admin')

@section('title')
    {{ $node->name }}: Настройки
@endsection

@section('content-header')
    <h1>{{ $node->name }}<small>Конфигурируйте настройки вашей ноды.</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">Администрация</a></li>
        <li><a href="{{ route('admin.nodes') }}">Ноды</a></li>
        <li><a href="{{ route('admin.nodes.view', $node->id) }}">{{ $node->name }}</a></li>
        <li class="active">Настройки</li>
    </ol>
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="nav-tabs-custom nav-tabs-floating">
            <ul class="nav nav-tabs">
                <li><a href="{{ route('admin.nodes.view', $node->id) }}">Информация</a></li>
                <li class="active"><a href="{{ route('admin.nodes.view.settings', $node->id) }}">Настройки</a></li>
                <li><a href="{{ route('admin.nodes.view.configuration', $node->id) }}">Конфигурация</a></li>
                <li><a href="{{ route('admin.nodes.view.allocation', $node->id) }}">Расположения</a></li>
                <li><a href="{{ route('admin.nodes.view.servers', $node->id) }}">Сервера</a></li>
            </ul>
        </div>
    </div>
</div>
<form action="{{ route('admin.nodes.view.settings', $node->id) }}" method="POST">
    <div class="row">
        <div class="col-sm-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Настройки</h3>
                </div>
                <div class="box-body row">
                    <div class="form-group col-xs-12">
                        <label for="name" class="control-label">Название ноды</label>
                        <div>
                            <input type="text" autocomplete="off" name="name" class="form-control" value="{{ old('name', $node->name) }}" />
                            <p class="text-muted"><small>Разрешенные знаки: <code>a-zA-Z0-9_.-</code> и <code>[Пробел]</code> (минимум 1, максимум 100 заков).</small></p>
                        </div>
                    </div>
                    <div class="form-group col-xs-12">
                        <label for="description" class="control-label">Описание</label>
                        <div>
                            <textarea name="description" id="description" rows="4" class="form-control">{{ $node->description }}</textarea>
                        </div>
                    </div>
                    <div class="form-group col-xs-12">
                        <label for="name" class="control-label">Расположение</label>
                        <div>
                            <select name="location_id" class="form-control">
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}" {{ (old('location_id', $node->location_id) === $location->id) ? 'selected' : '' }}>{{ $location->long }} ({{ $location->short }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-xs-12">
                        <label for="public" class="control-label">Разрешить автоматическое распределение расположения? <sup><a data-toggle="tooltip" data-placement="top" title="Разрешить автоматическое выделение порта на ноде?">?</a></sup></label>
                        <div>
                            <input type="radio" name="public" value="1" {{ (old('public', $node->public)) ? 'checked' : '' }} id="public_1" checked> <label for="public_1" style="padding-left:5px;">Да</label><br />
                            <input type="radio" name="public" value="0" {{ (old('public', $node->public)) ? '' : 'checked' }} id="public_0"> <label for="public_0" style="padding-left:5px;">Да</label>
                        </div>
                    </div>
                    <div class="form-group col-xs-12">
                        <label for="fqdn" class="control-label">Полное доменное имя</label>
                        <div>
                            <input type="text" autocomplete="off" name="fqdn" class="form-control" value="{{ old('fqdn', $node->fqdn) }}" />
                        </div>
                        <p class="text-muted"><small>Пожалуйста введите доменное имя. Например: <code>node.example.com</code> будет использоваться для подключения к демону. IP-адрес может использоваться только в том случае, если вы не используете SSL для этой ноды.
                                <a tabindex="0" data-toggle="popover" data-trigger="focus" title="Зачем мне нужно полное доменное имя?" data-content="Для обеспечения безопасности связи между вашим сервером и этой нодой мы используем протокол SSL. Мы не можем сгенерировать SSL-сертификат для IP-адресов, и поэтому вам необходимо будет предоставить полное доменное имя.">Почему?</a>
                            </small></p>
                    </div>
                    <div class="form-group col-xs-12">
                        <label class="form-label"><span class="label label-warning"><i class="fa fa-power-off"></i></span> Связь по протоколу SSL</label>
                        <div>
                            <div class="radio radio-success radio-inline">
                                <input type="radio" id="pSSLTrue" value="https" name="scheme" {{ (old('scheme', $node->scheme) === 'https') ? 'checked' : '' }}>
                                <label for="pSSLTrue"> Использовать SSL подключение</label>
                            </div>
                            <div class="radio radio-danger radio-inline">
                                <input type="radio" id="pSSLFalse" value="http" name="scheme" {{ (old('scheme', $node->scheme) !== 'https') ? 'checked' : '' }}>
                                <label for="pSSLFalse"> Использовать HTTP подключение</label>
                            </div>
                        </div>
                        <p class="text-muted small">В большинстве случаев вам следует выбрать использование SSL-соединения. Если вы используете IP-адрес или вообще не хотите использовать SSL, выберите HTTP-соединение.</p>
                    </div>
                    <div class="form-group col-xs-12">
                        <label class="form-label"><span class="label label-warning"><i class="fa fa-power-off"></i></span> Использовать прокси</label>
                        <div>
                            <div class="radio radio-success radio-inline">
                                <input type="radio" id="pProxyFalse" value="0" name="behind_proxy" {{ (old('behind_proxy', $node->behind_proxy) == false) ? 'checked' : '' }}>
                                <label for="pProxyFalse"> Не использовать прокси </label>
                            </div>
                            <div class="radio radio-info radio-inline">
                                <input type="radio" id="pProxyTrue" value="1" name="behind_proxy" {{ (old('behind_proxy', $node->behind_proxy) == true) ? 'checked' : '' }}>
                                <label for="pProxyTrue"> Использовать прокси </label>
                            </div>
                        </div>
                        <p class="text-muted small">Если вы запускаете демона за прокси-сервером, таким как Cloudflare, выберите этот параметр, чтобы демон пропустил поиск сертификатов при загрузке.</p>
                    </div>
                    <div class="form-group col-xs-12">
                        <label class="form-label"><span class="label label-warning"><i class="fa fa-wrench"></i></span> Режим технического обслуживания</label>
                        <div>
                            <div class="radio radio-success radio-inline">
                                <input type="radio" id="pMaintenanceFalse" value="0" name="maintenance_mode" {{ (old('behind_proxy', $node->maintenance_mode) == false) ? 'checked' : '' }}>
                                <label for="pMaintenanceFalse"> Выключен</label>
                            </div>
                            <div class="radio radio-warning radio-inline">
                                <input type="radio" id="pMaintenanceTrue" value="1" name="maintenance_mode" {{ (old('behind_proxy', $node->maintenance_mode) == true) ? 'checked' : '' }}>
                                <label for="pMaintenanceTrue"> Включен</label>
                            </div>
                        </div>
                        <p class="text-muted small">Если нода помечена как "Находящаяся на обслуживании", пользователи не смогут получить доступ к серверам, находящимся на ней.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Лимит ноды</h3>
                </div>
                <div class="box-body row">
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="form-group col-xs-6">
                                <label for="memory" class="control-label">Выделение ОЗУ</label>
                                <div class="input-group">
                                    <input type="text" name="memory" class="form-control" data-multiplicator="true" value="{{ old('memory', $node->memory) }}"/>
                                    <span class="input-group-addon">МегаБайт</span>
                                </div>
                            </div>
                            <div class="form-group col-xs-6">
                                <label for="memory_overallocate" class="control-label">Перераспределение</label>
                                <div class="input-group">
                                    <input type="text" name="memory_overallocate" class="form-control" value="{{ old('memory_overallocate', $node->memory_overallocate) }}"/>
                                    <span class="input-group-addon">%</span>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted small">Введите общий объем памяти, доступной на этой ноде для распределения между серверами. Вы также можете указать процент, который может позволить выделить больше, чем установленная память.</p>
                    </div>
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="form-group col-xs-6">
                                <label for="disk" class="control-label">Место на диске</label>
                                <div class="input-group">
                                    <input type="text" name="disk" class="form-control" data-multiplicator="true" value="{{ old('disk', $node->disk) }}"/>
                                    <span class="input-group-addon">МегаБайт</span>
                                </div>
                            </div>
                            <div class="form-group col-xs-6">
                                <label for="disk_overallocate" class="control-label">Перераспределение</label>
                                <div class="input-group">
                                    <input type="text" name="disk_overallocate" class="form-control" value="{{ old('disk_overallocate', $node->disk_overallocate) }}"/>
                                    <span class="input-group-addon">%</span>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted small">Введите общий объем дискового пространства, доступного на этой ноде для выделения сервера. Вы также можете указать процент, который будет определять объем дискового пространства, превышающий установленный допустимый предел.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Генеральная кофигурация</h3>
                </div>
                <div class="box-body row">
                    <div class="form-group col-xs-12">
                        <label for="disk_overallocate" class="control-label">Максимальный загружаемый размер через веб-интерфейс</label>
                        <div class="input-group">
                            <input type="text" name="upload_size" class="form-control" value="{{ old('upload_size', $node->upload_size) }}"/>
                            <span class="input-group-addon">МегаБайт</span>
                        </div>
                        <p class="text-muted"><small>Введите максимальный размер файлов, которые могут быть загружены через веб-интерфейс.</small></p>
                    </div>
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="daemonListen" class="control-label"><span class="label label-warning"><i class="fa fa-power-off"></i></span>Порт Daemon'а</label>
                                <div>
                                    <input type="text" name="daemonListen" class="form-control" value="{{ old('daemonListen', $node->daemonListen) }}"/>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="daemonSFTP" class="control-label"><span class="label label-warning"><i class="fa fa-power-off"></i></span>SFTP Порт Daemon'а</label>
                                <div>
                                    <input type="text" name="daemonSFTP" class="form-control" value="{{ old('daemonSFTP', $node->daemonSFTP) }}"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <p class="text-muted"><small>Демон запускает свой собственный контейнер управления SFTP и не использует процесс SSHd на главном физическом сервере. <strong>Не используйте тот же порт, который вы назначили для процесса SSH вашего физического сервера.</strong></small></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Сохранение настроек</h3>
                </div>
                <div class="box-body row">
                    <div class="form-group col-sm-6">
                        <div>
                            <input type="checkbox" name="reset_secret" id="reset_secret" /> <label for="reset_secret" class="control-label">Сбросить Главный ключ Daemon'a</label>
                        </div>
                        <p class="text-muted"><small>Сброс главного ключа Daemon'a приведет к аннулированию любого запроса, поступающего от старого ключа. Этот ключ используется для всех конфиденциальных операций с демоном, включая создание и удаление сервера. Мы рекомендуем регулярно менять этот ключ в целях безопасности.</small></p>
                    </div>
                </div>
                <div class="box-footer">
                    {!! method_field('PATCH') !!}
                    {!! csrf_field() !!}
                    <button type="submit" class="btn btn-primary pull-right">Сохранение настроек</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('footer-scripts')
    @parent
    <script>
    $('[data-toggle="popover"]').popover({
        placement: 'auto'
    });
    $('select[name="location_id"]').select2();
    </script>
@endsection