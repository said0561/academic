<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'student_id',
        'subject_id',
        'score',
        'grade',
        'remarks',
    ];

    /* ===================== Relationships ===================== */

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /* ===================== Helpers ===================== */

    /**
     * Compute percentage & grade based on exam total_marks.
     * TUNATUMIA PERCENTAGE moja kwa grading; exam->total_marks inaweza kuwa 50 au 100.
     */
   public static function computeGrade(float $score, int $examTotalMarks): array
{
    // Tunageuza marks alizoingiza mwalimu kuwa percentage ya 0–100
    $percentage = $examTotalMarks > 0
        ? round(($score / $examTotalMarks) * 100, 2)
        : 0.0;

    // Grading system ulivyoitaka:
    // A = 100–81
    // B = 80–61
    // C = 60–41
    // D = 40–21
    // E = 20–0
    $scale = [
        ['min' => 81, 'grade' => 'A', 'remarks' => 'Excellent'],
        ['min' => 61, 'grade' => 'B', 'remarks' => 'Very Good'],
        ['min' => 41, 'grade' => 'C', 'remarks' => 'Good'],
        ['min' => 21, 'grade' => 'D', 'remarks' => 'Fair'],
        ['min' => 0,  'grade' => 'E', 'remarks' => 'Poor'],
    ];

    foreach ($scale as $row) {
        if ($percentage >= $row['min']) {
            return [$percentage, $row['grade'], $row['remarks']];
        }
    }

    return [$percentage, 'E', 'Poor'];
}

}
