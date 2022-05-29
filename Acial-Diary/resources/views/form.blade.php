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
    <span class="focus_line"></span>
</div>