<?php

namespace Shopper\Framework\Http\Controllers\Auth;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Routing\Controller;

class ForgotPasswordController extends Controller
{
    use ValidatesRequests;
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest');

        ResetPassword::toMailUsing(function ($notifiable, $token) {
            return (new MailMessage())
                ->view('shopper::mails.email')
                ->line(__('You are receiving this email because we received a password reset request for your account.'))
                ->action(__('Reset Password'), url(config('app.url') . route('shopper.password.reset', $token, false)))
                ->line(__('If you did not request a password reset, no further action is required.'));
        });
    }

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\View\View
     */
    public function showLinkRequestForm()
    {
        return view('shopper::auth.passwords.email');
    }

    /**
     * Get the response for a successful password reset link.
     *
     * @param string $response
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function sendResetLinkResponse(Request $request, $response)
    {
        session()->flash('success', trans($response));

        return back();
    }

    /**
     * Get the response for a failed password reset link.
     *
     * @param string $response
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        session()->flash('error', trans($response));

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => trans($response)]);
    }
}
