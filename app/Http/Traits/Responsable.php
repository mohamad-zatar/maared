<?php

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

trait Responsable
{
    public function respondSuccess($content = [], $paginator = [],$code =200): JsonResponse
    {
        return response()->json([
            'result' => 'success',
            'content' => $content,
            'paginator' => $paginator,
            'error_des' => '',
            'error_code' => 0,
//            'images_prefix' => Storage::disk('public')->url('')
        ],$code);
    }

    public function respondError($message, $validationError = null,$code = 400): JsonResponse
    {
        return response()->json([
            'result' => 'error',
            'content' => null,
            'error_des' => $message,
            'error_validation' => $validationError,
            'date' =>date('Y-m-d')
        ],$code);
    }

    public function respondOut($message): JsonResponse
    {
        return response()->json([
            'result' => 'error',
            'content' => [],
            'error_des' => $message,
            'error_code' => -1,
            'date' =>date('Y-m-d')
        ]);
    }

    public function respondMessage($message): JsonResponse
    {
        return response()->json([
            'result' => 'success',
            'content' => [],
            'error_des' => $message,
            'error_code' => 0,
            'date' =>date('Y-m-d')
        ]);
    }

    public function respondMobileError($message): JsonResponse
    {
        return response()->json([
            'result' => 'error',
            'content' => $message,
            'error_des' => $message,
            'error_code' => 1,
            'date' =>date('Y-m-d')
        ],200);
    }


    protected function auctionNotBidResponse()
    {
        return response()->json([
            'status' => 1,
            'message' => __('validation.not_bid')
        ]);
    }

    protected function auctionNotRunningResponse()
    {
        return response()->json([
            'status' => 1,
            'message' => __('validation.bid_time_not_start')
        ]);
    }

    protected function auctionExpiredResponse(): JsonResponse
    {
        return response()->json([
            'status' => 1,
            'message' => __('validation.bid_time_ended')
        ],400);
    }

    protected function unAuthenticationResponse(): JsonResponse
    {
        return response()->json([
            'status' => 1,
            'message' => __('validation.user_not_found')
        ],403);
    }

    protected function invalidBidAmountResponse($minimumBid): JsonResponse
    {
        return response()->json([
            'status' => 1,
            'message' => __('validation.minimum_bid_amount', ['value' => $minimumBid])
        ]);
    }

    protected function bidAmountNotMultipleOf500Response()
    {
        return response()->json([
            'status' => 1,
            'message' =>__('validation.bid_in_multiples_of_500_only')
        ]);
    }

    protected function insufficientUserBalanceResponse()
    {
        return response()->json([
            'status' => 1,
            'message' => __('validation.not_enough_balance')
        ]);
    }

    protected function userIsCurrentWinnerResponse()
    {
        return response()->json([
            'status' => 1,
            'message' => __('validation.user_already_winner')
        ]);
    }

    protected function successResponse()
    {
        return response()->json([
            'status' => 0,
            'message' => __('validation.bid_placed_successfully')
        ]);
    }

    protected function errorResponse($message)
    {
        return response()->json([
            'status' => 1,
            'message' => $message
        ]);
    }

}
