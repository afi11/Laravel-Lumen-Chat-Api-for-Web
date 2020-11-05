<?php

function FindCharacter($karakter,$cari)
{
    if(strpos($karakter,$cari)){
        return true;
    }else{
        return false;
    }
}