<?php
declare(strict_types=1);

use ElasticAdapter\Indices\Mapping;
use ElasticAdapter\Indices\Settings;
use ElasticMigrations\Facades\Index;
use ElasticMigrations\MigrationInterface;

final class CreateStudentIndex implements MigrationInterface
{
    /**
     * Run the migration.
     */
    public function up(): void
    {
        $settings = [
            'analysis' => [
                'filter' => [
                    "autocomplete_filter" => [
                        "type" => "edge_ngram",
                        "min_gram" => 3,
                        "max_gram" => 25,
                        "token_chars" => [
                            "letter",
                            "whitespace",
                        ],
                    ],
                ],
                'analyzer' => [
                    "autocomplete" => [
                        "type" => "custom",
                        "tokenizer" => "standard",
                        "filter" => [
                            "lowercase",
                            "autocomplete_filter",
                        ],
                    ],
                    "whitespace_analyzer" => [
                        "type" => "custom",
                        "tokenizer" => "whitespace",
                        "filter" => [
                            "lowercase",
                            "autocomplete_filter",
                        ],
                    ],
                ],
            ],
        ];
        $mapping = [
            "properties" => [
                "studentId" => [
                    "type" => "integer",
                ],
                "studentName" => [
                    "type" => "text",
                    "analyzer" => "autocomplete",
                    "fielddata" => true,
                ],
                "classId" => [
                    "type" => "integer",
                ],
                "className" => [
                    "type" => "text",
                    "analyzer" => "autocomplete",
                    "fielddata" => true,
                ],
                "courseId" => [
                    "type" => "integer",
                ],
                "courseName" => [
                    "type" => "text",
                    "analyzer" => "autocomplete",
                    "fielddata" => true,
                ],

            ],
        ];
        //
        Index::createRaw('student_index', $mapping, $settings);
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        //
        Index::dropIfExists('student_index');
    }
}
