<?php

namespace App\Providers;

use App\Repositories\BranchRepository;
use App\Repositories\BranchRepositoryInterface;
use App\Repositories\CategoryRepository;
use App\Repositories\CategoryRepositoryInterface;
use App\Repositories\ClassRepository;
use App\Repositories\ClassRepositoryInterface;
use App\Repositories\ContentRepository;
use App\Repositories\ContentRepositoryInterface;
use App\Repositories\CourseRepository;
use App\Repositories\CourseRepositoryInterface;
use App\Repositories\FeedbackRepository;
use App\Repositories\FeedbackRepositoryInterface;
use App\Repositories\InstructionRepository;
use App\Repositories\InstructionRepositoryInterface;
use App\Repositories\LessonRepository;
use App\Repositories\LessonRepositoryInterface;
use App\Repositories\LevelRepository;
use App\Repositories\LevelRepositoryInterface;
use App\Repositories\ProgramRepository;
use App\Repositories\ProgramRepositoryInterface;
use App\Repositories\SettingRepository;
use App\Repositories\SettingRepositoryInterface;
use App\Repositories\SubjectRepository;
use App\Repositories\SubjectRepositoryInterface;
use App\Repositories\TimingSetRepository;
use App\Repositories\TimingSetRepositoryInterface;
use App\Repositories\TopicRepository;
use App\Repositories\TopicRepositoryInterface;
use App\Repositories\TrackRepository;
use App\Repositories\TrackRepositoryInterface;
use App\Repositories\ImportRepository;
use App\Repositories\ImportRepositoryInterface;

use App\Repositories\UniversitySettingRepository;
use App\Repositories\UniversitySettingRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\AcademicYearRepositoryInterface;
use App\Repositories\AcademicYearRepository;
use App\Repositories\TimetableRepositoryInterface;
use App\Repositories\TimetableRepository;
use Illuminate\Support\ServiceProvider;

class RepositoriesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(UserRepositoryInterface::class,UserRepository::class);
        $this->app->bind(AcademicYearRepositoryInterface::class,AcademicYearRepository::class);
        $this->app->bind(BranchRepositoryInterface::class,BranchRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class,CategoryRepository::class);
        $this->app->bind(ProgramRepositoryInterface::class,ProgramRepository::class);
        $this->app->bind(TrackRepositoryInterface::class,TrackRepository::class);
        $this->app->bind(ImportRepositoryInterface::class,ImportRepository::class);
        $this->app->bind(LevelRepositoryInterface::class,LevelRepository::class);
        $this->app->bind(SettingRepositoryInterface::class,SettingRepository::class);
        $this->app->bind(SubjectRepositoryInterface::class,SubjectRepository::class);

        $this->app->bind(CourseRepositoryInterface::class,CourseRepository::class);
        $this->app->bind(ClassRepositoryInterface::class,ClassRepository::class);
        $this->app->bind(UniversitySettingRepositoryInterface::class,UniversitySettingRepository::class);
        $this->app->bind(TimingSetRepositoryInterface::class,TimingSetRepository::class);
        $this->app->bind(FeedbackRepositoryInterface::class,FeedbackRepository::class);
        $this->app->bind(TopicRepositoryInterface::class,TopicRepository::class);
        $this->app->bind(ContentRepositoryInterface::class,ContentRepository::class);
        $this->app->bind(TimetableRepositoryInterface::class,TimetableRepository::class);
        $this->app->bind(LessonRepositoryInterface::class,LessonRepository::class);
        $this->app->bind(InstructionRepositoryInterface::class,InstructionRepository::class);

    }
}
