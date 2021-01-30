@extends('app')

@section('title', '記事一覧')

@section('content')
  @include('nav')
  <div class="container">
    @foreach($articles as $article)
    <div class="card mt-3">
      <div class="card-body d-flex flex-row">
        <i class="fas fa-user-circle fa-3x mr-1"></i>
        <div>
          <div class="font-weight-bold">
            <!-- ユーザー名 -->
            {{ $article->user->name }}
          </div>
          <div class="font-weight-lighter">
            <!-- 日時 -->
            {{ $article->created_at->format('Y/m/d H:i')}}
          </div>
        </div>
      </div>
      <div class="card-body pt-0 pb-2">
        <h3 class="h4 card-title">
          <!-- 記事タイトル -->
          {{ $article->title }}
        </h3>
        <div class="card-text">
          <!-- 記事本文 -->
          {!! nl2br(e( $article->body )) !!}
        </div>
      </div>
    </div>
    @endforeach
  </div>
@endsection
