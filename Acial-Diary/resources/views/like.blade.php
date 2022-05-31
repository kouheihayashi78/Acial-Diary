@extends('layouts.diary')
@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">ホーム画面</h1>

    </div>

</div>

<div id="container" class="wrapper">
    <main>
        <article>
            <div class="top">
                <p>自分の投稿</p>
            </div>
            @if( count($rows) )
            @foreach( $rows as $row )
            <h3>{{ $row->title }}</h3>
            <ul class="meta">
                <li>{{ $row->created_at }}</li>

            </ul>
            <ul class="meta" style="display: flex; align-items: center;">
                <li>
                    @if (!empty($row->user->icon))
                    <img src="/storage/icons/{{$row->user->icon}}" class="rounded-circle" style="object-fit: cover; width: 50px; height: 50px;">
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
        {{ $rows->links() }}
    </main>


</div>

@endsection