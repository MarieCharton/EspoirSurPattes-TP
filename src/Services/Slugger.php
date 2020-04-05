<?php
namespace App\Services;

class Slugger

{
    public function slugify($strToConvert){
       return preg_replace( '/[^a-zA-Z0-9]+(?:-[a-zA-Z0-9]+)*/', '-', strtolower(trim(strip_tags($strToConvert))));
    }
}