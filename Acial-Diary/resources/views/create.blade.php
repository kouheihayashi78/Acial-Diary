@extends('layouts.app')

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




    @section('scripts')
    @parent
    <script lang="text/javascript">
        $(function() {
            if ($('input[name=type]:checked').val() == 2) {
                $('div[id=url]').show();
                $('div[id=content]').hide();
            }
            $('input[name=type]').change(function() {
                let val = $(this).val();
                if (val == 1) {
                    $('div[id=url]').hide();
                    $('div[id=content]').show();
                } else {
                    $('div[id=url]').show();
                    $('div[id=content]').hide();
                }
            });
        });
    </script>
    @endsection
    <div class="" style="margin:10px; display: flex;justify-content: center;align-items: center;">
        <a href="" class="btn btn-svg" style="margin:0px">
            <svg>
                <rect x="2" y="2" rx="0" fill="none" width=200 height="50"></rect>
            </svg>
            <span>一覧に戻る</span>
        </a>
        <button type="submit" class="btn btn-svg">
            <svg>
                <rect x="2" y="2" rx="0" fill="none" width=200 height="50"></rect>
            </svg>
            <span>新規作成</span>
        </button>
    </div>
</form>

@endsection