<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SellerProductResource\Pages;
use App\Models\Product;
use App\Models\SellerProduct;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class SellerProductResource extends Resource
{
    protected static ?string $model = SellerProduct::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationGroup = 'Product Management';

    public static function form(Form $form): Form
    {
        return $form->schema([
            // Pilih produk dari daftar produk yang tersedia
            Forms\Components\Select::make('product_id')
                ->label('Product')
                ->options(Product::all()->pluck('name', 'id'))
                ->searchable()
                ->required(),

            // Input harga produk
            Forms\Components\TextInput::make('price')
                ->numeric()
                ->required(),

            // Input stok produk
            Forms\Components\TextInput::make('stock')
                ->numeric()
                ->required(),

            // Tidak ditampilkan di form, tapi wajib diisi otomatis
            Forms\Components\Hidden::make('seller_id')->default(auth()->id()),
        ]);
    }

    /**
     * Otomatis isi seller_id berdasarkan user yang login saat create.
     */
    public static function mutateFormDataBeforeCreate(array $data): array
    {
        $data['seller_id'] = Auth::id(); // bisa juga auth()->id()
        return $data;
    }

    /**
     * Tampilkan hanya produk milik seller yang sedang login.
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('seller_id', Auth::id());
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('product.name')->label('Product'),
            Tables\Columns\TextColumn::make('price')->money('IDR'),
            Tables\Columns\TextColumn::make('stock'),
            Tables\Columns\TextColumn::make('created_at')->date('d M Y'),
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
            'index' => Pages\ListSellerProducts::route('/'),
            'create' => Pages\CreateSellerProduct::route('/create'),
            'edit' => Pages\EditSellerProduct::route('/{record}/edit'),
        ];
    }

    /**
     * Bisa kamu tambahkan jika ingin membatasi akses hanya untuk seller.
     */
    public static function canAccess(): bool
    {
        return Auth::user()?->role === 'seller';
    }
}
