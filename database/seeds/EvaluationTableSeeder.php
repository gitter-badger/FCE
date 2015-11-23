<?php

use Illuminate\Database\Seeder; 

class EvaluationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $questions = Fce\Models\Questions::all();
        foreach ($questions as $question) {
        	if ($question->id >= 16) {
        		$question_set_id = 2;
        	} else {
        		$question_set_id = 1;
        	}
        	$evaluation_scores = $faker->shuffle([1, 0, 1, 0, 0]);
	        FCE\Models\Evaluation::create([
	            'section_id' => 1,
	            'question_id' => $question_id,
	            'question_set_id' => $question_set_id,
	            'one' => $evaluation_scores[0],
	            'two' => $evaluation_scores[1],
	            'three' => $evaluation_scores[2],
	            'four' => $evaluation_scores[3],
	            'five' => $evaluation_scores[4],
	            'comment' => $faker->sentence(5),
	        ]);
        }
    }
}
