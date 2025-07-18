<?php

namespace App\Filament\Resources\CityResource\Pages;

use App\Filament\Resources\CityResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateCity extends CreateRecord
{
    protected static string $view = "livewire.property-form";
    protected static string $resource = CityResource::class;
    protected function getCreatedNotification():?Notification{
        return Notification::make()
        ->success()
        ->title("City Created")
        ->body("The City Has Been Created Successfully");
    }
}
