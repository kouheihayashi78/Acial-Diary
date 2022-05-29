@extends('layouts.diary')

@section('content')


<div class="row">
    <form class="form-horizontal form-label-left" method="post">
        <div id="contents" class="cp_iptxt">
            <label for="title" class="d-flex">タイトル</label>
            {!! $form['title'] !!}
            @error('title')
            <span id="name-error" class="error invalid-feedback" style="display:block">{{$message}}</span>
            @enderror
            <span class="focus_line"></span>
        </div>

        <div id="contents" class="cp_iptxt">
            <label for="body" class="d-flex">記事内容</label>
            {!! $form['body'] !!}
            @error('body')
            <span id="name-error" class="error invalid-feedback" style="display:block">{{$message}}</span>
            @enderror
            <span class="focus_line"></span>
        </div>

        <div id="contents" class="cp_iptxt">
            <label for="img" class="col-sm-2 col-form-label">画像</label>
            <div class="col-sm-10">
                @foreach( $rows as $row )
                @if (!empty($row->img))
                <img src="{{$row->img}}" style="object-fit: cover; width: 300px; height: 300px;">
                @endif
                @error('img')
                <span id="name-error" class="error invalid-feedback" style="display:block">{{$message}}</span>
                @enderror
                @endforeach
            </div>
        </div>
    </form>


    <div class="form-group form-inline w-75 my-5 mx-auto">
        <a href="{{ route('my-post') }}" class="btn btn-secondary">一覧に戻る</a>
        @if( Auth::check() )

        @foreach( $rows as $row )
        <a href="{{ route('edit-post', $row->id) }}" class="btn btn-success">編集</a>
        <a href="{{ route('delete-post', $row->id) }}" class="btn btn-danger">削除</a>
        @endforeach

        @endif
    </div>
</div>
@endsection