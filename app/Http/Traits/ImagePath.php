<?php
namespace Traits;
/**
 * Created by PhpStorm.
 * User: ken
 * Date: 1/12/18
 * Time: 12:55 PM
 */
trait ImagePath
{
    function log($msg) {
        echo '<pre>';
        echo date('Y-m-d h:i:s') . ':' . '(' . __CLASS__ .  ') ' . $msg . '<br/>';
        echo '</pre>';
    }
}