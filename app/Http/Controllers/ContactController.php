<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactMeRequest;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * 显示表单
     *
     * @return View
     */
    public function showForm()
    {
        return view('blog.contact');
    }

    /**
     * Email the contact request
     *
     * @param ContactMeRequest $request
     * @return Redirect
     */
    public function sendContactInfo(ContactMeRequest $request)
    {
        $data = $request->only('name', 'email', 'phone');
        $data['messageLines'] = explode("\n", $request->get('message'));
//        dd($data);
//        Mail::send('emails.test',
//            ['testVar'=>'jk'],
//            function ($s){
//            $s->to('841916454@qq.com')->subject('dhghgjyj');
//        });

        Mail::queue('emails.contact', $data, function ($message) use ($data) {
            $message->subject('Blog Contact Form: ' . $data['name'])
                ->to($data['email'])
                ->replyTo(config('blog.contact_email'));
        });
//        dd(1223);

        return back()
            ->withSuccess("Thank you for your message. It has been sent.");
    }
}
