<?php
// app/Http/Middleware/AdminMiddleware.php
namespace App\Http\Middleware;
use Closure;
class AdminMiddleware {
    public function handle($request, Closure $next) {
        if ($request->user()?->role !== 'admin') {
            return response()->json(['message' => 'Akses hanya untuk admin'], 403);
        }
        return $next($request);
    }
}
