<?php

namespace App\View\Models;

class TeamFinderResult
{
    public function __construct(
        public readonly string $type,
        public readonly ?object $contact,
        public readonly array $scraped = []
    ) {}

    public function name(): ?string
    {
        if ($this->type === 'contact') {
            return $this->contact?->name;
        }

        $first = $this->scraped['first_name'] ?? null;
        $last = $this->scraped['last_name'] ?? null;
        $fallback = $this->scraped['name'] ?? null;

        $full = trim(trim((string) $first).' '.trim((string) $last));

        return $full ?: $fallback;
    }

    public function company(): ?string
    {
        return $this->type === 'contact'
            ? $this->contact?->company?->name
            : ($this->scraped['company'] ?? null);
    }

    public function position(): ?string
    {
        return $this->type === 'contact'
            ? $this->contact?->position
            : ($this->scraped['position'] ?? null);
    }

    public function email(): ?string
    {
        return $this->type === 'contact'
            ? $this->contact?->email
            : ($this->scraped['email'] ?? null);
    }

    public function location(): ?string
    {
        if ($this->type === 'contact') {
            $parts = [
                $this->contact?->city,
                $this->contact?->country,
            ];
            return trim(collect($parts)->filter()->implode(', '));
        }

        return $this->scraped['location'] ?? null;
    }

    public function source(): ?string
    {
        return $this->type === 'scraped'
            ? ($this->scraped['source'] ?? null)
            : null;
    }

    public function profileUrl(): ?string
    {
        return $this->type === 'contact'
            ? $this->contact?->linkedin_url
            : ($this->scraped['profile_url'] ?? null);
    }

    public function scrapedAt(): ?string
    {
        return $this->type === 'scraped'
            ? optional($this->scraped['scraped_at'] ?? null)?->diffForHumans()
            : null;
    }

    public function team(): ?string
    {
        return $this->type === 'contact'
            ? $this->contact?->team_name
            : ($this->scraped['team'] ?? null);
    }
}
