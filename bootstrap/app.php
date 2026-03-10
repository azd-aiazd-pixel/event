<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Auth; 

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        
      
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'admin' => \App\Http\Middleware\CheckRole::class . ':admin',
            'store' => \App\Http\Middleware\CheckRole::class . ':store',
            'participant' => \App\Http\Middleware\CheckRole::class . ':participant',
        ]);

      
        $middleware->redirectUsersTo(function () {
          
            $user = Auth::user();

         
         
            
            if ($user->isAdmin()) {
                return route('admin.dashboard');
            }

            if ($user->isStore()) {
                return route('store.select');
            }

            if ($user->isParticipant()) {
                return route('participant.dashboard');
            }

         abort(403, 'Accès refusé : Votre rôle ne permet pas de redirection automatique.');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();