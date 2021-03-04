<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;
use App\Tag;
use App\Http\Requests\ArticleRequest;

class ArticleController extends Controller
{

  public function __construct()
  {
    $this->authorizeResource(Article::class, 'article');
  }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $articles = Article::all()->sortByDesc('created_at')
        ->load(['user', 'likes', 'tags',]);

        return view('articles.index', ['articles' => $articles]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $allTagNames = Tag::all()->map(function($tag){
        return ['text' => $tag->name];
      });

        return view('articles.create', [
          'allTagNames' => $allTagNames,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticleRequest $request, Article $article)
    {
        $article->fill($request->all());
        $article->user_id = $request->user()->id;
        $article->save();

        $request->tags->each(function($tagName) use ($article){
          $tag = Tag::firstOrCreate(['name' => $tagName]);
          $article->tags()->attach($tag);
        });

        return redirect()->route('articles.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        return view('articles.show', ['article' => $article]);
    }

    // いいねボタンの登録と解除
    public function like(Request $request, Article $article)
    {
      $article->likes()->detach($request->user()->id);
      $article->likes()->attach($request->user()->id);

      return [
        'id' => $article->id,
        'countLikes' => $article->count_likes,
      ];
    }


    public function unlike(Request $request, Article $article)
    {
      $article->likes()->detach($request->user()->id);

      return [
        'id' => $article->id,
        'countLikes' => $article->count_likes,
      ];
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Article $article)
    {
        $tagNames = $article->tags->map(function($tag){
          return['text' => $tag->name];
        });

        $allTagNames = Tag::all()->map(function($tag){
          return ['text' => $tag->name];
        });

        return view('articles.edit', [
          'article' => $article,
          'tagNames' => $tagNames,
          'allTagNames' => $allTagNames,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ArticleRequest $request, Article $article)
    {
        $article->fill($request->all())->save();

        $article->tags()->detach();
        $request->tags->each(function($tagName) use ($article){
          $tag = Tag::firstOrCreate(['name' => $tagName]);
          $article->tags()->attach($tag);
        });

        return redirect()->route('articles.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        $article->delete();

        return redirect()->route('articles.index');
    }
}
