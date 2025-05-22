<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function visitor()
    {
        return view('visitor');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => 'required',
            'password' => 'required',
        ]);

        $field = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email_uti' : 'tel_uti';
        $user = Utilisateur::where($field, $request->login)->first();

        if ($user && Hash::check($request->password, $user->mot_de_passe_uti)) {
            Auth::login($user);
            $request->session()->regenerate();
            \Log::info('User logged in: ' . $user->email_uti);

            if ($request->remember) {
                Cookie::queue('user_login', $request->login, 43200);
            }

         switch ($user->role_uti) {
            case 'admin':
                return redirect()->route('admin.dashboard');

            case 'proprietaire':
                return redirect()->route('proprietaire.accueilproprietaire');

            case 'locataire':
            case 'colocataire':
                return redirect()->route('locataire.accueillocataire');

            default:
                return redirect()->route('visitor');
        }
       

        }

        Log::info('Login failed for: ' . $request->login);
        return back()->withErrors(['login' => 'information invalide']);
    }

    public function showSignup()
    {
        return view('auth.signup');
    }

    public function signup(Request $request)
    {
        $validated = $request->validate([
            'tel_uti' => 'required|string|max:15',
            'email_uti' => 'required|email|unique:utilisateurs,email_uti',
            'nom_uti' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'ville' => 'required|string|max:255',
            'date_naissance' => 'required|date',
            'role_uti' => 'required|in:locataire,colocataire,proprietaire,admin',
            'photodeprofil_uti' => 'nullable|image|max:2048',
            'mot_de_passe_uti' => 'required|string|min:8|confirmed',
        ], [
            'tel_uti.required' => 'Le numéro de téléphone est obligatoire.',
            'tel_uti.max' => 'Le numéro de téléphone ne doit pas dépasser 15 caractères.',
            'email_uti.required' => 'L\'adresse e-mail est obligatoire.',
            'email_uti.email' => 'Veuillez entrer une adresse e-mail valide.',
            'email_uti.unique' => 'Cette adresse e-mail est déjà utilisée.',
            'nom_uti.required' => 'Le nom est obligatoire.',
            'prenom.required' => 'Le prénom est obligatoire.',
            'ville.required' => 'La ville est obligatoire.',
            'date_naissance.required' => 'La date de naissance est obligatoire.',
            'role_uti.required' => 'Le rôle est obligatoire.',
            'photodeprofil_uti.image' => 'Le fichier doit être une image.',
            'photodeprofil_uti.max' => 'La taille de l\'image ne doit pas dépasser 2 Mo.',
            'mot_de_passe_uti.required' => 'Le mot de passe est obligatoire.',
            'mot_de_passe_uti.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'mot_de_passe_uti.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        $photoPath = null;
        if ($request->hasFile('photodeprofil_uti')) {
            $photoPath = $request->file('photodeprofil_uti')->store('profiles', 'public');
        }

        $user = Utilisateur::create([
            'nom_uti' => $validated['nom_uti'],
            'prenom' => $validated['prenom'],
            'email_uti' => $validated['email_uti'],
            'mot_de_passe_uti' => Hash::make($validated['mot_de_passe_uti']),
            'role_uti' => $validated['role_uti'],
            'tel_uti' => $validated['tel_uti'],
            'ville' => $validated['ville'],
            'date_naissance' => $validated['date_naissance'],
            'date_inscription_uti' => now(),
            'photodeprofil_uti' => $photoPath,
        ]);

        Auth::login($user);
        return redirect()->route('login');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Cookie::queue(Cookie::forget('user_login'));
        return redirect()->route('visitor');
    }

    public function home()
    {
        $user = Auth::user();
        if (!$user) {
            \Log::info('No authenticated user found when accessing home route.');
            return redirect()->route('login')->with('error', 'Veuillez vous connecter pour accéder à cette page');
        }
        return view('home', ['user' => $user]);
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email_uti' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email_uti'),
            function ($user, $token) {
                $resetUrl = route('reset-password.show', ['token' => $token, 'email' => $user->email_uti]);
                Mail::raw("Cliquez sur ce lien pour réinitialiser votre mot de passe: $resetUrl", function ($message) use ($user) {
                    $message->to($user->email_uti)->subject('Demande de réinitialisation de mot de passe');
                });
            }
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', 'Si un compte existe, un lien de réinitialisation a été envoyé.')
            : back()->withErrors(['email_uti' => __($status)]);
    }

    public function showResetPassword(Request $request, $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email_uti' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email_uti', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                // Update only the password field
                $user->mot_de_passe_uti = Hash::make($password);
                $user->save();

                event(new PasswordReset($user));
            }
        );

       return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', 'Réinitialisation du mot de passe réussie')
            : back()->withErrors(['email_uti' => __($status)]);
    }
}