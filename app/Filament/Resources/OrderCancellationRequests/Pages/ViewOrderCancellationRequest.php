<?php

namespace App\Filament\Resources\OrderCancellationRequests\Pages;

use App\Filament\Resources\OrderCancellationRequests\OrderCancellationRequestResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use App\Jobs\ProcessCancellation;

class ViewOrderCancellationRequest extends ViewRecord
{
    protected static string $resource = OrderCancellationRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('approve')
                ->label('Approve')
                ->color('success')
                ->requiresConfirmation()
                ->action(function () {
                    $record = $this->record;
                    if ($record->status !== 'requested') {
                        Notification::make()->danger()->title('Request already processed.')->send();
                        return;
                    }

                    $record->status = 'approved';
                    $record->admin_id = Auth::id();
                    $record->processed_at = now();
                    $record->save();

                    // Mark the linked order as cancelled so admin action takes effect immediately
                    if ($record->order) {
                        $record->order->status = 'cancelled';
                        $record->order->save();
                    }

                    ProcessCancellation::dispatch($record);

                    Notification::make()->success()->title('Cancellation request approved and queued for processing.')->send();
                    $this->redirect($this->getResource()::getUrl('index'));
                }),

            Action::make('reject')
                ->label('Reject')
                ->color('danger')
                ->form([
                    Textarea::make('admin_note')->label('Note')->rows(3),
                ])
                ->action(function (array $data) {
                    $record = $this->record;
                    if ($record->status !== 'requested') {
                        Notification::make()->danger()->title('Request already processed.')->send();
                        return;
                    }

                    $record->status = 'rejected';
                    $record->admin_id = Auth::id();
                    $record->admin_note = $data['admin_note'] ?? null;
                    $record->processed_at = now();
                    $record->save();

                    Notification::make()->success()->title('Cancellation request rejected.')->send();
                    $this->redirect($this->getResource()::getUrl('index'));
                }),
        ];
    }
}
