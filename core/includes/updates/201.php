<?php

return new class extends UpgradeScript {

    public function run(): void {
        $this->runMigrations();

        $this->setVersion('1.0');
    }
};
