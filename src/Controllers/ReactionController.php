<?php

namespace BalajiDharma\LaravelAdmin\Controllers;

use BalajiDharma\LaravelAdmin\Controllers\Controller;
use BalajiDharma\LaravelAdminCore\Actions\Reaction\ReactionCreateAction;
use BalajiDharma\LaravelAdminCore\Actions\Reaction\ReactionUpdateAction;
use BalajiDharma\LaravelAdminCore\Data\Reaction\ReactionCreateData;
use BalajiDharma\LaravelAdminCore\Data\Reaction\ReactionUpdateData;
use BalajiDharma\LaravelAdminCore\Grid\ReactionGrid;
use BalajiDharma\LaravelReaction\Models\Reaction;

class ReactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $this->authorize('adminViewAny', Reaction::class);
        $gridClass = config('admin.reaction.grid.reaction', ReactionGrid::class);
        $reactions = (new Reaction)->newQuery();

        $crud = app($gridClass)->list($reactions);

        return view('laravel-admin::crud.index', compact('crud'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->authorize('adminCreate', Reaction::class);
        $gridClass = config('admin.reaction.grid.reaction', ReactionGrid::class);
        $crud = app($gridClass)->form();

        return view('laravel-admin::crud.edit', compact('crud'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ReactionCreateData $data, ReactionCreateAction $reactionCreateAction)
    {
        $this->authorize('adminCreate', Reaction::class);
        $reactionCreateAction->handle($data);

        return crudRedirect('admin.reaction.index', 'Reaction created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\View\View
     */
    public function show(Reaction $reaction)
    {
        $this->authorize('adminView', $reaction);
        $gridClass = config('admin.reaction.grid.reaction', ReactionGrid::class);
        $crud = app($gridClass)->show($reaction);
        $relations = [];

        $userGridClass = config('admin.user.grid.user', \BalajiDharma\LaravelAdminCore\Grid\UserGrid::class);

        if ($reaction->reactor_type == 'App\Models\User') {
            $relations[] = [
                'crud' => app($userGridClass)->setTitle('Reactor')->show($reaction->reactor()->first()),
                'view' => 'show',
            ];
        }

        return view('laravel-admin::crud.show', compact('crud', 'relations'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\View\View
     */
    public function edit(Reaction $reaction)
    {
        $this->authorize('adminUpdate', $reaction);
        $gridClass = config('admin.reaction.grid.reaction', ReactionGrid::class);
        $crud = app($gridClass)->form($reaction);

        return view('laravel-admin::crud.edit', compact('crud'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ReactionUpdateData $data, Reaction $reaction, ReactionUpdateAction $reactionUpdateAction)
    {
        $this->authorize('adminUpdate', $reaction);
        $reactionUpdateAction->handle($data, $reaction);

        return crudRedirect('admin.reaction.index', 'Reaction updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Reaction $reaction)
    {
        $this->authorize('adminDelete', $reaction);
        $reaction->delete();

        return crudRedirect('admin.reaction.index', 'Reaction deleted successfully.');
    }
}
