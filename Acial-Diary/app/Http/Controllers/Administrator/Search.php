<?php

namespace App\Http\Controllers\Administrator;

use App\Http\TakemiLibs\InterfaceSearch;
use App\Http\Controllers\SimpleForm;
use \Form;

/**
 * 管理画面の検索条件入力フォーム
 */
class Search
{

    public function build(array $data = []): array
    {
        $form = [];
        $opt = ['class' => 'form_control'];

        //お知らせタイトル
        $form['name'] = Form::text('name', $data['name'] ?? '', $opt);

        $form['email'] = Form::text('email', $data['email'] ?? '', $opt);

        $form['active'] = SimpleForm::radio('active', $data['active'] ?? '', __('define.active'), []);


        return $form;
    }
}
