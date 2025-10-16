<?php

namespace App\Orchid\Screens;

use App\Models\Post;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

class PostListScreen extends Screen
{
    public function query(): iterable
    {
        return [
            'posts' => Post::with('user')->paginate(),
        ];
    }

    public function name(): ?string
    {
        return 'Posts Management';
    }

    public function description(): ?string
    {
        return 'Manage all posts in the system';
    }

    public function commandBar(): array
    {
        return [];
    }

    public function layout(): iterable
    {
        return [
            Layout::table('posts', [
                TD::make('id', 'ID')
                    ->sort()
                    ->filter(),

                TD::make('title', 'Title')
                    ->sort()
                    ->filter(),

                TD::make('text', 'Text')
                    ->width('300px')
                    ->render(function (Post $post) {
                        return strlen($post->text) > 100
                            ? substr($post->text, 0, 100) . '...'
                            : $post->text;
                    }),

                TD::make('user.name', 'Author')
                    ->sort(),

                TD::make('created_at', 'Created')
                    ->sort()
                    ->render(function (Post $post) {
                        return $post->created_at->format('Y-m-d H:i');
                    }),

                TD::make('actions', 'Actions')
                    ->render(function (Post $post) {
                        return Button::make('Delete')
                            ->icon('trash')
                            ->method('delete', ['post' => $post->id])
                            ->confirm('Are you sure you want to delete this post?');
                    }),
            ]),
        ];
    }

    public function delete(Post $post)
    {
        $post->delete();

        \Orchid\Support\Facades\Toast::info('Post deleted successfully.');
    }
}
