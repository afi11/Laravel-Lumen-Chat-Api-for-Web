<?php

function public_path()
{
    return rtrim(app()->basePath('public'));
}