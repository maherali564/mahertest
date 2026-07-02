<?php

namespace App\Filament\Resources\ComplaintResource\Pages;

use App\Filament\Resources\ComplaintResource;
use App\Models\Complaint;
use App\Notifications\ComplaintReplied;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewComplaint extends ViewRecord
{
    protected static string $resource = ComplaintResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            TextEntry::make('name'),
            TextEntry::make('email'),
            TextEntry::make('subject'),
            TextEntry::make('status')->badge()->color(fn (string $state) => match ($state) {
                'new' => 'danger',
                'in_progress' => 'warning',
                'replied' => 'info',
                'closed' => 'success',
            }),
            TextEntry::make('description')->columnSpanFull(),
            TextEntry::make('admin_reply')->label(__('complaints.admin_reply'))->columnSpanFull()->html(),
            TextEntry::make('replied_at')->dateTime()->visible(fn ($record) => $record->replied_at !== null),
            TextEntry::make('created_at')->dateTime(),
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('reply')
                ->label(__('complaints.reply'))
                ->icon('heroicon-o-chat-bubble-left-ellipsis')
                ->form([
                    Select::make('status')
                        ->options([
                            'new' => __('complaints.status_new'),
                            'in_progress' => __('complaints.status_in_progress'),
                            'replied' => __('complaints.status_replied'),
                            'closed' => __('complaints.status_closed'),
                        ])
                        ->required(),
                    Textarea::make('admin_reply')
                        ->label(__('complaints.admin_reply'))
                        ->required()
                        ->minLength(10),
                ])
                ->action(function (array $data, Complaint $record) {
                    $record->update([
                        'status' => $data['status'],
                        'admin_reply' => $data['admin_reply'],
                        'replied_at' => now(),
                    ]);

                    $record->notify(new ComplaintReplied($record));

                    Notification::make()->success()->title(__('complaints.reply_sent'))->send();
                }),
            Actions\DeleteAction::make(),
        ];
    }
}
