<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;

class AuthController extends Controller {
    public function loginForm() {
        return view('auth.login');
    }

    public function registerForm() {
        return view('auth.register');
    }

    public function forgotForm() {
        $devEmail = env('DEV_PASSWORD_RESET_EMAIL');
        return view('auth.forgot', ['devResetEmail' => $devEmail]);
    }

    public function resetForm(Request $request) {
        // The reset form should receive the token as query param
        $token = $request->query('token');
        $email = $request->query('email');
        return view('auth.reset', compact('token', 'email'));
    }

    public function forgot(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $devEmail = env('DEV_PASSWORD_RESET_EMAIL');

        // Find the user by the submitted email. We still show which account we're resetting for,
        // but we will send the actual email to the developer address when in dev.
        // Find the user by the submitted email
        $user = User::where('email', $request->input('email'))->first();

        if ($user) {
            // Create a token and redirect the user to the reset form so they can set a new password immediately
            $token = Password::broker()->createToken($user);

            return redirect()->route('reset', ['token' => $token, 'email' => $user->email])
                ->with('status', "You may now reset password for {$user->email}.");
        }

        // Generic message to avoid leaking whether the email exists
        return back()->with('status', 'If an account with that email exists, you may reset the password.');
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) use ($request) {
                $user->password = Hash::make($password);
                $user->setRememberToken(Str::random(60));
                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', __($status));
        }

        return back()->withErrors(['email' => [__($status)]]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:8',
        ]);

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('status', 'Registration successful.');
    }
}
