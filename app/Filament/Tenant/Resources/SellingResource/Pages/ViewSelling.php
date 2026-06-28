<?php
namespace App\Filament\Tenant\Resources\SellingResource\Pages;
use App\Features\PrintSellingA5;
use App\Filament\Tenant\Resources\SellingDetailResource\RelationManagers\SellingDetailsRelationManager;
use App\Filament\Tenant\Resources\SellingResource;
use App\Models\Tenants\About;
use App\Models\Tenants\Selling;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Colors\Color;
use Illuminate\Contracts\Support\Htmlable;

class ViewSelling extends ViewRecord
{
    protected static string $resource = SellingResource::class;

    public ?About $about = null;

    public function mount(int|string $record): void
    {
        parent::mount($record);

        $this->about = About::first();
    }

    public function getTitle(): string|Htmlable
    {
        return 'View ' . $this->getRecord()->code;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make(__('Print invoice'))
                ->icon('heroicon-s-printer')
                ->extraAttributes([
                    'id' => 'printInvoice',
                ])
                ->color(Color::Teal)
                ->visible(
                    can('can print selling')
                    && feature(PrintSellingA5::class)
                ),

            Action::make(__('Print receipt'))
                ->icon('heroicon-s-printer')
                ->extraAttributes([
                    'id' => 'printButton',
                ])
                ->visible(can('can print selling')),

            Action::make('surat_jalan')
                ->label('Surat Jalan')
                ->icon('heroicon-s-document-text')
                ->color(Color::Blue)
                ->url(fn () => route('surat-jalan.print', [
                    'selling' => $this->record->id,
                ]))
                ->openUrlInNewTab(),

            Action::make('kwitansi')
                ->label('Kwitansi')
                ->icon('heroicon-s-receipt-percent')
                ->color(Color::Amber)
                ->url(fn () => route('kwitansi.print', [
                    'selling' => $this->record->id,
                ]))
                ->openUrlInNewTab(),

            // Tombol Print Nomor Invoice
            Action::make('print_nomor_invoice')
                ->label('Print Nomor Invoice')
                ->icon('heroicon-s-document-duplicate')
                ->color(Color::Indigo)
                ->url(fn () => route('invoice-number.print', [
                    'selling' => $this->record->id,
                ]))
                ->openUrlInNewTab(),
        ];
    }

    public function getView(): string
    {
        return 'filament.tenant.resources.sellings.pages.view-selling';
    }

    public function getRecord(): Selling
    {
        return $this->record->load('sellingDetails.product');
    }

    public function getRelationManagers(): array
    {
        return [
            SellingDetailsRelationManager::make(),
        ];
    }
}

