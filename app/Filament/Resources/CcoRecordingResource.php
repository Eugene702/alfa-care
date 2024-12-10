<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CcoRecordingResource\Pages;
use App\Filament\Resources\CcoRecordingResource\RelationManagers;
use App\Models\Agent;
use App\Models\CcoRecording;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CcoRecordingResource extends Resource
{
    protected static ?string $model = CcoRecording::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $pluralModelLabel = 'CCO Recordings';
    protected static ?string $navigationLabel = 'CCO Recordings';

    public static function form(Form $form): Form
    {
        $agent = Agent::all(['id', 'name']);
        return $form
            ->schema([
                Select::make('agentId')
                    ->label('Agent')
                    ->options($agent->pluck('name', 'id')->toArray()),

                TextInput::make('ticket')
                    ->required(),

                TextInput::make('sample_name')
                    ->required(),

                Grid::make(2)
                    ->schema([
                        Select::make('cco_correct_opening_greeting')
                            ->label('CCO menyebutkan Salam pembuka dengan baik & benar')
                            ->required()
                            ->options([
                                '0' => 0,
                                '2' => 2
                            ]),

                        Textarea::make('cco_correct_opening_greeting_note')
                            ->label('Catatan')
                            ->rows(5)
                    ]),

                Grid::make(2)
                    ->schema([
                        Select::make('cco_asks_customer_name')
                            ->label('CCO menanyakan nama pelanggan')
                            ->required()
                            ->required()
                            ->options([
                                '0' => 0,
                                '2' => 2
                            ]),

                        Textarea::make('cco_asks_customer_name_note')
                            ->label('Catatan')
                            ->rows(5)
                    ]),

                Grid::make(2)
                    ->schema([
                        Select::make('cco_confirms_service_adequacy')
                            ->label('CCO mengkonfirmasi kecukupan pelayanan')
                            ->required()
                            ->required()
                            ->options([
                                '0' => 0,
                                '2' => 2
                            ]),

                        Textarea::make('cco_confirms_service_adequacy_note')
                            ->label('Catatan')
                            ->rows(5)
                    ]),

                Grid::make(2)
                    ->schema([
                        Select::make('cco_correct_closing_greeting')
                            ->label('CCO menyebutkan Salam penutup dengan baik dan benar')
                            ->required()
                            ->required()
                            ->options([
                                '0' => 0,
                                '2' => 2
                            ]),

                        Textarea::make('cco_correct_closing_greeting_note')
                            ->label('Catatan')
                            ->rows(5)
                    ]),

                Grid::make(2)
                    ->schema([
                        Select::make('hold_line')
                            ->label('Hold line')
                            ->required()
                            ->required()
                            ->options([
                                '0' => 0,
                                '2' => 2
                            ]),

                        Textarea::make('hold_line_note')
                            ->label('Catatan')
                            ->rows(5)
                    ]),

                Grid::make(2)
                    ->schema([
                        Select::make('cco_good_tone_and_intonation')
                            ->label('CCO memiliki nada suara yang baik dan intonasi yang baik')
                            ->required()
                            ->required()
                            ->options([
                                '0' => 0,
                                '2' => 2
                            ]),

                        Textarea::make('cco_good_tone_and_intonation_note')
                            ->label('Catatan')
                            ->rows(5)
                    ]),

                Grid::make(2)
                    ->schema([
                        Select::make('cco_good_speaking_speed_articulation_volume')
                            ->label('CCO memiliki kecepatan berbicara yang baik dan artikulasi suara yang baik (tidak terlalu cepat/lambat) dan volume suara yg baik (tdk terlalu keras/pelan)')
                            ->required()
                            ->required()
                            ->options([
                                '0' => 0,
                                '2' => 4
                            ]),

                        Textarea::make('cco_good_speaking_speed_articulation_volume_note')
                            ->label('Catatan')
                            ->rows(5)
                    ]),

                Grid::make(2)
                    ->schema([
                        Select::make('cco_mentions_customer_name')
                            ->label('CCO menyebutkan nama pelanggan pada bebarapa kesempatan')
                            ->required()
                            ->required()
                            ->options([
                                '0' => 0,
                                '2' => 2
                            ]),

                        Textarea::make('cco_mentions_customer_name_note')
                            ->label('Catatan')
                            ->rows(5)
                    ]),
                    
                Grid::make(2)
                    ->schema([
                        Select::make('cco_good_service_ethics_language')
                            ->label('CCO menggunakan Etika dan bahasa service yang baik')
                            ->required()
                            ->required()
                            ->options([
                                '0' => 0,
                                '2' => 10
                            ]),

                        Textarea::make('cco_good_service_ethics_language_note')
                            ->label('Catatan')
                            ->rows(5)
                    ]),

                Grid::make(2)
                    ->schema([
                        Select::make('cco_confirms_customer_complaint')
                            ->label('CCO melakukan konfirmasi informasi/keluhan yang disampaikan pelanggan (Rangkuman)')
                            ->required()
                            ->required()
                            ->options([
                                '0' => 0,
                                '2' => 10
                            ]),

                        Textarea::make('cco_confirms_customer_complaint_note')
                            ->label('Catatan')
                            ->rows(5)
                    ]),

                Grid::make(2)
                    ->schema([
                        Select::make('invalid_case_type_category')
                            ->label('Salah Case type/Category')
                            ->required()
                            ->required()
                            ->options([
                                '0' => 0,
                                '2' => 5
                            ]),

                        Textarea::make('invalid_case_type_category_note')
                            ->label('Catatan')
                            ->rows(5)
                    ]),

                Grid::make(2)
                    ->schema([
                        Select::make('task_suitability')
                            ->label('Task sudah sesuai atau belum')
                            ->required()
                            ->required()
                            ->options([
                                '0' => 0,
                                '2' => 5
                            ]),

                        Textarea::make('task_suitability_note')
                            ->label('Catatan')
                            ->rows(5)
                    ]),

                Grid::make(2)
                    ->schema([
                        Select::make('other_zendesk')
                            ->label('Other Zendesk …..')
                            ->required()
                            ->required()
                            ->options([
                                '0' => 0,
                                '2' => 5
                            ]),

                        Textarea::make('other_zendesk_note')
                            ->label('Catatan')
                            ->rows(5)
                    ]),

                Grid::make(2)
                    ->schema([
                        Select::make('cco_gives_positive_statements')
                            ->label('CCO memberikan pernyataan pendek positif saat pelanggan berbicara (empati)')
                            ->required()
                            ->required()
                            ->options([
                                '0' => 0,
                                '2' => 2
                            ]),

                        Textarea::make('cco_gives_positive_statements_note')
                            ->label('Catatan')
                            ->rows(5)
                    ]),

                Grid::make(2)
                    ->schema([
                        Select::make('cco_does_not_reask_customer_info')
                            ->label('CCO tidak bertanya kembali mengenai sesuatu hal yang telah pelanggan sebutkan (CCO tidak mendengarkan dengan baik)')
                            ->required()
                            ->required()
                            ->options([
                                '0' => 0,
                                '2' => 6
                            ]),

                        Textarea::make('cco_does_not_reask_customer_info_note')
                            ->label('Catatan')
                            ->rows(5)
                    ]),

                Grid::make(2)
                    ->schema([
                        Select::make('cco_explores_customer_info_needs')
                            ->label('CCO yang melayani pelanggan menggali kebutuhan informasi')
                            ->required()
                            ->required()
                            ->options([
                                '0' => 0,
                                '2' => 3
                            ]),

                        Textarea::make('cco_explores_customer_info_needs_note')
                            ->label('Catatan')
                            ->rows(5)
                    ]),

                Grid::make(2)
                    ->schema([
                        Select::make('no_service_transfer')
                            ->label('Tidak melakukan Pengalihan Layanan')
                            ->required()
                            ->required()
                            ->options([
                                '0' => 0,
                                '2' => 2
                            ]),

                        Textarea::make('no_service_transfer_note')
                            ->label('Catatan')
                            ->rows(5)
                    ]),

                Grid::make(2)
                    ->schema([
                        Select::make('cco_explanation_accuracy_complete')
                            ->label('Penjelasan yang diberikan oleh CCO sesuai dengan sumber informasi lainnya (Accuracy), lengkap dan dengan analisa yang benar ')
                            ->required()
                            ->required()
                            ->options([
                                '0' => 0,
                                '2' => 15
                            ]),

                        Textarea::make('cco_explanation_accuracy_complete_note')
                            ->label('Catatan')
                            ->rows(5)
                    ]),

                Grid::make(2)
                    ->schema([
                        Select::make('cco_provides_complete_solutions')
                            ->label('CCO memberikan solusi lengkap dan tuntas ')
                            ->required()
                            ->required()
                            ->options([
                                '0' => 0,
                                '2' => 15
                            ]),

                        Textarea::make('cco_provides_complete_solutions_note')
                            ->label('Catatan')
                            ->rows(5)
                    ]),

                Grid::make(2)
                    ->schema([
                        Select::make('cco_clear_voice_quality')
                            ->label('Suara CCO terdengar dengan baik (tidak kemerosok, tidak berdengung)')
                            ->required()
                            ->required()
                            ->options([
                                '0' => 0,
                                '2' => 2
                            ]),

                        Textarea::make('cco_clear_voice_quality_note')
                            ->label('Catatan')
                            ->rows(5)
                    ]),

                Grid::make(2)
                    ->schema([
                        Select::make('no_background_noise')
                            ->label('Tidak terdengar suara lainnya sebagai suara latar')
                            ->required()
                            ->required()
                            ->options([
                                '0' => 0,
                                '2' => 2
                            ]),

                        Textarea::make('no_background_noise_note')
                            ->label('Catatan')
                            ->rows(5)
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn  ::make('ticket')
                    ->searchable(),
                TextColumn::make('sample_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('agent.Name')
                    ->searchable(),
                TextColumn::make('created_at')
            ])
            ->filters([
                QueryBuilder::make()
                    ->constraints([
                        DateConstraint::make('created_at'),
                        TextConstraint::make('ticket'),
                        TextConstraint::make('agent.Name'),
                        TextConstraint::make('sample_name'),
                    ])
            ], layout: FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                DeleteAction::make()
                    ->successNotificationTitle("Hapus CCO Recording")
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListCcoRecordings::route('/'),
            'create' => Pages\CreateCcoRecording::route('/create'),
            'view' => Pages\ViewCcoRecording::route('/{record}'),
            'edit' => Pages\EditCcoRecording::route('/{record}/edit'),
        ];
    }
}
