<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAccount extends Model
{
    use HasFactory;

    protected $table = 'user_accounts';
    protected $primaryKey = 'payment_account_id';
    public $timestamps = true;

    protected $fillable = [ 'bank_name', 'account_number'];

}
