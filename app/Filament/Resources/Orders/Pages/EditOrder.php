<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    /**
     * Prevent changing status away from 'requesting' in the admin edit view.
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // if the current record is requesting, force the status back to requesting
        if ($this->record && $this->record->status === 'requesting') {
            $data['status'] = 'requesting';
        }

        return $data;
    }
}
