<?php

// namespace App\Http\Middleware;


// use Closure;
// use Illuminate\Support\Facades\URL;

// class SignURLMiddleware
// {
//     public function handle($request, Closure $next)
//     {
//         $this->signUrlsInRequest($request);

//         return $next($request);
//     }

//     protected function signUrlsInRequest($request)
//     {
//         $routeNames = [
//             'login.login-akun',
//             'login.login-proses',
//         ];

//         foreach ($routeNames as $routeName) {
//             $signedUrl = URL::signedRoute($routeName, [], now()->addHour());

//             $request->replace(
//                 $this->replaceUrlInArray(route($routeName, [], false), $signedUrl, $request->all())
//             );
//         }
//     }

//     protected function replaceUrlInArray($url, $signedUrl, $array)
//     {
//         $result = [];

//         foreach ($array as $key => $value) {
//             if (is_array($value)) {
//                 $result[$key] = $this->replaceUrlInArray($url, $signedUrl, $value);
//             } elseif (is_string($value)) {
//                 $result[$key] = str_replace($url, $signedUrl, $value);
//             } else {
//                 $result[$key] = $value;
//             }
//         }

//         return $result;
//     }
// }
