<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class WeatherController extends Controller
{
    /**
     * Menampilkan halaman utama dashboard.
     */
    public function dashboard()
    {
        return view('dashboard');
    }

    /**
     * Mengambil data cuaca via AJAX dan SELALU mengembalikan JSON.
     */
    public function getWeather(Request $request)
    {
        // Validasi input
        $city = $request->input('city');
        if (empty($city)) {
            return response()->json(['error' => 'Nama kota tidak boleh kosong.'], 400);
        }

        $apiKey = env('OPENWEATHER_API_KEY');
        $client = new Client(['timeout' => 10]); // Set timeout untuk Guzzle

        try {
            // Permintaan ke OpenWeatherMap API
            $response = $client->get("https://api.openweathermap.org/data/2.5/weather", [
                'query' => [
                    'q' => $city,
                    'appid' => $apiKey,
                    'units' => 'metric',
                    'lang' => 'id'
                ]
            ]);

            $weatherData = json_decode($response->getBody()->getContents(), true);

            // Pastikan data yang diterima valid
            if ($response->getStatusCode() === 200 && isset($weatherData['main'])) {
                $data = [
                    'city' => $weatherData['name'],
                    'country' => $weatherData['sys']['country'],
                    'temperature' => $weatherData['main']['temp'],
                    'description' => $weatherData['weather'][0]['description'],
                    'icon' => $weatherData['weather'][0]['icon'],
                    'humidity' => $weatherData['main']['humidity'],
                    'wind_speed' => $weatherData['wind']['speed'],
                    'clouds' => $weatherData['clouds']['all'],
                    'sunrise' => $weatherData['sys']['sunrise'],
                    'sunset' => $weatherData['sys']['sunset'],
                ];
                
                // Kembalikan respons JSON yang bersih
                return response()->json(['success' => true, 'data' => $data]);
            } else {
                // Jika data tidak lengkap meski status 200
                return response()->json(['error' => 'Data cuaca tidak lengkap dari server.'], 404);
            }

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            $errorMessage = 'Kota tidak ditemukan. Mohon periksa kembali nama kota.';
            
            if ($statusCode !== 404) {
                Log::error('OpenWeatherMap ClientException: ' . $e->getMessage());
                $errorMessage = 'Terjadi kesalahan saat mengambil data cuaca.';
            }
            return response()->json(['error' => $errorMessage], $statusCode);

        } catch (\Exception $e) {
            Log::error('General Error in getWeather: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan tidak terduga di server.'], 500);
        }
    }
}