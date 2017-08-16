<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    //
    public function read()
    {
        $this->read = 1;
    }
}
