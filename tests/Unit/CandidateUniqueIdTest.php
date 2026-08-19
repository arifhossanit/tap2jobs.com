<?php

namespace Tests\Unit;

use Tests\TestCase;

class CandidateUniqueIdTest extends TestCase
{
    public function test_candidate_id_generation_retries_without_losing_the_new_value(): void
    {
        $repository = file_get_contents(app_path('Repositories/Candidates/CandidateRepository.php'));
        $methodStart = strpos($repository, 'public function getUniqueCandidateId()');
        $method = substr($repository, $methodStart, 450);

        $this->assertStringContainsString('do {', $method);
        $this->assertStringContainsString('while (Candidate::whereUniqueId($candidateUniqueId)->exists());', $method);
        $this->assertStringNotContainsString('self::getUniqueCandidateId()', $method);
    }

    public function test_migration_repairs_duplicates_before_adding_unique_index(): void
    {
        $migration = file_get_contents(database_path('migrations/2026_08_19_000003_make_candidate_unique_id_unique.php'));
        $repairPosition = strpos($migration, "->update(['unique_id' => \$uniqueId])");
        $indexPosition = strpos($migration, "->unique('unique_id', 'candidates_unique_id_unique')");

        $this->assertNotFalse($repairPosition);
        $this->assertNotFalse($indexPosition);
        $this->assertLessThan($indexPosition, $repairPosition);
    }
}
