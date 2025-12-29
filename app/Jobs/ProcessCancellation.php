<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\OrderCancellationRequest;

class ProcessCancellation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $requestModel;

    public function __construct(OrderCancellationRequest $requestModel)
    {
        $this->requestModel = $requestModel;
    }

    public function handle()
    {
        // TODO: implement refund / stock restore / notify user logic
        // This is intentionally left as a stub to be expanded per payment gateway and inventory rules.
    }
}
