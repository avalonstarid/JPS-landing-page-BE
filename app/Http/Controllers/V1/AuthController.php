<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\UpdatePasswordRequest;
use App\Http\Requests\Auth\UpdateProfileRequest;
use App\Http\Requests\Auth\VerifyOtpRequest;
use App\Http\Resources\Auth\FetchResource;
use App\Models\User;
use Exception;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Unauthenticated;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Throwable;

#[Group("Auth", "API Endpoint for User Management.")]
class AuthController extends Controller
{
	/**
	 * Login
	 *
	 * @param LoginRequest $request
	 *
	 * @return JsonResponse
	 */
	#[Unauthenticated]
	public function login(LoginRequest $request): JsonResponse
	{
		$request->authenticate();

		$user = User::where('email', $request->safe()->input('email'))->firstOrFail();

		$user->sendOneTimePassword();

		return $this->response(
			message: 'Login berhasil. Lanjutkan ke proses verifikasi OTP.',
		);
	}

	/**
	 * Logout User
	 *
	 * @param Request $request
	 * @param         $type
	 *
	 * @return JsonResponse
	 */
	public function logout(Request $request, $type): JsonResponse
	{
		if ($type == 'cookie') {
			Auth::guard('web')->logout();

			$request->session()->invalidate();
			$request->session()->regenerateToken();
		} else {
			auth()->user()->currentAccessToken()->delete();
		}

		return $this->response(
			message: 'Logout berhasil.',
		);
	}

	/**
	 * Register
	 *
	 * @param RegisterRequest $request
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	#[Unauthenticated]
	public function register(RegisterRequest $request): JsonResponse
	{
		try {
			DB::beginTransaction();

			$data = User::create($request->validated());
			$data->assignRole('user');

			DB::commit();

			$data->sendOneTimePassword();

			return $this->response(
				message: 'Registrasi akun berhasil.',
				data: $data,
			);
		} catch (Exception $e) {
			DB::rollBack();

			return $this->response(
				message: $e->getMessage(),
				status_code: $e->getCode(),
			);
		}
	}

	/**
	 * Fetch Current User
	 *
	 * @param $type
	 *
	 * @return JsonResponse
	 */
	public function fetch($type): JsonResponse
	{
		$user = auth()->user()->load([
			'roles',
			'permissions',
		]);

		if ($type == 'cookie') {
			return response()->json(new FetchResource($user));
		} else {
			return $this->response(
				message: 'Berhasil mengambil data.',
				data: new FetchResource($user),
			);
		}
	}

	/**
	 * Forgot Password
	 *
	 * @throws ValidationException
	 */
	#[Unauthenticated]
	public function forgotPassword(Request $request): JsonResponse
	{
		$this->validate($request, [
			'email' => ['required', 'email'],
		]);

		$status = Password::sendResetLink(
			$request->only('email'),
		);

		if ($status != Password::RESET_LINK_SENT) {
			throw ValidationException::withMessages([
				'email' => __($status),
			]);
		}

		return $this->response(
			message: 'Forgot password successfully. Please check your email.',
		);
	}

	/**
	 * Verify Token Reset Password
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	#[Unauthenticated]
	public function verifyToken(Request $request): JsonResponse
	{
		$this->validate($request, [
			'email' => ['required', 'email'],
			'token' => ['required'],
		]);

		$credentials = $request->only('email', 'token');

		if (is_null($user = Password::getUser($credentials))) {
			return $this->response(__(Password::INVALID_USER), null, 404);
		}

		if (!Password::tokenExists($user, $request->token)) {
			return $this->response(__(Password::INVALID_TOKEN), null, 404);
		}

		return $this->response(
			message: 'Verify token successfully.',
		);
	}

	/**
	 * Reset Password
	 *
	 * @throws ValidationException
	 */
	#[Unauthenticated]
	public function resetPassword(Request $request): JsonResponse
	{
		$this->validate($request, [
			'token' => ['required'],
			'email' => ['required', 'email'],
			'password' => ['required', 'string', 'confirmed', 'min:8'],
		]);

		$status = Password::reset(
			$request->only('email', 'password', 'password_confirmation', 'token'),
			function ($user, $password) {
				$user->forceFill([
					'password' => $password,
				])->setRememberToken(Str::random(60));

				$user->save();

				event(new PasswordReset($user));
			},
		);

		if ($status != Password::PASSWORD_RESET) {
			throw ValidationException::withMessages([
				'email' => __($status),
			]);
		}

		return $this->response(
			message: 'Reset password berhasil.',
		);
	}

	/**
	 * Update Profile User
	 *
	 * @param UpdateProfileRequest $request
	 *
	 * @return JsonResponse
	 * @throws Throwable
	 */
	public function updateProfile(UpdateProfileRequest $request): JsonResponse
	{
		try {
			DB::beginTransaction();

			$user = auth()->user();
			$user->update($request->validated());

			DB::commit();

			return $this->response(
				message: 'Berhasil update profile.',
			);
		} catch (Exception $e) {
			DB::rollBack();

			return $this->response(
				message: $e->getMessage(),
				status_code: $e->getCode(),
			);
		}
	}

	/**
	 * Update Password User
	 *
	 * @throws ValidationException
	 */
	public function updatePassword(UpdatePasswordRequest $request): JsonResponse
	{
		auth()->user()->update($request->only('password'));

		return $this->response(
			message: 'Berhasil ganti password.',
		);
	}

	/**
	 * Update Avatar User
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 * @throws FileDoesNotExist
	 * @throws FileIsTooBig
	 */
	public function updateAvatar(Request $request): JsonResponse
	{
		$validated = $request->validate([
			'avatar' => ['required', 'file', 'image', 'max:5120'],
		]);

		$filename = Str::random(30) . '.' . $request->file('avatar')->getClientOriginalExtension();
		$user = auth()->user();
		$user->addMediaFromRequest('avatar')->usingFileName($filename)->toMediaCollection('avatar');

		return $this->response(
			message: 'Berhasil upload avatar.',
		);
	}

	/**
	 * Request Resend OTP
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	#[Unauthenticated]
	public function reqOtp(Request $request): JsonResponse
	{
		try {
			$this->validate($request, [
				'email' => ['required', 'email', Rule::exists('users', 'email')],
			]);

			$user = User::whereEmail($request->email)->first();

			$user->sendOneTimePassword();

			return $this->response(
				message: 'Request OTP successfully.',
			);
		} catch (ValidationException $e) {
			return $this->response(
				message: $e->getMessage(),
				errors: $e->validator->errors()->toArray(),
				status_code: 422,
			);
		} catch (Exception $e) {
			return $this->response(
				message: $e->getMessage(),
				status_code: $e->getCode(),
			);
		}
	}

	/**
	 * Verifikasi OTP
	 *
	 * @param VerifyOtpRequest $request
	 *
	 * @return JsonResponse
	 */
	#[Unauthenticated]
	public function verifyOtp(VerifyOtpRequest $request): JsonResponse
	{
		try {
			$user = User::whereEmail($request->safe()->input('email'))->first();

			$result = $user->consumeOneTimePassword($request->safe()->input('otp'));

			if ($result->isOk()) {
				if ($user->email_verified_at == null) {
					$user->update([
						'email_verified_at' => now(),
					]);
				}

				if ($request->safe()->string('type') == 'cookie') {
					Auth::login($user);
					$request->session()->regenerate();

					return $this->response(
						message: 'Verify OTP berhasil.',
					);
				} else if ($request->safe()->string('type') == 'token') {
					return $this->response(
						message: 'Verify OTP berhasil.',
						data: [
							'token' => $user->createToken('app-token')->plainTextToken,
							'user' => $user,
						],
					);
				} else {
					return $this->response(
						message: 'Verify OTP berhasil.',
					);
				}
			}

			throw ValidationException::withMessages([
				'otp' => $result->validationMessage(),
			]);
		} catch (Exception $e) {
			return $this->response(
				message: $e->getMessage(),
				status_code: $e->getCode(),
			);
		}
	}
}
