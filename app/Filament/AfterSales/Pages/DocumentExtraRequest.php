<?php

namespace App\Filament\AfterSales\Pages;

use App\Models\HistoricDocument;
use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Actions\Action;
use Filament\Notifications\Notification;

class DocumentExtraRequest extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.after-sales.pages.document-extra-request';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document';
    protected static ?string $navigationLabel = 'Extra Request';
    protected static ?string $navigationGroup = 'Customer Service';
    protected static ?int $navigationSort = 3;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('document_number')  // Cambiado de document_id a document_number
                    ->label('Document')
                    ->options(
                        HistoricDocument::query()
                            ->whereIn('document_type', ['FCT', 'BLT', 'NCV']) // Filtra solo facturas, boletas y NC
                            ->orderBy('document_date', 'desc')
                            ->limit(1000) // Limita para no sobrecargar
                            ->pluck('document_number', 'document_number')
                            ->toArray()
                    )
                    ->searchable()
                    ->required()
                    ->getSearchResultsUsing(function (string $search) {
                        return HistoricDocument::query()
                            ->whereIn('document_type', ['FCT', 'BLT', 'NCV'])
                            ->where('document_number', 'like', "%{$search}%")
                            ->orderBy('document_date', 'desc')
                            ->limit(50)
                            ->pluck('document_number', 'document_number')
                            ->toArray();
                    })
                    ->getOptionLabelUsing(function ($value) {
                        $doc = HistoricDocument::where('document_number', $value)->first();
                        return $value . ' - ' . ($doc->client ?? '') . ' (' . $doc->document_date?->format('Y-m-d') . ')';
                    }),
                
                Textarea::make('additional_text')
                    ->label('Additional Topics')
                    ->placeholder('Enter the additional information requested by the client...')
                    ->rows(4)
                    ->required()
                    ->columnSpanFull(),
            ])
            ->columns(1)
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('submit')
                ->label('Submit Request')
                ->submit()
                ->color('primary'),
        ];
    }

    public function submit(): void
    {
        $data = $this->form->getState();
        
        // Aquí implementa la lógica para guardar la solicitud
        // Por ejemplo:
        try {
            $document = HistoricDocument::where('document_number', $data['document_number'])->first();
            
            if ($document) {
                // Actualiza el documento o crea un registro relacionado
                $document->update([
                    'additional_requests' => $data['additional_text'],
                    'requested_at' => now(),
                ]);
                
                Notification::make()
                    ->title('Request Submitted')
                    ->success()
                    ->body('The additional request has been recorded for document ' . $data['document_number'])
                    ->send();
                    
                $this->form->fill();
            } else {
                Notification::make()
                    ->title('Document Not Found')
                    ->danger()
                    ->body('The selected document could not be found in the system.')
                    ->send();
            }
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->danger()
                ->body('An error occurred: ' . $e->getMessage())
                ->send();
        }
    }
}