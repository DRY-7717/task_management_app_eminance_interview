<?php

namespace App\Filament\Resources\TasksResource\Widgets;

use App\Models\Comment;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Widgets\Widget;

class CommentsWidget extends Widget implements HasForms, HasActions
{
    use InteractsWithForms;
    use InteractsWithActions; 

    protected static string $view = 'filament.resources.tasks-resource.widgets.comments-widget';

    protected int | string | array $columnSpan = 'full';

    public ?array $data = [];
    public $record;
    protected $listeners = ['refreshComments' => '$refresh'];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Textarea::make('body')
                    ->label('Add Comment')
                    ->required()
                    ->rows(3)
                    ->placeholder('Write your comment here...')
                    ->maxLength(1000),
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        $data = $this->form->getState();

        Comment::create([
            'task_id' => $this->record->id,
            'user_id' => auth()->id(),
            'body' => $data['body'],
        ]);

        $this->form->fill();

        Notification::make()
            ->success()
            ->title('Comment added successfully')
            ->body('Your comment has been posted.')
            ->send();

        $this->dispatch('refreshComments');
    }

    public function addReply(int $commentId, string $body): void
    {
        if (empty(trim($body))) {
            Notification::make()
                ->danger()
                ->title('Reply cannot be empty')
                ->send();
            return;
        }

        Comment::create([
            'task_id' => $this->record->id,
            'user_id' => auth()->id(),
            'parent_id' => $commentId,
            'body' => $body,
        ]);

        Notification::make()
            ->success()
            ->title('Reply added successfully')
            ->send();

        $this->dispatch('refreshComments');
    }

   
    public function deleteCommentAction(): Action
    {
        return Action::make('deleteComment')
            ->requiresConfirmation()
            ->modalHeading('Delete Comment')
            ->modalDescription('Are you sure you want to delete this comment? This action cannot be undone.')
            ->modalSubmitActionLabel('Yes, delete it')
            ->modalCancelActionLabel('Cancel')
            ->color('danger')
            ->icon('heroicon-o-trash')
            ->action(function (array $arguments) {
                $commentId = $arguments['commentId'];
                $comment = Comment::find($commentId);

                if (!$comment) {
                    Notification::make()
                        ->danger()
                        ->title('Comment not found')
                        ->send();
                    return;
                }

                if ($comment->user_id !== auth()->id() && !auth()->user()->is_admin) {
                    Notification::make()
                        ->danger()
                        ->title('Unauthorized')
                        ->body('You cannot delete this comment.')
                        ->send();
                    return;
                }

                
                $comment->replies()->delete();
                $comment->delete();

                Notification::make()
                    ->success()
                    ->title('Comment deleted')
                    ->body('Comment deleted successfully')
                    ->send();

                $this->dispatch('refreshComments');
            });
    }

    public function getCommentsProperty()
    {
        return $this->record->comments()->with(['user', 'replies.user'])->get();
    }
}
