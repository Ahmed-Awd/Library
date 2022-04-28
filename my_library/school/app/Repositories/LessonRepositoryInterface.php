<?php

namespace App\Repositories;

interface LessonRepositoryInterface
{
    public function check($topic, $class);

    public function get($subject, $classroom);
}
