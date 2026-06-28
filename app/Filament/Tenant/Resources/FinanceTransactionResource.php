<?php

namespace App\Filament\Tenant\Resources;

use App\Filament\Tenant\Resources\FinanceTransactionResource\Pages;
use App\Models\Tenants\FinanceTransaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FinanceTransactionResource extends Resource
{
    protected static ?string $model = FinanceTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'Finance';

    protected static ?string $navigationLabel = 'Finance Transaction';

    // 🔐 ACCESS CONTROL (FIX FINAL ROLE-BASED)
    public static function canViewAny(): bool
    {
        $user = auth()->user();

        return $user && $user->hasAnyRole(['Owner', 'Manager']);
    }

    public static function canCreate(): bool
    {
        $user = auth()->user();

        return $user && $user->hasAnyRole(['Owner', 'Manager']);
    }

    public static function canEdit($record): bool
    {
        $user = auth()->user();

        return $user && $user->hasAnyRole(['Owner', 'Manager']);
    }

    public static function canDelete($record): bool
    {
        $user = auth()->user();

        // 🔥 hanya Owner boleh delete
        return $user && $user->hasRole('Owner');
    }

    // 🧾 FORM INPUT
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('transaction_no')
                ->label('Transaction No')
                ->required()
                ->maxLength(50),

            Forms\Components\DatePicker::make('transaction_date')
                ->label('Date')
                ->required(),

            Forms\Components\Select::make('type')
                ->label('Type')
                ->options([
                    'income' => 'Income',
                    'expense' => 'Expense',
                ])
                ->required(),

            Forms\Components\TextInput::make('category')
                ->label('Category')
                ->required()
                ->maxLength(100),

            Forms\Components\TextInput::make('amount')
                ->label('Amount')
                ->numeric()
                ->required(),

            Forms\Components\Select::make('payment_method')
                ->label('Payment Method')
                ->options([
                    'cash' => 'Cash',
                    'transfer' => 'Transfer',
                    'qris' => 'QRIS',
                ])
                ->required(),

            Forms\Components\Textarea::make('description')
                ->label('Description')
                ->columnSpanFull(),

            Forms\Components\Hidden::make('created_by')
                ->default(fn () => auth()->id()),
        ]);
    }

    // 📊 TABLE LIST
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('transaction_no')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('transaction_date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state) => $state === 'income' ? 'success' : 'danger'),

                Tables\Columns\TextColumn::make('category')
                    ->searchable(),

                Tables\Columns\TextColumn::make('amount')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('payment_method'),

                Tables\Columns\TextColumn::make('created_by'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'income' => 'Income',
                        'expense' => 'Expense',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Tables\Actions\DeleteAction::make()
                    ->visible(fn () => auth()->user()?->hasRole('Owner')),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->visible(fn () => auth()->user()?->hasRole('Owner')),
            ]);
    }

    // 📄 PAGES
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFinanceTransactions::route('/'),
            'create' => Pages\CreateFinanceTransaction::route('/create'),
            'edit' => Pages\EditFinanceTransaction::route('/{record}/edit'),
        ];
    }
}