<?php

namespace App\Exceptions;

use App\rZeBot\rZeBotCommons;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\Route;
use App\Model\Scene;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof NotFoundHttpException) {

            if (Route::current() !== null) {
                $commons = new rZeBotCommons();
                $routeData = $route = Route::current()->parameters();

                $scenes = Scene::getTranslationSearch(false, $commons->language->id)
                    ->where('site_id', $commons->site->id)
                    ->where('status', 1)
                    ->orderBy('scene_id', 'desc')
                    ->paginate(24)
                ;
                
                return response()->view('tube.errors.404', [
                    'seo_title'       => $commons->site->getCategoriesTitle(),
                    'seo_description' => $commons->site->getCategoriesDescription(),
                    'site'            => $commons->site,
                    'profile'         => $routeData['host'],
                    'query_string'    => $request->input('q'),
                    'language'        => $commons->language,
                    'scenes'          => $scenes
                ], 404);
            }
        }

        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest(route('login'));
    }
}
