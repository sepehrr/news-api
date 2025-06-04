<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\LogoutRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\SetPasswordRequest;
use App\Http\Resources\UserResource;
use App\Mail\ResetPasswordMail;
use App\Models\PasswordResetToken;
use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Mail;

/** @package App\Http\Controllers\V1 */
class AuthController extends Controller
{
    /**
     * @OA/Post(
     *     path="/v1/auth/login",
     *     summary="User Login",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="email", type="string", format="email", example="test@example.com" description="User email"),
     *             @OA\Property(property="password", type="string", format="password", example="password123", description="User password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Login successful."),
     *             @OA\Property(property="data", type="object", ref="#/components/schemas/UserResource"),
     *             @OA\Property(property="token", type="string", example="your_generated_token_here")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid credentials")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object", additionalProperties=@OA\Schema(type="array", @OA\Items(type="string")))
     *         )
     *     )
     */
    public function login(LoginRequest $request)
    {
        if (!auth()->attempt($request->validated())) {
            return $this->error('Invalid credentials', 401);
        }

        event(new Login('sactum', auth()->user(), false));
        $token = auth()->user()->createToken('auth_token')->plainTextToken;

        return $this->success(__('Login successful.'), [
            'user' => UserResource::make(auth()->user()),
            'token' => $token,
        ]);


    }

    /**
     * @OA/Post(
     *     path="/v1/auth/register",
     *     summary="User Registration",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="test@test.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Registration successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Registration successful."),
     *             @OA\Property(property="data", type="object", ref="#/components/schemas/UserResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object", additionalProperties=@OA\Schema(type="array", @OA\Items(type="string")))
     *         )
     *     )
     * )
     */
    public function registration(RegisterRequest $request)
    {
        $user = User::create($request->validated());
        event(new Registered(auth()->user()));

        return $this->success(__('Registration successful.'), UserResource::make($user), 201);
    }

    /**
     * @OA/Post(
     *     path="/v1/auth/logout",
     *     summary="User Logout",
     *     tags={"Authentication"},
     *     @OA\Response(
     *         response=200,
     *         description="Logout successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Logout successful.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     )
     * )
     */
    public function logout(LogoutRequest $request)
    {
        auth()->user()->currentAccessToken()->delete();

        return $this->success(__('Logout successful.'));
    }

    /**
     * @OA/Post(
     *     path="/v1/auth/reset-password",
     *     summary="Request Password Reset",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="email", type="string", format="email", example="example@test.com", description="User email")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password reset link sent",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Password reset link sent")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object", additionalProperties=@OA\Schema(type="array", @OA\Items(type="string")))
     *         )
     *     )
     * )
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user) {
            $token = $user->passwordResetTokens()->create()->token;
            defer(
                fn () => Mail::to($user->email)->send(new ResetPasswordMail($token))
            );
        }

        // NOTE - for security reasons, even if user is not found, we return the same response
        // to prevent user enumeration attacks.
        // This is a common practice to avoid revealing whether an email is registered or not.
        return $this->success(__('Password reset link sent.'));
    }

    /**
     * @OA/Post(
     *     path="/v1/auth/set-password",
     *     summary="Set New Password",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *       required=true,
     *       @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="token", type="string", example="your_reset_token_here", description="Password reset token"),
     *         @OA\Property(property="password", type="string", format="password", example="newpassword123", description="New password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password has been set successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Password has been set successfully.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object", additionalProperties=@OA\Schema(type="array", @OA\Items(type="string")))
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Token not found or expired",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Token not found or expired.")
     *         )
     *     )
     * )
     */
    public function setPassword(SetPasswordRequest $request)
    {
        $token = PasswordResetToken::findByToken($request->token);
        $token->user->update(['password' => $request->password]);
        $token->delete();

        return $this->success(__('Password has been set successfully.'));
    }
}
