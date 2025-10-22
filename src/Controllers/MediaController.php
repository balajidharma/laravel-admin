<?php

namespace BalajiDharma\LaravelAdmin\Controllers;

use BalajiDharma\LaravelAdmin\Controllers\Controller;
use BalajiDharma\LaravelAdminCore\Actions\Media\MediaCreateAction;
use BalajiDharma\LaravelAdminCore\Actions\Media\MediaUpdateAction;
use BalajiDharma\LaravelAdminCore\Data\Media\MediaCreateData;
use BalajiDharma\LaravelAdminCore\Data\Media\MediaUpdateData;
use BalajiDharma\LaravelAdminCore\Grid\MediaGrid;
use BalajiDharma\LaravelFormBuilder\FormBuilder;
use BalajiDharma\LaravelMediaManager\Models\Media;

class MediaController extends Controller
{
    public function index()
    {
        $this->authorize('adminViewAny', Media::class);
        $mediaItems = (new Media)->newQuery();
        $mediaItems->whereIsOriginal();
        $gridClass = config('admin.media.grid.media', MediaGrid::class);
        $crud = app($gridClass)->list($mediaItems);

        return view('laravel-admin::crud.index', compact('crud'));
    }

    public function create()
    {
        $this->authorize('adminCreate', Media::class);
        $mediaItems = (new Media)->newQuery();
        $mediaItems->whereIsOriginal();
        $gridClass = config('admin.media.grid.media', MediaGrid::class);
        $crud = app($gridClass)->form();

        return view('laravel-admin::crud.edit', compact('crud'));
    }

    public function store(MediaCreateData $data, MediaCreateAction $mediaCreateAction)
    {
        $this->authorize('adminCreate', Media::class);
        $mediaCreateAction->handle($data);

        return redirect()->route('admin.media.index')
            ->with('message', __('Media created successfully.'));
    }

    public function show($id)
    {
        $media = Media::findOrFail($id);
        $this->authorize('adminView', $media);
        $gridClass = config('admin.media.grid.media', MediaGrid::class);
        $crud = app($gridClass)->show($media);

        return view('laravel-admin::crud.show', compact('crud'));
    }

    public function edit($id, FormBuilder $formBuilder)
    {
        $media = Media::findOrFail($id);
        $this->authorize('adminUpdate', $media);
        $gridClass = config('admin.media.grid.media', MediaGrid::class);
        $crud = app($gridClass)->form($media);

        return view('laravel-admin::crud.edit', compact('crud'));
    }

    public function update(MediaUpdateData $mediaUpdateData, $id, MediaUpdateAction $mediaUpdateAction)
    {
        $media = Media::findOrFail($id);
        $this->authorize('adminUpdate', $media);
        $mediaUpdateAction->handle($mediaUpdateData, $media);

        return redirect()->route('admin.media.index')
            ->with('message', __('Media updated successfully.'));
    }

    public function destroy($id)
    {
        $media = Media::findOrFail($id);
        $this->authorize('adminDelete', $media);
        $media->getAllVariantsAndSelf()->each(function (Media $variant) {
            $variant->delete();
        });

        return redirect()->route('admin.media.index')
            ->with('message', __('Media deleted successfully.'));
    }
}
