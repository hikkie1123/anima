<?php

namespace App;

use Laravel\Socialite\Contracts\User as ProviderUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\User;

class SocialAccountService
{
    public function find(ProviderUser $providerUser, $provider)
    {
        $account = LinkedSocialAccount::whereProviderName($provider)
                   ->whereProviderId($providerUser->id)
                   ->first();

        if($account == null){
            $user = null;
        }else{
            $user = $account->user;
        }

        return $user;
    }

    public function linkSocialAccount(ProviderUser $providerUser, $provider){
        \Log::info("SocialAccountService : linkSocialAccount() : Start");
        $user_id = Auth::id();
        $user = User::find($user_id);
        $token = null;
        $token_secret = null;
        if($provider == "twitter"){
            $token = encrypt($providerUser->token);
            $token_secret = encrypt($providerUser->tokenSecret);
        }

        DB::beginTransaction();
        try {
            $result = $user->accounts()->create([
                'provider_id'   => $providerUser->id,
                'provider_name' => $provider,
                'token' => $token,
                'token_secret' => $token_secret,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            \Log::emergency("SocialAccountService : linkSocialAccount() : Failed Link Account! : user_id = ".$user_id.", provider = ".$provider);
            \Log::emergency("Message : ".$e->getMessage());
            \Log::emergency("Codee : ".$e->getCode());

            return redirect()->to('/');
        }

        return $result;
    }

    public function unLinkSocialAccount(ProviderUser $providerUser, $provider){
        $user_id = Auth::id();
        $user = User::find($user_id);

        $result = $user->accounts()->where('provider_name', $provider)->delete();

        return $result;
    }
}
