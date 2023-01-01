<?php

class IntegrationUserData {

    public int $id;
    public int $integration_id;
    public string $user_id;
    public ?string $identifier;
    public ?string $username;
    public bool $verified;
    public int $date;
    public ?string $code;

    public function __construct(object $row) {
        $this->id = $row->id;
        $this->integration_id = $row->integration_id;
        $this->user_id = $row->user_id;
        $this->identifier = $row->identifier;
        $this->username = $row->username;
        $this->verified = $row->verified;
        $this->date = $row->date;
        $this->code = $row->code;
    }

}
