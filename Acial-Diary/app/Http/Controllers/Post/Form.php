<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \Form as FormF;
use Illuminate\Support\Facades\Validator;

class Form extends Controller
{
    public function buildCreate(array $data)
    {
        $user = Auth::id();

        $form = [];
        $opt = ['class' => 'form-control', 'autocomplete' => 'off'];
        $form['id'] = FormF::hidden('id', $data['id'] ?? '', $opt);
        $form['name'] = FormF::text('name', $data['name'] ?? '', $opt);
        $form['title'] = FormF::text('title', $data['title'] ?? '', $opt);

        $form['body'] = FormF::textarea('body', $data['body'] ?? '', $opt);
        $form['img'] = FormF::file('img', $opt, $data['img'] ?? '');
        $form['user_id'] = FormF::hidden('user_id', $user ?? '', $opt);
        $form['publish'] = FormF::text('publish', $data['publish'] ?? '', $opt);
        return $form;
    }

    public function postValidates(array $data)
    {
        validator::make($data, [
            'title' => 'required',
            'body' => 'required',
            'img' => 'nullable',
        ])->validate();
    }

    /**
     * フォームの値のHTMLを生成する
     * @param array $data
     * @return void
     */
    public function getHtml(array $data = [])
    {

        $data['title'] = "<pre>{$data['title']}<pre>";
        $data['body'] = "<pre>{$data['title']}<pre>";
        $data['img'] = "<pre>{$data['img']}<pre>";


        return $data;
    }
}
