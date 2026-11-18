<?php

namespace Theme\Homzen\Forms;

use Theme\Homzen\Http\Requests\ThemeCustomScriptsRequest;
use Botble\Base\Facades\Assets;
use Botble\Theme\Facades\Theme;

class ThemeCustomScriptsForm
{
    public function __construct()
    {
        add_action('theme_loaded', function () {
            Theme::asset()
                ->usePath()
                ->add('custom-css', 'css/custom.css', [], [], filemtime(theme_path('public/css/custom.css')));
        }, 120);
    }
}