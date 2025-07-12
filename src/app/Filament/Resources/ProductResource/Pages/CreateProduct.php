<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\TestStatus\Notice;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['client_id'] = Auth::user()?->client?->id;
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        Notification::make()
            ->title('Product create successfully')
            ->success()
            ->send();
    }
}
