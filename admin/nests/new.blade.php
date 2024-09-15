@extends('layouts.admin')

@section('title')
    Новое гнездо
@endsection

@section('content-header')
    <h1>Новое гнездо<small>Настройте новое гнездо для развертывания на всех узлах.</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">Админ</a></li>
        <li><a href="{{ route('admin.nests') }}">Гнезда</a></li>
        <li class="active">Новое</li>
    </ol>
@endsection

@section('content')
<form action="{{ route('admin.nests.new') }}" method="POST">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Новое гнездо</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label class="control-label">Название</label>
                        <div>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" />
                            <p class="text-muted"><small>Это должно быть описательное название категории, которое охватывает все яйца внутри гнезда.</small></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Описание</label>
                        <div>
                            <textarea name="description" class="form-control" rows="6">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    {!! csrf_field() !!}
                    <button type="submit" class="btn btn-primary pull-right">Сохранить</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
