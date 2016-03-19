<?php

namespace App;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Crypt;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the forms for this user.
     */
    public function forms()
    {
        return $this->belongsToMany('App\Form');
    }


    /**
     * Get the user's Google access token.
     *
     * @param  string  $value
     * @return array
     */
    public function getGoogleTokenAttribute($value)
    {
        try {
            $decrypted = Crypt::decrypt($value);
            return json_decode($decrypted);
        } catch (DecryptException $e) {
            return json_decode('{"success":false}');
        }

    }

    /**
     * Set the user's Google access toke.
     *
     * @param  mixed  $value
     * @return string
     */
    public function setGoogleTokenAttribute($value)
    {
        $this->attributes['google_token'] = Crypt::encrypt(json_encode($value));
    }

}
