<?php

if (!function_exists('getLanguages')) {
    function getLanguages()
    {
        // return ['ar', 'en'];
        return ['ar']; // FOR NOW
    }
}

if (!function_exists('inLanguages')) {
    function inLanguages($lang = null)
    {
        if ($lang) {
            return in_array($lang, getLanguages());
        }
        return false;
    }
}
