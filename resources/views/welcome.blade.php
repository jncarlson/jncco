@extends('app')

@section('content')

    <div id="form">
        <h1>Summoner's Digest</h1>
        <p>Weekly news, gossip, personal summoner analytics, and more.</p>
        <form method="POST" action="http://time.app/submit" accept-charset="utf-8">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group text-left">
                <label for="exampleInputEmail1">Email</label>
                <input name="email" type="email" class="form-control" id="exampleInputEmail1">
            </div>
            <div class="form-group text-left">
                <label for="exampleInputPassword1">LoL Username</label>
                <input name="username" type="text" class="form-control" id="exampleInputPassword1">
            </div>
            <div class="form-group text-left">
                <label for="exampleInputPassword1">LoL Username</label>
                <select class="form-control" name="region">
                    <option value="na">NA</option>
                    <option value="euw">EUW</option>
                    <option value="eune">EUNE</option>
                    <option value="br">BR</option>
                    <option value="tr">TR</option>
                    <option value="ru">RU</option>
                    <option value="lan">LAN</option>
                    <option value="las">LAS</option>
                    <option value="oce">OCE</option>
                    <option value="kr">KR</option>
                </select>
            </div>
            {{--<div class="g-recaptcha" data-sitekey="6Lc1ZgoTAAAAAO84X7BAlLzlTnnlfDBs5tQrwnfw"></div>--}}
            <div class="form-group buttons">
                <button type="submit" class="btn btn-default">Subscribe Free</button>
            </div>
        </form>
        @if(isset($verify))
            <div class="alert alert-info" role="alert">
                {{ $verify }}
            </div>
        @endif
    </div>

@stop