<?php
 
namespace App\Filament\Pages\Auth;
 
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\LoginPage as BaseEditProfile;
use Filament\Pages\Auth\Login as LoginProfile;
use Afsakar\FilamentOtpLogin\Filament\Pages\Login as OtpLogin;
use Illuminate\Contracts\Support\Htmlable;

class LoginPage extends OtpLogin
{

    public function getHeading(): string | Htmlable
    {
        return 'CITY-AI';
    }
}