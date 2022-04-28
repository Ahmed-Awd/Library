<?php

namespace App\Providers;

use App\Models\Academic;
use App\Models\Category;
use App\Models\ClassRoom;
use App\Models\Content;
use App\Models\Course;
use App\Models\Feedback;
use App\Models\Instruction;
use App\Models\Level;
use App\Models\Program;
use App\Models\TimingSet;
use App\Models\Topic;
use App\Models\Track;
use App\Models\User;
use App\Policies\AcademicYearPolicy;
use App\Models\Branch;
use App\Policies\BranchPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\ClassPolicy;
use App\Policies\ContentPolicy;
use App\Policies\CoursePolicy;
use App\Policies\FeedbackPolicy;
use App\Policies\InstructionPolicy;
use App\Policies\LevelPolicy;
use App\Policies\ProgramPolicy;
use App\Policies\TimingSetPolicy;
use App\Policies\TopicPolicy;
use App\Policies\TrackPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Academic::class => AcademicYearPolicy::class,
        Branch::class => BranchPolicy::class,
        Category::class => CategoryPolicy::class,
        Program::class => ProgramPolicy::class,
        Track::class    => TrackPolicy::class,
        Level::class  => LevelPolicy::class,
        Course::class  => CoursePolicy::class,
        ClassRoom::class => ClassPolicy::class,
        TimingSet::class => TimingSetPolicy::class,
        Feedback::class => FeedbackPolicy::class,
        Topic::class => TopicPolicy::class,
        Content::class => ContentPolicy::class,
        Instruction::class => InstructionPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('assign-level', function (User $user) {
            return $user->hasPermissionTo('assign level');
        });

        Gate::define('set-settings', function (User $user) {
            return $user->hasPermissionTo('set academic settings');
        });

        Gate::define('assign-staff', function (User $user) {
            return $user->hasPermissionTo('assign subjects to staff');
        });

        Gate::define('assign-subject', function (User $user) {
            return $user->hasPermissionTo('assign subjects');
        });

        Gate::define('check-lessons', function (User $user) {
            return $user->hasPermissionTo('check lessons');
        });

    }
}
