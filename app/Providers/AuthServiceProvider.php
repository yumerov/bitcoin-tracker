<?php

// phpcs:disable Squiz.Arrays.ArrayDeclaration.MultiLineNotAllowed
// phpcs:disable Squiz.Commenting.InlineComment.Empty
// phpcs:disable Squiz.Commenting.InlineComment.InvalidEndChar
// phpcs:disable Squiz.Commenting.InlineComment.NotCapital
// phpcs:disable Squiz.PHP.CommentedOutCode.Found
// phpcs:disable Squiz.WhiteSpace.FunctionSpacing.Before
// phpcs:disable Squiz.WhiteSpace.MemberVarSpacing.FirstIncorrect

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
