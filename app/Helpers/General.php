<?php


use App\Models\Language;
use Illuminate\Support\Facades\Config;

/*
 *  get all active languages.
 * */
function get_languages()
{
    return Language::active()->selection()->get();
}

/*
 *  get the default language for the app.
 * */
function get_default_lang()
{
    return Config::get('app.locale');
}

/*
 *  uploads any image.
 * */
function uploadImage($folder, $image)
{
    $image->store('/', $folder);
    $filename = $image->hashName();
    return 'images/' . $folder . '/' . $filename;
}
