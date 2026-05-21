<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class AutoJsonResponse
{
    /**
     * Otomatis mengubah View Response menjadi JSON Response jika diminta.
     * Sangat berguna untuk dokumentasi API tanpa merubah kode Controller.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Jika request menginginkan JSON ATAU dikirim dari fitur 'Try It' Scramble
        if (($request->wantsJson() || $request->header('X-Scramble-Try-It')) && !$request->is('docs/api*')) {
            
            // Jika response berupa Redirect (seperti redirect() atau back())
            if ($response instanceof \Illuminate\Http\RedirectResponse) {
                return response()->json([
                    'success' => true,
                    'message' => session('success') ?? 'Proses berhasil dilakukan.',
                    'redirect' => $response->getTargetUrl()
                ]);
            }

            // Jika isi response adalah sebuah View (Blade)
            if ($response instanceof Response && $response->getOriginalContent() instanceof View) {
                $view = $response->getOriginalContent();
                $data = $view->getData();

                // Bersihkan data dari objek-objek internal Laravel yang sangat besar/kompleks
                $filteredData = [];
                foreach ($data as $key => $value) {
                    // Abaikan data internal Laravel
                    if (in_array($key, ['errors', '__env', 'app', 'obLevel', 'message'])) continue;
                    
                    // Masukkan data ke array hasil
                    $filteredData[$key] = $value;
                }

                try {
                    return response()->json($filteredData);
                } catch (\Exception $e) {
                    // Jika gagal serialisasi (seperti error Enum tadi), kita kembalikan data yang sudah di-array-kan
                    return response()->json(json_decode(json_encode($filteredData, JSON_PARTIAL_OUTPUT_ON_ERROR), true));
                }
            }
        }

        return $response;
    }
}
