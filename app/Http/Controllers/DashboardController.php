<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman utama dashboard simulasi untuk user.
     */
    public function index()
    {
        // Cukup tampilkan view-nya. Semua aksi akan dilakukan via JavaScript.
        return view('dashboard');
    }

        /**
     * Endpoint KHUSUS UNTUK AJAX.
     * Mengambil data cuaca dan mengembalikannya sebagai JSON.
     */
    public function getWeatherForSimulation(Request $request)
    {
        // GANTI $request->input() MENJADI $request->json()
        $city = $request->json('city'); 

        if (empty($city)) {
            return response()->json(['error' => 'Nama kota tidak boleh kosong.'], 400);
        }

        $apiKey = env('OPENWEATHER_API_KEY');
        if (empty($apiKey)) {
            Log::error('OpenWeatherMap API Key is not set in .env file.');
            return response()->json(['error' => 'Konfigurasi server tidak lengkap.'], 500);
        }
        
        $client = new Client();

        try {
            $response = $client->get("https://api.openweathermap.org/data/2.5/weather", [
                'query' => [ 
                    'q' => $city, 
                    'appid' => $apiKey, 
                    'units' => 'metric', 
                    'lang' => 'id' 
                ]
            ]);
            $weatherData = json_decode($response->getBody(), true);

            if ($response->getStatusCode() === 200 && isset($weatherData['main'])) {
            $data = [
                'city' => $weatherData['name'],
                'temperature' => $weatherData['main']['temp'],
                'feels_like' => $weatherData['main']['feels_like'], // <-- DATA BARU
                'pressure' => $weatherData['main']['pressure'],     // <-- DATA BARU
                'visibility' => $weatherData['visibility'],         // <-- DATA BARU
                'description' => $weatherData['weather'][0]['description'],
                'icon' => $weatherData['weather'][0]['icon'],
                'humidity' => $weatherData['main']['humidity'],
                'wind_speed' => $weatherData['wind']['speed'],
                'clouds' => $weatherData['clouds']['all'],
                'sunrise' => $weatherData['sys']['sunrise'],
                'sunset' => $weatherData['sys']['sunset'],
            ];
            return response()->json(['success' => true, 'data' => $data]);
            } else {
                return response()->json(['error' => 'Kota tidak ditemukan.'], 404);
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            // Ini secara spesifik menangani error 401 (API Key salah) atau 404 (Kota tidak ditemukan)
            $statusCode = $e->getResponse()->getStatusCode();
            $message = 'Kota tidak ditemukan.';
            if($statusCode == 401){
                $message = 'API Key tidak valid. Hubungi administrator.';
            }
            Log::error("OpenWeatherMap ClientException for city {$city}: " . $e->getMessage());
            return response()->json(['error' => $message], $statusCode);

        } catch (\Exception $e) {
            Log::error("General Weather Simulation Error for city {$city}: " . $e->getMessage());
            return response()->json(['error' => 'Gagal mengambil data cuaca saat ini.'], 500);
        }
    }
}