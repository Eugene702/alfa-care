<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AgentResource\Pages;
use App\Filament\Resources\AgentResource\Pages\ViewAgent;
use App\Livewire\Agent\AgentRecordView;
use App\Models\Agent;
use App\Models\Cco;
use App\Models\CcoRecording;
use App\Models\Cho;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Livewire;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Table;

class AgentResource extends Resource
{
    protected static ?string $model = Agent::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('position')
                    ->options([
                        '' => 'Select Position',
                        'L1' => 'L1',
                        'L2' => 'L2',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('position'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                ViewAction::make(),
                DeleteAction::make()
                    ->successNotificationTitle("Hapus Agent")
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
            'index' => Pages\ListAgents::route('/'),
            'create' => Pages\CreateAgent::route('/create'),
            'edit' => Pages\EditAgent::route('/{record}/edit'),
            'view' => ViewAgent::route('/{record}'),
        ];
    }

    private static function calculateCcoData($agentId)
    {
        $serviceLevel = Cco::where("agentId", $agentId)
            ->whereYear('created_at', date('Y'))
            ->select(['sla_response'])
            ->avg('sla_response');

        $softSkillRecord = Cco::where("agentId", $agentId)
            ->whereYear('created_at', date('Y'))
            ->select(['email_format_valid', 'zendesk_ticket_content', 'related_case_check', 'task_status_check', 'category_validation_status', 'service_language_quality'])
            ->get();
        $softSkill = $softSkillRecord->flatMap(function ($item) {
            return collect($item)->map(function ($value, $key) {
                return $value;
            });
        })->avg();

        $hardSkillRecord = Cco::where("agentId", $agentId)
            ->whereYear('created_at', date('Y'))
            ->select(['cso_analysis_completeness', 'solution_fulfillment'])
            ->get();

        $hardSkill = $hardSkillRecord->flatMap(function ($item) {
            return collect($item)->map(function ($value, $key) {
                return $value;
            });
        })->avg();

        $total = $serviceLevel + $softSkill + $hardSkill;
        return [
            'serviceLevel' => round(($total > 0 ? ($serviceLevel / $total) * 100 : 0), 0),
            'softSkill' => round(($total > 0 ? ($softSkill / $total) * 100 : 0), 0),
            'hardSkill' => round(($total > 0 ? ($hardSkill / $total) * 100 : 0), 0),
        ];
    }

    private static function calculateCcoRecordingData($agentId)
    {
        $systemProcedureRecord = CcoRecording::where("agentId", $agentId)
            ->whereYear('created_at', date('Y'))
            ->select(['cco_correct_opening_greeting', 'cco_asks_customer_name', 'cco_confirms_service_adequacy', 'cco_correct_closing_greeting', 'hold_line'])
            ->get();
        $systemProcedure = $systemProcedureRecord->flatMap(function ($item) {
            return collect($item)->map(function ($value, $key) {
                return $value;
            });
        })->avg();

        $softSkillRecord = CcoRecording::where("agentId", $agentId)
            ->whereYear('created_at', date('Y'))
            ->select(['cco_good_tone_and_intonation', 'cco_good_speaking_speed_articulation_volume', 'cco_mentions_customer_name', 'cco_good_service_ethics_language', 'cco_confirms_customer_complaint', 'invalid_case_type_category', 'task_suitability', 'other_zendesk', 'cco_gives_positive_statements', 'cco_does_not_reask_customer_info'])
            ->get();
        $softSkill = $softSkillRecord->flatMap(function ($item) {
            return collect($item)->map(function ($value, $key) {
                return $value;
            });
        })->avg();

        $hardSkillRecord = CcoRecording::where('agentId', $agentId)
            ->whereYear('created_at', date('Y'))
            ->select(['cco_explores_customer_info_needs', 'no_service_transfer', 'cco_explanation_accuracy_complete', 'cco_provides_complete_solutions'])
            ->get();

        $hardSkill = $hardSkillRecord->flatMap(function ($item) {
            return collect($item)->map(function ($value, $key) {
                return $value;
            });
        })->avg();

        $enjoyingRecord = CcoRecording::where('agentId', $agentId)
            ->whereYear('created_at', date('Y'))
            ->select(['cco_clear_voice_quality', 'no_background_noise'])
            ->get();

        $enjoying = $enjoyingRecord->flatMap(function ($item) {
            return collect($item)->map(function ($value, $key) {
                return $value;
            });
        })->avg();

        $total = $systemProcedure + $softSkill + $hardSkill + $enjoying;
        return [
            'systemProcedure' => round(($total > 0 ? ($systemProcedure / $total) * 100 : 0), 0),
            'softSkill' => round(($total > 0 ? ($softSkill / $total) * 100 : 0), 0),
            'hardSkill' => round(($total > 0 ? ($hardSkill / $total) * 100 : 0), 0),
            'enjoying' => round(($total > 0 ? ($enjoying / $total) * 100 : 0), 0),
        ];
    }

    private static function calculateChoData($agentId)
    {
        $serviceLevelRecord = Cho::where('agentId', $agentId)
            ->whereYear('created_at', date('Y'))
            ->select(['sla_response', 'ticket_closure_sla'])
            ->get();

        $serviceLevel = $serviceLevelRecord->flatMap(function ($item) {
            return collect($item)->map(function ($value, $key) {
                return $value;
            });
        })->avg();

        $softSkillRecord = Cho::where('agentId', $agentId)
            ->whereYear('created_at', date('Y'))
            ->select(['email_punctuation_check', 'ticket_text_content', 'case_linkage_check', 'task_status_check', 'zendesk_category_check', 'is_language_ethical'])
            ->get();

        $softSkill = $softSkillRecord->flatMap(function ($item) {
            return collect($item)->map(function ($value, $key) {
                return $value;
            });
        })->avg();

        $hardSkillRecord = Cho::where('agentId', $agentId)
            ->whereYear('created_at', date('Y'))
            ->select(['analysis_correctness', 'complete_solution_status'])
            ->get();

        $hardSkill = $hardSkillRecord->flatMap(function ($item) {
            return collect($item)->map(function ($value, $key) {
                return $value;
            });
        })->avg();

        $total = $serviceLevel + $softSkill + $hardSkill;
        return [
            'serviceLevel' => round(($total > 0 ? ($serviceLevel / $total) * 100 : 0), 0),
            'softSkill' => round(($total > 0 ? ($softSkill / $total) * 100 : 0), 0),
            'hardSkill' => round(($total > 0 ? ($hardSkill / $total) * 100 : 0), 0),
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        $ccoData = [];
        $ccoRecordingData = [];
        $choData = [];
        $ccoDataCommentByMonth = [];
        $ccoRecordingDataCommendRecord = [];
        $choDataCommentByMonth = [];
        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        if ($infolist->record->position == 'L1') {
            $ccoData = AgentResource::calculateCcoData($infolist->record->id);
            $ccoRecordingData = AgentResource::calculateCcoRecordingData($infolist->record->id);

            $ccoDataCommentRecord = Cco::where("agentId", $infolist->record->id)
                ->whereYear("created_at", date('Y'))
                ->select(["ticket", 'sample_name', 'sla_response_note', 'email_format_valid_note', 'zendesk_ticket_content_note', 'related_case_check_note', 'task_status_check_note', 'category_validation_status_note', 'service_language_quality_note', 'cso_analysis_completeness_note', 'solution_fulfillment_note', 'created_at'])
                ->get();
            $ccoDataCommentByMonth = $ccoDataCommentRecord->groupBy(function ($data) {
                return $data->created_at->format("m");
            });

            $ccoRecordingDataCommendRecord = CcoRecording::where("agentId", $infolist->record->id)
                ->whereYear("created_at", date('Y'))
                ->select(["ticket", 'sample_name', 'cco_correct_opening_greeting_note', 'cco_asks_customer_name_note', 'cco_confirms_service_adequacy_note', 'cco_correct_closing_greeting_note', 'hold_line_note', 'cco_good_tone_and_intonation_note', 'cco_good_speaking_speed_articulation_volume_note', 'cco_mentions_customer_name_note', 'cco_good_service_ethics_language_note', 'cco_confirms_customer_complaint_note', 'invalid_case_type_category_note', 'task_suitability_note', 'other_zendesk_note', 'cco_gives_positive_statements_note', 'cco_does_not_reask_customer_info_note', 'cco_explores_customer_info_needs_note', 'no_service_transfer_note', 'cco_explanation_accuracy_complete_note', 'cco_provides_complete_solutions_note', 'cco_clear_voice_quality_note', 'no_background_noise_note', 'created_at'])
                ->get();
            $ccoRecordingDataCommentByMonth = $ccoRecordingDataCommendRecord->groupBy(function ($data) {
                return $data->created_at->format("m");
            });
        } else {
            $choData = AgentResource::calculateChoData($infolist->record->id);
            $choDataCommentRecord = Cho::where("agentId", $infolist->record->id)
                ->whereYear("created_at", date('Y'))
                ->select(["ticket", 'sample_name', 'sla_response_note', 'ticket_closure_sla_note', 'email_punctuation_check_note', 'ticket_text_content_note', 'case_linkage_check_note', 'task_status_check_note', 'zendesk_category_check_note', 'is_language_ethical_note', 'analysis_correctness_note', 'complete_solution_status_note', 'created_at'])
                ->get();
        }


        return $infolist
            ->schema([
                Section::make('Info Agent')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('Name')->label('Nama Agent'),
                                TextEntry::make('position') 
                                    ->badge()
                                    ->label('Posisi Agent'),
                            ]),
                    ]),

                Section::make('Skor Agent')
                    ->schema([
                        $infolist->record->position == 'L1' ?
                            Grid::make(3)
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        Section::make('CCO Email')
                                            ->schema([
                                                TextEntry::make('serviceLevel')
                                                    ->label('Service Level')
                                                    ->hint($ccoData['serviceLevel'] . "%"),
                                                TextEntry::make('softSkill')
                                                    ->label('Soft Skill')
                                                    ->hint($ccoData['softSkill'] . "%"),
                                                TextEntry::make('hardSkill')
                                                    ->label('Hard Skill')
                                                    ->hint($ccoData['hardSkill'] . "%"),
                                            ]),

                                        Section::make('CCO Recording')
                                            ->schema([
                                                TextEntry::make('systemProcedure')
                                                    ->label('System Procedure')
                                                    ->hint($ccoRecordingData['systemProcedure'] . "%"),
                                                TextEntry::make('softSkill')
                                                    ->label('Soft Skill')
                                                    ->hint($ccoRecordingData['softSkill'] . "%"),
                                                TextEntry::make('hardSkill')
                                                    ->label('Hard Skill')
                                                    ->hint($ccoRecordingData['hardSkill'] . "%"),
                                                TextEntry::make('enjoying')
                                                    ->label('Enjoying')
                                                    ->hint($ccoRecordingData['enjoying'] . "%"),
                                            ]),
                                    ])
                            ]) : Grid::make(4)
                            ->schema([
                                Section::make('CHO')
                                    ->schema([
                                        TextEntry::make('serviceLevel')
                                            ->label('Service Level')
                                            ->hint($choData['serviceLevel'] . "%"),
                                        TextEntry::make('softSkill')
                                            ->label('Soft Skill')
                                            ->hint($choData['softSkill'] . "%"),
                                        TextEntry::make('hardSkill')
                                            ->label('Hard Skill')
                                            ->hint($choData['hardSkill'] . "%"),
                                    ]),
                            ]),
                    ]),
                Livewire::make(AgentRecordView::class, ['agent' => $infolist->record]),
                Section::make("Komentar")
                    ->schema($infolist->record->position == 'L1' ? [
                        Grid::make(2)
                            ->schema([
                                Section::make("CCO")
                                    ->schema($ccoDataCommentByMonth->map(function ($data, $key) use ($months) {
                                        return Section::make($months[$key])
                                            ->schema($data->map(function ($item) {
                                                return TextEntry::make($item->ticket)
                                                    ->label($item->sample_name)
                                                    ->hint("#" . $item->ticket)
                                                    ->default(
                                                        collect([
                                                            $item->sla_response_note,
                                                            $item->email_format_valid_note,
                                                            $item->zendesk_ticket_content_note,
                                                            $item->related_case_check_note,
                                                            $item->task_status_check_note,
                                                            $item->category_validation_status_note,
                                                            $item->service_language_quality_note,
                                                            $item->cso_analysis_completeness_note,
                                                            $item->solution_fulfillment_note,
                                                        ])
                                                            ->filter(fn($note) => !is_null($note) && $note !== '')
                                                            ->implode('<br/>')
                                                    )
                                                    ->html();
                                            })->toArray());
                                    })->toArray()),
                                Section::make("CCO Recording")
                                    ->schema($ccoRecordingDataCommentByMonth->map(function ($data, $key) use ($months) {
                                        return Section::make($months[$key])
                                            ->schema($data->map(function ($item) {
                                                return TextEntry::make($item->ticket)
                                                    ->label($item->sample_name)
                                                    ->hint("#" . $item->ticket)
                                                    ->default(
                                                        collect([
                                                            $item->cco_correct_opening_greeting_note,
                                                            $item->cco_asks_customer_name_note,
                                                            $item->cco_confirms_service_adequacy_note,
                                                            $item->cco_correct_closing_greeting_note,
                                                            $item->hold_line_note,
                                                            $item->cco_good_tone_and_intonation_note,
                                                            $item->cco_good_speaking_speed_articulation_volume_note,
                                                            $item->cco_mentions_customer_name_note,
                                                            $item->cco_good_service_ethics_language_note,
                                                            $item->cco_confirms_customer_complaint_note,
                                                            $item->invalid_case_type_category_note,
                                                            $item->task_suitability_note,
                                                            $item->other_zendesk_note,
                                                            $item->cco_gives_positive_statements_note,
                                                            $item->cco_does_not_reask_customer_info_note,
                                                            $item->cco_explores_customer_info_needs_note,
                                                            $item->no_service_transfer_note,
                                                            $item->cco_explanation_accuracy_complete_note,
                                                            $item->cco_provides_complete_solutions_note,
                                                            $item->cco_clear_voice_quality_note,
                                                            $item->no_background_noise_note,
                                                        ])
                                                            ->filter(fn($note) => !is_null($note) && $note !== '')
                                                            ->implode('<br/>')
                                                    )
                                                    ->html();
                                            })->toArray());
                                    })->toArray())
                            ])
                    ] : [
                        Section::make("CHO")
                            ->schema($choDataCommentRecord->groupBy(function ($data) {
                                return $data->created_at->format("m");
                            })->map(function ($data, $key) use ($months) {
                                return Section::make($months[$key])
                                    ->schema($data->map(function ($item) {
                                        return TextEntry::make($item->ticket)
                                            ->label($item->sample_name)
                                            ->hint("#" . $item->ticket)
                                            ->default(
                                                collect([
                                                    $item->sla_response_note,
                                                    $item->ticket_closure_sla_note,
                                                    $item->email_punctuation_check_note,
                                                    $item->ticket_text_content_note,
                                                    $item->case_linkage_check_note,
                                                    $item->task_status_check_note,
                                                    $item->zendesk_category_check_note,
                                                    $item->is_language_ethical_note,
                                                    $item->analysis_correctness_note,
                                                    $item->complete_solution_status_note,
                                                ])
                                                    ->filter(fn($note) => !is_null($note) && $note !== '')
                                                    ->implode('<br/>')
                                            )
                                            ->html();
                                    })->toArray());
                            })->toArray())
                    ])
            ])
            ->columns(1);
    }
}
