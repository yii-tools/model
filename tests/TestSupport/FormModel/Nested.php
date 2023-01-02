<?php

declare(strict_types=1);

namespace Yii\Model\Tests\TestSupport\FormModel;

use Yii\Model\AbstractFormModel;

final class Nested extends AbstractFormModel
{
    private int|null $id = null;
    private Login $user;

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
