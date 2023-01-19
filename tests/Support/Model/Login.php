<?php

declare(strict_types=1);

namespace Yii\Model\Tests\Support\Model;

use Yii\Model\AbstractModel;
use Yii\Model\Tests\Support\HasHint;

final class Login extends AbstractModel
{
    use HasHint;

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
        ];
    }
}
