<?php

declare(strict_types=1);

class Money
{
    private float $amount;
    private string $currency;

    public function __construct(float $amount, string $currency = 'USD')
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}

enum MilestoneStatus: string
{
    case PENDING = 'pending';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case PAID = 'paid';
}

class Milestone
{
    private string $milestoneId;
    private string $title;
    private Money $amount;
    private DateTime $dueDate;
    private MilestoneStatus $status;
    private bool $deadlineEscalated = false;

    /**
     * @var Deliverable[]
     */
    private array $deliverables = [];

    public function __construct(
        string $milestoneId,
        string $title,
        Money $amount,
        DateTime $dueDate,
        MilestoneStatus $status = MilestoneStatus::PENDING
    ) {
        $this->milestoneId = $milestoneId;
        $this->title = $title;
        $this->amount = $amount;
        $this->dueDate = $dueDate;
        $this->status = $status;
    }

    public function addDeliverable(Deliverable $deliverable): void
    {
        $this->deliverables[] = $deliverable;
    }

    public function markPaidReleased(): void
    {
        $this->status = MilestoneStatus::PAID;
    }

    public function escalateDeadline(): void
    {
        $this->deadlineEscalated = true;
    }

    public function getMilestoneId(): string
    {
        return $this->milestoneId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getAmount(): Money
    {
        return $this->amount;
    }

    public function getDueDate(): DateTime
    {
        return $this->dueDate;
    }

    public function getStatus(): MilestoneStatus
    {
        return $this->status;
    }

    public function isDeadlineEscalated(): bool
    {
        return $this->deadlineEscalated;
    }

    /**
     * @return Deliverable[]
     */
    public function getDeliverables(): array
    {
        return $this->deliverables;
    }
}