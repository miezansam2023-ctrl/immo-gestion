<?php
 
namespace App\Http\Middleware;
 
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
 
class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier que l'utilisateur est connecté et admin
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            // Rediriger vers le dashboard si connecté mais pas admin
            if (auth()->check()) {
                return redirect()->route('dashboard')
                    ->with('error', 'Accès non autorisé.');
            }
            // Rediriger vers login si non connecté
            return redirect()->route('login');
        }
 
        return $next($request);
    }
}
