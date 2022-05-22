@extends('layouts.diary')

@section('content')


<div class="row">
    <form class="form-horizontal form-label-left" method="post">
        @include('form')
    </form>
    <div class="form-group form-inline w-75 my-5 mx-auto">
        <a href="{{ route('home') }}" class="btn btn-secondary">一覧に戻る</a>
        @if( Auth::check() )
        
        @foreach( $rows as $row )
        <a href="{{ route('edit-post', $row->id) }}" class="btn btn-success">編集</a>
        <a href="{{ route('delete-post', $row->id) }}" class="btn btn-danger">削除</a>
        @endforeach
        
        @endif
    </div>
</div>
@endsection