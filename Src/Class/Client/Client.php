<?php

class Client
{
    protected int $id;
    protected string $name;
    protected string $email;
    protected ?string $imageFilename;

    public function __construct(int $id, string $name, string $email, ?string $image = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;

        $this->setImageFilename($image);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    public function getImage(): string
    {
        if (defined('PUBLIC_URL')) {
            $publicUrl = PUBLIC_URL;
        } else {
            $publicUrl = '/Public';
        }

        if ($this->imageFilename === null) {
            return $publicUrl . '/images/client/defaultimage.jpg';
        }

        return $publicUrl . '/images/client/' . rawurlencode($this->imageFilename);
    }

    public function getImageFilename(): ?string
    {
        return $this->imageFilename;
    }

    public function setImageFilename(?string $imageFilename): void
    {
        $imageFilename = basename(str_replace('\\', '/', trim((string) $imageFilename)));
        $this->imageFilename = in_array($imageFilename, ['', '.', '..'], true) ? null : $imageFilename;
    }

    public function isAdmin()
    {
        if ($this->getEmail() == ADMIN_EMAIL) {
            return true;
        }
        return false;
    }
}
