<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\Pages\EditProduct;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Client;
use App\Models\Product;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('client_id', Auth::user()?->client?->id);
    }

    public function canAccessPanel(): bool
    {
        return Auth::check() && Auth::user()->hasRole('client');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::check() && Auth::user()->hasRole('client');
    }

    public static function canViewAny(): bool
    {
        return Auth::check() && Auth::user()->hasRole('client');
    }

    public static function canCreate(): bool
    {
        return Auth::check() && Auth::user()->hasRole('client');
    }

    public static function canEdit(Model $record): bool
    {
        return Auth::check() && Auth::user()->hasRole('client');
    }

    public static function canDelete(Model $record): bool
    {
        return Auth::check() && Auth::user()->hasRole('client');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms\Components\Select::make('client_id')
                //     ->label('Client Name')
                //     ->options(
                //         Client::with('user')
                //             ->get()
                //             ->mapWithKeys(fn($client) => [
                //                 $client->id => $client->user?->name ?? 'No User'
                //             ])
                //     )
                //     ->disabled(fn($livewire) => $livewire instanceof EditProduct)
                //     ->required(),

                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('Rp.'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('client.user.name')
                    ->label('Client')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->money('Rp.', true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
