<?php

namespace App\Filament\Resources\PropertyResource\Pages;
use Illuminate\Database\Eloquent\Model;
use Filament\Actions;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Toggle;

// use Filament\Forms\Components\Group;
use Filament\Forms\Components\View;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Components\{TextInput,Group,Tabs};
use Filament\Forms;
use App\Filament\Resources\PropertyResource;
use Filament\Resources\Pages\EditRecord;

class EditProperty extends EditRecord
{
    protected static string $resource = PropertyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
 

    
    protected function afterCreate():void{
        $images = $this->form->getState()
        ['images'] ?? [];

        foreach($images as $image){
            $new_path = 'property/'.$this->record->id.'/'.
            basename($image);
            Storage::disk('public')->move($image, $new_path);
            $this->record->images()->create([
                'img' => $new_path,
            ]);
        }
    }
}
