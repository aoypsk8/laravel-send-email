<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Store;
use App\Helpers\MyHelper;
use App\Models\StoreUser;
use Illuminate\Http\Request;
use App\Http\Requests\StoreRequest;
use App\Services\UploadFileService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class StoreController extends Controller
{
    public function addStore(StoreRequest $request)
    { 
        
        // $discount = MyHelper::calDiscount(100000);
        // return $discount;

        $filename = resolve(UploadFileService::class)->uploadFileStoreLogo($request);


        $addStore = new Store();
        $addStore->name = $request->name;
        $addStore->email_contract = $request->email_contract;
        $addStore->phone_number = $request->phone_number;
        $addStore->address = $request->address;
        $addStore->logo = $filename;
        $addStore->save();

        $addUser = new User();
        $addUser->name = $request->name;
        $addUser->email = $request->email;
        $addUser->password = $request->password;
        $addUser->save();

        $profilename = resolve(UploadFileService::class)->uploadFileUserProfile($request);

        $addStoreUser = new StoreUser();
        $addStoreUser->store_id = $addStore->id;
        $addStoreUser->user_id = $addUser->id;
        $addStoreUser->profile = $profilename;
        $addStoreUser->save();

        $getRoleStoreAdmin = Role::where('name', 'admin')->first();
        $addUser->attachRole($getRoleStoreAdmin);

        return response()->json([
            'message' => __('response.success')
        ]);
    }

    public function listStore(Request $request)
    {
        $listStores = Store::select(
            'stores.*',
        )
        ->paginate($request->per_page);
        $listStores->transform(function ($item) {
            $item['store_user'] =  StoreUser::select(
                'user.id',
                'user.name'
            )->join(
                'users as user',
                'user.id',
                'store_users.user_id'
            )->where('store_id', $item['id'])->get();

            return $item->format();
        });
        return response()->json([
            'stores' => $listStores
        ]);

    }
    public function editStore(Request $request)
    {
         
        $editStore = Store::find($request->id);
        $editStore->name = $request->name;
        $editStore->email_contract = $request->email_contract;
        $editStore->phone_number = $request->phone_number;
        $editStore->address = $request->address;
        if (isset($request['logo'])) { 
            resolve(UploadFileService::class)->editUploadFileStore($request,$editStore);
        }
        $editStore->save();

        return response()->json([
            'message' => 'ອັບເດດສຳເລັດ.'
        ]);
    }

    public function deleteStore(StoreRequest $request)
    {
        $deleteStore = Store::find($request->id);
        $deleteStore->delete();
    }
}
