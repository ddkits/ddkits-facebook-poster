<?php

namespace Ddkits\Ddkitsfacebook\Models;

use Illuminate\Database\Eloquent\Model;

class Ddkitsfacebook extends Model
{
    protected $table = 'ddkits_facebooks';

    protected $fillable = [
        'title',
        'appId',
        'status',
        'appSecret',
        'pageId',
        'userAccessToken',
        'foreverPageAccessToken',
        'tags',
        'categories',
    ];
}
