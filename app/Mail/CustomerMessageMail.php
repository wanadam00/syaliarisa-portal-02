<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomerMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Customer From Portal',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'view.name',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('New Customer Inquiry: ' . $this->data['subject'])
            ->html($this->buildHtml());
    }

    protected function buildHtml()
    {
        return "
        <h2>New Customer Message</h2>
        <p><strong>Name:</strong> {$this->data['name']}</p>
        <p><strong>Email:</strong> {$this->data['email']}</p>
        <p><strong>Subject:</strong> {$this->data['subject']}</p>
        <p><strong>Message:</strong></p>
        <p>{$this->data['message']}</p>
    ";
    }
}
