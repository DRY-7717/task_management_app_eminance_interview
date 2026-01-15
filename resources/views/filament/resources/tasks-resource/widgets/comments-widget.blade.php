<x-filament-widgets::widget>
    <x-filament::section>
        <div class="space-y-6">

            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold dark:text-white flex items-center gap-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-9 mx-auto text-gray-600">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
                    </svg>
                    <span>Comments</span>
                </h3>
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $this->comments->count() }} {{ Str::plural('comment', $this->comments->count()) }}
                </span>
            </div>

            <!-- Form untuk menambah comment -->
            <div class="border dark:border-gray-700 rounded-lg p-4 bg-gray-50 dark:bg-gray-800/50">
                <form wire:submit="submit" class="space-y-3">
                    {{ $this->form }}

                    <div class="flex justify-end">
                        <x-filament::button type="submit" icon="heroicon-o-paper-airplane">
                            Post Comment
                        </x-filament::button>
                    </div>
                </form>
            </div>


            <div class="space-y-4">
                @forelse($this->comments as $comment)
                    <div class="border dark:border-gray-700 rounded-lg p-4 space-y-3 bg-white dark:bg-gray-900 hover:shadow-md transition-shadow"
                        wire:key="comment-{{ $comment->id }}">

                        <div class="flex justify-between items-start">
                            <div class="flex items-center space-x-3">
                                <div>
                                    <p class="font-semibold dark:text-white">
                                        {{ $comment->user->name }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-x-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>{{ $comment->created_at->diffForHumans() }}</span>
                                    </p>
                                </div>
                            </div>


                            @if ($comment->user_id === auth()->id() || auth()->user()->is_admin)
                                {{ ($this->deleteCommentAction)(['commentId' => $comment->id]) }}
                            @endif
                        </div>


                        <div class="pl-0">
                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                                {{ $comment->body }}
                            </p>
                        </div>


                        @if ($comment->replies->count() > 0)
                            <div class="ml-6 mt-4 space-y-3 border-l-2 border-blue-500 dark:border-blue-600 pl-4">
                                @foreach ($comment->replies as $reply)
                                    <div class="space-y-2 bg-gray-50 dark:bg-gray-800/50 rounded-lg p-3"
                                        wire:key="reply-{{ $reply->id }}">
                                        <div class="flex justify-between items-start">
                                            <div class="flex items-center space-x-2">
                                                <div>
                                                    <p class="font-semibold text-sm dark:text-white">
                                                        {{ $reply->user->name }}
                                                    </p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ $reply->created_at->diffForHumans() }}
                                                    </p>
                                                </div>
                                            </div>


                                            @if ($reply->user_id === auth()->id() || auth()->user()->is_admin)
                                                {{ ($this->deleteCommentAction)(['commentId' => $reply->id]) }}
                                            @endif
                                        </div>

                                        <p class="text-gray-700 dark:text-gray-300 text-sm">
                                            {{ $reply->body }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div class="mt-3" x-data="{ showReply: false }">
                            <button @click="showReply = !showReply"
                                class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium inline-flex items-center space-x-1 transition-colors"
                                type="button">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                </svg>
                                <span x-text="showReply ? 'Cancel' : 'Reply'"></span>
                            </button>

                            <div x-show="showReply" x-cloak x-transition
                                class="mt-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg p-3">
                                <form wire:submit.prevent="addReply({{ $comment->id }}, $event.target.reply.value)"
                                    @submit="showReply = false; $event.target.reply.value = ''" class="space-y-2">
                                    <textarea name="reply" rows="2" placeholder="Write a reply..."
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:border-blue-500 focus:ring-blue-500 text-sm"
                                        required></textarea>
                                    <div class="flex justify-end">
                                        <x-filament::button type="submit" size="sm"
                                            icon="heroicon-o-paper-airplane">
                                            Post Reply
                                        </x-filament::button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 border-2 border-dashed dark:border-gray-700 rounded-lg">
                        <div class="text-6xl mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-28 mx-auto text-gray-600">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
                            </svg>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 font-medium">
                            No comments yet
                        </p>
                        <p class="text-sm text-gray-400 dark:text-gray-500">
                            Be the first to comment on this task!
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </x-filament::section>


    <x-filament-actions::modals />
</x-filament-widgets::widget>
