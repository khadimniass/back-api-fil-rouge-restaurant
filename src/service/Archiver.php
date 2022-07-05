<?php

namespace App\service;

class Archiver
{
    /**
     * @return void
     * @this function can help
     */
    public static function archiver($data)
    {
        if ($data->getEtat()==1) {
            $data->setEtat(0);
        }else{
            $data->setEtat(1);
        }
    }

}