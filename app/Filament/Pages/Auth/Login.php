<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Checkbox;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;

class Login extends BaseLogin
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
            ]);
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label('Username')
            ->email()
            ->required()
            ->autocomplete()
            ->autofocus()
            ->placeholder('Masukkan username')
            ->extraAttributes([
                'class' => 'form-control',
            ]);
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label('Password')
            ->password()
            ->required()
            ->placeholder('Masukkan password')
            ->extraAttributes([
                'class' => 'form-control',
            ]);
    }

    protected function getRememberFormComponent(): Component
    {
        return Checkbox::make('remember')
            ->label('Ingat saya')
            ->extraAttributes([
                'class' => 'remember-me',
            ]);
    }

    protected function hasFullWidthFormActions(): bool
    {
        return true;
    }

    public function authenticate(): ?LoginResponse
    {
        return parent::authenticate();
    }

    public function getTitle(): string
    {
        return 'HUMAS POLRI';
    }

    public function getHeading(): string
    {
        return 'Login';
    }
} 