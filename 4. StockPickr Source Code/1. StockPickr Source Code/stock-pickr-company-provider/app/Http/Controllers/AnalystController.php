<?php

namespace App\Http\Controllers;

use App\Services\MarketDataProvider;
use Exception;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Response;

class AnalystController extends Controller
{
    public function __construct(private MarketDataProvider $marketDataProvider)
    {
    }

    public function get(string $ticker)
    {
        try {
            return [
                'data' => $this->marketDataProvider->getAnalyst($ticker)->toArray()
            ];
        } catch (ClientException $ex) {
            return response()->json([
                'error' => $ex->getMessage()
            ], $ex->getResponse()->getStatusCode());
        } catch (Exception $ex) {
            return response()->json([
                'error' => $ex->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
