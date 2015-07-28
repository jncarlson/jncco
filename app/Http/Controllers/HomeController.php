<?php

namespace App\Http\Controllers;


use GuzzleHttp\Client;
use App\Services\RiotApi;
use Request;
use Mail;

class HomeController extends Controller
{
    public $riot;
    public $apiResults;

    public function __construct()
    {
        $this->riot = new RiotApi();
    }

    function index()
    {
//        $toAddress = 'joseph.jn.carlson@gmail.com';
//        $body = 'Laravel with Mailgun is easy! dif message';
//        $subject = 'Your weekly digest is here!';
//
//        Mail::raw($body, function($message, $toAddress, $subject)
//        {
//            $message->to($toAddress)->subject($subject);
//        });
        return view('welcome');
    }

    function calculate()
    {
        $this->apiResults = $this->riot->setUpCalls();
        echo '<pre>';
        print_r($this->apiResults);
        echo '</pre>';
    }

    function formSubmit()
    {
        $input = Request::all();
        $verifyResult = $this->riot->verifyUser($input);
        return view('welcome')->with('verify', $verifyResult);
    }
}
