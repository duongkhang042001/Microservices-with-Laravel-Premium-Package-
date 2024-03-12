<?php

namespace App\Http\Controllers;

use App\Services\MarketDataProvider;
use Exception;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Response;

class AvailableTickerController extends Controller
{
    public function __construct(private MarketDataProvider $marketDataProvider)
    {
    }

    public function index()
    {
        try {
            return [
                'data' => $this->marketDataProvider->getAvailableTickers()
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
