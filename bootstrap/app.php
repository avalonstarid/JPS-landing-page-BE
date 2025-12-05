<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Spatie\LaravelImageOptimizer\Middlewares\OptimizeImages;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
	->withRouting(
		web: __DIR__ . '/../routes/web.php',
		api: __DIR__ . '/../routes/api.php',
		commands: __DIR__ . '/../routes/console.php',
		channels: __DIR__ . '/../routes/channels.php',
		health: '/up',
		apiPrefix: '',
	)
	->withMiddleware(function (Middleware $middleware) {
		$middleware->statefulApi();

		$middleware->alias([
			'optimizeImages' => OptimizeImages::class,
			'permission' => PermissionMiddleware::class,
			'role' => RoleMiddleware::class,
			'role_or_permission' => RoleOrPermissionMiddleware::class,
		]);

		$middleware->trustProxies(
			at: '*',
			headers: Request::HEADER_X_FORWARDED_FOR |
			Request::HEADER_X_FORWARDED_HOST |
			Request::HEADER_X_FORWARDED_PORT |
			Request::HEADER_X_FORWARDED_PROTO |
			Request::HEADER_X_FORWARDED_AWS_ELB,
		);

//		$middleware->trustHosts(at: ['catatkaya.com']);
	})
	->withExceptions(function (Exceptions $exceptions) {
		$exceptions->render(function (AuthenticationException $e, $request) {
			return response()->json([
				'success' => false,
				'message' => 'Silakan login untuk melanjutkan.',
				'data' => null,
				'errors' => null,
			], 401);
		});
	})->create();
