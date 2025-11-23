<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function index(): View
    {
        $news = News::with('author')->latest('publish_date')->paginate(15);
        return view('news.index', compact('news'));
    }

    public function create(): View
    {
        return view('news.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'publish_date' => ['required', 'date'],
            'is_published' => ['boolean'],
        ]);

        $data['author_id'] = auth()->id();

        News::create($data);

        return redirect()->route('news.index')->with('status', 'Actualité ajoutée avec succès.');
    }

    public function show(News $news): View
    {
        return view('news.show', compact('news'));
    }

    public function edit(News $news): View
    {
        return view('news.edit', compact('news'));
    }

    public function update(Request $request, News $news): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'publish_date' => ['required', 'date'],
            'is_published' => ['boolean'],
        ]);

        $news->update($data);

        return redirect()->route('news.index')->with('status', 'Actualité mise à jour avec succès.');
    }

    public function destroy(News $news): RedirectResponse
    {
        $news->delete();

        return redirect()->route('news.index')->with('status', 'Actualité supprimée avec succès.');
    }
}
