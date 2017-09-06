<?php

namespace True\Standards\Database;

interface MigrationInterface
{
    public function up();
    public function down();
}
