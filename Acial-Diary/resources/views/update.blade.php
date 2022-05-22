@extends('layouts.diary')

<!-- @section('css') -->

@section('title')
記事編集
@endsection

@section('content')
<!--記事作成-->



<div class="row">
    <form action="{{route('edit-post-proc')}}" class="form-horizontal form-label-left" method="post" enctype="multipart/form-data">
        @csrf
        @include('form')

        <div class="form-group form-inline w-75 my-5 mx-auto">
            <a href="{{ route('home') }}" class="btn btn-svg" style="margin:0px">

                <span>一覧に戻る</span>
            </a>
            <button type='submit' class="btn btn-svg">
                <span>編集完了</span>
            </button>
        </div>
    </form>
</div>


@endsection