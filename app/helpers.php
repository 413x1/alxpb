<?php

if (!function_exists('isActiveRoute')) {
    function isActiveRoute($routeName, $output = 'active') {
        return request()->routeIs($routeName) ? $output : '';
    }
}
