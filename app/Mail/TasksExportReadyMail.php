<?php

namespace App\Mail;

use App\Models\Export;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TasksExportReadyMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $export;
    public $deepLink;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Export $export, ?string $deepLink)
    {
        $this->export = $export;
        $this->deepLink = $deepLink;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->markdown('mail.exports.ready');
        return $this->subject('Sua exportação de tarefas está pronta')
            ->markdown('mail.exports.ready', [
                'deepLink' => $this->deepLink
            ]);
    }
}
