<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function format()
    {
        return [
            'store' => [
                'id' => $this->id,
                'name' => $this->name,
                'email_contract' => $this->email_contract,
                'address' => $this->address,
                'phone_number' => $this->phone_number,
                'url_logo' => config('services.file_path.storeLogo').$this->logo,
                'url_logo1' => '127.0.0.1:8000/strage/images/Store/Logo' . $this->logo,
            ],
            'user' => [
                'id' => $this->store_user
            ]

        ];
    }

    public function store_user()
    {
        return $this->hasMany(StoreUser::class);
    }
}
