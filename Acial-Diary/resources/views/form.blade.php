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
    <div class="col-sm-10">
        @if (!empty($rows))
        @if (!empty($rows->img))
        <img src="{{$rows->img}}" style="object-fit: cover; width: 300px; height: 300px;">
        @else
        <img src="/images/blank_profile.png" style="object-fit: cover; width: 300px; height: 300px;">
        @endif
        @else
        {!! $form['img'] !!}
        @endif
        @error('img')
        <span id="name-error" class="error invalid-feedback" style="display:block">{{$message}}</span>
        @enderror
    </div>
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