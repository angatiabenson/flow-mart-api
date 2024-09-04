<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class GitHubWebhookService
{
    protected $repoDir;
    protected $branch;
    protected $secret;

    public function __construct()
    {
        $this->repoDir = config('app.github_repo_dir'); // Set this in your .env file
        $this->branch = config('app.github_branch', 'main'); // Default to 'main'
        $this->secret = config('app.github_secret');
    }

    public function handleWebhook($payload, $signature)
    {
        if (!$this->verifySignature($payload, $signature)) {
            Log::error('GitHub webhook signature verification failed.');
            return ['error' => true, 'message' => 'GitHub webhook signature verification failed.'];
        }

        $data = json_decode($payload);

        if ($data->ref === 'refs/heads/' . $this->branch) {
            return $this->performGitPull();
        }

        return ['error' => true, 'message' => "Pushed branch is not the target branch"];
    }

    protected function performGitPull()
    {
        try {
            chdir($this->repoDir);
            $this->runProcess(['git', 'pull', 'origin', $this->branch]);

            $this->clearCache();
            $this->configureApp();
            $this->optimizeApp();

            Log::info('GitHub webhook pull and cache clearing successful.');
            return ['error' => false, 'message' => "Pull and cache clearing successful."];
        } catch (ProcessFailedException $exception) {
            Log::error('GitHub webhook pull failed: ' . $exception->getMessage());
            return ['error' => true, 'message' => $exception->getMessage()];
        }
    }

    protected function clearCache()
    {
        $this->runProcess(['php', 'artisan', 'cache:clear']);
        $this->runProcess(['php', 'artisan', 'config:clear']);
        $this->runProcess(['php', 'artisan', 'route:clear']);
        $this->runProcess(['php', 'artisan', 'view:clear']);
    }

    protected function configureApp()
    {
        $this->runProcess(['php', 'artisan', 'config:cache']);
        $this->runProcess(['php', 'artisan', 'route:cache']);
    }

    protected function optimizeApp()
    {
        $this->runProcess(['php', 'artisan', 'optimize']);
    }

    protected function runProcess(array $command, $workingDirectory = null)
    {
        $process = new Process($command);
        if ($workingDirectory) {
            $process->setWorkingDirectory($workingDirectory);
        }
        $process->mustRun();
    }

    protected function verifySignature($payload, $githubSignature)
    {
        $signature = 'sha1=' . hash_hmac('sha1', $payload, $this->secret, false);
        return hash_equals($signature, $githubSignature);
    }
}