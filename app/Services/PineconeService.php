<?php

namespace App\Services;

use OpenAI\Client;
use Illuminate\Support\Facades\Http;

class PineconeService
{
    protected $pineconeUrl;
    protected $pineconeKey;
    protected $indexName;

    public function __construct()
    {
        $this->pineconeUrl = config('services.pinecone.url');
        $this->pineconeKey = config('services.pinecone.key');
        $this->indexName = config('services.pinecone.index');
    }

    // Upsert a single jurisprudence vector
    public function upsertJurisprudence($jurisprudenceId, $content)
    {
        // Step 1: Generate embeddings using OpenAI
        $vector = $this->generateEmbedding($content);

        if (!$vector) {
            throw new \Exception("Failed to generate vector embeddings.");
        }

        // Step 2: Upsert the vector to Pinecone
        $data = [
            'vectors' => [
                [
                    'id' => (string) $jurisprudenceId,
                    'values' => $vector,
                    'metadata' => ['type' => 'jurisprudence']
                ],
            ],
        ];

        $response = Http::withHeaders([
            'Api-Key' => $this->pineconeKey,
        ])->post("{$this->pineconeUrl}/indexes/{$this->indexName}/vectors/upsert", $data);

        return $response->json();
    }

    private function generateEmbedding($text)
    {
        $client = \OpenAI::client(env('OPENAI_API_KEY'));

        $response = $client->embeddings()->create([
            'model' => 'text-embedding-ada-002',
            'input' => $text,
        ]);

        return $response['data'][0]['embedding'] ?? null;
    }
}
