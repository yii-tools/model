<?php

declare(strict_types=1);

namespace Yii\Model\Tests\TestSupport\FormModel;

use Yii\Model\AbstractFormModel;

final class Login extends AbstractFormModel
{
    private string|null $login = null;
    private string|null $password = null;
    private bool $rememberMe = false;

    public function getLogin(): string|null
    {
        return $this->login;
    }

    public function getPassword(): string|null
    {
        return $this->password;
    }

    public function getRememberMe(): bool
    {
        return $this->rememberMe;
    }

    public function login(string $value): void
    {
        $this->login = $value;
    }

    public function password(string $value): void
    {
        $this->password = $value;
    }

    public function rememberMe(bool $value): void
    {
        $this->rememberMe = $value;
    }

    public function getFormName(): string
    {
        return 'Login';
    }

    public function getHints(): array
    {
        return [
            'login' => 'Write your id or email.',
            'password' => 'Write your password.',
        ];
    }

    public function getLabels(): array
    {
        return [
            'login' => 'Login:',
            'password' => 'Password:',
            'rememberMe' => 'remember Me:',
        ];
    }

    public function getPlaceholders(): array
    {
        return [
            'login' => 'Write Username or Email.',
            'password' => 'Write Password.',
        ];
    }
}
