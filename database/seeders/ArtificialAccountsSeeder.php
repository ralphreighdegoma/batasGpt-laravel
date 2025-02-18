<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Post;

class ArtificialAccountsSeeder extends Seeder
{

    private $geminiApiKey;
    private $geminiEndpoint = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent';

    public function __construct()
    {
        $this->geminiApiKey = env('GOOGLE_GEMINI_API_KEY');
        
        if (!$this->geminiApiKey) {
            throw new \Exception('GOOGLE_GEMINI_API_KEY not found in environment variables');
        }
    }

    private function callGeminiApi($prompt)
    {
        $client = new \GuzzleHttp\Client();

        try {
            $response = $client->post($this->geminiEndpoint . '?key=' . $this->geminiApiKey, [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ]
                ]
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gemini API Error: ' . $e->getMessage());
            throw $e;
        }
    }
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $data = $this->callGeminiApi('Generate 10 random user accounts with realistic values, avoid names like John, Jane, etc. Make it realistic and diverse names in the following json format: {users: [{name: "fill", email: "user email", bio: "user bio", title: "user title", address: "user address"}]}');
        $accounts = $this->handleGeminiResponse($data);
   
        //save to database with verified email and hashed id and password random 10 characters
        foreach ($accounts as $account) {
            foreach($account as $user){
                $userData = [
                    'name' => $user['name'],
                    'email' => random_int(3, 1000) . $user['email'],
                    'bio' => $user['bio'], 
                    'title' => $user['title'],
                    'address' => $user['address'],
                'password' => Hash::make(Str::random(10)),
                'hashId' => $this->generateHashId(),
                'email_verified_at' => now()
                ];
                $user = User::create($userData);

                $post = $this->generatePostContent();

                //create post
                $post = [
                        'user_id' => $user->id,
                        'content' => $post,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                Post::create($post);
            }
        }
    }

    private function generatePostContent()
    {
        $data = $this->callGeminiApi('Generate 1 random post content, avoid using ** for bolding, use bisayan language, make it short, dont use complete words, its like someone just joined a social network so make it short and simple, 100 words max');
        $data =  $data['candidates'][0]['content']['parts'][0]['text'];
        return $data;
    }

    private function handleGeminiResponse($response)
    {
        try {
            // Extract text from Gemini response
            $jsonText = $response['candidates'][0]['content']['parts'][0]['text'];
            
            // Clean up JSON string by removing markdown code blocks
            $jsonText = str_replace('```json', '', $jsonText);
            $jsonText = str_replace('```', '', $jsonText);
            
            // Decode JSON string to array
            $data = json_decode($jsonText, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Invalid JSON response');
            }
            
            return $data;
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error handling Gemini response: ' . $e->getMessage());
            return [];
        }
    }

    public function generateHashId()
    {
        //generate hash id but check if it is unique
        $hashId = Str::random(10);
        while (User::where('hashId', $hashId)->exists()) {
            $hashId = Str::random(10);
        }
        return $hashId;
    }
}
