@extends('layouts.app')

@section('title')
<title>Anima | {{ __('app.title.item', ['title' => $item->title]) }}</title>
<meta property="og:title" content="Anima | {{ __('app.title.item', ['title' => $item->title]) }}">
@endsection

@section('script')
<!-- Twitter share button -->
<script async src="https://platform.twitter.com/widgets.js" charset="utf-8" defer></script>
<!-- Line share button -->
<script src="https://d.line-scdn.net/r/web/social-plugin/js/thirdparty/loader.min.js" async="async" defer></script>

<script src="{{ asset('js/like.js') }}" defer></script>
<script src="{{ asset('js/review.js') }}" defer></script>
<script src="{{ asset('js/watchlist.js') }}" defer></script>
<script src="{{ asset('js/ajax/show_more_reviews.js') }}" defer></script>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 my-3">
            <div class="row justify-content-center no-gutters">
                <div class="col-12 text-left mb-2">
                    <p class="h5 font-bold">{{ $item->title }}</p>
                </div>
                <div class="col-6 text-left">
                    @if($item->image == null)
                        <img src="{{ asset('anima_image.png') }}" class="w-75">
                    @else
                        <img src="{{ config('app.image_path') }}/items/{{ $item->image }}" class="w-75">
                    @endif
                    <p class="mb-0">{{ __('app.word.item.source') }}</p>
                </div>
                <div class="col-6">
                    <div class="row text-left">
                        <span class="w-100 h7">{{ __('app.word.item.company', ['company' => $item->company]) }}</span>
                        <span class="w-100 h7">{{ __('app.word.item.season', ['season' => $item->season]) }}</span>
                        <span class="w-100 h7">{{ __('app.word.item.reviews_count', ['count' => $item->reviews_count]) }}</span>
                        <div class="star-rating d-inline-block">
                            <div class="star-rating-front" style="width:{{ $item->item_avg*20 }}%">★★★★★</div>
                            <div class="star-rating-back">★★★★★</div>
                        </div>
                        <span class="text-warning h7 mx-1">{{ $item->item_avg }}</span>
                    </div>
                </div>
                <div class="col-12 mt-3 text-right">
                    @guest
                        <a href="{{ url('/login') }}">
                            <img src="{{ asset('add_to_watchlist_a.png') }}" class="btn-watchlist">
                        </a>
                        <a href="{{ url('/login') }}">
                            <img src="{{ asset('post_review_a.png') }}" class="btn-watchlist">
                        </a>
                    @else
                        <div id="watchlist-button" class="d-inline-block cursor-pointer {{ $watchlist->status }}" data-item_id="{{$item->id}}" data-user_id="{{ Auth::user()->id }}" data-watchlist_id="{{ $watchlist->id }}">
                            @if($watchlist->status != "active")
                                <img src="{{ asset('add_to_watchlist_a.png') }}" class="btn-watchlist">
                            @else
                                <img src="{{ asset('add_to_watchlist_b.png') }}" class="btn-watchlist">
                            @endif
                        </div>
                        <div id="create-review-modal-button" class="d-inline-block cursor-pointer" data-toggle="modal" data-target="#create-review-modal" data-item_id="{{ $item->id }}">
                            @if($review_status != "active")
                                <img src="{{ asset('post_review_a.png') }}" class="btn-review">
                            @else
                                <img src="{{ asset('post_review_b.png') }}" class="btn-review">
                            @endif
                        </div>
                    @endguest
                </div>
                <div class="col-12 my-3 row justify-content-center">
                    <div class="col-8 text-center">
                        <a href="https://twitter.com/share?ref_src=twsrc%5Etfw" class="twitter-share-button" data-show-count="false" data-url="{{ url()->current() }}" data-text="" data-lang="ja"></a>
                        <div class="line-it-button" data-lang="ja" data-type="share-a" data-ver="2" data-url="{{ url()->current() }}" style="display: none;"></div>
                    </div>
                </div>
            </div>
            <div class="my-1">
                <div class="bg-grey text-dark">
                    <span class="h7 text-black font-bold p-1">{{ __('app.word.title.item.link') }}</span>
                </div>
                <p class="p-2 mb-0">{{ __('app.word.item.link') }}<a class="border-bottom border-dark"href="{{ $item->link }}">{{ __('app.word.item.here') }}</a></p>
            </div>
            <div class="my-1">
                <div class="bg-grey text-dark">
                    <span class="h7 text-black font-bold p-1">{{ __('app.word.title.item.official_link') }}</span>
                </div>
                <p class="p-2 mb-0">{{ __('app.word.item.official_link') }}<a class="border-bottom border-dark"href="{{ $item->official_link }}">{{ $item->official_link }}</a></p>
            </div>
            <div class="my-1">
                <div class="bg-grey text-dark">
                    <span class="h7 text-black font-bold p-1">{{ __('app.word.review') }}</span>
                </div>
                <ul id="reviews" class="list-unstyled mb-5">
                @foreach($reviews as $review)
                    <li class="py-2 border-bottom">
                        <div class="row">
                            <div class="align-top col-8">
                                <a href="{{ route('user', ['nickname' => $review->user_nickname]) }}">
                                    <div class="row align-items-center mb-2">
                                        <div class="col-2 col-md-1">
                                                @if($review->user_image == null)
                                                    <img class="rounded-circle align-top profile" src="{{ asset('user_image.jpg') }}">
                                                @else
                                                    <img class="rounded-circle align-top profile" src="{{ config('app.image_path') }}/users/{{ $review->user_image }}">
                                                @endif
                                        </div>
                                        <div class="col-8 pr-0">
                                            <p class="h7 font-bold mb-0 light-black">{{ $review->user_name }}</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="row col-4 justify-content-end">
                                @auth
                                    @if($review->user_id == Auth::user()->id)
                                        <div class="d-inline-block mx-md-3 cursor-pointer text-right p-0">
                                            <button type="button" class="bg-white border border-0 review-modal-button" data-toggle="modal" data-target="#operate-review-modal" data-review_id="{{ $review->review_id }}" data-score="{{ $review->review_score }}" data-content="{{ $review->review_content }}">
                                                <img src="{{ asset('edit.png') }}" class="zwicon-icon">
                                            </button>
                                        </div>
                                    @else
                                        <div class="d-inline-block"></div>
                                    @endif
                                @endauth
                                <div class="d-inline-block text-right align-top">
                                    <p class="created-date h7">{{ $review->review_created }}</p>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('review', ['review_id' => $review->review_id]) }}">
                            <div class="star-rating d-inline-block">
                                <div class="star-rating-front" style="width:{{ $review->review_score*20 }}%">★★★★★</div>
                                <div class="star-rating-back">★★★★★</div>
                            </div>
                            <span class="text-warning h7">{{ $review->review_score }}</span>
                            <pre class="py-1 h7 content-length light-black">{{ $review->review_content }}</pre>
                        </a>
                        <div class="text-left">
                            @guest
                                <div class="d-inline-block cursor-pointer">
                                    <a href="{{ url('/login') }}">
                                        <img src="{{ asset('like.png') }}" class="zwicon-icon">
                                        @if($review->likes_count > 0)
                                            <span class="h7 light-black">
                                                {{ $review->likes_count }}
                                            </span>
                                            <span class="h7 light-black">{{ __('app.word.count') }}</span>
                                        @endif
                                    </a>
                                </div>
                            @else
                                @if($review->like_id)
                                    <div id="like-review-button-{{ $review->review_id }}" class="{{ $review->like_status }} like-review-button d-inline-block cursor-pointer text-danger" data-review_id="{{ $review->review_id }}" data-like_id="{{ $review->like_id }}">
                                        <img src="{{ asset('like_on.png') }}" class="zwicon-icon">
                                        @if($review->likes_count > 0)
                                            <span id="likes-review-count-{{ $review->review_id }}" class="h7">
                                                {{ $review->likes_count }}
                                            </span>
                                            <span class="count-word-{{$review->review_id}} h7">{{ __('app.word.count') }}</span>
                                        @endif
                                    </div>
                                @else
                                    <div id="like-review-button-{{ $review->review_id }}" class="{{ $review->like_status }} like-review-button d-inline-block cursor-pointer" data-review_id="{{ $review->review_id }}" data-like_id="{{ $review->like_id }}">
                                        <img src="{{ asset('like.png') }}" class="zwicon-icon">
                                        @if($review->likes_count > 0)
                                            <span id="likes-review-count-{{ $review->review_id }}" class="h7">
                                                {{ $review->likes_count }}
                                            </span>
                                            <span class="count-word-{{$review->review_id}} h7">{{ __('app.word.count') }}</span>
                                        @else
                                            <span id="likes-review-count-{{ $review->review_id }}" class="h7">
                                            </span>
                                            <span class="count-word-{{$review->review_id}} h7"></span>
                                        @endif
                                    </div>
                                @endif
                            @endguest
                            <a class="cursor-pointer" href="{{ route('review', ['review_id' => $review->review_id]) }}">
                                <img src="{{ asset('comment.png') }}" class="zwicon-icon">
                                @if($review->comments_count > 0)
                                    <span class="h7">
                                        {{ $review->comments_count }}
                                    </span>
                                    <span class="h7">{{ __('app.word.count') }}</span>
                                @endif
                            </a>
                        </div>
                    </li>
                @endforeach
                </ul>
                @if(count($reviews) == 20)
                    <div id="show-more-reviews">
                        <div class="mb-5 text-center">
                            <button type="button" id="show-more-reviews-button" class="btn btn-outline-secondary w-100" data-item_id="{{ $item->id }}">{{ __('app.button.show_more') }}</button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@include('partials.form.review.operate')
@include('partials.form.review.create')
@include('partials.form.review.edit')
@include('partials.form.review.delete')
@endsection
