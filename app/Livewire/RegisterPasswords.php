<?php

namespace App\Livewire;

use ZxcvbnPhp\Zxcvbn;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Contracts\View\View;

class RegisterPasswords extends Component
{
    public string $password = '';

    public string $passwordConfirmation = '';

    public int $strengthScore = 0;

    public array $strengthLevels = [
        1 => 'Weak',
        2 => 'Fair',
        3 => 'Good',
        4 => 'Strong',
    ];

    public function updatedPassword($value): void
    {
        $this->strengthScore = (new Zxcvbn())->passwordStrength($value)['score'];
    }

    public function generatePassword(): void
    {
        $password = Str::password(12);

        $this->setPasswords($password);
    }

    protected function setPasswords($value): void
    {
        $this->password = $value;
        $this->passwordConfirmation = $value;
        $this->updatedPassword($value);
    }

    public function render(): View
    {
        return view('livewire.register-passwords');
    }
}
