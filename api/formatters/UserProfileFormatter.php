<?php

namespace api\formatters;

use api\helpers\DateHelper;
use shop\entities\User\User;
use shop\helpers\UserHelper;

class UserProfileFormatter implements ApiFormatterInterface
{
    private $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function format(): array
    {
        return [
            'id' => $this->model->id,
            'name' => $this->model->username,
            'email' => $this->model->email,
            'date' => [
                'created' => DateHelper::formatApi($this->model->created_at),
                'updated' => DateHelper::formatApi($this->model->updated_at),
            ],
            'status' => [
                'code' => $this->model->status,
                'status' => UserHelper::statusName($this->model->status),
            ],
        ];
    }
}