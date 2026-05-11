<?php

declare(strict_types=1);

enum VerificationStatus: string
{
    case PENDING = 'pending';
    case VERIFIED = 'verified';
    case REJECTED = 'rejected';
}

class Deliverable
{
    private string $deliverableId;
    private string $fileUrl;
    private string $description;
    private VerificationStatus $verificationStatus;

    public function __construct(
        string $deliverableId,
        string $fileUrl,
        string $description,
        VerificationStatus $verificationStatus = VerificationStatus::PENDING
    ) {
        $this->deliverableId = $deliverableId;
        $this->fileUrl = $fileUrl;
        $this->description = $description;
        $this->verificationStatus = $verificationStatus;
    }

    public function attach(string $url): void
    {
        $this->fileUrl = $url;
    }

    public function markVerified(): void
    {
        $this->verificationStatus = VerificationStatus::VERIFIED;
    }

    public function markRejected(): void
    {
        $this->verificationStatus = VerificationStatus::REJECTED;
    }

    public function getDeliverableId(): string
    {
        return $this->deliverableId;
    }

    public function getFileUrl(): string
    {
        return $this->fileUrl;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getVerificationStatus(): VerificationStatus
    {
        return $this->verificationStatus;
    }
}