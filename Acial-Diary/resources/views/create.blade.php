@extends('layouts.form')

<!-- @section('css') -->

@section('title')
新規記事作成
@endsection

@section('content')
<!--記事作成-->

<div class="top">
    <p>記事登録</p>
</div>


<form action="{{route('create-post-proc')}}" class="form-horizontal form-label-left" method="post" enctype="multipart/form-data">
    @csrf
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
        <label for="img" class="d-flex">画像</label>
        {!! $form['img'] !!}
        @error('img')
        <span id="name-error" class="error invalid-feedback" style="display:block">{{$message}}</span>
        @enderror

    </div>


    <div class="d-flex justify-content-center align-items-center mt-5">
        <a href="{{ route('home') }}" class="btn btn-svg">
            <span>一覧に戻る</span>
        </a>
        <button type="submit" class="btn btn-svg">
            <span>新規作成</span>
        </button>
    </div>
</form>

@endsection