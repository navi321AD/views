@extends('layouts.admin')

@section('title')
    Гнезда &rarr; Яйцо: {{ $egg->name }}
@endsection

@section('content-header')
    <h1>{{ $egg->name }}<small>{{ str_limit($egg->description, 50) }}</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">Админ</a></li>
        <li><a href="{{ route('admin.nests') }}">Гнезда</a></li>
        <li><a href="{{ route('admin.nests.view', $egg->nest->id) }}">{{ $egg->nest->name }}</a></li>
        <li class="active">{{ $egg->name }}</li>
    </ol>
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="nav-tabs-custom nav-tabs-floating">
            <ul class="nav nav-tabs">
                <li class="active"><a href="{{ route('admin.nests.egg.view', $egg->id) }}">Конфигурация</a></li>
                <li><a href="{{ route('admin.nests.egg.variables', $egg->id) }}">Переменные</a></li>
                <li><a href="{{ route('admin.nests.egg.scripts', $egg->id) }}">Скрипт установки</a></li>
            </ul>
        </div>
    </div>
</div>
<form action="{{ route('admin.nests.egg.view', $egg->id) }}" enctype="multipart/form-data" method="POST">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-danger">
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-8">
                            <div class="form-group no-margin-bottom">
                                <label for="pName" class="control-label">Файл яйца</label>
                                <div>
                                    <input type="file" name="import_file" class="form-control" style="border: 0;margin-left:-10px;" />
                                    <p class="text-muted small no-margin-bottom">Если вы хотите заменить настройки этого Яйца, загрузив новый JSON файл, выберите его здесь и нажмите "Обновить яйцо". Это не изменит существующие строки запуска или образы Docker для существующих серверов.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-4">
                            {!! csrf_field() !!}
                            <button type="submit" name="_method" value="PUT" class="btn btn-sm btn-danger pull-right">Обновить яйцо</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<form action="{{ route('admin.nests.egg.view', $egg->id) }}" method="POST">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Конфигурация</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="pName" class="control-label">Название <span class="field-required"></span></label>
                                <input type="text" id="pName" name="name" value="{{ $egg->name }}" class="form-control" />
                                <p class="text-muted small">Простое, читаемое человеком название для идентификации этого Яйца.</p>
                            </div>
                            <div class="form-group">
                                <label for="pUuid" class="control-label">UUID</label>
                                <input type="text" id="pUuid" readonly value="{{ $egg->uuid }}" class="form-control" />
                                <p class="text-muted small">Это глобально уникальный идентификатор для этого Яйца, который Демон использует для идентификации.</p>
                            </div>
                            <div class="form-group">
                                <label for="pAuthor" class="control-label">Автор</label>
                                <input type="text" id="pAuthor" readonly value="{{ $egg->author }}" class="form-control" />
                                <p class="text-muted small">Автор этой версии Яйца. Загрузка новой конфигурации Яйца от другого автора изменит это значение.</p>
                            </div>
                            <div class="form-group">
                                <label for="pDockerImage" class="control-label">Образы Docker <span class="field-required"></span></label>
                                <textarea id="pDockerImages" name="docker_images" class="form-control" rows="4">{{ implode(PHP_EOL, $images) }}</textarea>
                                <p class="text-muted small">
                                    Образы docker, доступные для серверов, использующих это яйцо. Введите один на строку. Пользователи
                                    смогут выбирать из этого списка образов, если предоставлено несколько значений.
                                    Дополнительно, можно указать отображаемое имя, предварив образ именем, за которым следует символ "|", и затем URL образа. Пример: <code>Имя для отображения|ghcr.io/my/egg</code>
                                </p>
                            </div>
                            <div class="form-group">
                                <div class="checkbox checkbox-primary no-margin-bottom">
                                    <input id="pForceOutgoingIp" name="force_outgoing_ip" type="checkbox" value="1" @if($egg->force_outgoing_ip) checked @endif />
                                    <label for="pForceOutgoingIp" class="strong">Принудительный исходящий IP</label>
                                    <p class="text-muted small">
                                        Принуждает весь исходящий сетевой трафик иметь исходящий IP, преобразованный в IP основного выделенного IP сервера.
                                        Требуется для некоторых игр, чтобы они работали правильно, когда узел имеет несколько публичных IP адресов.
                                        <br>
                                        <strong>
                                            Включение этой опции отключит внутреннюю сеть для серверов, использующих это яйцо,
                                            что приведет к невозможности внутреннего доступа к другим серверам на том же узле.
                                        </strong>
                                    </p>
                                </div>
                            </div>

                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="pDescription" class="control-label">Описание</label>
                                <textarea id="pDescription" name="description" class="form-control" rows="8">{{ $egg->description }}</textarea>
                                <p class="text-muted small">Описание этого Яйца, которое будет отображаться на панели по мере необходимости.</p>
                            </div>
                            <div class="form-group">
                                <label for="pStartup" class="control-label">Команда запуска <span class="field-required"></span></label>
                                <textarea id="pStartup" name="startup" class="form-control" rows="8">{{ $egg->startup }}</textarea>
                                <p class="text-muted small">Команда запуска, которая должна использоваться для новых серверов, использующих это Яйцо.</p>
                            </div>
                            <div class="form-group">
                                <label for="pConfigFeatures" class="control-label">Функции</label>
                                <div>
                                    <select class="form-control" name="features[]" id="pConfigFeatures" multiple>
                                        @foreach(($egg->features ?? []) as $feature)
                                            <option value="{{ $feature }}" selected>{{ $feature }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-muted small">Дополнительные функции, принадлежащие яйцу. Полезно для настройки дополнительных модификаций панели.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Управление процессами</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="alert alert-warning">
                                <p>Следующие параметры конфигурации не должны изменяться, если вы не понимаете, как работает эта система. Если они будут изменены неправильно, это может привести к сбоям демона.</p>
                                <p>Все поля обязательны для заполнения, если вы не выберете другой вариант из выпадающего списка "Копировать настройки из", в этом случае поля могут быть оставлены пустыми для использования значений из того Яйца.</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="pConfigFrom" class="form-label">Копировать настройки из</label>
                                <select name="config_from" id="pConfigFrom" class="form-control">
                                    <option value="">Нет</option>
                                    @foreach($egg->nest->eggs as $o)
                                        <option value="{{ $o->id }}" {{ ($egg->config_from !== $o->id) ?: 'selected' }}>{{ $o->name }} &mdash; {{ str_limit($o->description, 50) }}</option>
                                    @endforeach
                                </select>
                                <p class="text-muted small">Если вы хотите скопировать настройки из существующего Яйца, выберите его здесь.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {!! csrf_field() !!}
    <button type="submit" name="_method" value="PUT" class="btn btn-danger pull-right">Обновить яйцо</button>
</form>
@endsection
