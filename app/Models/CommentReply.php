<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommentReply extends Model
{
    //
    public function like()
    {
        $this->like += 1;
    }
}
