<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Service\GitHubWebhookService;
use Illuminate\Http\Request;


class WebhookController extends Controller
{
    protected $webhookService;

    public function __construct(GitHubWebhookService $webhookService)
    {
        $this->webhookService = $webhookService;
    }

    public function processGithubWebHookRequest(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('X-Hub-Signature');

        $result = $this->webhookService->handleWebhook($payload, $signature);

        return response()->json($result, $result['error'] ? 403 : 200);
    }
}