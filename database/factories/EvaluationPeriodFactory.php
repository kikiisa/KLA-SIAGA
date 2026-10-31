<?php

namespace Database\Factories;

use App\Models\EvaluationPeriod;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EvaluationPeriod>
 */
class EvaluationPeriodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $year = (int) date('Y');
        $startDate = fake()->date('Y-m-d', $year.'-01-01');
        $endDate = fake()->date('Y-m-d', $year.'-'.fake()->numberBetween(6, 12).'-01');

        return [
            'code' => fake()->unique()->bothify('KLA-##??'),
            'name' => 'Evaluasi KLA '.fake()->unique()->year(),
            'year' => $year,
            'description' => fake()->sentence(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => EvaluationPeriod::STATUS_DRAFT,
            'created_by' => User::factory(),
        ];
    }

    /**
     * State for an active period.
     */
    public function active(): static
    {
        return $this->state(fn (): array => [
            'status' => EvaluationPeriod::STATUS_ACTIVE,
        ]);
    }

    /**
     * State for a closed period.
     */
    public function closed(): static
    {
        return $this->state(fn (): array => [
            'status' => EvaluationPeriod::STATUS_CLOSED,
        ]);
    }

    /**
     * State for an archived period.
     */
    public function archived(): static
    {
        return $this->state(fn (): array => [
            'status' => EvaluationPeriod::STATUS_ARCHIVED,
        ]);
    }
}
