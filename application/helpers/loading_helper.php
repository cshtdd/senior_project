<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( !function_exists('loading_img'))
{
    function loading_img()
    {
        return img(array(
            'src' => 'img/ajax-loader.gif',
            'id' => 'loading_img'
            )
        );
    }
}

?>