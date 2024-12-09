<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CcoResource\Pages;
use App\Models\Agent;
use App\Models\Cco;
use Faker\Provider\ar_EG\Text;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Table;

class CcoResource extends Resource
{
    protected static ?string $model = Cco::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $pluralModelLabel = 'CCO';
    protected static ?string $navigationLabel = 'CCO';

    public static function form(Form $form): Form
    {
        $agent = Agent::all(['id', 'name']);
        return $form
            ->schema([
                Forms\Components\Select::make('agentId')
                    ->label('Agent')
                    ->options($agent->pluck('name', 'id')->toArray()),

                TextInput::make('ticket')
                    ->required(),

                TextInput::make('sample_name')
                    ->required(),

                Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('sla_response')
                            ->label('SLA Response')
                            ->required()
                            ->maxLength(255)
                            ->integer()
                            ->minValue(1)
                            ->maxValue(2),

                        Textarea::make('sla_response_note')
                            ->label('Catatan')
                            ->rows(5)
                    ]),

                Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('email_format_valid')
                            ->label('Email memakai penggunaan tanda baca yang baik')
                            ->required()
                            ->maxLength(255)
                            ->integer()
                            ->minValue(1)
                            ->maxValue(2),

                        Textarea::make('email_format_valid_note')
                            ->label('Catatan')
                            ->rows(5)
                    ]),


                Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('zendesk_ticket_content')
                            ->label('Penulisan konten Ticket di Zendesk')
                            ->required()
                            ->maxLength(255)
                            ->integer()
                            ->minValue(1)
                            ->maxValue(2),

                        Textarea::make('zendesk_ticket_content_note')
                            ->label('Catatan')
                            ->rows(5)
                    ]),

                Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('related_case_check')
                            ->label('Cek keterkaitan dengan case/tiket lain')
                            ->required()
                            ->maxLength(255)
                            ->integer()
                            ->minValue(1)
                            ->maxValue(2),

                        Textarea::make('related_case_check_note')
                            ->label('Catatan')
                            ->rows(5)
                    ]),

                Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('task_status_check')
                            ->label('Task sudah sesuai atau belum')
                            ->required()
                            ->maxLength(255)
                            ->integer()
                            ->minValue(1)
                            ->maxValue(2),

                        Textarea::make('task_status_check_note')
                            ->label('Catatan')
                            ->rows(5)
                    ]),

                Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('category_validation_status')
                            ->label('Category Ticket Zendesk sudah sesuai atau belum')
                            ->required()
                            ->maxLength(255)
                            ->integer()
                            ->minValue(1)
                            ->maxValue(2),

                        Textarea::make('category_validation_status_note')
                            ->label('Catatan')
                            ->rows(5)
                    ]),

                Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('service_language_quality')
                            ->label('CCO menggunakan etika dan bahasa Service yang baik')
                            ->required()
                            ->maxLength(255)
                            ->integer()
                            ->minValue(1)
                            ->maxValue(2),

                        Textarea::make('service_language_quality_note')
                            ->label('Catatan')
                            ->rows(5)
                    ]),

                Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('cso_analysis_completeness')
                            ->label('Analisa CSO sudah lengkap atau belum')
                            ->required()
                            ->maxLength(255)
                            ->integer()
                            ->minValue(1)
                            ->maxValue(2),

                        Textarea::make('cso_analysis_completeness_note')
                            ->label('Catatan')
                            ->rows(5)
                    ]),

                Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('solution_fulfillment')
                            ->label('Solusi yg diberikan lengkap dan tuntas')
                            ->required()
                            ->maxLength(255)
                            ->integer()
                            ->minValue(1)
                            ->maxValue(2),

                        Textarea::make('solution_fulfillment_note')
                            ->label('Catatan')
                            ->rows(5)
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ticket')
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
                Tables\Actions\EditAction::make(),
                ViewAction::make(),
                DeleteAction::make()
                    ->successNotificationTitle("Hapus CCO Berhasil")
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCcos::route('/'),
            'create' => Pages\CreateCco::route('/create'),
            'edit' => Pages\EditCco::route('/{record}/edit'),
            'view' => Pages\ViewCco::route('/{record}'),
        ];
    }
}
