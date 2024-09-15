@extends('layouts.admin')

@section('title')
    {{ $node->name }}: Настройки
@endsection

@section('content-header')
    <h1>{{ $node->name }}<small>Настройте параметры узла.</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">Админ</a></li>
        <li><a href="{{ route('admin.nodes') }}">Узлы</a></li>
        <li><a href="{{ route('admin.nodes.view', $node->id) }}">{{ $node->name }}</a></li>
        <li class="active">Настройки</li>
    </ol>
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="nav-tabs-custom nav-tabs-floating">
            <ul class="nav nav-tabs">
                <li><a href="{{ route('admin.nodes.view', $node->id) }}">О проекте</a></li>
                <li class="active"><a href="{{ route('admin.nodes.view.settings', $node->id) }}">Настройки</a></li>
                <li><a href="{{ route('admin.nodes.view.configuration', $node->id) }}">Конфигурация</a></li>
                <li><a href="{{ route('admin.nodes.view.allocation', $node->id) }}">Распределение</a></li>
                <li><a href="{{ route('admin.nodes.view.servers', $node->id) }}">Серверы</a></li>
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
                        <label for="name" class="control-label">Имя узла</label>
                        <div>
                            <input type="text" autocomplete="off" name="name" class="form-control" value="{{ old('name', $node->name) }}" />
                            <p class="text-muted"><small>Ограничения символов: <code>a-zA-Z0-9_.-</code> и <code>[Пробел]</code> (мин 1, макс 100 символов).</small></p>
                        </div>
                    </div>
                    <div class="form-group col-xs-12">
                        <label for="description" class="control-label">Описание</label>
                        <div>
                            <textarea name="description" id="description" rows="4" class="form-control">{{ $node->description }}</textarea>
                        </div>
                    </div>
                    <div class="form-group col-xs-12">
                        <label for="name" class="control-label">Местоположение</label>
                        <div>
                            <select name="location_id" class="form-control">
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}" {{ (old('location_id', $node->location_id) === $location->id) ? 'selected' : '' }}>{{ $location->long }} ({{ $location->short }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-xs-12">
                        <label for="public" class="control-label">Разрешить автоматическое распределение <sup><a data-toggle="tooltip" data-placement="top" title="Разрешить автоматическое распределение на этот узел?">?</a></sup></label>
                        <div>
                            <input type="radio" name="public" value="1" {{ (old('public', $node->public)) ? 'checked' : '' }} id="public_1" checked> <label for="public_1" style="padding-left:5px;">Да</label><br />
                            <input type="radio" name="public" value="0" {{ (old('public', $node->public)) ? '' : 'checked' }} id="public_0"> <label for="public_0" style="padding-left:5px;">Нет</label>
                        </div>
                    </div>
                    <div class="form-group col-xs-12">
                        <label for="fqdn" class="control-label">Полное доменное имя</label>
                        <div>
                            <input type="text" autocomplete="off" name="fqdn" class="form-control" value="{{ old('fqdn', $node->fqdn) }}" />
                        </div>
                        <p class="text-muted"><small>Введите доменное имя (например, <code>node.example.com</code>), которое будет использоваться для подключения к демону. IP-адрес может использоваться только в случае, если вы не используете SSL для этого узла.
                                <a tabindex="0" data-toggle="popover" data-trigger="focus" title="Зачем мне FQDN?" data-content="Для обеспечения безопасности связи между вашим сервером и этим узлом мы используем SSL. Мы не можем сгенерировать SSL-сертификат для IP-адресов, и поэтому вам нужно предоставить FQDN.">Зачем?</a>
                            </small></p>
                    </div>
                    <div class="form-group col-xs-12">
                        <label class="form-label"><span class="label label-warning"><i class="fa fa-power-off"></i></span> Связь через SSL</label>
                        <div>
                            <div class="radio radio-success radio-inline">
                                <input type="radio" id="pSSLTrue" value="https" name="scheme" {{ (old('scheme', $node->scheme) === 'https') ? 'checked' : '' }}>
                                <label for="pSSLTrue"> Использовать SSL-соединение</label>
                            </div>
                            <div class="radio radio-danger radio-inline">
                                <input type="radio" id="pSSLFalse" value="http" name="scheme" {{ (old('scheme', $node->scheme) !== 'https') ? 'checked' : '' }}>
                                <label for="pSSLFalse"> Использовать HTTP-соединение</label>
                            </div>
                        </div>
                        <p class="text-muted small">В большинстве случаев вам следует выбрать использование SSL-соединения. Если используете IP-адрес или не хотите использовать SSL, выберите HTTP-соединение.</p>
                    </div>
                    <div class="form-group col-xs-12">
                        <label class="form-label"><span class="label label-warning"><i class="fa fa-power-off"></i></span> За прокси</label>
                        <div>
                            <div class="radio radio-success radio-inline">
                                <input type="radio" id="pProxyFalse" value="0" name="behind_proxy" {{ (old('behind_proxy', $node->behind_proxy) == false) ? 'checked' : '' }}>
                                <label for="pProxyFalse"> Не за прокси </label>
                            </div>
                            <div class="radio radio-info radio-inline">
                                <input type="radio" id="pProxyTrue" value="1" name="behind_proxy" {{ (old('behind_proxy', $node->behind_proxy) == true) ? 'checked' : '' }}>
                                <label for="pProxyTrue"> За прокси </label>
                            </div>
                        </div>
                        <p class="text-muted small">Если вы запускаете демон за прокси, таким как Cloudflare, выберите этот вариант, чтобы демон пропускал поиск сертификатов при запуске.</p>
                    </div>
                    <div class="form-group col-xs-12">
                        <label class="form-label"><span class="label label-warning"><i class="fa fa-wrench"></i></span> Режим обслуживания</label>
                        <div>
                            <div class="radio radio-success radio-inline">
                                <input type="radio" id="pMaintenanceFalse" value="0" name="maintenance_mode" {{ (old('maintenance_mode', $node->maintenance_mode) == false) ? 'checked' : '' }}>
                                <label for="pMaintenanceFalse"> Отключено</label>
                            </div>
                            <div class="radio radio-warning radio-inline">
                                <input type="radio" id="pMaintenanceTrue" value="1" name="maintenance_mode" {{ (old('maintenance_mode', $node->maintenance_mode) == true) ? 'checked' : '' }}>
                                <label for="pMaintenanceTrue"> Включено</label>
                            </div>
                        </div>
                        <p class="text-muted small">Если узел помечен как «На обслуживании», пользователи не смогут получить доступ к серверам, которые находятся на этом узле.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Ограничения распределения</h3>
                </div>
                <div class="box-body row">
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="form-group col-xs-12">
                                <label for="memory" class="control-label">Максимум памяти (МБ)</label>
                                <div>
                                    <input type="text" autocomplete="off" name="memory" class="form-control" value="{{ old('memory', $node->memory) }}" />
                                    <p class="text-muted"><small>Введите максимальное количество памяти в мегабайтах, которое этот узел может выделить для серверов.</small></p>
                                </div>
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="disk" class="control-label">Максимум диска (ГБ)</label>
                                <div>
                                    <input type="text" autocomplete="off" name="disk" class="form-control" value="{{ old('disk', $node->disk) }}" />
                                    <p class="text-muted"><small>Введите максимальный объём диска в гигабайтах, который этот узел может выделить для серверов.</small></p>
                                </div>
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="cpu" class="control-label">Максимум процессора (%)</label>
                                <div>
                                    <input type="text" autocomplete="off" name="cpu" class="form-control" value="{{ old('cpu', $node->cpu) }}" />
                                    <p class="text-muted"><small>Введите максимальный процент использования процессора, который этот узел может выделить для серверов.</small></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary">Сохранить изменения</button>
        <a href="{{ route('admin.nodes.view', $node->id) }}" class="btn btn-default">Отмена</a>
    </div>
    {{ csrf_field() }}
</form>
@endsection
