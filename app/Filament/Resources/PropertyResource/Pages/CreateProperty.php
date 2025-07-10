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
use Illuminate\Support\Facades\Storage;
use Filament\Resources\Pages\CreateRecord;
use  App\Models\Property;
use  App\Models\Photo;

class CreateProperty extends CreateRecord
{   
    use CreateRecord\Concerns\HasWizard;
    protected static string $resource = PropertyResource::class;
    protected function getRedirecUrl():string{
        return $this->getResource()::getUrl("index");
    }
    protected function getCreatedNotificationTitle():string{
        return "تم الحفظ";
    }
    protected function getSteps():array{
        return [
            Step::make("معلومات العقار")
            ->columns(2)
            ->description('معلومات عامة عن العقار')
            ->schema([
                TextInput::make("name")
                ->label("اسم العقار")
                ->live(onBlur: true)
                ->required(),
                Select::make("user_id")->relationship("user","name")
                ->label("اسم المالك")
                ->required(),
                Select::make("street_id")->relationship("street","title")
                ->label("اسم الشارع")
                ->required(),
                Select::make("type")
                ->options([
                    "apartment"=>"شقة",
                     "villa"=>"فيلا",
                     "room"=>"غرفة",
                ])
                ->reactive()
                ->required()
                ->label("نوع العقار"),

                Select::make("purpose")
                ->options([
                    "selling"=>"بيع",
                    "renting"=>"ايجار",
                ])
                ->label("الغرض"),
                TextInput::make("area_2m")
                ->label("مربع(مترxمتر) حجم العقار "),
                // Toggle::make("is_verified")
                // ->label("تم التوثيق"),
                TextInput::make("description")
                ->required(),
                Select::make("status")
                ->options([
                "available"=>"متاح",
                "pendding"=>"قيد المعالجة",
                "booked"=>"محجوز",
            ]),
            TextInput::make("price")
            ->label("مبلغ")
            ->required(),
            
            Toggle::make("negotiable")
            ->label("قابل لتفاوض"),

               FileUpload::make("primary_path")
               ->image()
               ->label("الصورة الرئيسية للعقار")
               ->directory("primary_path/")
               ->preserveFilenames()
               ->reorderable()
               ->live()
               ->required()
               ->acceptedFileTypes(['image/jpeg','image/png','image/jpg','image/webp']),
            
            Hidden::make("altitude")
            ->dehydrated(true),
            Hidden::make("longitude")
            ->dehydrated(true),
                

                ViewField::make('map_picker')
                // ->label("المواقع على الخريطة")
                ->view('filament.map-picker')
                ->viewData([
                    'lat'=>$record->altitude ?? 15.3821,
                    'lng'=>$record->longitude ?? 14.1910,
                    "editable"=>true,
                    "latFieldId"=>'latInput',
                    "lngFieldId"=>'lngInput',
                ])
                ->live()
                ->dehydrated(false)
                ->columnSpanFull(),
                // ->extraAttributes([
                //     'lat'=>$record->altitude ?? 15.3821,
                //     'lng'=>$record->longitude ?? 14.1910,
                //     "editable"=>true,
                //     "latFieldId"=>'latInput',
                //     "lngFieldId"=>'lngInput',
                // ]),
        ]),
        Step::make("تفاصيل العقار")
        ->description("must enter accurate data about the properties")
        ->schema([
            Group::make([

                Fieldset::make("apartment")
                ->relationship(
                'apartment'
                // condition:fn((?array $state):bool=>filled($state['property_id']))
                )
                ->schema([
                    // TextInput::make("sssssssssss")
                    // ->hidden(fn(Get $get):bool=>filled($get('name'))),
                    

                    TextInput::make("room_no")
                    ->label("رقم الشقة")
                    ->required(),
                    TextInput::make("bedrooms")
                    ->label("عداد الغرف")
                    ->required(),
                    Select::make("bathrooms")
                    ->options([
                        "one"=>"واحد",
                        "two"=>"اثنين"
                        ])
                        ->label("دورات المياة"),
                        Select::make("living_room")
                        ->options([
                            "sperated_bath"=>"مجلس وحمام منعزل",
                            "no_bath"=>"مجلس منعزل بدون حمام",
                            "no_existing"=>"لا يوجد"
                            ])
                            ->native(false)
                            ->label("المجلس")
                            ->required(),
                            
                            Select::make("hall")
                            ->options([
                                "wide"=>"واسعة",
                                "middle"=>"متوسطة",
                                "no-exists"=>"لا يوجد",
                                ])
                                ->required()
                                ->label("الصالة"),
                                Toggle::make("kitchen")
                                ->label("يوجد مطبخ"),  
                                
                                Toggle::make("has_balcony")
                                ->label("يوجد بلكونة"), 
                                TextInput::make("floor")
                                ->label("رقم الدور"),
                                TextInput::make("floor_id")
                                ->label("رقم الدور"),
                                
                                
                ])->visible(fn(Get $get)=>$get("type")==='apartment'),
            
                Group::make([

                Fieldset::make("villa")
                ->relationship(
                'villa'
                )
                ->schema([
                    // TextInput::make("sssssssssss")
                    // ->hidden(fn(Get $get):bool=>filled($get('name'))),                 
                    TextInput::make("floors")
                    ->label("عداد الطوابق")
                    ->integer()
                    ->required(),
                    TextInput::make("rooms")
                    ->label("عداد الغرف")
                    ->required(),
                    Select::make("entrance")
                    ->options([
                        "one"=>"واحد",
                        "two"=>"اثنين"
                        ])
                        ->native(false)
                        ->label("عداد المداخل"),

                        // Select::make("living_room")
                        // ->options([
                        //     "sperated_bath"=>"مجلس وحمام منعزل",
                        //     "no_bath"=>"مجلس منعزل بدون حمام",
                        //     "no_existing"=>"لا يوجد"
                        //     ])
                        //     ->label("المجلس")
                        //     ->required(),
                            
                            Select::make("hall")
                            ->options([
                                "wide"=>"واسعة",
                                "middle"=>"متوسطة",
                                "no-exists"=>"لا يوجد",
                                ])
                                ->native(false)
                                ->required()
                                ->label("الصالة"),
                            Toggle::make("has_gardan")
                                ->label("محوش"),  
                                
                                Toggle::make("has_pool")
                                ->label("مسباح"), 

                                Toggle::make("has_grash")
                                ->label("جراش "),
                           
                                
                                
                ])->visible(fn(Get $get)=>$get("type")==='villa'),
            
            
            ]),
            ]),
            // Select::make("floor_id")
            // ->relationship("floor","id")
            // ->label("الدور")
            // ->required(),
     

        ]),
        Step::make("صور العقار")
        ->description("يجب ان تكون الصور  واضحة ل العقار الحد الاقصى 6")
        ->schema([

            FileUpload::make("path")
                        ->image()
                        ->multiple()
                        ->label("الصوار")
                        ->directory("detials_photos")
                        ->preserveFilenames()
                        ->reorderable()
                        ->live()
                        ->required()
                        ->acceptedFileTypes(['image/jpeg','image/png','image/jpg','image/webp']),
                        
                    ])
        ];
    }
    public function hasSkippableSteps():bool{
        return true;
    }
}
