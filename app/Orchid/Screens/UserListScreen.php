<?php

namespace App\Orchid\Screens;

use App\Models\User;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

class UserListScreen extends Screen
{
    public function query(): iterable
    {
        return [
            'users' => User::paginate(),
        ];
    }

    public function name(): ?string
    {
        return 'Users Management';
    }

    public function description(): ?string
    {
        return 'Manage all users in the system';
    }

    public function commandBar(): array
    {
        return [
            Link::make('Create user')
                ->icon('plus')
                ->route('platform.user.edit'),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::table('users', [
                TD::make('id', 'ID')
                    ->sort()
                    ->filter(),

                TD::make('name', 'Name')
                    ->sort()
                    ->filter(),

                TD::make('email', 'Email')
                    ->sort()
                    ->filter(),

                TD::make('role', 'Role')
                    ->sort()
                    ->filter(),

                TD::make('created_at', 'Created')
                    ->sort()
                    ->render(function (User $user) {
                        return $user->created_at->format('Y-m-d H:i');
                    }),

                TD::make('actions', 'Actions')
                    ->render(function (User $user) {
                        return Link::make('Edit')
                            ->icon('pencil')
                            ->route('platform.user.edit', ['user' => $user->id]);
                    }),
            ]),
        ];
    }
}
