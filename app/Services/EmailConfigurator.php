<?php
namespace App\Services;

class EmailConfigurator
{
    protected $attachments = [];
    protected $inlineImages = [];
    protected $inlineImageVariables = [];

    // Method to add attachments
    public function attach($filePath, $options = [])
    {
        $this->attachments[] = ['file' => $filePath, 'options' => $options];
    }

    /**
     * Embed an image in the email:
     *
     * @param string $file Path to the image file.
     * @param string $key Unique key to identify the image.
     * @param array $options Additional metadata (optional).
     */
    public function embedImage(string $file, string $key, array $options = []): void
    {
        // Add the image with its key and options to the list
        $this->inlineImages[] = [
            'file' => $file,
            'key' => $key,
            'options' => $options,
        ];
    }

    // Get attachments
    public function getAttachments()
    {
        return $this->attachments;
    }

    // Get embedded images
    public function getInlineImages(): array
    {
        return $this->inlineImages;
    }

    // Get the embedded image variables
    public function getInlineImageVariables()
    {
        return $this->inlineImageVariables;
    }
}
