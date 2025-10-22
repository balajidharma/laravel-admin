<?php

namespace BalajiDharma\LaravelAdmin\Controllers;

use BalajiDharma\LaravelAdmin\Controllers\Controller;
use BalajiDharma\LaravelAdminCore\Actions\Comment\CommentCreateAction;
use BalajiDharma\LaravelAdminCore\Actions\Comment\CommentUpdateAction;
use BalajiDharma\LaravelAdminCore\Data\Comment\CommentCreateData;
use BalajiDharma\LaravelAdminCore\Data\Comment\CommentUpdateData;
use BalajiDharma\LaravelAdminCore\Grid\ActivityLogGrid;
use BalajiDharma\LaravelAdminCore\Grid\CommentGrid;
use BalajiDharma\LaravelComment\Models\Comment;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $this->authorize('adminViewAny', Comment::class);
        $comments = (new Comment)->newQuery();
        $gridClass = config('admin.comment.grid.comment', CommentGrid::class);
        $crud = app($gridClass)->list($comments);

        return view('laravel-admin::crud.index', compact('crud'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->authorize('adminCreate', Comment::class);
        $gridClass = config('admin.comment.grid.comment', CommentGrid::class);
        $crud = app($gridClass)->form();

        return view('laravel-admin::crud.edit', compact('crud'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CommentCreateData $data, CommentCreateAction $commentCreateAction)
    {
        $this->authorize('adminCreate', Comment::class);
        $commentCreateAction->handle($data);

        return crudRedirect('admin.comment.index', 'Comment created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\View\View
     */
    public function show(Comment $comment)
    {
        $this->authorize('adminView', $comment);
        $gridClass = config('admin.comment.grid.comment', CommentGrid::class);
        $crud = app($gridClass)->show($comment);
        $relations = [];

        $userGridClass = config('admin.user.grid.user', \BalajiDharma\LaravelAdminCore\Grid\UserGrid::class);
        $activityLogGridClass = config('admin.activitylog.grid.activitylog', ActivityLogGrid::class);

        if ($comment->commenter_type == 'App\Models\User') {
            $relations[] = [
                'crud' => app($userGridClass)->setTitle('Commenter')->show($comment->commenter()->first()),
                'view' => 'show',
            ];
        }

        $relations[] = [
            'crud' => app($activityLogGridClass)->setRedirectUrl()->list($comment->activities()->getQuery()),
            'view' => 'list',
        ];

        return view('laravel-admin::crud.show', compact('crud', 'relations'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\View\View
     */
    public function edit(Comment $comment)
    {
        $this->authorize('adminUpdate', $comment);
        $gridClass = config('admin.comment.grid.comment', CommentGrid::class);
        $crud = app($gridClass)->form($comment);

        return view('laravel-admin::crud.edit', compact('crud'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(CommentUpdateData $data, Comment $comment, CommentUpdateAction $commentUpdateAction)
    {
        $this->authorize('adminUpdate', $comment);
        $commentUpdateAction->handle($data, $comment);

        return crudRedirect('admin.comment.index', 'Comment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Comment $comment)
    {
        $this->authorize('adminDelete', $comment);
        $comment->delete();

        return crudRedirect('admin.comment.index', 'Comment deleted successfully.');
    }
}
