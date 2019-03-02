@extends('layouts.app')

@section('title')
<title>Anima | ユーザー登録</title>
@endsection

@section('script')
<script src="{{ asset('js/mypage.js') }}" defer></script>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group row my-md-4 justify-content-center">
                    <div class="col-md-7 text-center">
                        <img id="user-image" class="rounded-circle profile-lg" src="{{ $image }}">
                        <input type="file" name="image" id="upload-image">

                        @if ($errors->has('image'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('image') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group text-left">
                    <div class="col-md-7 mx-auto">
                        <label for="nickname" class="h5">Anima ID(20文字以内)</label><span class="text-danger">*必須</span>
                        <p>※登録後の変更はできません。</p>
                    </div>
                    <div class="col-md-7 mx-auto">
                        <input id="nickname" type="text" class="form-control{{ $errors->has('nickname') ? ' is-invalid' : '' }}" name="nickname" value="{{ $nickname }}" required autofocus>

                        @if ($errors->has('nickname'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('nickname') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group text-left">
                    <div class="col-md-7 mx-auto">
                        <label for="name" class="h5">ユーザー名(20文字以内)</label><span class="text-danger">*必須</span>
                        <p>登録後も設定から変更できます。</p>
                    </div>
                    <div class="col-md-7 mx-auto">
                        <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ $name }}" required>

                        @if ($errors->has('name'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group text-left">
                    <div class="col-md-7 mx-auto">
                        <label for="content" class="h5">自己紹介文(300文字以内)</label><span class="text-secondary">*任意</span>
                    </div>
                    <div class="col-md-7 mx-auto">
                        <textarea id="content" class="form-control{{ $errors->has('content') ? ' is-invalid' : '' }}" name="content" rows="10" cols="30"></textarea>

                        @if ($errors->has('content'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('content') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 mx-auto text-center">
                        <button type="submit" class="btn btn-success">
                            登録する
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
