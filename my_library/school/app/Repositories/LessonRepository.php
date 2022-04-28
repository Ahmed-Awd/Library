<?php


namespace App\Repositories;


use App\Models\Lesson;
use Illuminate\Support\Facades\Auth;

class LessonRepository implements LessonRepositoryInterface
{
    private Lesson $lesson;

    public function __construct(Lesson $lesson)
    {
        $this->lesson = $lesson;
    }

    public function check($topic, $class)
    {
        $current = $this->lesson->where(['topic_id' => $topic, 'class_id' => $class])->first();
        if ($current) {
            $current->completed = !$current->completed;
            $current->save();
        } else {
            $this->lesson->create(['topic_id' => $topic, 'staff_id' => Auth::user()->id, 'class_id' => $class]);
        }
    }

    public function get($subject, $classroom)
    {
        $classroom == null ? $class = null : $class = $classroom->id;

        return $subject->semesterTopics(null)->with('subTopics')
            ->with('subTopics.lesson', function ($query) use ($class) {
                $query->where('class_id','=',$class);
            })->get();
    }
}
