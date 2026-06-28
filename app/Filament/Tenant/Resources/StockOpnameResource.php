<?php

namespace App\Filament\Tenant\Resources;

use App\Constants\StockOpnameStatus;
use App\Filament\Tenant\Resources\StockOpnameResource\Pages;
use App\Filament\Tenant\Resources\StockOpnameResource\Traits\HasStockOpnameItemForm;
use App\Models\Tenants\Profile;
use App\Models\Tenants\StockOpname;
use App\Traits\HasTranslatableResource;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class StockOpnameResource extends Resource
{
    use HasStockOpnameItemForm, HasTranslatableResource;

    protected static ?string $model = StockOpname::class;

    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';

    /*
    |--------------------------------------------------------------------------
    | 🔐 USER HELPER
    |--------------------------------------------------------------------------
    */

    public static function user()
    {
        return auth()->user();
    }

    /*
    |--------------------------------------------------------------------------
    | 🔐 ACCESS CONTROL (OWNER + ROLE)
    |--------------------------------------------------------------------------
    */

    public static function canAccess(): bool
    {
        $user = self::user();

        if (! $user) {
            return false;
        }

        return (
            (string) $user->is_owner === '1'
            || $user->hasRole(['admin', 'cashier'])
        );
    }

    public static function canViewAny(): bool
    {
        return self::canAccess();
    }

    public static function canCreate(): bool
    {
        return self::canAccess();
    }

    public static function canView(Model $record): bool
    {
        return self::canAccess();
    }

    public static function canEdit(Model $record): bool
    {
        $user = self::user();

        if (! $user) {
            return false;
        }

        return (
            (string) $user->is_owner === '1'
            || $user->hasRole('admin') // cashier tidak boleh edit
        );
    }

    public static function canDelete(Model $record): bool
    {
        $user = self::user();

        if (! $user) {
            return false;
        }

        return (
            (string) $user->is_owner === '1'
            || $user->hasRole('admin') // hanya admin + owner
        );
    }

    public static function shouldRegisterNavigation(): bool
    {
        return self::canAccess();
    }

    /*
    |--------------------------------------------------------------------------
    | 🧾 FORM
    |--------------------------------------------------------------------------
    */
    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('pic')
                ->required()
                ->readOnly()
                ->default(self::user()?->name)
                ->label(__('PIC')),

            DatePicker::make('date')
                ->required()
                ->default(now())
                ->closeOnDateSelection()
                ->native(false)
                ->label(__('Date')),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | 📊 TABLE
    |--------------------------------------------------------------------------
    */
    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('number')
                    ->searchable()
                    ->translateLabel(),

                TextColumn::make('stock_opname_items_count')
                    ->label(__('Item amounts'))
                    ->counts('stockOpnameItems'),

                TextColumn::make('date')
                    ->date(),

                TextColumn::make('approved_at')
                    ->dateTime(timezone: Profile::get()->timezone),

                TextColumn::make('status')
                    ->badge()
                    ->translateLabel()
                    ->color(fn (string $state): string => match ($state) {
                        StockOpnameStatus::pending => 'gray',
                        StockOpnameStatus::reviewing => 'warning',
                        StockOpnameStatus::approved => 'success',
                    }),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(StockOpnameStatus::all()->toArray()),

                Filter::make('date')
                    ->form([
                        DatePicker::make('start_date')->native(false),
                        DatePicker::make('end_date')->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (!($data['start_date'] && $data['end_date'])) {
                            return $query;
                        }

                        return $query->whereBetween('date', [
                            Carbon::parse($data['start_date']),
                            Carbon::parse($data['end_date']),
                        ]);
                    }),
            ])
            ->deferFilters()
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | 📄 INFOLIST
    |--------------------------------------------------------------------------
    */
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            TextEntry::make('status')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    StockOpnameStatus::pending => 'gray',
                    StockOpnameStatus::reviewing => 'warning',
                    StockOpnameStatus::approved => 'success',
                }),

            TextEntry::make('pic'),

            TextEntry::make('date')->date(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | 📌 PAGES
    |--------------------------------------------------------------------------
    */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStockOpnames::route('/'),
            'create' => Pages\CreateStockOpname::route('/create'),
            'view' => Pages\ViewStockOpname::route('/{record}'),
            'edit' => Pages\EditStockOpname::route('/{record}/edit'),
        ];
    }
}