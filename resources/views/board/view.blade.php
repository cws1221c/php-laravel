@extends('layout/master')

@section('content')
    <h1>{{$data->title}}</h1>

    <div class="card">
        <div class="card-header">
            <span>{{$data->user()->first()->name}}</span>
            <span>{{$data->crated_at}}</span>
        </div>
            <div class="card-body">
                <?php
                    $images = explode('/', $data->file);
                ?>
                @foreach($images as $image)
                    <img src="/image/{{ $image }}" alt="">
                @endforeach
                {{ $data->content }}
            </div>

    </div>
@endsection