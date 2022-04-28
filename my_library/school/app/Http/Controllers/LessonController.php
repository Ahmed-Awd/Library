<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Subject;
use App\Models\Topic;
use App\Repositories\LessonRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    private LessonRepositoryInterface $lessonRepository;

    public function __construct(LessonRepositoryInterface $lessonRepository)
    {
        $this->lessonRepository = $lessonRepository;
    }

    public function check(Topic $topic,ClassRoom $class = null): JsonResponse
    {
        $class == null ?  $this->lessonRepository->check($topic->id,$class)
            : $this->lessonRepository->check($topic->id,$class->id);

        return response()->json(["message" => "success"]);
    }

    public function get(Subject $subject,ClassRoom $class = null)
    {
        return $this->lessonRepository->get($subject,$class);
    }
}
