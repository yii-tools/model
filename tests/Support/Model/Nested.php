<?php

declare(strict_types=1);

namespace Yii\Model\Tests\Support\Model;

use Yii\Model\AbstractModel;
use Yii\Model\Tests\Support\HasHint;

final class Nested extends AbstractModel
{
    use HasHint;

    private int|null $id = null;
    private readonly Login $user;

    public function __construct()
    {
        $this->user = new Login();

        parent::__construct();
    }

    public function getLabels(): array
    {
        return [
            'id' => 'Id',
        ];
    }

    public function getHints(): array
    {
        return [
            'id' => 'Readonly ID',
        ];
    }
}
