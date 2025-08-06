<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use App\Models\SellerProduct;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationLabel = 'Order Masuk';
    protected static ?string $navigationGroup = 'Seller';

    public static function canAccess(): bool
    {
        return auth()->user()?->role === 'seller';
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('seller_id', auth()->id());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Order ID')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('buyer.name')
                    ->label('Buyer')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('produk_names')
                    ->label('Produk Name')
                    ->getStateUsing(fn($record) => $record->orderItems->map(fn($item) => $item->product->name ?? '-')->implode(', '))
                    ->limit(30),

                Tables\Columns\TextColumn::make('total_qty')
                    ->label('Qty')
                    ->getStateUsing(fn($record) => $record->orderItems->sum('quantity')),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created at')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->sortable(),
            ])
 ->actions([
    Action::make('Confirm')
        // ->label('Konfirmasi')
        ->color('success') // hijau
        ->requiresConfirmation()
        ->hidden(fn ($record) => $record->status !== 'pending')
        ->action(function ($record) {
            DB::transaction(function () use ($record) {
                // Reduce stock for each order item
                foreach ($record->orderItems as $orderItem) {
                    $sellerProduct = SellerProduct::find($orderItem->seller_product_id);
                    if ($sellerProduct && $sellerProduct->stock >= $orderItem->quantity) {
                        $sellerProduct->decrement('stock', $orderItem->quantity);
                    }
                }
                
                // Update order status
                $record->update(['status' => 'confirmed']);
            });
        }),

    Action::make('Reject')
        // ->label('Tolak')
        ->color('danger') // merah
        ->requiresConfirmation()
        ->hidden(fn ($record) => !in_array($record->status, ['pending', 'confirmed']))
        ->action(function ($record) {
            // If order was already confirmed, restore stock
            if ($record->status === 'confirmed') {
                DB::transaction(function () use ($record) {
                    foreach ($record->orderItems as $orderItem) {
                        $sellerProduct = SellerProduct::find($orderItem->seller_product_id);
                        if ($sellerProduct) {
                            $sellerProduct->increment('stock', $orderItem->quantity);
                        }
                    }
                    
                    $record->update(['status' => 'cancelled']);
                });
            } else {
                $record->update(['status' => 'cancelled']);
            }
        }),

    Action::make('Ready')
        // ->label('Siap Diambil')
        ->color('primary') // biru
        ->requiresConfirmation()
        ->hidden(fn ($record) => $record->status !== 'confirmed')
        ->action(fn ($record) => $record->update(['status' => 'ready_to_pickup'])),

    Action::make('Done')
        // ->label('Selesai')
        ->color('gray') // abu-abu
        ->requiresConfirmation()
        ->hidden(fn ($record) => $record->status !== 'ready_to_pickup')
        ->action(fn ($record) => $record->update(['status' => 'done'])),
])

            ->bulkActions([]);
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
