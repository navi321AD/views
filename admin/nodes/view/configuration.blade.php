@extends('layouts.admin')

@section('title')
    {{ $node->name }}: Конфигурация
@endsection

@section('content-header')
    <h1>{{ $node->name }}<small>Файл конфигурации вашего демона.</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">Админка</a></li>
        <li><a href="{{ route('admin.nodes') }}">Узлы</a></li>
        <li><a href="{{ route('admin.nodes.view', $node->id) }}">{{ $node->name }}</a></li>
        <li class="active">Конфигурация</li>
    </ol>
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="nav-tabs-custom nav-tabs-floating">
            <ul class="nav nav-tabs">
                <li><a href="{{ route('admin.nodes.view', $node->id) }}">Информация</a></li>
                <li><a href="{{ route('admin.nodes.view.settings', $node->id) }}">Настройки</a></li>
                <li class="active"><a href="{{ route('admin.nodes.view.configuration', $node->id) }}">Конфигурация</a></li>
                <li><a href="{{ route('admin.nodes.view.allocation', $node->id) }}">Выделения</a></li>
                <li><a href="{{ route('admin.nodes.view.servers', $node->id) }}">Серверы</a></li>
            </ul>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-8">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Файл конфигурации</h3>
            </div>
            <div class="box-body">
                <pre class="no-margin">{{ $node->getYamlConfiguration() }}</pre>
            </div>
            <div class="box-footer">
                <p class="no-margin">Этот файл должен быть размещён в корневом каталоге вашего демона (обычно <code>/etc/pterodactyl</code>) в файле с именем <code>config.yml</code>.</p>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Авторазвёртывание</h3>
            </div>
            <div class="box-body">
                <p class="text-muted small">
                    Используйте кнопку ниже, чтобы сгенерировать команду для настройки, которую можно использовать для конфигурации
                    wings на целевом сервере одной командой.
                </p>
            </div>
            <div class="box-footer">
                <button type="button" id="configTokenBtn" class="btn btn-sm btn-default" style="width:100%;">Сгенерировать токен</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer-scripts')
    @parent
    <script>
    $('#configTokenBtn').on('click', function (event) {
        $.ajax({
            method: 'POST',
            url: '{{ route('admin.nodes.view.configuration.token', $node->id) }}',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        }).done(function (data) {
            swal({
                type: 'success',
                title: 'Токен создан.',
                text: '<p>Чтобы автоматически настроить ваш узел, выполните следующую команду:<br /><small><pre>cd /etc/pterodactyl && sudo wings configure --panel-url {{ config('app.url') }} --token ' + data.token + ' --node ' + data.node + '{{ config('app.debug') ? ' --allow-insecure' : '' }}</pre></small></p>',
                html: true
            })
        }).fail(function () {
            swal({
                title: 'Ошибка',
                text: 'Произошла ошибка при создании токена.',
                type: 'error'
            });
        });
    });
    </script>
@endsection
