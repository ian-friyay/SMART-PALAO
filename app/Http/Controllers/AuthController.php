<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Show login form.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return Auth::user()->isOwner()
                ? redirect()->route('admin.dashboard')
                : redirect()->route('store');
        }

        return view('auth.login');
    }

    /**
     * Show registration form.
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('store');
        }

        return view('auth.register');
    }

    /**
     * Handle web login.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            if (Auth::user()->isOwner()) {
                return redirect()->intended(route('admin.dashboard'))
                    ->with('success', 'Welcome back, Farm Master! Connected to Incubation Hub.');
            }

            return redirect()->intended(route('store'))
                ->with('success', 'Welcome back to Smart Palao Egg Store!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Handle web registration.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'role' => 'nullable|in:owner,buyer',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'] ?? 'buyer', // Default to buyer
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
        ]);

        Auth::login($user);

        if ($user->isOwner()) {
            return redirect()->route('admin.dashboard')->with('success', 'Account created with Owner privileges.');
        }

        return redirect()->route('store')->with('success', 'Account created! Welcome to Smart Palao.');
    }

    /**
     * Handle quick switch between Owner and Buyer for live demo & evaluation.
     */
    public function quickLogin(Request $request, string $role)
    {
        $role = strtolower($role);
        if (!in_array($role, ['owner', 'buyer'])) {
            $role = 'buyer';
        }

        $user = User::where('role', $role)->first();
        if (!$user) {
            // Create seed user if none exists
            $user = User::create([
                'name' => $role === 'owner' ? 'Don Palao (Farm Owner)' : 'Maria Santos (Egg Buyer)',
                'email' => $role === 'owner' ? 'owner@smartpalao.com' : 'buyer@gmail.com',
                'password' => Hash::make('password123'),
                'role' => $role,
                'phone' => $role === 'owner' ? '+63 917 123 4567' : '+63 928 987 6543',
                'address' => $role === 'owner' ? 'Gloria Farm, Oriental Mindoro' : 'Station 2, Boracay Island, Malay, Aklan',
            ]);
        }

        Auth::login($user);
        $request->session()->regenerate();

        if ($role === 'owner') {
            return redirect()->route('admin.dashboard')->with('success', 'Switched session to OWNER role (Full IoT & Admin Access).');
        } else {
            return redirect()->route('store')->with('success', 'Switched session to BUYER role (E-Commerce Storefront Access Only).');
        }
    }

    /**
     * Handle web logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('store')->with('info', 'You have been logged out.');
    }

    /**
     * API Login for Sanctum token generation.
     */
    public function apiLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('smart-palao-api', [$user->role])->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Authenticated successfully.',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
        ]);
    }

    /**
     * API Register.
     */
    public function apiRegister(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'nullable|in:owner,buyer',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'] ?? 'buyer',
        ]);

        $token = $user->createToken('smart-palao-api', [$user->role])->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registered successfully.',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
        ], 201);
    }

    /**
     * API Logout.
     */
    public function apiLogout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Token revoked successfully.',
        ]);
    }

    /**
     * API Me.
     */
    public function apiMe(Request $request)
    {
        return response()->json([
            'success' => true,
            'user' => $request->user(),
        ]);
    }
}
