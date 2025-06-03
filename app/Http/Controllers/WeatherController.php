<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log; // Untuk logging error

class WeatherController extends Controller
{
    public function index()
    {
        // Menampilkan halaman utama dengan form pencarian
        return view('weather');
    }

    public function getWeather(Request $request)
    {
        $city = $request->input('city'); // Ambil nama kota dari input form

        // Validasi input kota
        if (empty($city)) {
            // Jika ini request AJAX, kembalikan JSON error
            if ($request->ajax()) {
                return response()->json(['error' => 'Nama kota tidak boleh kosong.'], 400);
            }
            // Jika bukan AJAX, lakukan redirect seperti biasa
            return redirect()->back()->with('error', 'Nama kota tidak boleh kosong.');
        }

        $apiKey = env('OPENWEATHER_API_KEY'); // Ambil API Key dari .env
        $client = new Client(); // Inisialisasi Guzzle HTTP client

        try {
            // Buat permintaan ke OpenWeatherMap API
            $response = $client->get("https://api.openweathermap.org/data/2.5/weather", [
                'query' => [
                    'q' => $city, // Nama kota
                    'appid' => $apiKey, // Kunci API
                    'units' => 'metric', // Satuan suhu (metric untuk Celcius, imperial untuk Fahrenheit)
                    'lang' => 'id' // Bahasa (id untuk Indonesia)
                ]
            ]);

            $weatherData = json_decode($response->getBody(), true); // Dekode respons JSON

            // Pastikan data yang diterima valid
            if ($weatherData && isset($weatherData['main']) && isset($weatherData['weather'][0])) {
                $data = [
                    'city' => $weatherData['name'],
                    'country' => $weatherData['sys']['country'],
                    'temperature' => $weatherData['main']['temp'],
                    'feels_like' => $weatherData['main']['feels_like'],
                    'temp_min' => $weatherData['main']['temp_min'],
                    'temp_max' => $weatherData['main']['temp_max'],
                    'description' => $weatherData['weather'][0]['description'],
                    'icon' => $weatherData['weather'][0]['icon'],
                    'humidity' => $weatherData['main']['humidity'],
                    'pressure' => $weatherData['main']['pressure'],
                    'wind_speed' => $weatherData['wind']['speed'],
                    'wind_deg' => isset($weatherData['wind']['deg']) ? $weatherData['wind']['deg'] : null,
                    'visibility' => isset($weatherData['visibility']) ? $weatherData['visibility'] : null, // Kirim dalam meter, JS akan konversi ke km
                    'clouds' => $weatherData['clouds']['all'],
                    // Kirim timestamp mentah (integer) untuk sunrise dan sunset
                    'sunrise' => isset($weatherData['sys']['sunrise']) ? $weatherData['sys']['sunrise'] : null,
                    'sunset' => isset($weatherData['sys']['sunset']) ? $weatherData['sys']['sunset'] : null,
                    'timezone' => isset($weatherData['timezone']) ? $weatherData['timezone'] : null,
                    'weather_id' => $weatherData['weather'][0]['id'] ?? null, // Tambahkan weather_id untuk dynamic background
                    'coord' => [
                        'lat' => $weatherData['coord']['lat'],
                        'lon' => $weatherData['coord']['lon']
                    ]
                ];

                // Jika ini request AJAX, kembalikan JSON data cuaca
                if ($request->ajax()) {
                    return response()->json(['success' => true, 'data' => $data]);
                }

                // Jika bukan AJAX, kirim data ke view seperti biasa
                return view('weather', compact('data'));
            } else {
                // Jika ini request AJAX, kembalikan JSON error
                if ($request->ajax()) {
                    return response()->json(['error' => 'Data cuaca tidak ditemukan atau tidak lengkap.'], 404);
                }
                // Jika bukan AJAX, lakukan redirect seperti biasa
                return redirect()->back()->with('error', 'Data cuaca tidak ditemukan atau tidak lengkap.');
            }

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            $errorMessage = '';

            if ($statusCode === 404) {
                $errorMessage = 'Kota tidak ditemukan. Mohon periksa kembali nama kota.';
            } else {
                Log::error('OpenWeatherMap API Error: ' . $e->getMessage());
                $errorMessage = 'Terjadi kesalahan saat mengambil data cuaca. Silakan coba lagi nanti.';
            }

            // Jika ini request AJAX, kembalikan JSON error
            if ($request->ajax()) {
                return response()->json(['error' => $errorMessage], $statusCode);
            }
            // Jika bukan AJAX, lakukan redirect seperti biasa
            return redirect()->back()->with('error', $errorMessage);

        } catch (\Exception $e) {
            Log::error('General Error: ' . $e->getMessage());
            $errorMessage = 'Terjadi kesalahan tak terduga. Silakan coba lagi nanti.';

            // Jika ini request AJAX, kembalikan JSON error
            if ($request->ajax()) {
                return response()->json(['error' => $errorMessage], 500);
            }
            // Jika bukan AJAX, lakukan redirect seperti biasa
            return redirect()->back()->with('error', $errorMessage);
        }
    }
}
