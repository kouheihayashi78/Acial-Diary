///////////////////////////////////////
// いいね！用のJavaScript
///////////////////////////////////////

$(function () {
    // いいね！がクリックされたとき
    $('.js-like').click(function () {
        const this_obj = $(this);
        const post_id = $(this).data('post-id');
        const like_id = this_obj.data('like-id');
        const like_count_obj = this_obj.parent().find('.like-counter');
        let like_count = Number(like_count_obj.html());

        $.ajax({
            headers: { //HTTPヘッダ情報をヘッダ名と値のマップで記述
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/post/likes',
            type: 'POST',
            data: {
                'post_id': post_id
            },
            timeout: 10000
        })// いいね！が成功
            .done((data) => {
                this_obj.toggleClass('loved'); //likedクラスのON/OFF切り替え。
                $('.js-like .like-counter').html(data.post_likes_count);
            }).fail((data) => {
                alert('処理中にエラーが発生しました。');
                console.log(data);
            }
            );
    })
})