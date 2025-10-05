<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->numeric()
                    ->default(null),
                TextInput::make('status')
                    ->required()
                    ->default('pending'),
                TextInput::make('total')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                Textarea::make('shipping_address')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
