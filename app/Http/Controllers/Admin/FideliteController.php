<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\FideliteService;
use Illuminate\Http\Request;

class FideliteController extends Controller
{
    public function envoyerCodes(Request $request, FideliteService $fideliteService)
    {
        $fideliteService->verifierEtEnvoyerCodesPromo();
        
        return redirect()->back()->with('success', 'Les codes promo ont été envoyés aux clients fidèles');
    }
}