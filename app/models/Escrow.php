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

    public function add(Money $money): Money
    {
        if ($this->currency !== $money->getCurrency()) {
            throw new Exception("Currency mismatch.");
        }

        return new Money(
            $this->amount + $money->getAmount(),
            $this->currency
        );
    }
}

enum EscrowStatus: string
{
    case ACTIVE = 'active';
    case CLOSED = 'closed';
    case DISPUTED = 'disputed';
}

enum TransactionType: string
{
    case DEPOSIT = 'deposit';
    case RELEASE = 'release';
}

enum TransactionStatus: string
{
    case PENDING = 'pending';
    case COMPLETED = 'completed';
    case FAILED = 'failed';
}

class Transaction
{
    private string $transactionId;
    private TransactionType $type;
    private Money $amount;
    private TransactionStatus $status;

    public function __construct(
        string $transactionId,
        TransactionType $type,
        Money $amount,
        TransactionStatus $status = TransactionStatus::PENDING
    ) {
        $this->transactionId = $transactionId;
        $this->type = $type;
        $this->amount = $amount;
        $this->status = $status;
    }

    public function complete(): void
    {
        $this->status = TransactionStatus::COMPLETED;
    }

    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    public function getType(): TransactionType
    {
        return $this->type;
    }

    public function getAmount(): Money
    {
        return $this->amount;
    }

    public function getStatus(): TransactionStatus
    {
        return $this->status;
    }
}

class EscrowAccount
{
    private string $escrowId;
    private Money $totalDeposited;
    private Money $totalReleased;
    private EscrowStatus $status;

    /**
     * @var Transaction[]
     */
    private array $transactions = [];

    public function __construct(
        string $escrowId,
        Money $totalDeposited,
        Money $totalReleased,
        EscrowStatus $status = EscrowStatus::ACTIVE
    ) {
        $this->escrowId = $escrowId;
        $this->totalDeposited = $totalDeposited;
        $this->totalReleased = $totalReleased;
        $this->status = $status;
    }

    public function deposit(Money $amount): Transaction
    {
        $this->totalDeposited = $this->totalDeposited->add($amount);

        $transaction = new Transaction(
            uniqid('txn_'),
            TransactionType::DEPOSIT,
            $amount,
            TransactionStatus::COMPLETED
        );

        $this->transactions[] = $transaction;

        return $transaction;
    }

    public function releasePartial(Money $amount): Transaction
    {
        $availableBalance =
            $this->totalDeposited->getAmount()
            - $this->totalReleased->getAmount();

        if ($amount->getAmount() > $availableBalance) {
            throw new Exception("Insufficient escrow balance.");
        }

        $this->totalReleased = $this->totalReleased->add($amount);

        $transaction = new Transaction(
            uniqid('txn_'),
            TransactionType::RELEASE,
            $amount,
            TransactionStatus::COMPLETED
        );

        $this->transactions[] = $transaction;

        return $transaction;
    }

    public function closeAccount(): void
    {
        $this->status = EscrowStatus::CLOSED;
    }

    public function getEscrowId(): string
    {
        return $this->escrowId;
    }

    public function getTotalDeposited(): Money
    {
        return $this->totalDeposited;
    }

    public function getTotalReleased(): Money
    {
        return $this->totalReleased;
    }

    public function getStatus(): EscrowStatus
    {
        return $this->status;
    }

    /**
     * @return Transaction[]
     */
    public function getTransactions(): array
    {
        return $this->transactions;
    }
}