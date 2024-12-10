<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChoResource\Pages;
use App\Filament\Resources\ChoResource\RelationManagers;
use App\Models\Agent;
use App\Models\Cho;
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

class ChoResource extends Resource
{
    protected static ?string $model = Cho::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $pluralModelLabel = 'CHO';
    protected static ?string $navigationLabel = 'CHO';

    public static function form(Form $form): Form
    {
        $agent = Agent::select('id', 'name')->where("position", "L2")->get();
        return $form
            ->schema([
                Select::make('agentId')
                    ->label('Agent')
                    ->options($agent->pluck('name', 'id')->toArray())
                    ->required(),

                TextInput::make('sample_name')
                    ->label("Nama Sampel")
                    ->required(),

                TextInput::make('ticket')
                    ->required(),

                Grid::make(2)
                    ->schema([
                        Select::make('sla_response')
                            ->required()
                            ->options([
                                '0' => 0,
                                '10' => 10
                            ])
                            ->required(),
                        Textarea::make('sla_response_note')
                            ->label('Catatan')
                            ->rows(5)
                            ->nullable()
                    ]),

                Grid::make(2)
                    ->schema([
                        Select::make('ticket_closure_sla')
                            ->label("SLA Closed Tiket")
                            ->required()
                            ->options([
                                '0' => 0,
                                '10' => 10
                            ])
                            ->required(),
                        Textarea::make('ticket_closure_sla_note')
                            ->label('Catatan')
                            ->rows(5)
                            ->nullable()
                    ]),
                Grid::make(2)
                    ->schema([
                        Select::make('email_punctuation_check')
                            ->label("Email memakai penggunaan tanda baca yang baik")
                            ->required()
                            ->options([
                                '0' => 0,
                                '5' => 5
                            ])
                            ->required(),
                        Textarea::make('email_punctuation_check_note')
                            ->label('Catatan')
                            ->rows(5)
                            ->nullable()
                    ]),

                Grid::make(2)
                    ->schema([
                        Select::make('ticket_text_content')
                            ->label("Penulisan konten Ticket di Zendesk")
                            ->required()
                            ->options([
                                '0' => 0,
                                '5' => 5
                            ])
                            ->required(),
                        Textarea::make('ticket_text_content_note')
                            ->label('Catatan')
                            ->rows(5)
                            ->nullable()
                    ]),

                Grid::make(2)
                    ->schema([
                        Select::make('case_linkage_check')
                            ->label("Cek keterkaitan dengan case/tiket lain")
                            ->required()
                            ->options([
                                '0' => 0,
                                '5' => 5
                            ])
                            ->required(),
                        Textarea::make('case_linkage_check_note')
                            ->label('Catatan')
                            ->rows(5)
                            ->nullable()
                    ]),

                Grid::make(2)
                    ->schema([
                        Select::make('task_status_check')
                            ->label("Task sudah sesuai atau belum")
                            ->required()
                            ->options([
                                '0' => 0,
                                '5' => 5
                            ])
                            ->required(),
                        Textarea::make('task_status_check_note')
                            ->label('Catatan')
                            ->rows(5)
                            ->nullable()
                    ]),

                Grid::make(2)
                    ->schema([
                        Select::make('zendesk_category_check')
                            ->label("Category Ticket Zendesk sudah sesuai atau belum")
                            ->required()
                            ->options([
                                '0' => 0,
                                '5' => 5
                            ])
                            ->required(),
                        Textarea::make('zendesk_category_check_note')
                            ->label('Catatan')
                            ->rows(5)
                            ->nullable()
                    ]),
                Grid::make(2)
                    ->schema([
                        Select::make('is_language_ethical')
                            ->label("Menggunakan etika dan bahasa Service yang baik")
                            ->required()
                            ->options([
                                '0' => 0,
                                '15' => 15
                            ])
                            ->required(),
                        Textarea::make('is_language_ethical_note')
                            ->label('Catatan')
                            ->rows(5)
                            ->nullable()
                    ]),
                Grid::make(2)
                    ->schema([
                        Select::make('analysis_correctness')
                            ->label("Analisa yang benar")
                            ->required()
                            ->options([
                                '0' => 0,
                                '20' => 20
                            ])
                            ->required(),
                        Textarea::make('analysis_correctness_note')
                            ->label('Catatan')
                            ->rows(5)
                            ->nullable()
                    ]),
                Grid::make(2)
                    ->schema([
                        Select::make('complete_solution_status')
                            ->label("Solusi yg diberikan lengkap dan tuntas")
                            ->required()
                            ->options([
                                '0' => 0,
                                '20' => 20
                            ])
                            ->required(),
                        Textarea::make('complete_solution_status_note')
                            ->label('Catatan')
                            ->rows(5)
                            ->nullable()
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ticket')
                    ->label('Ticket'),

                TextColumn::make('sample_name')
                    ->label('Nama Sampel'),

                TextColumn::make('agent.Name')
                    ->label('Agent'),

                TextColumn::make('created_at')
                    ->label('Tanggal')
            ])
            ->filters([
                QueryBuilder::make()
                    ->constraints([
                        DateConstraint::make('created_at'),
                        TextConstraint::make('ticket'),
                        TextConstraint::make('sample_name'),
                        TextConstraint::make('agent.Name')
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
            'index' => Pages\ListChos::route('/'),
            'create' => Pages\CreateCho::route('/create'),
            'view' => Pages\ViewCho::route('/{record}'),
            'edit' => Pages\EditCho::route('/{record}/edit'),
        ];
    }
}
