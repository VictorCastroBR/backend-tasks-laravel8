@component('mail::message')
# Nova tarefa criada

**Titulo:** {{ $task->title }}
**Prioridade:** {{ ucfirst($task->priority) }}
**Status:** {{ ucfirst($task->status) }}
@isset($task->due_date)
**Prazo:** {{ \Illuminate\Support\Carbon::parse($task->due_date)->toFormattedDateString() }}
@endisset

@component('mail::button', ['url' => config('app.url')])
Abrir Aplicativo
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
