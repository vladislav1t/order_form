<?php

function validate_russian_phone_number ($tel)
{
    $tel = trim((string)$tel);
    $tel = preg_replace('#[^0-9+]+#uis', '', $tel);
    if (!preg_match('#^(?:\\+?7|8|)(.*?)$#uis', $tel, $m)) return false;
    $tel = '+7' . preg_replace('#[^0-9]+#uis', '', $m[1]);
    if (!preg_match('#^\\+7[0-9]{10}$#uis', $tel, $m)) return false;
    return $tel;
}

function empty_field ()
{

}