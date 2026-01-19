<?php
namespace App\Message;

class GeneratePdfMessage
{
    private $adminId;
    private $matches;

    public function __construct(int $adminId, array $matches)
    {
        $this->adminId = $adminId;
        $this->matches = $matches;
    }

    public function getAdminId(): int
    {
        return $this->adminId;
    }

    public function getMatches(): array
    {
        return $this->matches;
    }
}
