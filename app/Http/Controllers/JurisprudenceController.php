<?php

namespace App\Http\Controllers;

use App\Models\Jurisprudence;
use App\Services\PineconeService;
use Illuminate\Http\Request;
use App\Services\JurisprudenceService;



class JurisprudenceController extends Controller
{
    protected $pineconeService;
    protected $jurisprudenceService;


    public function __construct(
        PineconeService $pineconeService,
        JurisprudenceService $jurisprudenceService
    ) {
        $this->pineconeService = $pineconeService;
        $this->jurisprudenceService = $jurisprudenceService;
    }

    public function upsertJurisprudenceToPinecone($id)
    {
        $jurisprudence = Jurisprudence::findOrFail($id);

        try {
            $result = $this->pineconeService->upsertJurisprudence($jurisprudence->id, $jurisprudence->content);
            return response()->json(['success' => true, 'message' => 'Upserted successfully', 'result' => $result]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function search(Request $request)
    {

        //validate and return error if query is empty

        if (empty($request->input('query'))) {
            return response()->json([
                'success' => false,
                'error' => 'Query is empty'
            ]);
        }

        
        $query = $request->input('query');
        $results = $this->jurisprudenceService->search($query);

        return response()->json([
            'success' => true,
            'results' => $results
        ]);
    }
}
