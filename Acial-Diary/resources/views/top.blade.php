@extends('layouts.diary')
@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">ホーム画面</h1>
        <form role="search" method="GET" class="d-none d-sm-inline-block form-inline ml-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
            @csrf
            <div class="input-group">
                <input type="text" class="form-control border-0 small" name="keyword" placeholder="キーワード検索" aria-label="Search" aria-describedby="basic-addon2">
                <input type="submit" id="searchsubmit" name="btnSearchClear" value="✖️" style="font-size: 23px; top: -3px; right: 15rem; background: white; border: none;">
                <input type="submit" id="searchsubmit" name="btnSearch" value="検索" style="border-top-right-radius: 15px; border-bottom-right-radius: 15px; border: 1px solid;">
            </div>
        </form>
    </div>

</div>

<div id="container" class="wrapper">
    <main>
        <article>
            <div class="top">
                <p>投稿</p>
            </div>
            @if( count($rows) )
            @foreach( $rows as $row )
            <h3>{{ $row->title }}</h3>
            <ul class="meta">
                <li>{{ $row->created_at }}</li>

            </ul>
            <ul class="meta" style="display: flex; align-items: center;">
                <li>
                    @if (!empty($user->icon))
                    <img src="/storage/icons/{{$user->icon}}" class="rounded-circle" style="object-fit: cover; width: 50px; height: 50px;">
                    @else
                    <img src="/images/blank_profile.png" class="rounded-circle" style="object-fit: cover; width: 50px; height: 50px;">
                    @endif
                </li>
                <li><a href="{{ route('post-detail', $row->id) }}">{{ $row->user->name }}さんの投稿</a></li>

            </ul>
            <div style="display: block; text-align: center;">
                <a href="{{route('post-detail', $row->id)}}" data-lightbox="gallery-group">
                    @if (!empty($row->img))
                    <img src="{{$row->img}}" style="object-fit: cover; width: 350px; height: 350px;">

                    @endif
                </a>
                <p class="text">
                    {{ $row->body }}
                </p>
            </div>


            <div class="readmore"><a href="{{route('post-detail', $row->id)}}">READ MORE</a></div>
            @endforeach
        </article>
        
        @else
        <span>記事がありません</span>
        @endif
    </main>


</div>

@endsection