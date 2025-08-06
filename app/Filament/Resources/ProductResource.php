<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationGroup = 'Product Management';

   public static function form(Form $form): Form
{
    return $form->schema([
        Forms\Components\TextInput::make('name')
            ->required()
            ->maxLength(255),

        Forms\Components\FileUpload::make('image_url')
            ->label('Product Image')
            ->image()
            ->imageEditor()
            ->imageEditorAspectRatios([
                '16:9',
                '4:3',
                '1:1',
            ])
            ->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/png', 'image/webp'])
            ->rules(['image', 'mimes:jpeg,jpg,png,webp', 'max:2048'])
            ->directory('products')
            ->disk('s3') // ✅ simpan ke AWS S3
            ->visibility('public') // ✅ agar bisa diakses publik
            ->required(),

        Forms\Components\Textarea::make('description')
            ->rows(4),
    ]);
}

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        if (!isset($data['created_by']) && auth()->user()?->role === 'admin') {
            $data['created_by'] = auth()->id();
        }
        return $data;
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
            Tables\Columns\ImageColumn::make('image_url')
                ->label('Image')
                ->square()
                ->getStateUsing(function ($record) {
                    return $record->image_url ? \Illuminate\Support\Facades\Storage::url($record->image_url) : null;
                }),
            Tables\Columns\TextColumn::make('description')->limit(40),
            Tables\Columns\TextColumn::make('created_at')->dateTime('d M Y'),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    /**
     * Batasi akses hanya untuk admin
     */
    public static function canAccess(): bool
    {
        return auth()->user()?->role === 'admin';
    }
}
