@component('mail::message')
# Tarefa Concluída 🎉

A tarefa **{{ $task->title }}** foi marcada como concluída.

**Concluída em:** {{ optional($task->completed_at)->format('d/m/Y H:i') }}

@component('mail::button', ['url' => 'app.url'])
Ver tarefa
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
