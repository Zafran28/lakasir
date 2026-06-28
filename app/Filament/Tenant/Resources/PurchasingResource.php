<?php

namespace App\Filament\Tenant\Resources;

use App\Constants\PurchasingStatus;
use App\Filament\Tenant\Resources\PurchasingResource\Pages;
use App\Filament\Tenant\Resources\PurchasingResource\Traits\HasPurchasingForm;
use App\Models\Tenants\Profile;
use App\Models\Tenants\Purchasing;
use App\Models\Tenants\Supplier;
use App\Services\Tenants\PurchasingService;
use App\Traits\HasTranslatableResource;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\ActionSize;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class PurchasingResource extends Resource
{
    use HasPurchasingForm, HasTranslatableResource;

    protected static ?string $model = Purchasing::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box-arrow-down';

    /*
    |--------------------------------------------------------------------------
    | 🔐 ACCESS CONTROL (OWNER + ADMIN PURCHASING)
    |--------------------------------------------------------------------------
    */

    public static function user()
    {
        return auth()->user();
    }

    public static function canAccess(): bool
    {
        $user = self::user();

        if (! $user) {
            return false;
        }

        return (
            (string) $user->is_owner === '1'
            || $user->hasRole('admin purchasing')
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
        return self::canAccess();
    }

    public static function canDelete(Model $record): bool
    {
        return self::canAccess();
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
            Select::make('supplier_id')
                ->relationship(name: 'supplier', titleAttribute: 'name')
                ->required()
                ->native(false)
                ->createOptionForm(Supplier::form())
                ->afterStateUpdated(
                    fn (Set $set, ?string $state) =>
                    $set('supplier_phone_number', Supplier::find($state)?->phone_number ?? '')
                )
                ->live()
                ->translateLabel(),

            Select::make('payment_method_id')
                ->relationship(name: 'paymentMethod', titleAttribute: 'name')
                ->required()
                ->native(false)
                ->translateLabel(),

            TextInput::make('supplier_phone_number')
                ->readOnly()
                ->translateLabel(),

            DatePicker::make('date')
                ->default(now())
                ->required()
                ->native(false)
                ->closeOnDateSelection()
                ->translateLabel(),

            DatePicker::make('due_date')
                ->required()
                ->native(false)
                ->closeOnDateSelection()
                ->translateLabel(),

            FileUpload::make('image')
                ->image()
                ->disk(config('filesystems.upload_disk'))
                ->translateLabel(),
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
                TextColumn::make('supplier.name')->searchable()->translateLabel(),
                TextColumn::make('paymentMethod.name')->searchable()->default('-')->translateLabel(),
                TextColumn::make('number')->searchable()->translateLabel(),
                TextColumn::make('date')->date()->translateLabel(),
                TextColumn::make('stocks_count')->counts('stocks')->label(__('Item amounts')),
                TextColumn::make('approved_at')
                    ->dateTime(timezone: Profile::get()->timezone)
                    ->translateLabel(),
                TextColumn::make('payment_status')
                    ->badge()
                    ->color(fn (bool $state): string => $state ? 'success' : 'gray')
                    ->formatStateUsing(fn ($state) => $state ? __('Paid') : __('Unpaid'))
                    ->translateLabel(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        PurchasingStatus::pending => 'gray',
                        PurchasingStatus::reviewing => 'warning',
                        PurchasingStatus::approved => 'success',
                    })
                    ->translateLabel(),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),

                    Action::make('set_paid')
                        ->label(fn (Purchasing $record) => $record->payment_status ? 'Set unpaid' : 'Set paid')
                        ->icon('heroicon-s-pencil-square')
                        ->action(function (Purchasing $record) {
                            $record->update([
                                'payment_status' => ! $record->payment_status,
                            ]);
                        }),

                    Action::make('update_status')
                        ->visible(fn (Purchasing $record) => $record->status !== PurchasingStatus::approved)
                        ->form([
                            Select::make('status')
                                ->required()
                                ->options(
                                    Arr::where(
                                        PurchasingStatus::all()->toArray(),
                                        fn ($key) =>
                                            $key !== PurchasingStatus::approved
                                            || auth()->user()?->can('approve purchasing')
                                    )
                                ),
                        ])
                        ->action(function ($data, Purchasing $record, PurchasingService $service) {
                            $service->updateStatus($record, $data['status']);
                        })
                        ->icon('heroicon-s-pencil-square'),
                ])
                ->icon('heroicon-m-ellipsis-vertical')
                ->button()
                ->size(ActionSize::Small),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(PurchasingStatus::all()->toArray()),

                Filter::make('date')
                    ->form([
                        DatePicker::make('start_date')->native(false),
                        DatePicker::make('end_date')->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (! $data['start_date'] || ! $data['end_date']) {
                            return $query;
                        }

                        return $query->whereBetween('date', [
                            Carbon::parse($data['start_date']),
                            Carbon::parse($data['end_date']),
                        ]);
                    }),
            ])
            ->deferFilters();
    }

    /*
    |--------------------------------------------------------------------------
    | 📄 INFOLIST
    |--------------------------------------------------------------------------
    */
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            TextEntry::make('status')->badge()->translateLabel(),
            TextEntry::make('supplier.name')->translateLabel(),
            TextEntry::make('supplier.phone_number')->label(__('Supplier phone number')),
            TextEntry::make('due_date')->date()->translateLabel(),
            TextEntry::make('date')->date()->translateLabel(),
            ImageEntry::make('image')->translateLabel(),
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
            'index' => Pages\ListPurchasings::route('/'),
            'create' => Pages\CreatePurchasing::route('/create'),
            'view' => Pages\ViewPurchasing::route('/{record}'),
            'edit' => Pages\EditPurchasing::route('/{record}/edit'),
        ];
    }
}