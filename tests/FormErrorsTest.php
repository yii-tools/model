<?php

declare(strict_types=1);

namespace Yii\Model\Tests;

use PHPUnit\Framework\TestCase;
use Yii\Model\Tests\TestSupport\FormModel\Login;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class FormErrorsTest extends TestCase
{
    public function testAdd(): void
    {
        $formModel = new Login();
        $errorMessage = 'Invalid password.';
        $formModel->error()->add('password', $errorMessage);

        $this->assertTrue($formModel->error()->has('password'));
        $this->assertSame($errorMessage, $formModel->error()->getFirst('password'));
    }

    public function testAddErrors(): void
    {
        $formModel = new Login();
        $errorMessage = ['password' => ['0' => 'Invalid password.']];
        $formModel->error()->clear();

        $this->assertEmpty($formModel->error()->getFirst('password'));

        $formModel->error()->addErrors($errorMessage);

        $this->assertTrue($formModel->error()->has('password'));
        $this->assertSame('Invalid password.', $formModel->error()->getFirst('password'));
    }

    public function testClearAllErrors(): void
    {
        $formModel = new Login();
        $formModel->error()->add('login', 'Login is required.');
        $formModel->error()->add('password', 'Password is required.');

        $this->assertSame(
            ['login' => ['Login is required.'], 'password' => ['Password is required.']],
            $formModel->error()->getAll(),
        );

        $formModel->error()->clear();

        $this->assertEmpty($formModel->error()->getAll());
    }

    public function testClearForAttribute(): void
    {
        $formModel = new Login();
        $formModel->error()->add('login', 'Login is required.');
        $formModel->error()->add('password', 'Password is required.');

        $this->assertSame(
            ['login' => ['Login is required.'], 'password' => ['Password is required.']],
            $formModel->error()->getAll(),
        );

        $formModel->error()->clear('login');

        $this->assertSame(['password' => ['Password is required.']], $formModel->error()->getAll());
    }

    public function testGet(): void
    {
        $formModel = new Login();
        $formModel->error()->add('login', 'Login is required.');
        $formModel->error()->add('password', 'Password is required.');

        $this->assertSame(['Login is required.'], $formModel->error()->get('login'));
        $this->assertSame(['Password is required.'], $formModel->error()->get('password'));
    }

    public function testGetWithEmpty(): void
    {
        $formModel = new Login();

        $this->assertSame([], $formModel->error()->get('password'));
    }

    public function testGetAll(): void
    {
        $formModel = new Login();

        $this->assertSame([], $formModel->error()->getAll());
    }

    public function testGetFirst(): void
    {
        $formModel = new Login();

        $this->assertSame('', $formModel->error()->getFirst('password'));
    }

    public function testGetFirsts(): void
    {
        $formModel = new Login();

        $this->assertSame([], $formModel->error()->getFirsts());
    }

    public function testGetSummary(): void
    {
        $formModel = new Login();
        $formModel->error()->add('login', 'Login is required.');
        $formModel->error()->add('password', 'Password is required.');

        $this->assertSame(
            ['Login is required.', 'Password is required.'],
            $formModel->error()->getSummary(),
        );
    }

    public function testGetSummaryFirst(): void
    {
        $formModel = new Login();
        $formModel->error()->add('login', 'Login is required.');
        $formModel->error()->add('password', 'Password is required.');

        $this->assertSame(
            ['login' => 'Login is required.', 'password' => 'Password is required.'],
            $formModel->error()->getSummaryFirst(),
        );
    }

    public function testGetSummaryOnlyAttributes(): void
    {
        $formModel = new Login();

        $formModel->error()->add('login', 'This value is not a valid email address.');
        $formModel->error()->add('password', 'Is too short.');

        $this->assertSame(
            ['This value is not a valid email address.'],
            $formModel->error()->getSummary(['login']),
        );
        $this->assertSame(
            ['Is too short.'],
            $formModel->error()->getSummary(['password']),
        );
    }

    public function testHas(): void
    {
        $formModel = new Login();

        $formModel->error()->add('login', 'Login is required.');
        $formModel->error()->add('password', 'Password is required.');

        $this->assertTrue($formModel->error()->has());
        $this->assertTrue($formModel->error()->has('login'));
        $this->assertTrue($formModel->error()->has('password'));
        $this->assertFalse($formModel->error()->has('email'));
    }
}
